<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

$product = Product::where('name', 'like', '%korosho%')->first();

if ($product) {
    echo "Korosho Product Details:\n";
    echo "========================\n";
    echo "ID: {$product->id}\n";
    echo "Name: {$product->name}\n";
    echo "SKU: {$product->sku}\n";
    echo "Status: {$product->status}\n";
    echo "Is Active: " . ($product->is_active ? 'YES' : 'NO') . "\n";
    echo "Stock: {$product->quantity_in_stock}\n";
    echo "Approved At: " . ($product->approved_at ? $product->approved_at : 'Not approved') . "\n";
    echo "Approved By: " . ($product->approved_by ? $product->approved_by : 'N/A') . "\n";
    echo "\n";
    
    if ($product->status === 'approved' && !$product->is_active) {
        echo "⚠️ Product is APPROVED but INACTIVE. Activating now...\n";
        $product->update(['is_active' => true]);
        echo "✅ Product activated!\n";
    } elseif ($product->status === 'approved' && $product->is_active) {
        echo "✅ Product is already approved and active!\n";
    } else {
        echo "⚠️ Product status: {$product->status}, Active: " . ($product->is_active ? 'YES' : 'NO') . "\n";
    }
} else {
    echo "❌ Korosho product not found!\n";
}
