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
        // Statistics (excluding arrived/completed flights)
        $totalFlights = Flight::whereNotIn('status', ['completed', 'arrived'])->count();
        $scheduledFlights = Flight::where('status', 'scheduled')->count();
        $todayFlights = Flight::whereDate('departure_time', today())
            ->whereNotIn('status', ['completed', 'arrived'])
            ->count();
        $upcomingFlights = Flight::where('departure_time', '>', now())
            ->where('departure_time', '<', now()->addDays(7))
            ->whereNotIn('status', ['completed', 'arrived'])
            ->orderBy('departure_time', 'asc')
            ->limit(10)
            ->get();

        // Recent flights (excluding arrived/completed)
        $recentFlights = Flight::whereNotIn('status', ['completed', 'arrived'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Flight statistics by status (excluding arrived/completed)
        $flightsByStatus = Flight::whereNotIn('status', ['completed', 'arrived'])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Flights with requests (excluding arrived/completed)
        $flightsWithRequests = Flight::has('requests')
            ->whereNotIn('status', ['completed', 'arrived'])
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
