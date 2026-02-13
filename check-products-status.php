<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

echo "=== ALL PRODUCTS ===\n";
$products = Product::all();
foreach ($products as $p) {
    echo $p->id . ': ' . $p->name . ' (Active: ' . ($p->is_active ? 'Yes' : 'No') . ', Recently Added: ' . $p->created_at . ')' . "\n";
}

echo "\n=== SUMMARY ===\n";
echo "Total Products: " . Product::count() . "\n";
echo "Active Products: " . Product::where('is_active', true)->count() . "\n";
echo "Inactive Products: " . Product::where('is_active', false)->count() . "\n";
echo "Recently Added (last 10): " . Product::where('is_active', true)->latest()->limit(10)->count() . "\n";
