<?php

namespace App\Http\Controllers\FlightDispatcher;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\FlightDispatch;
use App\Models\Request as RequestModel;
use App\Models\RequestMessage;
use Illuminate\Http\Request;

class DispatchController extends Controller
{
    /**
     * Show all dispatch records
     */
    public function index()
    {
        $dispatches = FlightDispatch::with(['flight', 'dispatcher', 'request'])
            ->latest()
            ->paginate(20);

        return view('flight-dispatcher.dispatches.index', compact('dispatches'));
    }

    /**
     * Create new dispatch record
     */
    public function create(Request $request)
    {
        $flights = Flight::where('departure_time', '>', now())
            ->where('status', '!=', 'cancelled')
            ->orderBy('departure_time', 'asc')
            ->get();

        $requests = RequestModel::with(['flight'])
            ->whereIn('status', ['ramp_dispatched', 'awaiting_flight_dispatcher'])
            ->latest()
            ->get();

        return view('flight-dispatcher.dispatches.create', compact('flights', 'requests'));
    }

    /**
     * Store new dispatch record
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'flight_id' => 'required|exists:flights,id',
            'request_id' => 'nullable|exists:requests,id',
            'fuel_status' => 'required|in:pending,confirmed,insufficient',
            'fuel_notes' => 'nullable|string',
            'crew_readiness' => 'required|in:pending,confirmed,not_ready',
            'crew_notes' => 'nullable|string',
            'catering_status' => 'required|in:pending,confirmed,delayed',
            'catering_notes' => 'nullable|string',
            'baggage_status' => 'required|in:pending,confirmed,delayed',
            'baggage_notes' => 'nullable|string',
            'operational_notes' => 'nullable|string',
            'delay_reason' => 'nullable|string',
        ]);

        $validated['dispatcher_id'] = auth()->id();
        $validated['overall_status'] = 'in_progress';

        // Set confirmation timestamps
        if ($validated['fuel_status'] === 'confirmed') {
            $validated['fuel_confirmed_at'] = now();
        }
        if ($validated['crew_readiness'] === 'confirmed') {
            $validated['crew_confirmed_at'] = now();
        }
        if ($validated['catering_status'] === 'confirmed') {
            $validated['catering_confirmed_at'] = now();
        }
        if ($validated['baggage_status'] === 'confirmed') {
            $validated['baggage_confirmed_at'] = now();
        }

        $dispatch = FlightDispatch::create($validated);

        return redirect()
            ->route('flight-dispatcher.dispatches.show', $dispatch)
            ->with('success', 'Dispatch record created successfully.');
    }

    /**
     * Show dispatch record details
     */
    public function show(FlightDispatch $dispatch)
    {
        $dispatch->load(['flight', 'dispatcher', 'request.items.product']);

        return view('flight-dispatcher.dispatches.show', compact('dispatch'));
    }

    /**
     * Edit dispatch record
     */
    public function edit(FlightDispatch $dispatch)
    {
        $dispatch->load(['flight', 'request']);

        return view('flight-dispatcher.dispatches.edit', compact('dispatch'));
    }

