<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Flight::query()->orderBy('departure_time', 'desc');

        // Search by flight number, airline, origin, or destination
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('flight_number', 'like', "%{$search}%")
                  ->orWhere('airline', 'like', "%{$search}%")
                  ->orWhere('origin', 'like', "%{$search}%")
                  ->orWhere('destination', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('departure_time', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('departure_time', '<=', $request->date_to);
        }

        $flights = $query->paginate(20);

        return view('admin.flights.index', compact('flights'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.flights.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'flight_number' => 'required|string|max:255|unique:flights',
            'airline' => 'required|string|max:255',
            'departure_time' => 'required|date',
            'arrival_time' => 'nullable|date|after:departure_time',
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'aircraft_type' => 'nullable|string|max:255',
            'passenger_capacity' => 'nullable|integer|min:1',
            'status' => 'required|in:scheduled,boarding,departed,arrived,cancelled',
            'notes' => 'nullable|string',
        ]);

        Flight::create($request->all());

        return redirect()->route('admin.flights.index')
            ->with('success', 'Flight created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Flight $flight)
    {
        return view('admin.flights.show', compact('flight'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Flight $flight)
    {
        return view('admin.flights.edit', compact('flight'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Flight $flight)
    {
        $request->validate([
            'flight_number' => 'required|string|max:255|unique:flights,flight_number,' . $flight->id,
            'airline' => 'required|string|max:255',
            'departure_time' => 'required|date',
            'arrival_time' => 'nullable|date|after:departure_time',
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'aircraft_type' => 'nullable|string|max:255',
            'passenger_capacity' => 'nullable|integer|min:1',
            'status' => 'required|in:scheduled,boarding,departed,arrived,cancelled',
            'notes' => 'nullable|string',
        ]);

        $flight->update($request->all());

        return redirect()->route('admin.flights.index')
            ->with('success', 'Flight updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Flight $flight)
    {
        // Check if flight has any requests
        if ($flight->requests()->count() > 0) {
            return back()->withErrors([
                'error' => 'Cannot delete flight with existing requests.'
            ]);
        }

        $flight->delete();

        return redirect()->route('admin.flights.index')
            ->with('success', 'Flight deleted successfully.');
    }
}
