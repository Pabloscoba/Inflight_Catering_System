<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Request as RequestModel;
use App\Models\Flight;

echo "Deleting remaining test data...\n\n";

// Delete Request #4 with flight SEC-206
$request4 = RequestModel::find(4);
if ($request4) {
    echo "Deleting Request #4 (Flight: {$request4->flight->flight_number})...\n";
    $request4->items()->delete();
    $flight = $request4->flight;
    $request4->delete();
    if ($flight) {
        $flight->delete();
        echo "  ✓ Deleted flight {$flight->flight_number}\n";
    }
    echo "  ✓ Deleted request and items\n\n";
}

// Delete orphaned flight TZ-101 (ID: 3)
$tzFlight = Flight::where('flight_number', 'TZ-101')->first();
if ($tzFlight) {
    echo "Deleting orphaned flight TZ-101...\n";
    $tzFlight->delete();
    echo "  ✓ Deleted\n\n";
}

echo "✅ All test data cleaned up!\n\n";

// Show remaining data
echo "=== Remaining Data ===\n";
$requests = RequestModel::with('flight')->get();
echo "Requests: {$requests->count()}\n";
foreach ($requests as $req) {
    echo "  Request #{$req->id} - Flight: {$req->flight->flight_number} - Status: {$req->status}\n";
}

$flights = Flight::all();
echo "\nFlights: {$flights->count()}\n";
foreach ($flights as $flight) {
    echo "  {$flight->flight_number} - {$flight->aircraft_type}\n";
}
