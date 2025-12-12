<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Request as CateringRequest;
use App\Models\Flight;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $pendingRequests = CateringRequest::whereIn('status', ['pending_inventory', 'pending_supervisor'])->count();
        $completedRequests = CateringRequest::where('status', 'delivered')->count();
        $totalRequests = CateringRequest::count();
        $totalFlights = Flight::count();
        
        // Request trends by status
        $requestsByStatus = [
            'Pending' => CateringRequest::whereIn('status', ['pending_inventory', 'pending_supervisor'])->count(),
            'Approved' => CateringRequest::whereIn('status', ['supervisor_approved', 'security_approved', 'catering_approved'])->count(),
            'In Progress' => CateringRequest::whereIn('status', ['ready_for_dispatch', 'dispatched', 'loaded'])->count(),
            'Completed' => CateringRequest::where('status', 'delivered')->count(),
        ];
        
        // Requests by department (based on requester user role)
        $requestsByDepartment = [
            'Catering Staff' => CateringRequest::whereHas('requester', function($q) {
                $q->whereHas('roles', fn($r) => $r->where('name', 'Catering Staff'));
            })->count(),
            'Inventory' => CateringRequest::whereIn('status', ['pending_inventory', 'pending_supervisor', 'supervisor_approved'])->count(),
            'Security' => CateringRequest::whereIn('status', ['sent_to_security', 'security_approved'])->count(),
            'Ramp Operations' => CateringRequest::whereIn('status', ['ready_for_dispatch', 'dispatched'])->count(),
            'Flight Operations' => CateringRequest::whereIn('status', ['loaded', 'delivered'])->count(),
        ];
        
        // Latest requests (5 most recent)
        $latestRequests = CateringRequest::with([
            'requester', 
            'flight',
            'items.product',
            'approver',
            'cateringApprover',
            'securityDispatcher',
            'rampAgent',
            'flightPurser',
            'cabinCrew'
        ])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Latest approvals (requests that changed status recently)
        $latestApprovals = CateringRequest::with([
            'requester', 
            'flight',
            'approver', // Inventory Supervisor
            'cateringApprover', // Catering Incharge
            'securityDispatcher', // Security Staff
            'items.product'
        ])
            ->whereIn('status', ['supervisor_approved', 'security_approved', 'catering_approved', 'sent_to_ramp', 'dispatched', 'loaded', 'delivered'])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();
        
        // Pending stock movements awaiting approval
        $pendingStockMovements = \App\Models\StockMovement::with(['product', 'user'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->take(10)
            ->get();
        
        // Recent stock movements with full details
        $recentStock = \App\Models\StockMovement::with(['product', 'user', 'approvedBy'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        return view('dashboard.index', compact(
            'totalUsers',
            'totalProducts',
            'totalCategories',
            'pendingRequests',
            'completedRequests',
            'totalRequests',
            'totalFlights',
            'requestsByStatus',
            'requestsByDepartment',
            'latestRequests',
            'latestApprovals',
            'pendingStockMovements',
            'recentStock'
        ));
    }
}
