<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use Illuminate\Support\Facades\DB;

echo "=== ADD STOCK TO PRODUCTS ===\n\n";

DB::beginTransaction();

try {
    // Get all active products
    $products = Product::where('is_active', true)->get();
    
    echo "📦 Adding stock to products...\n\n";
    
    foreach ($products as $product) {
        // Add 50 units to BOTH catering_stock and quantity_in_stock
        $oldCateringStock = $product->catering_stock;
        $oldMainStock = $product->quantity_in_stock;
        
        $product->catering_stock = 50;
        $product->quantity_in_stock = 50;
        $product->save();
        
        echo "✅ {$product->name}:\n";
        echo "   Old Catering Stock: {$oldCateringStock} → New: {$product->catering_stock}\n";
        echo "   Old Main Stock: {$oldMainStock} → New: {$product->quantity_in_stock}\n\n";
    }
    
    DB::commit();
    
    echo "✅ SUCCESS! Stock added to all products.\n\n";
    
    // Verify
    echo "📊 Verification:\n";
    $products = Product::where('is_active', true)->get();
    foreach ($products as $p) {
        echo "   - {$p->name}:\n";
        echo "     Main Stock: {$p->quantity_in_stock} units\n";
        echo "     Catering Stock: {$p->catering_stock} units\n\n";
    }
    
} catch (\Exception $e) {
    DB::rollBack();
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
