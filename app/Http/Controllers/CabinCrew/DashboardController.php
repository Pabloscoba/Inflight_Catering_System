<?php

namespace App\Http\Controllers\CabinCrew;

use App\Http\Controllers\Controller;
use App\Models\Request as RequestModel;
use App\Models\Flight;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Count loaded requests (ready for cabin crew)
        $loadedRequests = RequestModel::where('status', 'loaded')->count();
        
        // Count delivered requests
        $deliveredRequests = RequestModel::where('status', 'delivered')->count();
        
        // Get total flights handled
        $totalFlights = RequestModel::whereIn('status', ['loaded', 'delivered'])
            ->distinct('flight_id')
            ->count();
        
        // Get requests loaded onto aircraft (ready to receive)
        $requestsToReceive = RequestModel::with(['flight', 'requester', 'items.product'])
            ->where('status', 'loaded')
            ->whereHas('flight', function($query) {
                $query->where('departure_time', '>', now());
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get recently delivered requests
        $deliveredRequestsList = RequestModel::with(['flight', 'requester', 'items.product'])
            ->where('status', 'delivered')
            ->orderBy('delivered_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('cabin-crew.dashboard', compact(
            'loadedRequests',
            'deliveredRequests',
            'totalFlights',
            'requestsToReceive',
            'deliveredRequestsList'
        ));
    }
}
