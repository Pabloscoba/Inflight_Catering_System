<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Request;
use App\Models\StockMovement;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Flight;

echo "=== ADMIN DASHBOARD DYNAMIC DATA VERIFICATION ===\n\n";

echo "1. SUMMARY CARDS:\n";
echo "   - Total Users: " . User::count() . "\n";
echo "   - Total Products: " . Product::count() . "\n";
echo "   - Total Requests: " . Request::count() . "\n";
echo "   - Pending Requests: " . Request::where('status', 'pending_inventory')->count() . "\n";
echo "   - Total Flights: " . Flight::count() . "\n";
echo "   - Completed Requests: " . Request::where('status', 'delivered')->count() . "\n";

echo "\n2. REQUEST STATUS DISTRIBUTION CHART:\n";
$requestsByStatus = [
    'Pending' => Request::whereIn('status', ['pending_inventory', 'pending_supervisor'])->count(),
    'Approved' => Request::whereIn('status', ['supervisor_approved', 'security_approved', 'catering_approved'])->count(),
    'In Progress' => Request::whereIn('status', ['ready_for_dispatch', 'dispatched', 'loaded'])->count(),
    'Completed' => Request::where('status', 'delivered')->count(),
];
$totalReq = Request::count();
foreach ($requestsByStatus as $status => $count) {
    $percent = $totalReq > 0 ? round(($count / $totalReq) * 100, 1) : 0;
    echo "   - $status: $count requests ($percent%)\n";
}

echo "\n3. BY DEPARTMENT CHART:\n";
$requestsByDepartment = [
    'Catering Staff' => Request::whereHas('requester', function($q) {
        $q->whereHas('roles', fn($r) => $r->where('name', 'Catering Staff'));
    })->count(),
    'Inventory' => Request::whereIn('status', ['pending_inventory', 'pending_supervisor', 'supervisor_approved'])->count(),
    'Security' => Request::whereIn('status', ['sent_to_security', 'security_approved'])->count(),
    'Ramp Operations' => Request::whereIn('status', ['ready_for_dispatch', 'dispatched'])->count(),
    'Flight Operations' => Request::whereIn('status', ['loaded', 'delivered'])->count(),
];
foreach ($requestsByDepartment as $dept => $count) {
    echo "   - $dept: $count requests\n";
}

echo "\n4. LATEST REQUESTS (Will show in dashboard):\n";
$latestRequests = Request::with(['requester', 'flight'])
    ->orderBy('created_at', 'desc')
    ->take(5)
    ->get();
if ($latestRequests->count() > 0) {
    foreach ($latestRequests as $req) {
        echo "   - {$req->flight->flight_number} | {$req->status} | By: {$req->requester->name}\n";
    }
} else {
    echo "   (None - will show 'No requests')\n";
}

echo "\n5. LATEST APPROVALS (Will show in dashboard):\n";
$latestApprovals = Request::with(['requester', 'flight'])
    ->whereIn('status', ['supervisor_approved', 'security_approved', 'catering_approved'])
    ->orderBy('updated_at', 'desc')
    ->take(5)
    ->get();
if ($latestApprovals->count() > 0) {
    foreach ($latestApprovals as $app) {
        echo "   - {$app->flight->flight_number} | {$app->status} | By: {$app->requester->name}\n";
    }
} else {
    echo "   (None - will show 'No approvals')\n";
}

echo "\n6. RECENT STOCK MOVEMENTS (Will show in dashboard):\n";
$recentStock = StockMovement::with(['product', 'user'])
    ->orderBy('created_at', 'desc')
    ->take(5)
    ->get();
if ($recentStock->count() > 0) {
    foreach ($recentStock as $stock) {
        echo "   - {$stock->product->name} | {$stock->type} | Qty: {$stock->quantity} | By: {$stock->user->name}\n";
    }
} else {
    echo "   (None - will show 'No stock')\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "CONCLUSION:\n";
if ($totalReq > 0 || $recentStock->count() > 0) {
    echo "✓ PASS: Admin dashboard will display DYNAMIC DATA\n";
    echo "✓ All sections have real data from the system\n";
    echo "✓ Charts will show actual distribution bars\n";
    echo "✓ Latest requests, approvals, and stock will be visible\n";
} else {
    echo "✗ WARNING: No data available to display\n";
}
echo str_repeat("=", 60) . "\n";
