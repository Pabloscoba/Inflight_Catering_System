<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;

echo "=== All Products in Database ===\n\n";

$products = Product::select('id', 'name', 'sku', 'is_active', 'quantity_in_stock', 'catering_stock')
    ->get();

if ($products->isEmpty()) {
    echo "No products found in database.\n";
} else {
    echo "Total Products: " . $products->count() . "\n\n";
    
    foreach ($products as $product) {
        echo "ID: {$product->id}\n";
        echo "Name: {$product->name}\n";
        echo "SKU: {$product->sku}\n";
        echo "Is Active: " . ($product->is_active ? 'Yes' : 'No') . "\n";
        echo "Main Stock: {$product->quantity_in_stock}\n";
        echo "Catering Stock: {$product->catering_stock}\n";
        echo str_repeat("-", 50) . "\n\n";
    }
}
