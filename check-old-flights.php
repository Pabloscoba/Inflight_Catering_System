<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Flight;
use Carbon\Carbon;

echo "Current date: " . now() . PHP_EOL;
echo "Cutoff date (30 days ago): " . now()->subDays(30) . PHP_EOL;
echo PHP_EOL;

$flights = Flight::whereIn('status', ['departed', 'arrived'])->get();

echo "Found " . $flights->count() . " departed/arrived flights:" . PHP_EOL;
echo str_repeat('-', 80) . PHP_EOL;

foreach($flights as $f) {
    $isOld = $f->departure_time < now()->subDays(30) ? 'YES ✓' : 'NO';
    echo sprintf("%-10s | %-20s | Status: %-10s | Old? %s", 
        $f->flight_number, 
        $f->departure_time->format('Y-m-d H:i'),
        $f->status,
        $isOld
    ) . PHP_EOL;
}

echo PHP_EOL;
echo "Running update command..." . PHP_EOL;

// Run the update
$oldFlights = Flight::where('departure_time', '<', now()->subDays(30))
    ->whereIn('status', ['departed', 'arrived'])
    ->get();

echo "Flights to archive: " . $oldFlights->count() . PHP_EOL;

foreach ($oldFlights as $flight) {
    if (!in_array($flight->status, ['completed'])) {
        $flight->update(['status' => 'completed']);
        echo "✓ Flight {$flight->flight_number} marked as completed" . PHP_EOL;
    }
}

echo PHP_EOL . "Done!" . PHP_EOL;
