<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Request as RequestModel;

// Set one request to loaded status for testing
$request6 = RequestModel::find(6);

if ($request6) {
    $request6->update([
        'status' => 'loaded',
        'loaded_by' => 7, // Flight Purser user ID
        'loaded_at' => now()
    ]);
    
    echo "Request #6 updated to 'loaded' status\n";
    echo "Status: {$request6->status}\n";
    echo "Loaded by: {$request6->loaded_by}\n";
    echo "Loaded at: {$request6->loaded_at}\n\n";
} else {
    echo "Request #6 not found\n";
}

// Keep request 7 as dispatched for Flight Purser testing
$request7 = RequestModel::find(7);
if ($request7) {
    echo "Request #7 status: {$request7->status} (kept for Flight Purser testing)\n";
}

echo "\nDone! Request #6 is now loaded and will appear in Cabin Crew dashboard.\n";
echo "Request #7 remains dispatched for Flight Purser testing.\n";
