<?php

namespace App\Http\Controllers\RampDispatcher;

use App\Http\Controllers\Controller;
use App\Models\Request as RequestModel;
use App\Models\Flight;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $approvedOrders = RequestModel::where('status', 'ready_for_dispatch')->count();
        $dispatchedToday = RequestModel::where('status', 'dispatched')
            ->whereDate('updated_at', today())
            ->count();
        
        // Flights needing dispatch today
        $todayFlights = Flight::whereDate('departure_time', today())
            ->count();

        // Orders ready for dispatch (sent by Catering Staff)
        $ordersToDispatch = RequestModel::with(['flight', 'requester', 'items.product'])
            ->where('status', 'ready_for_dispatch')
            ->whereHas('flight', function($query) {
                $query->where('departure_time', '>', now());
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Recently dispatched orders
        $recentDispatches = RequestModel::with(['flight', 'requester'])
            ->where('status', 'dispatched')
            ->latest()
            ->limit(10)
            ->get();

        // Upcoming flights (next 7 days)
        $upcomingFlights = Flight::with(['requests'])
            ->where('departure_time', '>', now())
            ->where('departure_time', '<', now()->addDays(7))
            ->where('status', 'scheduled')
            ->orderBy('departure_time', 'asc')
            ->get();

        return view('ramp-dispatcher.dashboard', compact(
            'approvedOrders',
            'dispatchedToday',
            'todayFlights',
            'ordersToDispatch',
            'recentDispatches',
            'upcomingFlights'
        ));
    }
}
