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
     * Sign and approve request (NEW WORKFLOW - send to Flight Purser)
     */
    public function markDispatched(RequestModel $request)
    {
        // Check if awaiting ramp dispatch
        if ($request->status !== 'security_authenticated') {
            return back()->with('error', 'This request is not ready for dispatch.');
        }

        // Mark sent to Flight Dispatcher for assessment
        $request->update([
            'status' => 'awaiting_flight_dispatcher',
            'dispatched_by' => auth()->id(),
            'dispatched_at' => now(),
        ]);

        // Notify Flight Dispatcher(s)
        $flightDispatchers = \App\Models\User::role('Flight Dispatcher')->get();
        foreach ($flightDispatchers as $fd) {
            $fd->notify(new \App\Notifications\RequestApprovedNotification($request));
        }

        activity()
            ->causedBy(auth()->user())
            ->performedOn($request)
            ->log('Dispatched request #' . $request->id . ' to Flight Dispatcher for assessment');

        return back()->with('success', 'Request signed and forwarded to Flight Dispatcher for assessment.');
    }

    /**
     * View all dispatched requests
     */
    public function dispatched()
    {
        $requests = RequestModel::with(['flight', 'requester', 'items.product'])
            ->whereIn('status', ['ramp_dispatched', 'loaded', 'delivered'])
            ->latest('dispatched_at')
            ->paginate(20);

        return view('ramp-dispatcher.dispatched', compact('requests'));
    }
}
