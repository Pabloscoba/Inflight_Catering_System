<?php

namespace App\Http\Controllers\CateringIncharge;

use App\Http\Controllers\Controller;
use App\Models\Request as RequestModel;
use App\Models\CateringStock;
use App\Models\Product;
use App\Models\Flight;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Pending product receipts from inventory (need approval)
        $pendingReceipts = CateringStock::where('status', 'pending')->count();
        
        // Requests awaiting Catering Incharge approval (supervisor_approved products OR pending meals)
        $pendingRequests = RequestModel::where(function($q) {
            $q->where('status', 'supervisor_approved') // Product requests
              ->orWhere(function($query) {
                  $query->where('status', 'pending')
                        ->where('request_type', 'meal'); // Meal requests
              });
        })->count();
        
        // Approved product receipts
        $approvedReceipts = CateringStock::where('status', 'approved')->count();
        
        // Approved staff requests
        $approvedRequests = RequestModel::where('status', 'approved')->count();
        
        // Total available catering stock (approved)
        $totalCateringStock = CateringStock::where('status', 'approved')->sum('quantity_available');
        
        // Low stock items in catering (below 20% of received quantity)
        $lowStockItems = CateringStock::with(['product', 'product.category'])
            ->where('status', 'approved')
            ->whereRaw('quantity_available < (quantity_received * 0.2)')
            ->orderBy('quantity_available', 'asc')
            ->limit(10)
            ->get();
        
        // Pending receipts list (latest 10)
        $pendingReceiptsList = CateringStock::with(['product', 'receivedBy'])
            ->where('status', 'pending')
            ->latest()
            ->limit(10)
            ->get();
        
        // Requests awaiting Catering Incharge approval (supervisor_approved products OR pending meals)
        $pendingStaffRequests = RequestModel::with(['flight', 'requester', 'items.product'])
            ->where(function($q) {
                $q->where('status', 'supervisor_approved') // Product requests from Supervisor
                  ->orWhere(function($query) {
                      $query->where('status', 'pending')
                            ->where('request_type', 'meal'); // Meal requests from Staff
                  });
            })
            ->latest()
            ->limit(10)
            ->get();
        
        // Recently approved receipts
        $recentlyApproved = CateringStock::with(['product', 'receivedBy', 'cateringIncharge'])
            ->where('status', 'approved')
            ->latest('approved_date')
            ->limit(10)
            ->get();
        
        // Stock overview by product
        $stockOverview = CateringStock::with(['product'])
            ->where('status', 'approved')
            ->select('product_id', DB::raw('SUM(quantity_available) as total_available'))
            ->groupBy('product_id')
            ->having('total_available', '>', 0)
            ->orderBy('total_available', 'desc')
            ->limit(10)
            ->get();

        // Upcoming flights needing catering
        $upcomingFlights = Flight::with(['requests'])
            ->where('departure_time', '>', now())
            ->where('departure_time', '<', now()->addDays(3))
            ->orderBy('departure_time', 'asc')
            ->limit(10)
            ->get();

        return view('catering-incharge.dashboard', compact(
            'pendingReceipts',
            'pendingRequests',
            'approvedReceipts',
            'approvedRequests',
            'totalCateringStock',
            'lowStockItems',
            'pendingReceiptsList',
            'pendingStaffRequests',
            'recentlyApproved',
            'stockOverview',
            'upcomingFlights'
        ));
    }
}
