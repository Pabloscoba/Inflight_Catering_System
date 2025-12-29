<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use Illuminate\Support\Facades\Schema;

echo "=== PRODUCT STOCK CHECK ===\n\n";

// Check if main_stock column exists
$columns = Schema::getColumnListing('products');
echo "📋 Products table columns:\n";
foreach ($columns as $col) {
    if (strpos($col, 'stock') !== false || strpos($col, 'quantity') !== false) {
        echo "   - $col\n";
    }
}
echo "\n";

// Get all active products
$products = Product::where('is_active', true)->get();

echo "📊 Active Products Stock Status:\n";
echo str_repeat("=", 80) . "\n";
printf("%-5s | %-30s | %-15s\n", "ID", "Product Name", "Catering Stock");
echo str_repeat("=", 80) . "\n";

foreach ($products as $product) {
    printf(
        "%-5d | %-30s | %-15d\n",
        $product->id,
        substr($product->name, 0, 30),
        $product->catering_stock ?? 0
    );
}

echo str_repeat("=", 80) . "\n";
echo "\n📈 Summary:\n";
echo "   Total Products: " . $products->count() . "\n";
echo "   Out of Stock: " . $products->where('catering_stock', '<=', 0)->count() . "\n";
echo "   In Stock: " . $products->where('catering_stock', '>', 0)->count() . "\n";
