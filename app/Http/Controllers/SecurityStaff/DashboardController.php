<?php

namespace App\Http\Controllers\SecurityStaff;

use App\Http\Controllers\Controller;
use App\Models\Request as RequestModel;
use App\Models\ProductReturn;
use App\Models\Flight;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $pendingVerification = RequestModel::where('status', 'sent_to_security')->count();
        $verifiedToday = RequestModel::where('status', 'security_approved')
            ->whereDate('updated_at', today())
            ->count();
        $blockedToday = RequestModel::where('status', 'rejected')
            ->whereDate('updated_at', today())
            ->count();

        // Orders pending security check
        $ordersToVerify = RequestModel::with(['flight', 'requester', 'items.product'])
            ->where('status', 'sent_to_security')
            ->whereHas('flight', function($query) {
                $query->where('departure_time', '>', now());
            })
            ->latest()
            ->limit(15)
            ->get();

        // Recently verified orders
        $recentVerifications = RequestModel::with(['flight', 'requester'])
            ->whereIn('status', ['security_approved', 'rejected'])
            ->latest()
            ->limit(10)
            ->get();

        // Returns management
        $pendingReturns = ProductReturn::where('status', 'pending_security')->count();
        
        // Today's flights needing security clearance
        $todayFlights = Flight::with(['requests'])
            ->whereDate('departure_time', today())
            ->orderBy('departure_time', 'asc')
            ->get();

        // Returns management
        $pendingReturns = ProductReturn::where('status', 'pending_security')->count();
        
        // Recent stock movements authenticated by Security Staff
        $securityRoleId = \Spatie\Permission\Models\Role::where('name', 'Security Staff')->first()?->id;
        $securityUserIds = \App\Models\User::role('Security Staff')->pluck('id');
        
        $recentStockMovements = \App\Models\StockMovement::with(['product', 'user'])
            ->where('type', 'issued')
            ->whereIn('user_id', $securityUserIds)
            ->where('notes', 'like', '%security authentication%')
            ->latest('movement_date')
            ->limit(10)
            ->get();

        return view('security-staff.dashboard', compact(
            'pendingVerification',
            'verifiedToday',
            'blockedToday',
            'ordersToVerify',
            'recentVerifications',
            'todayFlights',
            'recentStockMovements',
            'pendingReturns'
        ));
    }
}