    /**
     * Update dispatch record
     */
    public function update(Request $request, FlightDispatch $dispatch)
    {
        $validated = $request->validate([
            'fuel_status' => 'required|in:pending,confirmed,insufficient',
            'fuel_notes' => 'nullable|string',
            'crew_readiness' => 'required|in:pending,confirmed,not_ready',
            'crew_notes' => 'nullable|string',
            'catering_status' => 'required|in:pending,confirmed,delayed',
            'catering_notes' => 'nullable|string',
            'baggage_status' => 'required|in:pending,confirmed,delayed',
            'baggage_notes' => 'nullable|string',
            'operational_notes' => 'nullable|string',
            'delay_reason' => 'nullable|string',
            'dispatch_recommendation' => 'nullable|in:clear_to_dispatch,hold,delay',
        ]);

        // Update confirmation timestamps
        if ($validated['fuel_status'] === 'confirmed' && $dispatch->fuel_status !== 'confirmed') {
            $validated['fuel_confirmed_at'] = now();
        }
        if ($validated['crew_readiness'] === 'confirmed' && $dispatch->crew_readiness !== 'confirmed') {
            $validated['crew_confirmed_at'] = now();
        }
        if ($validated['catering_status'] === 'confirmed' && $dispatch->catering_status !== 'confirmed') {
            $validated['catering_confirmed_at'] = now();
        }
        if ($validated['baggage_status'] === 'confirmed' && $dispatch->baggage_status !== 'confirmed') {
            $validated['baggage_confirmed_at'] = now();
        }

        // Auto-update overall status
        if ($dispatch->isReadyToDispatch()) {
            $validated['overall_status'] = 'ready';
        }

        if ($validated['dispatch_recommendation'] === 'clear_to_dispatch') {
            $validated['recommended_at'] = now();
        }

        $dispatch->update($validated);

        return redirect()
            ->route('flight-dispatcher.dispatches.show', $dispatch)
            ->with('success', 'Dispatch record updated successfully.');
    }

