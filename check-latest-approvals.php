<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Latest Approvals Check ===\n\n";

$approvedStatuses = ['supervisor_approved', 'security_approved', 'catering_approved', 'sent_to_ramp', 'dispatched', 'loaded', 'delivered'];

$latestApprovals = App\Models\Request::with([
    'requester', 
    'flight',
    'approver',
    'cateringApprover',
    'securityDispatcher',
    'dispatcher',
    'flightPurser',
    'cabinCrew'
])
    ->whereIn('status', $approvedStatuses)
    ->orderBy('updated_at', 'desc')
    ->take(5)
    ->get();

if ($latestApprovals->isEmpty()) {
    echo "❌ No approved requests found!\n";
    echo "Current requests statuses:\n";
    $allRequests = App\Models\Request::all();
    foreach ($allRequests as $req) {
        echo "  Request #{$req->id}: {$req->status}\n";
    }
    exit;
}

echo "Found {$latestApprovals->count()} approved requests:\n\n";

foreach ($latestApprovals as $approval) {
    echo "✅ Request #{$approval->id}\n";
    echo "   Status: {$approval->status}\n";
    echo "   Flight: " . ($approval->flight->flight_number ?? 'N/A') . "\n";
    echo "   Requester: " . ($approval->requester->name ?? 'Unknown') . "\n";
    
    // Determine approver
    $approvedBy = 'Not set';
    if ($approval->status === 'supervisor_approved' && $approval->approver) {
        $approvedBy = $approval->approver->name . ' (Inventory Supervisor)';
    } elseif ($approval->status === 'security_approved' && $approval->securityDispatcher) {
        $approvedBy = $approval->securityDispatcher->name . ' (Security Staff)';
    } elseif ($approval->status === 'catering_approved' && $approval->cateringApprover) {
        $approvedBy = $approval->cateringApprover->name . ' (Catering Incharge)';
    } elseif ($approval->status === 'dispatched' && $approval->dispatcher) {
        $approvedBy = $approval->dispatcher->name . ' (Ramp Dispatcher)';
    } elseif ($approval->status === 'loaded' && $approval->flightPurser) {
        $approvedBy = $approval->flightPurser->name . ' (Flight Purser)';
    } elseif ($approval->status === 'delivered' && $approval->cabinCrew) {
        $approvedBy = $approval->cabinCrew->name . ' (Cabin Crew)';
    }
    
    echo "   Approved By: {$approvedBy}\n";
    echo "   Updated: {$approval->updated_at->diffForHumans()}\n";
    echo "   ---\n";
}
