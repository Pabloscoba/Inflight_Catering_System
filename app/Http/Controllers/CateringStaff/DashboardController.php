<?php

namespace App\Http\Controllers\CateringStaff;

use App\Http\Controllers\Controller;
use App\Models\Request as RequestModel;
use App\Models\Flight;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Get statistics for own requests
        $totalRequests = RequestModel::where('requester_id', $userId)->count();
        $pendingRequests = RequestModel::where('requester_id', $userId)
            ->whereIn('status', ['pending_inventory', 'pending_supervisor', 'supervisor_approved', 'sent_to_security', 'security_approved'])
            ->count();
        $approvedRequests = RequestModel::where('requester_id', $userId)
            ->where('status', 'catering_approved')
            ->count();
        $rejectedRequests = RequestModel::where('requester_id', $userId)
            ->where('status', 'rejected')
            ->count();

        // Recent own requests
        $myRecentRequests = RequestModel::with(['flight'])
            ->where('requester_id', $userId)
            ->latest()
            ->limit(10)
            ->get();

        // Upcoming flights that need requests
        $upcomingFlights = Flight::where('departure_time', '>', now())
            ->where('departure_time', '<', now()->addDays(7))
            ->where('status', 'scheduled')
            ->orderBy('departure_time', 'asc')
            ->limit(10)
            ->get();

        // Approved requests ready for collection
        $readyForCollection = RequestModel::with(['flight', 'items.product'])
            ->where('requester_id', $userId)
            ->where('status', 'catering_approved')
            ->latest()
            ->limit(5)
            ->get();

        // Available Catering Stock - Only products received from Inventory Personnel through approved workflow
        $availableStock = \DB::table('catering_stock')
            ->join('products', 'catering_stock.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('users as catering_incharge', 'catering_stock.catering_incharge_id', '=', 'catering_incharge.id')
            ->select(
                'products.id',
                'products.name',
                'products.sku',
                'categories.name as category',
                \DB::raw('SUM(catering_stock.quantity_received) as total_received'),
                \DB::raw('SUM(catering_stock.quantity_available) as total_available'),
                \DB::raw('MIN(products.min_stock_level) as min_stock'),
                \DB::raw('MAX(catering_stock.approved_date) as last_restocked')
            )
            ->where('catering_stock.status', 'approved')
            ->where('products.is_active', true)
            ->groupBy('products.id', 'products.name', 'products.sku', 'categories.name')
            ->orderBy('total_available', 'asc')
            ->get();

        // Low stock items (below minimum threshold - critical alert)
        $lowStockItems = $availableStock->filter(function($item) {
            return $item->total_available > 0 && $item->total_available < ($item->min_stock ?? 10);
        });

        // Near empty items (less than 5 units or 20% of min stock)
        $nearEmptyItems = $availableStock->filter(function($item) {
            $threshold = max(5, ($item->min_stock ?? 10) * 0.2);
            return $item->total_available > 0 && $item->total_available <= $threshold;
        });

        // Out of stock items (0 available)
        $outOfStockItems = $availableStock->filter(function($item) {
            return $item->total_available == 0;
        });

        // UNIFIED STOCK VIEW - Combine Mini Stock + Workflow Stock per product
        $allProducts = \App\Models\Product::with('category')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $unifiedStock = $allProducts->map(function($product) use ($availableStock) {
            // Get mini stock (from direct transfers)
            $miniStock = $product->catering_stock ?? 0;
            
            // Get workflow stock (from catering_stock table)
            $workflowStockItem = $availableStock->firstWhere('id', $product->id);
            $workflowStock = $workflowStockItem ? $workflowStockItem->total_available : 0;
            
            // Calculate total
            $totalAvailable = $miniStock + $workflowStock;
            
            // Only include products that have stock from either source
            if ($totalAvailable > 0) {
                return (object)[
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'category' => $product->category->name,
                    'mini_stock' => $miniStock,
                    'workflow_stock' => $workflowStock,
                    'total_available' => $totalAvailable,
                    'reorder_level' => $product->catering_reorder_level ?? 10,
                    'is_low_stock' => $totalAvailable <= ($product->catering_reorder_level ?? 10),
                ];
            }
            return null;
        })->filter(); // Remove null entries

        // Count low stock items in unified view
        $unifiedLowStockCount = $unifiedStock->where('is_low_stock', true)->count();

        return view('catering-staff.dashboard', compact(
            'totalRequests',
            'pendingRequests',
            'approvedRequests',
            'rejectedRequests',
            'myRecentRequests',
            'upcomingFlights',
            'readyForCollection',
            'availableStock',
            'lowStockItems',
            'nearEmptyItems',
            'outOfStockItems',
            'unifiedStock',
            'unifiedLowStockCount'
        ));
    }
}
