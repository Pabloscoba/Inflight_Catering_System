<?php

namespace App\Http\Controllers\FlightDispatcher;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\FlightDispatch;
use App\Models\FlightStatusUpdate;
use App\Models\Request as RequestModel;
use App\Models\RequestMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the Flight Dispatcher Dashboard
     */
    public function index()
    {
        // Today's Flights
        $todaysFlights = Flight::with(['requests'])
            ->whereDate('departure_time', today())
            ->orderBy('departure_time', 'asc')
            ->get();

        // Upcoming Flights (Next 24 hours)
        $upcomingFlights = Flight::with(['requests'])
            ->where('departure_time', '>', now())
            ->where('departure_time', '<=', now()->addDay())
            ->where('status', '!=', 'cancelled')
            ->orderBy('departure_time', 'asc')
            ->get();

        // Active Dispatch Records
        $activeDispatches = FlightDispatch::with(['flight', 'dispatcher', 'request'])
            ->whereIn('overall_status', ['pending', 'in_progress', 'ready'])
            ->latest()
            ->take(10)
            ->get();

        // Requests Awaiting Assessment
        $awaitingRequests = RequestModel::with(['flight', 'requester', 'items.product'])
            ->where('status', 'awaiting_flight_dispatcher')
            ->latest()
            ->take(10)
            ->get();

        // Requests Assessed - awaiting clearance
        $assessedRequests = RequestModel::with(['flight', 'requester', 'items.product', 'flightDispatcherAssessor'])
            ->where('status', 'flight_dispatcher_assessed')
            ->latest()
            ->take(10)
            ->get();

        // Recently Cleared Flights
        $clearedFlights = RequestModel::with(['flight', 'requester', 'flightDispatcherAssessor'])
            ->where('status', 'flight_cleared_for_departure')
            ->latest()
            ->take(10)
            ->get();

        // Unread Messages
        $unreadMessages = RequestMessage::with(['request.flight', 'sender'])
            ->forRole('Flight Dispatcher')
            ->unread()
            ->latest()
            ->take(10)
            ->get();

        // Statistics
        $stats = [
            'flights_today' => $todaysFlights->count(),
            'flights_upcoming' => $upcomingFlights->count(),
            'active_dispatches' => $activeDispatches->count(),
            'awaiting_requests' => $awaitingRequests->count(),
            'assessed_requests' => $assessedRequests->count(),
            'cleared_flights' => $clearedFlights->count(),
            'unread_messages' => $unreadMessages->count(),
            'flights_boarding' => Flight::where('status', 'boarding')->count(),
            'flights_departed_today' => Flight::where('status', 'departed')
                ->whereDate('departure_time', today())
                ->count(),
        ];

        return view('flight-dispatcher.dashboard', compact(
            'todaysFlights',
            'upcomingFlights',
            'activeDispatches',
            'awaitingRequests',
            'assessedRequests',
            'clearedFlights',
            'unreadMessages',
            'stats'
        ));
    }

    /**
     * Show flight schedule
     */
    public function flightSchedule(Request $request)
    {
        $query = Flight::with(['requests']);

        // Filter by date
        if ($request->has('date')) {
            $query->whereDate('departure_time', $request->date);
        } else {
            // Default to today and next 7 days
            $query->where('departure_time', '>=', now()->startOfDay())
                  ->where('departure_time', '<=', now()->addWeek());
        }

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by airline
        if ($request->has('airline') && $request->airline !== 'all') {
            $query->where('airline', $request->airline);
        }

        $flights = $query->orderBy('departure_time', 'asc')->paginate(20);

        $airlines = Flight::select('airline')->distinct()->pluck('airline');

        return view('flight-dispatcher.flights.schedule', compact('flights', 'airlines'));
    }

    /**
     * Show specific flight details
     */
    public function showFlight(Flight $flight)
    {
        $flight->load([
            'requests.items.product',
            'requests.requester'
        ]);

        // Get dispatch record if exists
        $dispatch = FlightDispatch::where('flight_id', $flight->id)
            ->with(['dispatcher'])
            ->latest()
            ->first();

        // Get status update history
        $statusUpdates = FlightStatusUpdate::where('flight_id', $flight->id)
            ->with(['updatedBy'])
            ->latest()
            ->get();

        // Get messages related to this flight's requests
        $messages = RequestMessage::whereIn('request_id', $flight->requests->pluck('id'))
            ->with(['sender', 'request'])
            ->latest()
            ->take(20)
            ->get();

        return view('flight-dispatcher.flights.show', compact(
            'flight',
            'dispatch',
            'statusUpdates',
            'messages'
        ));
    }

    /**
     * Update flight status
     */
    public function updateFlightStatus(Request $request, Flight $flight)
    {
        $validated = $request->validate([
            'status' => 'required|in:scheduled,boarding,delayed,departed,cancelled',
            'reason' => 'nullable|string|max:500',
        ]);

        // Record the status change
        FlightStatusUpdate::create([
            'flight_id' => $flight->id,
            'updated_by' => auth()->id(),
            'old_status' => $flight->status,
            'new_status' => $validated['status'],
            'old_departure_time' => $flight->departure_time,
            'new_departure_time' => $flight->departure_time,
            'reason' => $validated['reason'] ?? null,
        ]);

        $flight->update([
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'Flight status updated successfully.');
    }

    /**
     * Update flight estimated times
     */
    public function updateFlightTimes(Request $request, Flight $flight)
    {
        $validated = $request->validate([
            'departure_time' => 'nullable|date',
            'arrival_time' => 'nullable|date',
            'reason' => 'nullable|string|max:500',
        ]);

        // Record the time change
        FlightStatusUpdate::create([
            'flight_id' => $flight->id,
            'updated_by' => auth()->id(),
            'old_status' => $flight->status,
            'new_status' => $flight->status,
            'old_departure_time' => $flight->departure_time,
            'new_departure_time' => $validated['departure_time'] ?? $flight->departure_time,
            'old_arrival_time' => $flight->arrival_time,
            'new_arrival_time' => $validated['arrival_time'] ?? $flight->arrival_time,
            'reason' => $validated['reason'] ?? null,
        ]);

        $flight->update([
            'departure_time' => $validated['departure_time'] ?? $flight->departure_time,
            'arrival_time' => $validated['arrival_time'] ?? $flight->arrival_time,
        ]);

        return back()->with('success', 'Flight times updated successfully.');
    }
}
