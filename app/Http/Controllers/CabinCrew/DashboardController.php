<?php

namespace App\Http\Controllers\CabinCrew;

use App\Http\Controllers\Controller;
use App\Models\Request as RequestModel;
use App\Models\Flight;
use App\Models\ProductReturn;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Count loaded/received requests (ready for cabin crew) - include meal requests
        $loadedRequests = RequestModel::whereIn('status', ['loaded', 'flight_received'])->count();
        
        // Count delivered/served requests
        $deliveredRequests = RequestModel::whereIn('status', ['delivered', 'served'])->count();
        
        // Get total flights handled
        $totalFlights = RequestModel::whereIn('status', ['loaded', 'delivered', 'flight_received', 'served'])
            ->distinct('flight_id')
            ->count();
        
        // Get requests ready for service (product + meal)
        $requestsToReceive = RequestModel::with(['flight', 'requester', 'items.product'])
            ->where(function($query) {
                $query->where('status', 'loaded')
                      ->orWhere('status', 'flight_received'); // meal requests
            })
            ->whereHas('flight', function($query) {
                $query->where('departure_time', '>', now());
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get recently delivered/served requests
        $deliveredRequestsList = RequestModel::with(['flight', 'requester', 'items.product'])
            ->where(function($query) {
                $query->where('status', 'delivered')
                      ->orWhere('status', 'served'); // meal requests
            })
            ->orderByRaw("COALESCE(served_at, delivered_at) DESC")
            ->limit(10)
            ->get();
        
        // FLIGHT OVERVIEW - Today's and upcoming flights
        $todayFlights = Flight::with(['requests' => function($query) {
            $query->whereIn('status', ['loaded', 'flight_received', 'delivered', 'served']);
        }])
        ->whereDate('departure_time', today())
        ->orderBy('departure_time', 'asc')
        ->get();
        
        $upcomingFlights = Flight::with(['requests' => function($query) {
            $query->whereIn('status', ['loaded', 'flight_received']);
        }])
        ->where('departure_time', '>', now()->endOfDay())
        ->where('departure_time', '<', now()->addDays(3))
        ->orderBy('departure_time', 'asc')
        ->limit(5)
        ->get();
        
        // ITEMS RECEIVED - Summary by category
        $itemsReceivedSummary = RequestModel::with(['items.product.category'])
            ->whereIn('status', ['loaded', 'flight_received'])
            ->get()
            ->flatMap(function($request) {
                return $request->items;
            })
            ->groupBy(function($item) {
                return $item->product->category->name ?? 'Uncategorized';
            })
            ->map(function($items, $category) {
                return [
                    'category' => $category,
                    'total_items' => $items->sum('quantity_approved') ?? $items->sum('quantity_requested'),
                    'unique_products' => $items->unique('product_id')->count(),
                    'meal_items' => $items->filter(function($item) {
                        return $item->meal_type !== null;
                    })->count()
                ];
            });
        
        // PRODUCTS AVAILABLE - Breakdown by meal type
        $productsAvailable = RequestModel::with(['items.product'])
            ->whereIn('status', ['loaded', 'flight_received'])
            ->get()
            ->flatMap(function($request) {
                return $request->items;
            })
            ->groupBy('meal_type')
            ->map(function($items, $mealType) {
                return [
                    'meal_type' => $mealType ?: 'non_meal',
                    'count' => $items->sum('quantity_approved') ?? $items->sum('quantity_requested'),
                    'products' => $items->unique('product_id')->count()
                ];
            });
        
        // LIVE USAGE SUMMARY - Today's service statistics
        $todayServed = RequestModel::whereIn('status', ['delivered', 'served'])
            ->whereDate('updated_at', today())
            ->count();
        
        $todayItemsServed = RequestModel::with('items')
            ->whereIn('status', ['delivered', 'served'])
            ->whereDate('updated_at', today())
            ->get()
            ->flatMap(function($request) {
                return $request->items;
            })
            ->sum('quantity_approved');
        
        $activeFlightsCount = $todayFlights->filter(function($flight) {
            return $flight->departure_time->isFuture() || $flight->departure_time->isToday();
        })->count();
        
        // NOTIFICATIONS - Urgent items and alerts
        $urgentFlights = Flight::with(['requests' => function($query) {
            $query->whereIn('status', ['loaded', 'flight_received']);
        }])
        ->where('departure_time', '>', now())
        ->where('departure_time', '<', now()->addHours(3))
        ->get();
        
        $pendingItems = $requestsToReceive->sum(function($request) {
            return $request->items->count();
        });
        
        // Returns management
        $activeReturns = ProductReturn::where('returned_by', auth()->id())
            ->whereIn('status', ['pending_ramp', 'received_by_ramp', 'pending_security'])
            ->count();
        
        return view('cabin-crew.dashboard', compact(
            'loadedRequests',
            'deliveredRequests',
            'totalFlights',
            'requestsToReceive',
            'deliveredRequestsList',
            'todayFlights',
            'upcomingFlights',
            'itemsReceivedSummary',
            'productsAvailable',
            'todayServed',
            'todayItemsServed',
            'activeFlightsCount',
            'urgentFlights',
            'pendingItems',
            'activeReturns'
        ));
    }
}
