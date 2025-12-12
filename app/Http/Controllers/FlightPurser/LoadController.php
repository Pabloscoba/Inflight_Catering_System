<?php

namespace App\Http\Controllers\FlightPurser;

use App\Http\Controllers\Controller;
use App\Models\Request as RequestModel;
use Illuminate\Http\Request;

class LoadController extends Controller
{
    /**
     * Receive handover from Ramp and mark as loaded onto aircraft
     */
    public function markLoaded(RequestModel $request)
    {
        // Accept both meal and product requests
        $validStatuses = ['dispatched', 'handed_to_flight'];
        
        if (!in_array($request->status, $validStatuses)) {
            return back()->with('error', 'This request is not ready for receiving.');
        }

        // Different status transitions based on request type
        if ($request->request_type === 'meal') {
            // Meal requests: flight_received -> in_service (ready for cabin crew)
            $request->update([
                'status' => 'flight_received',
                'flight_received_by' => auth()->id(),
                'flight_received_at' => now(),
            ]);
            
            return back()->with('success', "Meal request #{$request->id} received. Ready for Cabin Crew service.");
        } else {
            // Product requests: loaded (original flow)
            $request->update([
                'status' => 'loaded',
                'loaded_by' => auth()->id(),
                'loaded_at' => now(),
            ]);

            return back()->with('success', "Request #{$request->id} marked as loaded onto aircraft. Ready for Cabin Crew.");
        }
    }

    /**
     * View all loaded requests (includes meal requests received)
     */
    public function loaded()
    {
        $requests = RequestModel::with(['flight', 'requester', 'items.product'])
            ->where(function($query) {
                $query->where('status', 'loaded')
                      ->orWhere('status', 'flight_received'); // meal requests
            })
            ->latest('loaded_at')
            ->paginate(20);

        return view('flight-purser.loaded', compact('requests'));
    }

    /**
     * Show request details for Flight Purser
     */
    public function show(RequestModel $request)
    {
        $request->load(['flight', 'requester', 'items.product.category']);
        
        return view('flight-purser.requests.show', compact('request'));
    }
}
