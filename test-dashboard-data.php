<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Request;
use App\Models\StockMovement;
use App\Models\User;

echo "=== TESTING ADMIN DASHBOARD DATA QUERIES ===\n\n";

// Same queries as in DashboardController
$totalRequests = Request::count();
echo "Total Requests: $totalRequests\n\n";

// Request trends by status
echo "Request Status Distribution:\n";
$requestsByStatus = [
    'Pending' => Request::whereIn('status', ['pending_inventory', 'pending_supervisor'])->count(),
    'Approved' => Request::whereIn('status', ['supervisor_approved', 'security_approved', 'catering_approved'])->count(),
    'In Progress' => Request::whereIn('status', ['ready_for_dispatch', 'dispatched', 'loaded'])->count(),
    'Completed' => Request::where('status', 'delivered')->count(),
];

foreach ($requestsByStatus as $status => $count) {
    $percentage = $totalRequests > 0 ? round(($count / $totalRequests) * 100, 1) : 0;
    echo "  - $status: $count ($percentage%)\n";
}

// Requests by department
echo "\nRequests by Department:\n";
$requestsByDepartment = [
    'Catering Staff' => Request::whereHas('requester', function($q) {
        $q->whereHas('roles', fn($r) => $r->where('name', 'Catering Staff'));
    })->count(),
    'Inventory' => Request::whereIn('status', ['pending_inventory', 'pending_supervisor', 'supervisor_approved'])->count(),
    'Security' => Request::whereIn('status', ['sent_to_security', 'security_approved'])->count(),
    'Ramp Operations' => Request::whereIn('status', ['ready_for_dispatch', 'dispatched'])->count(),
    'Flight Operations' => Request::whereIn('status', ['loaded', 'delivered'])->count(),
];

$maxCount = max(array_values($requestsByDepartment)) ?: 1;
foreach ($requestsByDepartment as $dept => $count) {
    $width = round(($count / $maxCount) * 100, 1);
    echo "  - $dept: $count (bar width: $width%)\n";
}

// Latest requests
echo "\nLatest 5 Requests:\n";
$latestRequests = Request::with(['requester', 'flight'])
    ->orderBy('created_at', 'desc')
    ->take(5)
    ->get();

if ($latestRequests->count() > 0) {
    foreach ($latestRequests as $request) {
        $flightNumber = $request->flight->flight_number ?? 'N/A';
        $requesterName = $request->requester->name ?? 'Unknown';
        $ago = $request->created_at->diffForHumans();
        echo "  - Flight: $flightNumber, Status: {$request->status}, By: $requesterName, $ago\n";
    }
} else {
    echo "  No requests found\n";
}

// Latest approvals
echo "\nLatest 5 Approvals:\n";
$latestApprovals = Request::with(['requester', 'flight'])
    ->whereIn('status', ['supervisor_approved', 'security_approved', 'catering_approved'])
    ->orderBy('updated_at', 'desc')
    ->take(5)
    ->get();

if ($latestApprovals->count() > 0) {
    foreach ($latestApprovals as $approval) {
        $flightNumber = $approval->flight->flight_number ?? 'N/A';
        $requesterName = $approval->requester->name ?? 'Unknown';
        $ago = $approval->updated_at->diffForHumans();
        echo "  - Flight: $flightNumber, Status: {$approval->status}, By: $requesterName, $ago\n";
    }
} else {
    echo "  No approvals found\n";
}

// Recent stock movements
echo "\nRecent 5 Stock Movements:\n";
$recentStock = StockMovement::with(['product', 'user'])
    ->orderBy('created_at', 'desc')
    ->take(5)
    ->get();

if ($recentStock->count() > 0) {
    foreach ($recentStock as $stock) {
        $productName = $stock->product->name ?? 'N/A';
        $userName = $stock->user->name ?? 'System';
        $ago = $stock->created_at->diffForHumans();
        echo "  - Product: $productName, Type: {$stock->type}, Qty: {$stock->quantity}, By: $userName, $ago\n";
    }
} else {
    echo "  No stock movements found\n";
}

echo "\n=== DASHBOARD DATA QUERIES TEST COMPLETE ===\n";
echo "\nConclusion: ";
if ($totalRequests > 0 || $recentStock->count() > 0) {
    echo "✓ System has data - Dashboard should display dynamic information\n";
} else {
    echo "✗ No data found - Dashboard will show empty states\n";
}
