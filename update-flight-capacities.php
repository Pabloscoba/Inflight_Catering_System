<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== UPDATING FLIGHT CAPACITIES ===\n\n";

$flights = App\Models\Flight::all();

echo "Total flights: " . $flights->count() . "\n\n";

foreach ($flights as $flight) {
    $oldCapacity = $flight->passenger_capacity;
    
    // Generate random capacity between 50 and 90
    $newCapacity = rand(50, 90);
    
    $flight->passenger_capacity = $newCapacity;
    $flight->save();
    
    echo "✓ {$flight->flight_number}: {$oldCapacity} → {$newCapacity}\n";
}

echo "\n=== CAPACITY UPDATE COMPLETE ===\n";
echo "All flight capacities are now between 50-90 passengers.\n";
