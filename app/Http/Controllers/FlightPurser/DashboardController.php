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
        // Get statistics - include meal requests
        $dispatchedRequests = RequestModel::whereIn('status', ['dispatched', 'handed_to_flight'])->count();
        $loadedRequests = RequestModel::whereIn('status', ['loaded', 'flight_received'])->count();
        
        $upcomingFlights = Flight::where('departure_time', '>', now())
            ->where('departure_time', '<', now()->addDays(7))
            ->count();

        // Requests handed over by Ramp - waiting to be received (meal + product)
        $requestsToLoad = RequestModel::with(['flight', 'requester', 'items.product'])
            ->where(function($query) {
                $query->where('status', 'dispatched')
                      ->orWhere('status', 'handed_to_flight'); // meal requests
            })
            ->whereHas('flight', function($query) {
                $query->where('departure_time', '>', now());
            })
            ->orderByRaw("COALESCE(handed_to_flight_at, dispatched_at) DESC")
            ->get();

        // Recently received/loaded requests
        $loadedRequestsList = RequestModel::with(['flight', 'requester', 'items.product'])
            ->where(function($query) {
                $query->where('status', 'loaded')
                      ->orWhere('status', 'flight_received'); // meal requests
            })
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
