<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Testing Catering Staff Receive Items ===" . PHP_EOL . PHP_EOL;

// Find Request #3
$request = App\Models\Request::find(3);

if (!$request) {
    echo "Request #3 not found!" . PHP_EOL;
    exit(1);
}

echo "Before:" . PHP_EOL;
echo "Status: " . $request->status . PHP_EOL;
echo "Received By: " . ($request->received_by ?? 'NULL') . PHP_EOL;
echo "Received Date: " . ($request->received_date ?? 'NULL') . PHP_EOL;

if ($request->status !== 'items_issued') {
    echo PHP_EOL . "ERROR: Request is not in 'items_issued' status!" . PHP_EOL;
    exit(1);
}

// Simulate receiving items (as Catering Staff user)
$cateringStaff = App\Models\User::role('Catering Staff')->first();
if (!$cateringStaff) {
    echo "ERROR: No Catering Staff user found!" . PHP_EOL;
    exit(1);
}

// Update request
$request->update([
    'status' => 'pending_final_approval',
    'received_by' => $cateringStaff->id,
    'received_date' => now(),
]);

echo PHP_EOL . "After:" . PHP_EOL;
echo "Status: " . $request->status . PHP_EOL;
echo "Received By: " . $cateringStaff->name . PHP_EOL;
echo "Received Date: " . $request->received_date . PHP_EOL;

// Notify Catering Incharge
$cateringIncharge = App\Models\User::role('Catering Incharge')->first();
if ($cateringIncharge) {
    $cateringIncharge->notify(new \App\Notifications\RequestApprovedNotification($request));
    echo PHP_EOL . "✓ Notification sent to Catering Incharge: " . $cateringIncharge->name . PHP_EOL;
}

echo PHP_EOL . "=== Check Catering Incharge Final Approval Page ===" . PHP_EOL;
$pendingFinalApproval = App\Models\Request::where('status', 'pending_final_approval')->count();
echo "Requests awaiting final approval: " . $pendingFinalApproval . PHP_EOL;

echo PHP_EOL . "SUCCESS! Request #3 is now ready for Catering Incharge final approval." . PHP_EOL;
