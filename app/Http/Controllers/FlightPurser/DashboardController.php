<?php

namespace App\Http\Controllers\FlightPurser;

use App\Http\Controllers\Controller;
use App\Models\Request as RequestModel;
use App\Models\Flight;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $dispatchedRequests = RequestModel::where('status', 'dispatched')->count();
        $loadedRequests = RequestModel::where('status', 'loaded')->count();
        
        $upcomingFlights = Flight::where('departure_time', '>', now())
            ->where('departure_time', '<', now()->addDays(7))
            ->count();

        // Requests dispatched by Ramp - waiting to be loaded
        $requestsToLoad = RequestModel::with(['flight', 'requester', 'items.product'])
            ->where('status', 'dispatched')
            ->whereHas('flight', function($query) {
                $query->where('departure_time', '>', now());
            })
            ->orderBy('dispatched_at', 'desc')
            ->get();

        // Recently loaded requests
        $loadedRequestsList = RequestModel::with(['flight', 'requester', 'items.product'])
            ->where('status', 'loaded')
            ->latest('loaded_at')
            ->limit(10)
            ->get();

        // My upcoming flights as Purser
        $myUpcomingFlights = Flight::with(['requests.items.product'])
            ->where('departure_time', '>', now())
            ->where('departure_time', '<', now()->addDays(7))
            ->orderBy('departure_time', 'asc')
            ->limit(10)
            ->get();

        // Flight schedule overview
        $flightSchedule = Flight::where('departure_time', '>', now())
            ->where('departure_time', '<', now()->addDays(3))
            ->orderBy('departure_time', 'asc')
            ->get();

        return view('flight-purser.dashboard', compact(
            'dispatchedRequests',
            'loadedRequests',
            'upcomingFlights',
            'requestsToLoad',
            'loadedRequestsList',
            'myUpcomingFlights'
        ));
    }
}
