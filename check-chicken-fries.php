<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

$product = Product::where('name', 'like', '%chicken%')->first();

if ($product) {
    echo "Product Found:\n";
    echo "- Name: {$product->name}\n";
    echo "- SKU: {$product->sku}\n";
    echo "- Status: {$product->status}\n";
    echo "- Stock: {$product->quantity_in_stock}\n";
    echo "- Approved At: " . ($product->approved_at ? $product->approved_at : 'Not approved') . "\n";
    echo "- Is Active: " . ($product->is_active ? 'Yes' : 'No') . "\n";
    
    if ($product->status !== 'approved') {
        echo "\n❌ Product is NOT approved yet. Approving now...\n";
        
        $product->update([
            'status' => 'approved',
            'is_active' => true,
            'approved_by' => 1, // Assuming admin ID is 1
            'approved_at' => now(),
        ]);
        
        echo "✅ Product has been approved successfully!\n";
    } else {
        echo "\n✅ Product is already approved!\n";
    }
} else {
    echo "❌ Chicken fries product not found!\n";
}
