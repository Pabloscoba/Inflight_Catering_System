<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing route generation...\n\n";

// Test 1: Generate URL with ID
try {
    $url = route('flight-dispatcher.dispatches.edit', ['dispatch' => 1]);
    echo "✅ Route with ID works: $url\n";
} catch (\Exception $e) {
    echo "❌ Route with ID failed: " . $e->getMessage() . "\n";
}

// Test 2: Check if dispatches exist
try {
    $count = \App\Models\FlightDispatch::count();
    echo "✅ Found $count dispatch records\n";
} catch (\Exception $e) {
    echo "❌ Database query failed: " . $e->getMessage() . "\n";
}

echo "\nDone!\n";
