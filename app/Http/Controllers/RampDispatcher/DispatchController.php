<?php

namespace App\Http\Controllers\RampDispatcher;

use App\Http\Controllers\Controller;
use App\Models\Request as RequestModel;
use Illuminate\Http\Request;

class DispatchController extends Controller
{
    /**
     * Mark request as dispatched (handed to Flight Purser)
     */
    public function markDispatched(RequestModel $request)
    {
        if ($request->status !== 'ready_for_dispatch') {
            return back()->with('error', 'This request is not ready for dispatch.');
        }

        $request->update([
            'status' => 'dispatched',
            'dispatched_by' => auth()->id(),
            'dispatched_at' => now(),
        ]);

        return back()->with('success', 'Request marked as dispatched. Handed to Flight Purser.');
    }

    /**
     * View all dispatched requests
     */
    public function dispatched()
    {
        $requests = RequestModel::with(['flight', 'requester', 'items.product'])
            ->where('status', 'dispatched')
            ->latest('dispatched_at')
            ->paginate(20);

        return view('ramp-dispatcher.dispatched', compact('requests'));
    }
}
