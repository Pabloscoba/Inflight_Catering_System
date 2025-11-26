<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== WORKFLOW VERIFICATION ===\n\n";

// Check Request statuses
echo "1. REQUEST STATUS FLOW:\n";
echo "   catering_approved → dispatched → loaded → delivered\n\n";

// Check if requests exist at catering_approved stage
$cateringApproved = App\Models\Request::where('status', 'catering_approved')->count();
echo "   Requests at 'catering_approved' (ready for dispatch): $cateringApproved\n";

$dispatched = App\Models\Request::where('status', 'dispatched')->count();
echo "   Requests at 'dispatched' (with Ramp): $dispatched\n";

$loaded = App\Models\Request::where('status', 'loaded')->count();
echo "   Requests at 'loaded' (on aircraft): $loaded\n";

$delivered = App\Models\Request::where('status', 'delivered')->count();
echo "   Requests at 'delivered' (to cabin crew): $delivered\n\n";

// Check user roles exist
echo "2. USER ROLES:\n";
$roles = ['Catering Staff', 'Ramp Dispatcher', 'Flight Purser', 'Cabin Crew'];
foreach ($roles as $role) {
    $user = App\Models\User::role($role)->first();
    if ($user) {
        echo "   ✓ $role: {$user->name} ({$user->email})\n";
    } else {
        echo "   ✗ $role: NOT FOUND\n";
    }
}

echo "\n3. SAMPLE REQUEST (ID: 7):\n";
$request = App\Models\Request::find(7);
if ($request) {
    echo "   Status: {$request->status}\n";
    echo "   Requester: {$request->requester->name}\n";
    echo "   Flight: {$request->flight->flight_number}\n";
    echo "   Items: {$request->items->count()}\n";
}

echo "\n=== END ===\n";
