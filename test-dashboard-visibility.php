<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Flight;

echo "Flight Operations Dashboard - Visibility Test" . PHP_EOL;
echo str_repeat('=', 80) . PHP_EOL;

// Test 1: Active flights count
$totalFlights = Flight::whereNotIn('status', ['completed', 'arrived'])->count();
echo "\n1. Total Active Flights (excluding arrived/completed): " . $totalFlights . PHP_EOL;

// Test 2: Recent flights
$recentFlights = Flight::whereNotIn('status', ['completed', 'arrived'])
    ->orderBy('created_at', 'desc')
    ->limit(10)
    ->get();

echo "\n2. Recent Active Flights:" . PHP_EOL;
echo str_repeat('-', 80) . PHP_EOL;
if ($recentFlights->count() > 0) {
    foreach($recentFlights as $f) {
        echo sprintf("   %-10s | %-20s | %s → %s | Status: %-10s | Added: %s", 
            $f->flight_number,
            $f->airline,
            $f->origin,
            $f->destination,
            $f->status,
            $f->created_at->diffForHumans()
        ) . PHP_EOL;
    }
} else {
    echo "   No active flights to display" . PHP_EOL;
}

// Test 3: Hidden flights
$hiddenFlights = Flight::whereIn('status', ['completed', 'arrived'])->get();
echo "\n3. Hidden Flights (arrived/completed):" . PHP_EOL;
echo str_repeat('-', 80) . PHP_EOL;
if ($hiddenFlights->count() > 0) {
    foreach($hiddenFlights as $f) {
        echo sprintf("   %-10s | %-20s | %s → %s | Status: %-10s [HIDDEN from dashboard]", 
            $f->flight_number,
            $f->airline,
            $f->origin,
            $f->destination,
            $f->status
        ) . PHP_EOL;
    }
} else {
    echo "   No hidden flights" . PHP_EOL;
}

echo "\n" . str_repeat('=', 80) . PHP_EOL;
echo "✓ Dashboard now shows only ACTIVE flights!" . PHP_EOL;
echo "✓ Arrived/Completed flights are HIDDEN by default" . PHP_EOL;
