<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Get the meal with ID 1
$meal = \App\Models\Product::find(1);

if ($meal && $meal->photo) {
    echo "Photo path in database: " . $meal->photo . "\n";
    echo "Generated URL: " . \Storage::url($meal->photo) . "\n";
    echo "APP_URL: " . config('app.url') . "\n";
    echo "Full URL: " . asset('storage/' . str_replace('meals/', 'meals/', $meal->photo)) . "\n";
} else {
    echo "No meal found or no photo.\n";
}
