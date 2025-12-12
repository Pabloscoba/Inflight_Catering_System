<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Updating Flight #1 Data ===\n\n";

$flight = App\Models\Flight::find(1);

if (!$flight) {
    echo "❌ Flight not found!\n";
    exit;
}

echo "Current Data:\n";
echo "  Flight Number: {$flight->flight_number}\n";
echo "  Aircraft Type: " . ($flight->aircraft_type ?? 'NULL') . "\n";
echo "  Passenger Capacity: " . ($flight->passenger_capacity ?? 'NULL') . "\n\n";

// Update with proper aircraft and capacity data
$flight->update([
    'aircraft_type' => 'Boeing 737-800',
    'passenger_capacity' => 189,
]);

echo "✅ Updated Data:\n";
$flight->refresh();
echo "  Flight Number: {$flight->flight_number}\n";
echo "  Aircraft Type: {$flight->aircraft_type}\n";
echo "  Passenger Capacity: {$flight->passenger_capacity}\n";

echo "\n✅ Flight #1 now has complete aircraft and capacity information!\n";
