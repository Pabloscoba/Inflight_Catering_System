<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Update Flight #9 departure time to future
$flight = App\Models\Flight::find(9);
if ($flight) {
    $flight->departure_time = now()->addDays(2)->setTime(14, 30, 0); // 2 days from now at 14:30
    $flight->arrival_time = now()->addDays(2)->setTime(16, 0, 0); // 2 days from now at 16:00
    $flight->save();
    
    echo "Flight #{$flight->id} ({$flight->flight_number}) updated:\n";
    echo "  Departure: {$flight->departure_time}\n";
    echo "  Arrival: {$flight->arrival_time}\n\n";
} else {
    echo "Flight #9 not found\n\n";
}

// Verify requests now match dashboard query
echo "=== Requests matching dashboard query ===\n";
$ordersToDispatch = App\Models\Request::with(['flight', 'requester'])
    ->where('status', 'ready_for_dispatch')
    ->whereHas('flight', function($query) {
        $query->where('departure_time', '>', now());
    })
    ->get();
echo "Count: " . $ordersToDispatch->count() . "\n";
foreach ($ordersToDispatch as $order) {
    echo "  Request #{$order->id}: Flight {$order->flight->flight_number}, Departure: {$order->flight->departure_time}\n";
}
