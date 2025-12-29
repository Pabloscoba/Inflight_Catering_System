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
        
        // CORRECTED WORKFLOW: Pending requests awaiting first approval
        $pendingRequests = RequestModel::where('status', 'pending_catering_incharge')->count();
        
        // CORRECTED WORKFLOW: Pending final approvals (from catering staff after receiving items)
        $pendingItemReceipts = RequestModel::where('status', 'pending_final_approval')->count();
        
        // Approved product receipts
        $approvedReceipts = CateringStock::where('status', 'approved')->count();
        
        // Approved staff requests
        $approvedRequests = RequestModel::whereIn('status', [
            'catering_approved', 'supervisor_approved', 'items_issued', 
            'pending_final_approval', 'catering_final_approved', 'security_authenticated', 
            'ramp_dispatched', 'loaded', 'delivered'
        ])->count();
        
        // Total available catering stock - REAL-TIME from Product table
        $totalCateringStock = Product::where('is_active', true)->sum('quantity_in_stock');
        
        // Low stock items in main inventory - REAL-TIME from Product table
        $lowStockItems = Product::with(['category'])
            ->where('is_active', true)
            ->whereColumn('quantity_in_stock', '<=', 'reorder_level')
            ->where('quantity_in_stock', '>=', 0)
            ->orderBy('quantity_in_stock', 'asc')
            ->limit(10)
            ->get();
        
        // Pending receipts list (latest 10)
        $pendingReceiptsList = CateringStock::with(['product', 'receivedBy'])
            ->where('status', 'pending')
            ->latest()
            ->limit(10)
            ->get();
        
        // Requests awaiting Catering Incharge INITIAL approval (NEW WORKFLOW)
        // Catering Incharge is the FIRST approver, not after supervisor
        $pendingStaffRequests = RequestModel::with(['flight', 'requester', 'items.product'])
            ->where('status', 'pending_catering_incharge')
            ->latest()
            ->limit(10)
            ->get();
        
        // Recently approved receipts
        $recentlyApproved = CateringStock::with(['product', 'receivedBy', 'cateringIncharge'])
            ->where('status', 'approved')
            ->latest('approved_date')
            ->limit(10)
            ->get();
        
        // Stock overview by product - REAL-TIME from Product table (main inventory)
        $stockOverview = Product::with(['category'])
            ->where('is_active', true)
            ->where('quantity_in_stock', '>', 0)
            ->select('id', 'name', 'category_id', 'quantity_in_stock', 'reorder_level', 'unit_of_measure')
            ->orderBy('quantity_in_stock', 'desc')
            ->limit(10)
            ->get();

        // Upcoming flights needing catering
        $upcomingFlights = Flight::with(['requests'])
            ->where('departure_time', '>', now())
            ->where('departure_time', '<', now()->addDays(3))
            ->orderBy('departure_time', 'asc')
            ->limit(10)
            ->get();

        // Catering Staff Activity Overview (for oversight)
        $cateringStaffActivity = [
            'total_staff' => User::role('Catering Staff')->count(),
            'active_requests' => RequestModel::where('requester_id', '!=', null)
                ->whereIn('status', ['pending_catering_incharge', 'catering_approved', 'supervisor_approved', 'items_issued', 'catering_staff_received', 'pending_final_approval'])
                ->count(),
            'pending_staff_receipt' => RequestModel::where('status', 'items_issued')->count(),
            'pending_final_approval' => RequestModel::where('status', 'pending_final_approval')->count(),
            'recent_staff_requests' => RequestModel::with(['flight', 'requester', 'items.product'])
                ->whereHas('requester', function($q) {
                    $q->role('Catering Staff');
                })
                ->latest()
                ->limit(5)
                ->get(),
        ];

        // Recent authenticated stock movements (for tracking)
        $recentAuthenticatedRequests = RequestModel::with(['flight', 'requester', 'items.product'])
            ->whereIn('status', ['security_authenticated', 'ramp_dispatched', 'loaded', 'delivered'])
            ->latest('updated_at')
            ->limit(10)
            ->get();

        return view('catering-incharge.dashboard', compact(
            'pendingReceipts',
            'pendingRequests',
            'pendingItemReceipts',
            'approvedReceipts',
            'approvedRequests',
            'totalCateringStock',
            'lowStockItems',
            'pendingReceiptsList',
            'pendingStaffRequests',
            'recentlyApproved',
            'stockOverview',
            'upcomingFlights',
            'cateringStaffActivity',
            'recentAuthenticatedRequests'
        ));
    }
}
