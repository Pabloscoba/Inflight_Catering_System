<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

echo "=== REAL-TIME CATERING STOCK LEVELS ===\n\n";

// Total stock
$totalStock = Product::where('is_active', true)->sum('catering_stock');
echo "Total Catering Stock: {$totalStock} units\n\n";

// Low stock items
$lowStockItems = Product::with(['category'])
    ->where('is_active', true)
    ->whereColumn('catering_stock', '<=', 'catering_reorder_level')
    ->where('catering_stock', '>=', 0)
    ->orderBy('catering_stock', 'asc')
    ->get();

echo "Low Stock Items: " . $lowStockItems->count() . "\n";
echo str_repeat('-', 80) . "\n";

if ($lowStockItems->count() > 0) {
    foreach ($lowStockItems as $product) {
        $status = $product->catering_stock == 0 ? '🚨 OUT OF STOCK' : '⚠️ LOW STOCK';
        echo sprintf(
            "%-40s | Stock: %4d | Reorder: %4d | %s\n",
            substr($product->name, 0, 40),
            $product->catering_stock,
            $product->catering_reorder_level,
            $status
        );
    }
} else {
    echo "✅ All stock levels are healthy!\n";
}

echo "\n=== ALL PRODUCTS WITH STOCK ===\n";
echo str_repeat('-', 80) . "\n";

$allProducts = Product::with(['category'])
    ->where('is_active', true)
    ->where('catering_stock', '>', 0)
    ->orderBy('catering_stock', 'desc')
    ->get();

echo "Products in Stock: " . $allProducts->count() . "\n\n";

foreach ($allProducts->take(10) as $product) {
    $stockStatus = $product->catering_stock <= $product->catering_reorder_level 
        ? ($product->catering_stock == 0 ? '🚨 OUT' : '⚠️ LOW') 
        : '✅ OK';
    
    echo sprintf(
        "%-35s | %-15s | Stock: %4d | Reorder: %4d | %s\n",
        substr($product->name, 0, 35),
        substr($product->category->name ?? 'N/A', 0, 15),
        $product->catering_stock,
        $product->catering_reorder_level,
        $stockStatus
    );
}

echo "\n✅ Stock check complete!\n";
