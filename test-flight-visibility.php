<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Flight;

echo "Checking visible flights (excluding arrived and completed)..." . PHP_EOL;
echo str_repeat('=', 80) . PHP_EOL;

$query = Flight::query()
    ->whereNotIn('status', ['completed', 'arrived'])
    ->orderBy('departure_time', 'desc');

$flights = $query->get();

echo "Found " . $flights->count() . " visible flights:" . PHP_EOL;
echo str_repeat('-', 80) . PHP_EOL;

if ($flights->count() > 0) {
    foreach($flights as $f) {
        echo sprintf("%-10s | %-20s | %-20s | Status: %-10s", 
            $f->flight_number, 
            $f->airline,
            $f->departure_time->format('Y-m-d H:i'),
            $f->status
        ) . PHP_EOL;
    }
} else {
    echo "No flights to display (all are hidden)" . PHP_EOL;
}

echo PHP_EOL;
echo "Hidden flights (arrived or completed):" . PHP_EOL;
echo str_repeat('-', 80) . PHP_EOL;

$hiddenFlights = Flight::whereIn('status', ['completed', 'arrived'])->get();

if ($hiddenFlights->count() > 0) {
    foreach($hiddenFlights as $f) {
        echo sprintf("%-10s | %-20s | %-20s | Status: %-10s [HIDDEN]", 
            $f->flight_number, 
            $f->airline,
            $f->departure_time->format('Y-m-d H:i'),
            $f->status
        ) . PHP_EOL;
    }
} else {
    echo "No hidden flights" . PHP_EOL;
}

echo PHP_EOL;
echo "✓ Flights with 'arrived' or 'completed' status are now hidden by default!" . PHP_EOL;
