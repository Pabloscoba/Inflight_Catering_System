<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Flight Data Check for Request #1 ===\n\n";

$request = App\Models\Request::with('flight')->find(1);

if (!$request || !$request->flight) {
    echo "❌ Request or Flight not found!\n";
    exit;
}

$flight = $request->flight;

echo "Flight Details:\n";
echo "  ID: {$flight->id}\n";
echo "  Flight Number: {$flight->flight_number}\n";
echo "  Airline: " . ($flight->airline ?? 'NULL') . "\n";
echo "  Origin: " . ($flight->origin ?? 'NULL') . "\n";
echo "  Destination: " . ($flight->destination ?? 'NULL') . "\n";
echo "  Aircraft Type: " . ($flight->aircraft_type ?? 'NULL') . "\n";
echo "  Passenger Capacity: " . ($flight->passenger_capacity ?? 'NULL') . "\n";
echo "  Departure: {$flight->departure_time}\n";

echo "\n=== Database Direct Check ===\n";
$flightData = DB::table('flights')->where('id', $flight->id)->first();
echo "aircraft_type: '" . ($flightData->aircraft_type ?? 'NULL') . "'\n";
echo "passenger_capacity: '" . ($flightData->passenger_capacity ?? 'NULL') . "'\n";
echo "airline: '" . ($flightData->airline ?? 'NULL') . "'\n";
