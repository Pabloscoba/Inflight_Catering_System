<?php

namespace App\Http\Controllers\FlightPurser;

use App\Http\Controllers\Controller;
use App\Models\Request as RequestModel;
use Illuminate\Http\Request;

class LoadController extends Controller
{
    /**
     * Receive handover from Ramp and load onto aircraft (NEW WORKFLOW)
     */
    public function markLoaded(RequestModel $request)
    {
        // Check if awaiting loading
        if ($request->status !== 'ramp_dispatched') {
            return back()->with('error', 'This request is not ready for loading.');
        }

        // Mark as loaded and ready for Cabin Crew
        $request->update([
            'status' => 'loaded',
            'loaded_by' => auth()->id(),
            'loaded_at' => now(),
        ]);

        // Notify Cabin Crew
        $cabinCrew = \App\Models\User::role('Cabin Crew')->get();
        foreach ($cabinCrew as $crew) {
            $crew->notify(new \App\Notifications\RequestLoadedNotification($request));
        }

        return back()->with('success', "Request #{$request->id} loaded onto aircraft and ready for Cabin Crew.");
    }

    /**
     * View all loaded requests
     */
    public function loaded()
    {
        $requests = RequestModel::with(['flight', 'requester', 'items.product'])
            ->whereIn('status', ['loaded', 'delivered', 'served'])
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
