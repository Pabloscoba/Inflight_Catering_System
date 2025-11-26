<?php

namespace App\Http\Controllers\CateringStaff;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    public function create()
    {
        return view('catering-staff.flights.create');
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
            'passenger_count' => 'nullable|integer|min:1',
        ]);

        $data['status'] = 'scheduled';

        Flight::create($data);

        return redirect()->route('catering-staff.dashboard')->with('success', 'Flight added successfully! You can now create requests for this flight.');
    }
}
