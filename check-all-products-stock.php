<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use Illuminate\Support\Facades\DB;

echo "=== ALL PRODUCTS STOCK CHECK ===\n\n";

// Get ALL products (active and inactive)
$allProducts = Product::all();

echo "📊 All Products in Database:\n";
echo str_repeat("=", 100) . "\n";
printf("%-5s | %-30s | %-15s | %-15s | %-10s\n", "ID", "Product Name", "Main Stock", "Catering Stock", "Active");
echo str_repeat("=", 100) . "\n";

foreach ($allProducts as $product) {
    printf(
        "%-5d | %-30s | %-15d | %-15d | %-10s\n",
        $product->id,
        substr($product->name, 0, 30),
        $product->quantity_in_stock ?? 0,
        $product->catering_stock ?? 0,
        $product->is_active ? 'Yes' : 'No'
    );
}

echo str_repeat("=", 100) . "\n";
echo "\n📈 Summary:\n";
echo "   Total Products: " . $allProducts->count() . "\n";
echo "   Active Products: " . $allProducts->where('is_active', true)->count() . "\n";
echo "   Inactive Products: " . $allProducts->where('is_active', false)->count() . "\n";
echo "   Products with Main Stock > 0: " . $allProducts->where('quantity_in_stock', '>', 0)->count() . "\n";
echo "   Products with Catering Stock > 0: " . $allProducts->where('catering_stock', '>', 0)->count() . "\n";

echo "\n🔍 Active Products Details:\n";
$activeProducts = Product::where('is_active', true)->get();
foreach ($activeProducts as $p) {
    echo "   - {$p->name}: Main={$p->quantity_in_stock}, Catering={$p->catering_stock}\n";
}
