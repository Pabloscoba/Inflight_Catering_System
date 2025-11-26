<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Request as RequestModel;

// Find requests #6 and #7
$request6 = RequestModel::find(6);
$request7 = RequestModel::find(7);

if ($request6) {
    echo "Request #6 current status: {$request6->status}\n";
    
    // Update to dispatched status
    $request6->update([
        'status' => 'dispatched',
        'dispatched_by' => 6, // Ramp Dispatcher user ID
        'dispatched_at' => now()
    ]);
    
    echo "Request #6 updated to: dispatched\n";
    echo "Dispatched at: {$request6->dispatched_at}\n";
} else {
    echo "Request #6 not found\n";
}

echo "\n";

if ($request7) {
    echo "Request #7 current status: {$request7->status}\n";
    
    // Update to dispatched status
    $request7->update([
        'status' => 'dispatched',
        'dispatched_by' => 6, // Ramp Dispatcher user ID
        'dispatched_at' => now()
    ]);
    
    echo "Request #7 updated to: dispatched\n";
    echo "Dispatched at: {$request7->dispatched_at}\n";
} else {
    echo "Request #7 not found\n";
}

echo "\nDone! Requests #6 and #7 are now dispatched and ready for Flight Purser to load.\n";
