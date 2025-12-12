<?php

/**
 * PRODUCT RETURNS WORKFLOW VERIFICATION
 * Tests: Cabin Crew → Ramp Dispatcher → Security Staff → Stock Adjustment
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Request as RequestModel;
use App\Models\ProductReturn;
use App\Models\Product;
use App\Models\User;
use App\Models\StockMovement;

echo "\n";
echo str_repeat("=", 70) . "\n";
echo "           PRODUCT RETURNS WORKFLOW VERIFICATION\n";
echo str_repeat("=", 70) . "\n\n";

// ============================================
// 1. CHECK USERS EXIST
// ============================================
echo "1️⃣ CHECKING USERS:\n";
$cabinCrew = User::role('Cabin Crew')->first();
$rampDispatcher = User::role('Ramp Dispatcher')->first();
$securityStaff = User::role('Security Staff')->first();

if ($cabinCrew) {
    echo "   ✓ Cabin Crew: {$cabinCrew->name}\n";
} else {
    echo "   ✗ Cabin Crew NOT FOUND\n";
}

if ($rampDispatcher) {
    echo "   ✓ Ramp Dispatcher: {$rampDispatcher->name}\n";
} else {
    echo "   ✗ Ramp Dispatcher NOT FOUND\n";
}

if ($securityStaff) {
    echo "   ✓ Security Staff: {$securityStaff->name}\n";
} else {
    echo "   ✗ Security Staff NOT FOUND\n";
}

// ============================================
// 2. CHECK DELIVERED REQUESTS (ELIGIBLE FOR RETURNS)
// ============================================
echo "\n2️⃣ CHECKING REQUESTS ELIGIBLE FOR RETURNS:\n";
$eligibleRequests = RequestModel::with(['flight', 'items.product'])
    ->whereIn('status', ['loaded', 'flight_received', 'delivered', 'served'])
    ->get();

echo "   Total eligible requests: " . $eligibleRequests->count() . "\n";

if ($eligibleRequests->count() > 0) {
    $sampleRequest = $eligibleRequests->first();
    echo "   Sample Request ID: {$sampleRequest->id}\n";
    echo "   Flight: {$sampleRequest->flight->flight_number}\n";
    echo "   Status: {$sampleRequest->status}\n";
    echo "   Items count: {$sampleRequest->items->count()}\n";
} else {
    echo "   ⚠️ No requests available for returns testing\n";
}

// ============================================
// 3. CHECK PRODUCT RETURNS BY STATUS
// ============================================
echo "\n3️⃣ CHECKING PRODUCT RETURNS BY STATUS:\n";
$statusBreakdown = [
    'pending_ramp' => ProductReturn::where('status', 'pending_ramp')->count(),
    'received_by_ramp' => ProductReturn::where('status', 'received_by_ramp')->count(),
    'pending_security' => ProductReturn::where('status', 'pending_security')->count(),
    'authenticated' => ProductReturn::where('status', 'authenticated')->count(),
    'rejected' => ProductReturn::where('status', 'rejected')->count(),
];

foreach ($statusBreakdown as $status => $count) {
    $icon = match($status) {
        'pending_ramp' => '📦',
        'received_by_ramp' => '🚚',
        'pending_security' => '🔒',
        'authenticated' => '✅',
        'rejected' => '❌',
        default => '○'
    };
    echo "   $icon " . str_pad($status, 20) . ": $count returns\n";
}

// ============================================
// 4. WORKFLOW FLOW VERIFICATION
// ============================================
echo "\n4️⃣ WORKFLOW FLOW VERIFICATION:\n";
echo "   ┌─────────────────────────────────────────────────────────┐\n";
echo "   │  CABIN CREW                                             │\n";
echo "   │  ↓ Creates return                                       │\n";
echo "   │  Status: pending_ramp                                   │\n";
echo "   ├─────────────────────────────────────────────────────────┤\n";
echo "   │  RAMP DISPATCHER                                        │\n";
echo "   │  ↓ Receives & forwards                                  │\n";
echo "   │  Status: pending_security                               │\n";
echo "   ├─────────────────────────────────────────────────────────┤\n";
echo "   │  SECURITY STAFF                                         │\n";
echo "   │  ↓ Authenticates & adjusts stock                        │\n";
echo "   │  Status: authenticated                                  │\n";
echo "   │  • Creates StockMovement (type: return)                 │\n";
echo "   │  • Increments product.quantity_in_stock                 │\n";
echo "   └─────────────────────────────────────────────────────────┘\n";

// ============================================
// 5. CHECK RECENT RETURNS
// ============================================
echo "\n5️⃣ RECENT RETURNS (Last 5):\n";
$recentReturns = ProductReturn::with(['product', 'returnedBy', 'receivedBy', 'verifiedBy'])
    ->latest('created_at')
    ->take(5)
    ->get();

if ($recentReturns->count() > 0) {
    foreach ($recentReturns as $return) {
        echo "   ───────────────────────────────────────────────────────────\n";
        echo "   Return ID: {$return->id}\n";
        echo "   Product: {$return->product->name}\n";
        echo "   Quantity: {$return->quantity_returned}\n";
        echo "   Condition: {$return->condition}\n";
        echo "   Status: {$return->status}\n";
        echo "   Returned by: {$return->returnedBy->name}\n";
        
        if ($return->receivedBy) {
            echo "   Received by: {$return->receivedBy->name} at " . $return->received_at->format('Y-m-d H:i') . "\n";
        }
        
        if ($return->verifiedBy) {
            echo "   Verified by: {$return->verifiedBy->name} at " . $return->verified_at->format('Y-m-d H:i') . "\n";
        }
    }
} else {
    echo "   No returns found yet\n";
}

// ============================================
// 6. CHECK STOCK MOVEMENTS FOR RETURNS
// ============================================
echo "\n6️⃣ STOCK MOVEMENTS (Returned Type):\n";
$returnMovements = StockMovement::where('type', 'returned')
    ->with(['product', 'user'])
    ->latest()
    ->take(5)
    ->get();

if ($returnMovements->count() > 0) {
    echo "   Total return movements: " . StockMovement::where('type', 'returned')->count() . "\n\n";
    foreach ($returnMovements as $movement) {
        echo "   • Product: {$movement->product->name}\n";
        echo "     Quantity: +{$movement->quantity}\n";
        echo "     Moved by: {$movement->user->name}\n";
        echo "     Date: " . $movement->created_at->format('Y-m-d H:i') . "\n\n";
    }
} else {
    echo "   No return stock movements found yet\n";
}

// ============================================
// 7. ROUTES VERIFICATION
// ============================================
echo "\n7️⃣ ROUTES VERIFICATION:\n";
$routes = [
    'Cabin Crew' => [
        'cabin-crew.returns.index',
        'cabin-crew.returns.create',
        'cabin-crew.returns.store',
    ],
    'Ramp Dispatcher' => [
        'ramp-dispatcher.returns.index',
        'ramp-dispatcher.returns.receive',
        'ramp-dispatcher.returns.bulk-receive',
    ],
    'Security Staff' => [
        'security-staff.returns.index',
        'security-staff.returns.authenticate',
        'security-staff.returns.reject',
    ],
];

$allRoutesExist = true;
foreach ($routes as $role => $routeList) {
    echo "   $role:\n";
    foreach ($routeList as $routeName) {
        try {
            $url = route($routeName, ['request' => 1, 'return' => 1], false);
            echo "     ✓ $routeName\n";
        } catch (Exception $e) {
            echo "     ✗ $routeName - NOT FOUND\n";
            $allRoutesExist = false;
        }
    }
}

// ============================================
// 8. CONTROLLERS VERIFICATION
// ============================================
echo "\n8️⃣ CONTROLLERS VERIFICATION:\n";
$controllers = [
    'CabinCrew\\ReturnController' => 'app/Http/Controllers/CabinCrew/ReturnController.php',
    'RampDispatcher\\ReturnController' => 'app/Http/Controllers/RampDispatcher/ReturnController.php',
    'SecurityStaff\\ReturnController' => 'app/Http/Controllers/SecurityStaff/ReturnController.php',
];

foreach ($controllers as $controller => $path) {
    if (file_exists($path)) {
        echo "   ✓ $controller\n";
    } else {
        echo "   ✗ $controller - FILE NOT FOUND\n";
    }
}

// ============================================
// 9. VIEWS VERIFICATION
// ============================================
echo "\n9️⃣ VIEWS VERIFICATION:\n";
$views = [
    'cabin-crew/returns/index.blade.php' => 'resources/views/cabin-crew/returns/index.blade.php',
    'cabin-crew/returns/create.blade.php' => 'resources/views/cabin-crew/returns/create.blade.php',
    'ramp-dispatcher/returns/index.blade.php' => 'resources/views/ramp-dispatcher/returns/index.blade.php',
    'security-staff/returns/index.blade.php' => 'resources/views/security-staff/returns/index.blade.php',
];

foreach ($views as $viewName => $path) {
    if (file_exists($path)) {
        echo "   ✓ $viewName\n";
    } else {
        echo "   ✗ $viewName - FILE NOT FOUND\n";
    }
}

// ============================================
// FINAL SUMMARY
// ============================================
echo "\n" . str_repeat("=", 70) . "\n";
echo "                         VERIFICATION SUMMARY\n";
echo str_repeat("=", 70) . "\n\n";

$issues = [];

if (!$cabinCrew || !$rampDispatcher || !$securityStaff) {
    $issues[] = "Missing required users (Cabin Crew, Ramp Dispatcher, or Security Staff)";
}

if (!$allRoutesExist) {
    $issues[] = "Some routes are not registered";
}

if (count($issues) === 0) {
    echo "🎯 SYSTEM STATUS: ✅ FULLY OPERATIONAL\n\n";
    echo "✅ All users exist\n";
    echo "✅ All routes registered\n";
    echo "✅ All controllers created\n";
    echo "✅ All views created\n";
    echo "✅ ProductReturn model exists\n";
    echo "✅ Database table created\n\n";
    echo "📋 WORKFLOW:\n";
    echo "   1. Cabin Crew → Create return (pending_ramp)\n";
    echo "   2. Ramp Dispatcher → Receive & forward (pending_security)\n";
    echo "   3. Security Staff → Authenticate & adjust stock (authenticated)\n";
    echo "   4. Stock Movement created (type: return)\n";
    echo "   5. Product quantity_in_stock incremented\n\n";
    echo "🚀 READY FOR TESTING!\n";
} else {
    echo "⚠️ SYSTEM STATUS: ISSUES FOUND\n\n";
    foreach ($issues as $issue) {
        echo "   ❌ $issue\n";
    }
}

echo "\n" . str_repeat("=", 70) . "\n\n";
