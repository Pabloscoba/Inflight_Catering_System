<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Request as RequestModel;
use App\Models\User;

echo "\n=== CHECKING NEW 9-STEP WORKFLOW ===\n\n";

// Find the test request (ID #3)
$request = RequestModel::find(3);

if (!$request) {
    echo "❌ Request #3 not found\n";
    exit;
}

echo "Request #3 Details:\n";
echo "- Flight: " . $request->flight->flight_number . "\n";
echo "- Current Status: " . $request->status . "\n";
echo "- Created By: " . $request->requester->name . "\n";
echo "\n";

// Check Catering Incharge
$cateringIncharge = User::role('Catering Incharge')->first();
if ($cateringIncharge) {
    echo "✓ Catering Incharge found: " . $cateringIncharge->name . "\n";
    echo "  - Has permission 'approve deny catering requests': " . ($cateringIncharge->can('approve deny catering requests') ? 'YES' : 'NO') . "\n";
} else {
    echo "❌ No Catering Incharge user found\n";
}
echo "\n";

// Check Inventory Supervisor
$supervisor = User::role('Inventory Supervisor')->first();
if ($supervisor) {
    echo "✓ Inventory Supervisor found: " . $supervisor->name . "\n";
    echo "  - Has permission 'approve deny catering requests': " . ($supervisor->can('approve deny catering requests') ? 'YES' : 'NO') . "\n";
    echo "  - Has permission 'view incoming requests from catering staff': " . ($supervisor->can('view incoming requests from catering staff') ? 'YES' : 'NO') . "\n";
    
    // Check if supervisor can see the request
    $pendingForSupervisor = RequestModel::where('status', 'catering_approved')->count();
    echo "  - Requests with status 'catering_approved': $pendingForSupervisor\n";
} else {
    echo "❌ No Inventory Supervisor user found\n";
}
echo "\n";

// Check Inventory Personnel
$personnel = User::role('Inventory Personnel')->first();
if ($personnel) {
    echo "✓ Inventory Personnel found: " . $personnel->name . "\n";
    echo "  - Has permission 'issue products to catering staff': " . ($personnel->can('issue products to catering staff') ? 'YES' : 'NO') . "\n";
} else {
    echo "❌ No Inventory Personnel user found\n";
}
echo "\n";

// Check workflow statuses in database
echo "Workflow Status Checks:\n";
$statuses = ['pending_catering_incharge', 'catering_approved', 'supervisor_approved', 'items_issued', 'catering_staff_received', 'pending_final_approval'];
foreach ($statuses as $status) {
    $count = RequestModel::where('status', $status)->count();
    echo "  - $status: $count request(s)\n";
}
echo "\n";

// Check notifications
echo "Checking notifications for Inventory Supervisor:\n";
if ($supervisor) {
    $unreadNotifications = $supervisor->unreadNotifications()->count();
    $allNotifications = $supervisor->notifications()->count();
    echo "  - Total notifications: $allNotifications\n";
    echo "  - Unread notifications: $unreadNotifications\n";
    
    if ($allNotifications > 0) {
        $latestNotification = $supervisor->notifications()->latest()->first();
        echo "  - Latest notification type: " . class_basename($latestNotification->type) . "\n";
        $data = $latestNotification->data;
        echo "  - Action URL: " . ($data['action_url'] ?? 'N/A') . "\n";
    }
}
echo "\n";

echo "=== WORKFLOW FLOW ===\n";
echo "1. Catering Staff creates request → status: pending_catering_incharge\n";
echo "2. Catering Incharge approves → status: catering_approved (forwards to Inventory Supervisor)\n";
echo "3. Inventory Supervisor approves → status: supervisor_approved (forwards to Inventory Personnel)\n";
echo "4. Inventory Personnel issues items → status: items_issued\n";
echo "5. Catering Staff receives items → status: catering_staff_received\n";
echo "6. System sets status → pending_final_approval\n";
echo "7. Catering Incharge final approval → status: catering_final_approved (forwards to Security)\n";
echo "8. Security authenticates → status: security_authenticated (forwards to Ramp)\n";
echo "9. Ramp dispatches → status: ramp_dispatched (forwards to Flight Purser)\n";
echo "10. Flight Purser loads → status: loaded\n";
echo "11. Cabin Crew delivers → status: delivered\n";
echo "12. Cabin Crew serves → status: served\n";
echo "\n";

echo "=== RECOMMENDATIONS ===\n";
if ($request->status == 'catering_approved') {
    echo "✓ Request is correctly at step 2 (catering_approved)\n";
    echo "→ Inventory Supervisor should now be able to see and approve this request\n";
    echo "→ Check: http://localhost:8000/inventory-supervisor/requests/pending\n";
} else {
    echo "! Request is at status: " . $request->status . "\n";
    echo "→ Expected status for Inventory Supervisor approval: catering_approved\n";
}
echo "\n";

