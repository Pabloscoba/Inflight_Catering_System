<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Request as RequestModel;

// Check requests with loaded status
$loadedRequests = RequestModel::where('status', 'loaded')->get();

echo "Requests with 'loaded' status:\n";
echo "Total count: " . $loadedRequests->count() . "\n\n";

if ($loadedRequests->count() > 0) {
    foreach ($loadedRequests as $request) {
        echo "Request ID: {$request->id}\n";
        echo "Status: {$request->status}\n";
        echo "Flight ID: {$request->flight_id}\n";
        echo "Loaded by: {$request->loaded_by}\n";
        echo "Loaded at: {$request->loaded_at}\n";
        echo "---\n";
    }
} else {
    echo "No requests with 'loaded' status found.\n";
}

// Check requests with dispatched status (ready to load)
$dispatchedRequests = RequestModel::where('status', 'dispatched')->get();

echo "\nRequests with 'dispatched' status (ready to load):\n";
echo "Total count: " . $dispatchedRequests->count() . "\n\n";

if ($dispatchedRequests->count() > 0) {
    foreach ($dispatchedRequests as $request) {
        echo "Request ID: {$request->id}\n";
        echo "Status: {$request->status}\n";
        echo "Flight ID: {$request->flight_id}\n";
        echo "Dispatched by: {$request->dispatched_by}\n";
        echo "Dispatched at: {$request->dispatched_at}\n";
        echo "---\n";
    }
}
