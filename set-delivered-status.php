<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Request;

echo "=== SETTING ONE REQUEST TO DELIVERED STATUS ===\n\n";

// Find a loaded request and mark it as delivered
$loadedRequest = Request::where('status', 'loaded')->first();

if ($loadedRequest) {
    echo "Found Request #{$loadedRequest->id} with status: {$loadedRequest->status}\n";
    echo "Changing to 'delivered' status...\n\n";
    
    $loadedRequest->status = 'delivered';
    $loadedRequest->received_by = 8; // Cabin Crew user ID
    $loadedRequest->received_date = now();
    $loadedRequest->save();
    
    echo "✓ Success! Request #{$loadedRequest->id} is now delivered\n\n";
    
    echo "Updated Status Counts:\n";
    echo "- Delivered (Completed): " . Request::where('status', 'delivered')->count() . "\n";
    echo "- Loaded: " . Request::where('status', 'loaded')->count() . "\n";
} else {
    echo "No loaded requests found to update\n";
}
