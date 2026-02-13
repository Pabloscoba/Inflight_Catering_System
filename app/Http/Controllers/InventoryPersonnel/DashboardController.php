<?php

namespace App\Http\Controllers\InventoryPersonnel;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Http\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get stock statistics (all products - active and inactive)
        $totalProducts = Product::count();
        $lowStockProducts = Product::where('is_active', true)
            ->whereColumn('quantity_in_stock', '<', 'reorder_level')
            ->where('quantity_in_stock', '>', 0)
            ->count();
        $outOfStockProducts = Product::where('is_active', true)
            ->where('quantity_in_stock', '=', 0)
            ->count();
        $totalStockValue = Product::where('is_active', true)
            ->sum(DB::raw('quantity_in_stock * unit_price'));

        // Recent stock movements (last 10)
        $recentMovements = StockMovement::with(['product', 'user'])
            ->where('user_id', auth()->id())
            ->latest()
            ->limit(10)
            ->get();

        // Products needing attention (low stock or out of stock)
        $lowStockItems = Product::with('category')
            ->where('is_active', true)
            ->whereColumn('quantity_in_stock', '<=', 'reorder_level')
            ->orderBy('quantity_in_stock', 'asc')
            ->limit(10)
            ->get();

        // Pending requests from Catering Staff (need review)
        $pendingRequestsCount = \App\Models\Request::where('status', 'pending_inventory')->count();

        // Supervisor approved requests (need to forward to Security)
        $supervisorApprovedCount = \App\Models\Request::where('status', 'supervisor_approved')->count();
        $supervisorApprovedRequests = \App\Models\Request::with(['flight', 'requester', 'items.product'])
            ->where('status', 'supervisor_approved')
            ->latest()
            ->limit(10)
            ->get();

        // Pending transfers to catering (awaiting supervisor approval)
        $pendingTransfers = StockMovement::with(['product', 'user'])
            ->where('type', 'transfer_to_catering')
            ->where('status', 'pending')
            ->where('user_id', auth()->id())
            ->latest()
            ->limit(10)
            ->get();
        $pendingTransfersCount = $pendingTransfers->count();

        // Approved transfers (supervisor approved)
        $approvedTransfers = StockMovement::with(['product', 'user', 'approvedBy'])
            ->where('type', 'transfer_to_catering')
            ->where('status', 'approved')
            ->where('user_id', auth()->id())
            ->latest('approved_at')
            ->limit(10)
            ->get();
        $approvedTransfersCount = StockMovement::where('type', 'transfer_to_catering')
            ->where('status', 'approved')
            ->where('user_id', auth()->id())
            ->count();

        // All products in main stock (with quantity > 0)
        $productsInStock = Product::with('category')
            ->where('is_active', true)
            ->where('quantity_in_stock', '>', 0)
            ->orderBy('quantity_in_stock', 'desc')
            ->paginate(15);

        // Recently added products (last 10) - all products regardless of active status
        $recentProducts = Product::with('category')
            ->latest('created_at')
            ->limit(10)
            ->get();

        return view('inventory-personnel.dashboard', compact(
            'totalProducts',
            'lowStockProducts',
            'outOfStockProducts',
            'totalStockValue',
            'lowStockItems',
            'pendingRequestsCount',
            'supervisorApprovedCount',
            'supervisorApprovedRequests',
            'pendingTransfers',
            'pendingTransfersCount',
            'approvedTransfers',
            'approvedTransfersCount',
            'productsInStock',
            'recentProducts'
        ));
    }
}
