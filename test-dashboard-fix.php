<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

echo "=== TESTING DASHBOARD VARIABLES ===\n";

// Test totalProducts (should now be 5, including kuku)
$totalProducts = Product::count();
echo "totalProducts: " . $totalProducts . " (should be 5)\n";

// Test recentProducts (should now include kuku since it was just added)
$recentProducts = Product::with('category')
    ->latest('created_at')
    ->limit(10)
    ->get();
echo "recentProducts count: " . $recentProducts->count() . " (should be 5)\n";
echo "\nRecent Products List:\n";
foreach ($recentProducts as $product) {
    echo "  - " . $product->name . " (ID: " . $product->id . ", Active: " . ($product->is_active ? "Yes" : "No") . ")\n";
}

// Verify kuku is in the recent products
$kukuInRecent = $recentProducts->where('name', 'kuku')->count();
echo "\nKuku in recent products: " . ($kukuInRecent > 0 ? "✅ YES" : "❌ NO") . "\n";