    /**
     * Confirm specific checklist item
     */
    public function confirmItem(Request $request, FlightDispatch $dispatch)
    {
        $validated = $request->validate([
            'item' => 'required|in:fuel,crew,catering,baggage',
            'status' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $updates = [];

        switch ($validated['item']) {
            case 'fuel':
                $updates['fuel_status'] = $validated['status'];
                $updates['fuel_notes'] = $validated['notes'] ?? null;
                if ($validated['status'] === 'confirmed') {
                    $updates['fuel_confirmed_at'] = now();
                }
                break;
            case 'crew':
                $updates['crew_readiness'] = $validated['status'];
                $updates['crew_notes'] = $validated['notes'] ?? null;
                if ($validated['status'] === 'confirmed') {
                    $updates['crew_confirmed_at'] = now();
                }
                break;
            case 'catering':
                $updates['catering_status'] = $validated['status'];
                $updates['catering_notes'] = $validated['notes'] ?? null;
                if ($validated['status'] === 'confirmed') {
                    $updates['catering_confirmed_at'] = now();
                }
                break;
            case 'baggage':
                $updates['baggage_status'] = $validated['status'];
                $updates['baggage_notes'] = $validated['notes'] ?? null;
                if ($validated['status'] === 'confirmed') {
                    $updates['baggage_confirmed_at'] = now();
                }
                break;
        }

        $dispatch->update($updates);

        return back()->with('success', ucfirst($validated['item']) . ' status updated successfully.');
    }

    /**
     * Forward to Flight Purser after assessment
     */
    public function forward(RequestModel $request)
    {
        // Allow forwarding from either awaiting_flight_dispatcher or ramp_dispatched
        if (! in_array($request->status, ['awaiting_flight_dispatcher', 'ramp_dispatched'])) {
            return back()->with('error', 'This request is not awaiting Flight Dispatcher assessment.');
        }

        // Mark as ready for Flight Purser
        $request->update([
            'status' => 'ramp_dispatched',
        ]);

        // Notify Flight Purser(s)
        $flightPursers = \App\Models\User::role('Flight Purser')->get();
        foreach ($flightPursers as $purser) {
            $purser->notify(new \App\Notifications\RequestApprovedNotification($request));
        }

        activity()
            ->causedBy(auth()->user())
            ->performedOn($request)
            ->log('Flight Dispatcher forwarded request #' . $request->id . ' to Flight Purser');

        return back()->with('success', 'Request forwarded to Flight Purser for loading.');
    }

    /**
     * Save a dispatcher comment on a request
     */
    public function comment(RequestModel $request, Request $r)
    {
        $this->authorize('comment on request');

        $request->update([
            'dispatcher_comments' => $r->input('comment'),
        ]);

        activity()->causedBy(auth()->user())->performedOn($request)->log('Flight Dispatcher commented on request #' . $request->id);

        return back()->with('success', 'Comment saved.');
    }

    /**
     * Recommend dispatch to Flight Operations (flag only)
     */
    public function recommend(RequestModel $request)
    {
        $this->authorize('recommend dispatch to flight operations');

        $request->update([
            'dispatcher_recommended' => true,
            'dispatcher_recommended_by' => auth()->id(),
            'dispatcher_recommended_at' => now(),
        ]);

        activity()->causedBy(auth()->user())->performedOn($request)->log('Flight Dispatcher recommended dispatch for request #' . $request->id);

        return back()->with('success', 'Dispatch recommended to Flight Operations.');
    }

    /**
     * Assess aircraft and request for flight readiness
     */
    public function assessRequest(RequestModel $request, Request $r)
    {
        $this->authorize('assess aircraft');

        // Check if awaiting assessment
        if ($request->status !== 'awaiting_flight_dispatcher') {
            return back()->with('error', 'This request is not awaiting Flight Dispatcher assessment.');
        }

        $validated = $r->validate([
            'assessment_notes' => 'required|string|max:1000',
            'aircraft_condition' => 'required|in:good,fair,needs_attention',
            'fuel_status' => 'required|in:sufficient,check_required',
            'crew_readiness' => 'required|in:ready,not_ready',
            'catering_check' => 'required|in:approved,needs_review',
        ]);

        // Update assessment status
        $request->update([
            'status' => 'flight_dispatcher_assessed',
            'flight_dispatcher_assessed_by' => auth()->id(),
            'flight_dispatcher_assessed_at' => now(),
            'flight_clearance_notes' => $validated['assessment_notes'],
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($request)
            ->withProperties($validated)
            ->log('Flight Dispatcher assessed request #' . $request->id);

        return back()->with('success', 'Aircraft assessment completed. Proceed with flight clearance.');
    }

    /**
     * Clear aircraft for departure - final approval
     */
    public function clearFlightForDeparture(RequestModel $request, Request $r)
    {
        $this->authorize('clear flight for operations');

        // Check if already assessed
        if ($request->status !== 'flight_dispatcher_assessed') {
            return back()->with('error', 'Request must be assessed before clearing for departure.');
        }

        $validated = $r->validate([
            'clearance_notes' => 'nullable|string|max:500',
        ]);

        \DB::transaction(function () use ($request, $validated) {
            // Clear aircraft for departure
            $request->update([
                'status' => 'flight_cleared_for_departure',
                'flight_cleared_for_departure_at' => now(),
                'flight_cleared' => true,
                'flight_clearance_notes' => ($request->flight_clearance_notes ?? '') . "\n\nClearance: " . ($validated['clearance_notes'] ?? 'Approved for departure'),
            ]);

            // Notify Flight Purser that flight is cleared
            $flightPursers = \App\Models\User::role('Flight Purser')->get();
            foreach ($flightPursers as $purser) {
                $purser->notify(new \App\Notifications\FlightClearedNotification($request));
            }

            // Notify Cabin Crew
            $cabinCrew = \App\Models\User::role('Cabin Crew')
                ->whereHas('requests', function ($q) use ($request) {
                    $q->where('id', $request->id);
                })
                ->get();

            foreach ($cabinCrew as $crew) {
                $crew->notify(new \App\Notifications\FlightClearedNotification($request));
            }
        });

        activity()
            ->causedBy(auth()->user())
            ->performedOn($request)
            ->log('Flight Dispatcher cleared flight for departure - Request #' . $request->id . ' for flight ' . $request->flight->flight_number);

        return redirect()
            ->route('flight-dispatcher.dashboard')
            ->with('success', 'Flight cleared for departure! Flight Purser and Cabin Crew have been notified.');
    }
}
