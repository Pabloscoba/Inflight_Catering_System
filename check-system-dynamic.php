<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Request as RequestModel;
use App\Models\Flight;
use App\Models\Product;
use App\Models\Category;

echo "=== COMPREHENSIVE SYSTEM VERIFICATION ===\n\n";

// 1. Check Users and Roles
echo "1. USERS & ROLES:\n";
echo str_repeat("-", 50) . "\n";
$users = User::with('roles')->get();
foreach ($users as $user) {
    $roleName = $user->roles->first()->name ?? 'No Role';
    echo "User #{$user->id}: {$user->name} - Role: {$roleName}\n";
}

// 2. Check Flights
echo "\n2. FLIGHTS:\n";
echo str_repeat("-", 50) . "\n";
$flights = Flight::all();
echo "Total Flights: {$flights->count()}\n";
foreach ($flights as $flight) {
    $status = $flight->status ?? 'scheduled';
    $departure = \Carbon\Carbon::parse($flight->departure_time);
    $isPast = $departure->isPast() ? '(PAST)' : '(FUTURE)';
    echo "Flight #{$flight->id}: {$flight->flight_number} | {$flight->origin}→{$flight->destination} | ";
    echo "{$departure->format('Y-m-d H:i')} {$isPast}\n";
}

// 3. Check Requests and Status Flow
echo "\n3. REQUESTS & STATUS FLOW:\n";
echo str_repeat("-", 50) . "\n";
$requests = RequestModel::with(['flight', 'requester', 'items'])->get();
echo "Total Requests: {$requests->count()}\n\n";

$statusCounts = [
    'pending_inventory' => 0,
    'pending_supervisor' => 0,
    'supervisor_approved' => 0,
    'sent_to_security' => 0,
    'security_approved' => 0,
    'catering_approved' => 0,
    'ready_for_dispatch' => 0,
    'dispatched' => 0,
    'loaded' => 0,
    'delivered' => 0,
];

foreach ($requests as $req) {
    if (isset($statusCounts[$req->status])) {
        $statusCounts[$req->status]++;
    }
    echo "Request #{$req->id}: Status={$req->status} | Flight={$req->flight->flight_number} | ";
    echo "Items={$req->items->count()} | Requester={$req->requester->name}\n";
}

echo "\nStatus Distribution:\n";
foreach ($statusCounts as $status => $count) {
    if ($count > 0) {
        echo "  - {$status}: {$count}\n";
    }
}

// 4. Check Products and Categories
echo "\n4. PRODUCTS & CATEGORIES:\n";
echo str_repeat("-", 50) . "\n";
$categories = Category::withCount('products')->get();
echo "Total Categories: {$categories->count()}\n";
foreach ($categories as $category) {
    echo "  - {$category->name}: {$category->products_count} products\n";
}

$totalProducts = Product::count();
$activeProducts = Product::where('is_active', true)->count();
$approvedProducts = Product::where('status', 'approved')->count();
echo "\nTotal Products: {$totalProducts} (Active: {$activeProducts}, Approved: {$approvedProducts})\n";

// 5. Check Request Items with Usage Tracking
echo "\n5. REQUEST ITEMS USAGE TRACKING:\n";
echo str_repeat("-", 50) . "\n";
$itemsWithUsage = \DB::table('request_items')
    ->where('quantity_used', '>', 0)
    ->orWhere('quantity_defect', '>', 0)
    ->get();
    
echo "Items with usage tracking: {$itemsWithUsage->count()}\n";
if ($itemsWithUsage->count() > 0) {
    foreach ($itemsWithUsage as $item) {
        echo "  Item #{$item->id}: Used={$item->quantity_used}, Defect={$item->quantity_defect}\n";
    }
} else {
    echo "  (No usage data yet - ready for Cabin Crew to track)\n";
}

