<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

$products = Product::where('name', 'like', '%yaket%')->get();

echo "Yaket Water Products:\n";
echo "===================\n\n";

foreach ($products as $product) {
    echo "ID: {$product->id}\n";
    echo "Name: {$product->name}\n";
    echo "SKU: {$product->sku}\n";
    echo "Status: {$product->status}\n";
    echo "Is Active: " . ($product->is_active ? 'YES' : 'NO') . "\n";
    echo "Stock: {$product->quantity_in_stock}\n";
    echo "Approved At: " . ($product->approved_at ? $product->approved_at : 'Not approved') . "\n";
    echo "Approved By: " . ($product->approved_by ? $product->approved_by : 'N/A') . "\n";
    echo "---\n";
}

// Activate them
echo "\n\nActivating all Yaket Water products...\n";
foreach ($products as $product) {
    if (!$product->is_active && $product->status === 'approved') {
        $product->update(['is_active' => true]);
        echo "✅ Activated: {$product->name} (SKU: {$product->sku})\n";
    }
}

echo "\n✅ Done!\n";
