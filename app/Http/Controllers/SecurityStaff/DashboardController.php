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
        // Get statistics (NEW WORKFLOW)
        $pendingVerification = RequestModel::where('status', 'catering_final_approved')->count();
        $verifiedToday = RequestModel::where('status', 'security_authenticated')
            ->whereDate('updated_at', today())
            ->count();
        $blockedToday = RequestModel::where('status', 'rejected')
            ->whereDate('updated_at', today())
            ->count();

        // Orders pending security check (NEW WORKFLOW - awaiting security authentication)
        $ordersToVerify = RequestModel::with(['flight', 'requester', 'items.product.category'])
            ->where('status', 'catering_final_approved')
            ->whereHas('flight', function($query) {
                $query->where('departure_time', '>', now());
            })
            ->latest()
            ->limit(15)
            ->get();
        
        // Calculate verification metrics for each request
        foreach ($ordersToVerify as $request) {
            // Document-level checks
            $request->document_checks = [
                'has_flight_number' => !empty($request->flight->flight_number),
                'has_aircraft_type' => !empty($request->flight->aircraft_type),
                'has_requester' => !empty($request->requester_id),
                'is_within_cutoff' => $request->flight->departure_time > now()->addHours(2),
                'status_valid' => in_array($request->status, ['catering_final_approved']),
            ];
            
            // Items verification
            $request->items_metrics = [
                'total_items' => $request->items->count(),
                'items_with_category' => $request->items->filter(fn($i) => $i->product->category_id)->count(),
                'high_quantity_items' => $request->items->filter(fn($i) => $i->quantity > 50)->count(),
            ];
            
            // Auto risk assessment
            $riskScore = 0;
            if ($request->flight->departure_time < now()->addHours(6)) $riskScore += 2; // Flight soon
            if ($request->items->count() > 20) $riskScore += 1; // Many items
            if ($request->items->sum('quantity') > 100) $riskScore += 1; // High quantity
            
            $request->risk_level = $riskScore >= 3 ? 'HIGH' : ($riskScore >= 1 ? 'MEDIUM' : 'LOW');
            $request->risk_color = $riskScore >= 3 ? 'red' : ($riskScore >= 1 ? 'orange' : 'green');
        }

        // Recently verified orders (NEW WORKFLOW - show all authenticated requests)
        $recentVerifications = RequestModel::with(['flight', 'requester'])
            ->whereIn('status', ['security_authenticated', 'ramp_dispatched', 'loaded', 'delivered', 'served', 'rejected'])
            ->latest('updated_at')
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
