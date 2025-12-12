<?php

namespace App\Http\Controllers\RampDispatcher;

use App\Http\Controllers\Controller;
use App\Models\Request as RequestModel;
use App\Models\Product;
use Illuminate\Http\Request;

class DispatchController extends Controller
{
    /**
     * Dispatch a meal (for meal workflow)
     */
    public function dispatchMeal(Product $meal)
    {
        if ($meal->status !== 'authenticated') {
            return back()->with('error', 'Only authenticated meals can be dispatched.');
        }

        $meal->update([
            'status' => 'dispatched',
            'dispatched_by' => auth()->id(),
            'dispatched_at' => now(),
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($meal)
            ->log('Dispatched meal: ' . $meal->name);

        return back()->with('success', 'Meal dispatched successfully. Sent to Flight Purser for loading.');
    }

    /**
     * Handover request to Flight Crew (handles meal and product requests)
     */
    public function markDispatched(RequestModel $request)
    {
        // Check valid statuses: sent_to_ramp OR ready_for_dispatch
        $validStatuses = ['sent_to_ramp', 'ready_for_dispatch', 'security_dispatched', 'catering_approved'];
        if (!in_array($request->status, $validStatuses)) {
            return back()->with('error', 'This request is not ready for dispatch.');
        }

        $request->update([
            'status' => 'dispatched',
            'dispatched_by' => auth()->id(),
            'dispatched_at' => now(),
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($request)
            ->log('Dispatched request #' . $request->id . ' to aircraft');

        return back()->with('success', 'Request dispatched to Flight Crew successfully.');
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
