<?php

namespace App\Http\Controllers\CabinCrew;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ServiceReportController extends Controller
{
    public function index(Request $request)
    {
        // Date range filter
        $start = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::today()->startOfDay();
        $end = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::today()->endOfDay();
        $date = $start->format('M d, Y') . ($start->ne($end) ? ' - ' . $end->format('M d, Y') : '');

        // Get flights in range
        $flights = \App\Models\Flight::with(['requests.requester', 'requests.items'])
            ->whereBetween('departure_time', [$start, $end])
            ->get()
            ->map(function($flight) use ($start, $end) {
                // Only count delivered/served requests in range
                $requests = $flight->requests->whereIn('status', ['delivered', 'served'])
                    ->filter(function($req) use ($start, $end) {
                        return $req->updated_at >= $start && $req->updated_at <= $end;
                    });
                $crewNames = $requests->pluck('requester.name')->unique()->filter()->implode(', ');
                // Only sum used/delivered products
                $itemsDelivered = $requests->flatMap(function($req) {
                    return $req->items;
                })->sum('quantity_used');
                return (object) [
                    'flight_number' => $flight->flight_number,
                    'origin' => $flight->origin,
                    'destination' => $flight->destination,
                    'requests_count' => $requests->count(),
                    'items_delivered' => $itemsDelivered,
                    'crew_names' => $crewNames,
                ];
            });

        $totalFlights = $flights->count();
        $deliveredRequests = \App\Models\Request::whereIn('status', ['delivered', 'served'])
            ->whereBetween('updated_at', [$start, $end])
            ->count();
        $todayItemsServed = \App\Models\Request::with('items')
            ->whereIn('status', ['delivered', 'served'])
            ->whereBetween('updated_at', [$start, $end])
            ->get()
            ->flatMap(function($request) {
                return $request->items;
            })
            ->sum('quantity_used');
        $serviceRate = $totalFlights > 0 ? round(($deliveredRequests / $totalFlights) * 100, 1) : 0;

        if ($request->has('pdf')) {
            $pdf = Pdf::loadView('cabin-crew.service-report', compact('date', 'flights', 'totalFlights', 'deliveredRequests', 'todayItemsServed', 'serviceRate'))->setPaper('a4', 'portrait');
            return $pdf->download('Cabin-Crew-Service-Report-' . now()->format('Ymd') . '.pdf');
        }
        return view('cabin-crew.service-report', compact('date', 'flights', 'totalFlights', 'deliveredRequests', 'todayItemsServed', 'serviceRate'));
    }
}
