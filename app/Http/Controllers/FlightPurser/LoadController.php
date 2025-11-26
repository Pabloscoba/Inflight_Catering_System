<?php

namespace App\Http\Controllers\FlightPurser;

use App\Http\Controllers\Controller;
use App\Models\Request as RequestModel;
use Illuminate\Http\Request;

class LoadController extends Controller
{
    /**
     * Mark request as loaded onto aircraft
     */
    public function markLoaded(RequestModel $request)
    {
        if ($request->status !== 'dispatched') {
            return back()->with('error', 'This request has not been dispatched yet.');
        }

        $request->update([
            'status' => 'loaded',
            'loaded_by' => auth()->id(),
            'loaded_at' => now(),
        ]);

        return back()->with('success', "Request #{$request->id} marked as loaded onto aircraft. Ready for Cabin Crew.");
    }

    /**
     * View all loaded requests
     */
    public function loaded()
    {
        $requests = RequestModel::with(['flight', 'requester', 'items.product'])
            ->where('status', 'loaded')
            ->latest('loaded_at')
            ->paginate(20);

        return view('flight-purser.loaded', compact('requests'));
    }
}
