<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Request as RequestModel;
use App\Models\Flight;

echo "=== Checking All Requests and Flights ===\n\n";

// Get all requests
$requests = RequestModel::with('flight')->get();
echo "Total Requests: {$requests->count()}\n\n";

foreach ($requests as $req) {
    $flightNum = $req->flight->flight_number ?? 'N/A';
    echo "Request #{$req->id} - Flight: {$flightNum} - Status: {$req->status}\n";
}

echo "\n=== All Flights ===\n\n";
$flights = Flight::all();
echo "Total Flights: {$flights->count()}\n\n";

foreach ($flights as $flight) {
    echo "Flight: {$flight->flight_number} - Aircraft: {$flight->aircraft_type} - Departure: {$flight->departure_time}\n";
}

// Check for test flights (SEC-, TZ- patterns from our tests)
echo "\n=== Potential Test Flights ===\n\n";
$testFlights = Flight::where('flight_number', 'like', 'SEC-%')
    ->orWhere('flight_number', 'like', 'TZ-1%')
    ->orWhere('flight_number', 'like', 'TZ-2%')
    ->orWhere('flight_number', 'like', 'TZ-3%')
    ->get();

if ($testFlights->count() > 0) {
    echo "Found {$testFlights->count()} test flights:\n";
    foreach ($testFlights as $flight) {
        $requestCount = RequestModel::where('flight_id', $flight->id)->count();
        echo "  {$flight->flight_number} (ID: {$flight->id}) - {$requestCount} request(s)\n";
    }
} else {
    echo "No test flights found.\n";
}