// 6. Check Additional Product Requests
echo "\n6. ADDITIONAL PRODUCT REQUESTS:\n";
echo str_repeat("-", 50) . "\n";
$additionalRequests = \DB::table('additional_product_requests')->get();
echo "Total Additional Requests: {$additionalRequests->count()}\n";
if ($additionalRequests->count() > 0) {
    $pending = $additionalRequests->where('status', 'pending')->count();
    $approved = $additionalRequests->where('status', 'approved')->count();
    $delivered = $additionalRequests->where('status', 'delivered')->count();
    $rejected = $additionalRequests->where('status', 'rejected')->count();
    
    echo "  - Pending: {$pending}\n";
    echo "  - Approved: {$approved}\n";
    echo "  - Delivered: {$delivered}\n";
    echo "  - Rejected: {$rejected}\n";
} else {
    echo "  (No additional requests yet - ready for Cabin Crew to request)\n";
}

// 7. Workflow Validation
echo "\n7. WORKFLOW VALIDATION:\n";
echo str_repeat("-", 50) . "\n";

// Check if requests can move through workflow
$workflow = [
    'catering_approved' => 'Can be sent to Ramp',
    'ready_for_dispatch' => 'Can be dispatched by Ramp',
    'dispatched' => 'Can be loaded by Flight Purser',
    'loaded' => 'Can be delivered by Cabin Crew',
];

foreach ($workflow as $status => $action) {
    $count = RequestModel::where('status', $status)->count();
    $icon = $count > 0 ? '✓' : '○';
    echo "{$icon} {$status}: {$count} requests ({$action})\n";
}

// 8. Check Dashboard Queries
echo "\n8. DASHBOARD QUERIES VALIDATION:\n";
echo str_repeat("-", 50) . "\n";

// Catering Staff Dashboard
$readyForCollection = RequestModel::where('status', 'catering_approved')->count();
echo "Catering Staff - Approved Requests: {$readyForCollection}\n";

// Ramp Dispatcher Dashboard
$ordersToDispatch = RequestModel::where('status', 'ready_for_dispatch')
    ->whereHas('flight', function($query) {
        $query->where('departure_time', '>', now());
    })->count();
echo "Ramp Dispatcher - Orders to Dispatch: {$ordersToDispatch}\n";

// Flight Purser Dashboard
$requestsToLoad = RequestModel::where('status', 'dispatched')
    ->whereHas('flight', function($query) {
        $query->where('departure_time', '>', now());
    })->count();
echo "Flight Purser - Requests to Load: {$requestsToLoad}\n";

// Cabin Crew Dashboard
$requestsToReceive = RequestModel::where('status', 'loaded')
    ->whereHas('flight', function($query) {
        $query->where('departure_time', '>', now());
    })->count();
echo "Cabin Crew - Requests to Receive: {$requestsToReceive}\n";

// 9. Check Dynamic Relationships
echo "\n9. RELATIONSHIP VALIDATION:\n";
echo str_repeat("-", 50) . "\n";
$sampleRequest = RequestModel::with(['flight', 'requester', 'items.product'])->first();
if ($sampleRequest) {
    echo "✓ Request → Flight relationship: Working\n";
    echo "✓ Request → Requester relationship: Working\n";
    echo "✓ Request → Items → Product relationship: Working\n";
    echo "  Sample: Request #{$sampleRequest->id} has {$sampleRequest->items->count()} items\n";
} else {
    echo "✗ No requests found to test relationships\n";
}

// 10. Final Summary
echo "\n" . str_repeat("=", 50) . "\n";
echo "VERIFICATION SUMMARY:\n";
echo str_repeat("=", 50) . "\n";

$checks = [
    'Users & Roles' => $users->count() > 0,
    'Flights' => $flights->count() > 0,
    'Products' => $totalProducts > 0,
    'Requests' => $requests->count() > 0,
    'Status Flow' => count(array_filter($statusCounts)) > 0,
    'Usage Tracking Fields' => \DB::getSchemaBuilder()->hasColumn('request_items', 'quantity_used'),
    'Additional Requests Table' => \DB::getSchemaBuilder()->hasTable('additional_product_requests'),
];

$allPassed = true;
foreach ($checks as $check => $result) {
    $status = $result ? '✓ PASS' : '✗ FAIL';
    echo "{$status}: {$check}\n";
    if (!$result) $allPassed = false;
}

echo "\n" . ($allPassed ? "✓ SYSTEM IS FULLY DYNAMIC AND READY!" : "⚠ Some checks failed") . "\n";
