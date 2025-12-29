<?php

namespace App\Http\Controllers\RampDispatcher;

use App\Http\Controllers\Controller;
use App\Models\Request as RequestModel;
use App\Models\ProductReturn;
use App\Models\Flight;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics - NEW WORKFLOW
        $approvedOrders = RequestModel::where('status', 'security_authenticated')->count();
        $dispatchedToday = RequestModel::where('status', 'ramp_dispatched')
            ->whereDate('updated_at', today())
            ->count();
        
        // Flights needing dispatch today
        $todayFlights = Flight::whereDate('departure_time', today())
            ->count();

        // Orders ready for dispatch (authenticated by security)
        $ordersToDispatch = RequestModel::with(['flight', 'requester', 'items.product'])
            ->where('status', 'security_authenticated')
            ->whereHas('flight', function($query) {
                $query->where('departure_time', '>', now());
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Recently dispatched orders
        $recentDispatches = RequestModel::with(['flight', 'requester'])
            ->where('status', 'ramp_dispatched')
            ->latest()
            ->limit(10)
            ->get();
        
        // Returns management
        $pendingReturns = ProductReturn::where('status', 'pending_ramp')->count();

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
            'upcomingFlights',
            'pendingReturns'
        ));
    }
}
