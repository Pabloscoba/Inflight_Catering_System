<?php

namespace App\Http\Controllers\InventorySupervisor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Request as RequestModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get pending approvals count
        $pendingProducts = Product::where('status', 'pending')->count();
        $pendingMovements = StockMovement::where('status', 'pending')->count();
        $pendingRequests = RequestModel::where('status', 'pending')->count();
        
        // Get total approved items
        $approvedProducts = Product::where('status', 'approved')->count();
        $approvedMovements = StockMovement::where('status', 'approved')->count();
        
        // Get recent pending products (need approval)
        $pendingProductsList = Product::with(['category'])
            ->where('status', 'pending')
            ->latest()
            ->limit(10)
            ->get();
        
        // Get recent pending stock movements (need approval)
        $movementsToVerify = StockMovement::with(['product', 'user'])
            ->where('status', 'pending')
            ->latest()
            ->limit(10)
            ->get();
        
        // Get recently approved items (last 10)
        $recentlyApproved = StockMovement::with(['product', 'user', 'approvedBy'])
            ->where('status', 'approved')
            ->latest('approved_at')
            ->limit(10)
            ->get();

        // Pending requests from catering staff
        $pendingCateringRequests = RequestModel::with(['flight', 'requester'])
            ->where('status', 'pending')
            ->latest()
            ->limit(10)
            ->get();

        // Inventory alerts (only approved products)
        $lowStockProducts = Product::where('status', 'approved')
            ->where('quantity_in_stock', '<', DB::raw('reorder_level'))
            ->count();
        $outOfStockProducts = Product::where('status', 'approved')
            ->where('quantity_in_stock', '=', 0)
            ->count();
        
        // Low stock items list for display
        $lowStockItems = Product::with('category')
            ->where('status', 'approved')
            ->where('quantity_in_stock', '<', DB::raw('reorder_level'))
            ->orderBy('quantity_in_stock', 'asc')
            ->limit(10)
            ->get();

        return view('inventory-supervisor.dashboard', compact(
            'pendingProducts',
            'pendingMovements',
            'pendingRequests',
            'approvedProducts',
            'approvedMovements',
            'pendingProductsList',
            'movementsToVerify',
            'recentlyApproved',
            'pendingCateringRequests',
            'lowStockProducts',
            'outOfStockProducts',
            'lowStockItems'
        ));
    }
}
