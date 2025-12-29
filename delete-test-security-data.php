<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Request as RequestModel;
use App\Models\Flight;

echo "Deleting test security verification data...\n\n";

// Delete test requests (5, 6, 7)
$testRequests = RequestModel::whereIn('id', [5, 6, 7])->get();

foreach ($testRequests as $request) {
    echo "Deleting Request #{$request->id} (Flight: {$request->flight->flight_number})...\n";
    
    // Delete associated items
    $request->items()->delete();
    
    // Store flight for deletion
    $flight = $request->flight;
    
    // Delete request
    $request->delete();
    
    // Delete flight
    if ($flight) {
        $flight->delete();
        echo "  ✓ Deleted flight {$flight->flight_number}\n";
    }
    
    echo "  ✓ Deleted request and items\n\n";
}

echo "✅ All test data deleted!\n";
