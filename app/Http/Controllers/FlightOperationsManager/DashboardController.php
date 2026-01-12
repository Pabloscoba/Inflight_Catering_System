<?php

namespace App\Http\Controllers\FlightOperationsManager;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\Request as RequestModel;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $totalFlights = Flight::count();
        $scheduledFlights = Flight::where('status', 'scheduled')->count();
        $todayFlights = Flight::whereDate('departure_time', today())->count();
        $upcomingFlights = Flight::where('departure_time', '>', now())
            ->where('departure_time', '<', now()->addDays(7))
            ->orderBy('departure_time', 'asc')
            ->limit(10)
            ->get();

        // Recent flights
        $recentFlights = Flight::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Flight statistics by status
        $flightsByStatus = Flight::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Flights with requests
        $flightsWithRequests = Flight::has('requests')
            ->withCount('requests')
            ->orderBy('departure_time', 'desc')
            ->limit(5)
            ->get();

        return view('flight-operations-manager.dashboard', compact(
            'totalFlights',
            'scheduledFlights',
            'todayFlights',
            'upcomingFlights',
            'recentFlights',
            'flightsByStatus',
            'flightsWithRequests'
        ));
    }
}
