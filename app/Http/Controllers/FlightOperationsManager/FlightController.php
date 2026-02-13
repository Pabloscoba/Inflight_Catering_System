<?php

namespace App\Http\Controllers\FlightOperationsManager;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FlightController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 20);
        $q = $request->input('q');
        $status = $request->input('status');

        $query = Flight::query();

        // Exclude completed/archived and arrived flights from default view
        if (!$request->has('show_archived')) {
            $query->whereNotIn('status', ['completed', 'arrived']);
        }

        if ($q) {
            $query->where(function($r) use ($q) {
                $r->where('flight_number', 'like', "%{$q}%")
                  ->orWhere('airline', 'like', "%{$q}%")
                  ->orWhere('origin', 'like', "%{$q}%")
                  ->orWhere('destination', 'like', "%{$q}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $flights = $query->withCount('requests')->orderBy('departure_time', 'desc')->paginate($perPage)->appends($request->except('page'));

        return view('flight-operations-manager.flights.index', compact('flights', 'q', 'status', 'perPage'));
    }

    public function create()
    {
        return view('flight-operations-manager.flights.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'flight_number' => 'required|string|max:20|unique:flights,flight_number',
            'airline' => 'required|string|max:100',
            'origin' => 'required|string|max:10',
            'destination' => 'required|string|max:10',
            'departure_time' => 'required|date|after:now',
            'arrival_time' => 'required|date|after:departure_time',
            'aircraft_type' => 'nullable|string|max:50',
            'passenger_count' => 'nullable|integer|min:1',
            'route' => 'nullable|string|max:200',
        ]);

        $data['status'] = 'scheduled';

        Flight::create($data);

        activity()
            ->causedBy(auth()->user())
            ->performedOn(Flight::where('flight_number', $data['flight_number'])->first())
            ->log('Created flight: ' . $data['flight_number']);

        return redirect()->route('flight-operations-manager.flights.index')
            ->with('success', 'Flight added successfully!');
    }

    public function edit(Flight $flight)
    {
        return view('flight-operations-manager.flights.edit', compact('flight'));
    }

    public function update(Request $request, Flight $flight)
    {
        $data = $request->validate([
            'flight_number' => 'required|string|max:20|unique:flights,flight_number,' . $flight->id,
            'airline' => 'required|string|max:100',
            'origin' => 'required|string|max:10',
            'destination' => 'required|string|max:10',
            'departure_time' => 'required|date',
            'arrival_time' => 'required|date|after:departure_time',
            'aircraft_type' => 'nullable|string|max:50',
            'passenger_count' => 'nullable|integer|min:1',
            'route' => 'nullable|string|max:200',
            'status' => 'required|in:scheduled,boarding,departed,arrived,cancelled,delayed',
        ]);

        $flight->update($data);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($flight)
            ->log('Updated flight: ' . $flight->flight_number);

        return redirect()->route('flight-operations-manager.flights.index')
            ->with('success', 'Flight updated successfully!');
    }

    public function destroy(Flight $flight)
    {
        // Check if flight has associated requests
        if ($flight->requests()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete flight with existing catering requests. Please delete requests first.');
        }

        $flightNumber = $flight->flight_number;
        $flight->delete();

        activity()
            ->causedBy(auth()->user())
            ->log('Deleted flight: ' . $flightNumber);

        return redirect()->route('flight-operations-manager.flights.index')
            ->with('success', 'Flight deleted successfully!');
    }

    public function show(Flight $flight)
    {
        $flight->load(['requests.requester', 'requests.items.product']);
        return view('flight-operations-manager.flights.show', compact('flight'));
    }
}
