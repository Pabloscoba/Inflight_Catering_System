<?php

namespace App\Http\Controllers\InventoryPersonnel;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get stock statistics (only approved products)
        $totalProducts = Product::where('status', 'approved')->count();
        $lowStockProducts = Product::where('status', 'approved')
            ->where('quantity_in_stock', '<', DB::raw('reorder_level'))
            ->count();
        $outOfStockProducts = Product::where('status', 'approved')
            ->where('quantity_in_stock', '=', 0)
            ->count();
        $totalStockValue = Product::where('status', 'approved')
            ->sum(DB::raw('quantity_in_stock * unit_price'));

        // Recent stock movements (last 10, including pending)
        $recentMovements = StockMovement::with(['product', 'user'])
            ->where('user_id', auth()->id())
            ->latest()
            ->limit(10)
            ->get();

        // Products needing attention (low stock, only approved)
        $lowStockItems = Product::with('category')
            ->where('status', 'approved')
            ->where('quantity_in_stock', '<', DB::raw('reorder_level'))
            ->orderBy('quantity_in_stock', 'asc')
            ->limit(10)
            ->get();

        // Pending requests from Catering Staff (need review)
        $pendingRequestsCount = \App\Models\Request::where('status', 'pending_inventory')->count();

        return view('inventory-personnel.dashboard', compact(
            'totalProducts',
            'lowStockProducts',
            'outOfStockProducts',
            'totalStockValue',
            'recentMovements',
            'lowStockItems',
            'pendingRequestsCount'
        ));
    }
}
