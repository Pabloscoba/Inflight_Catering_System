<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;

echo "=== PRODUCTS STATUS & VISIBILITY CHECK ===\n\n";

// Get ALL products
$allProducts = Product::all();

echo "📊 All Products with Status:\n";
echo str_repeat("=", 110) . "\n";
printf("%-5s | %-30s | %-15s | %-15s | %-10s | %-10s\n", "ID", "Product Name", "Status", "Catering Stock", "Active", "Category");
echo str_repeat("=", 110) . "\n";

foreach ($allProducts as $product) {
    printf(
        "%-5d | %-30s | %-15s | %-15d | %-10s | %-10s\n",
        $product->id,
        substr($product->name, 0, 30),
        $product->status ?? 'NULL',
        $product->catering_stock ?? 0,
        $product->is_active ? 'Yes' : 'No',
        $product->category->name ?? 'N/A'
    );
}

echo str_repeat("=", 110) . "\n";

echo "\n🔍 Filter Analysis:\n";
echo "   Products with status='approved' AND is_active=true: " . 
    Product::where('status', 'approved')->where('is_active', true)->count() . "\n";
echo "   Products with status='pending': " . 
    Product::where('status', 'pending')->count() . "\n";
echo "   Products with status='rejected': " . 
    Product::where('status', 'rejected')->count() . "\n";
echo "   Products with NULL status: " . 
    Product::whereNull('status')->count() . "\n";

echo "\n📋 Products Visible to Catering Staff (status=approved AND is_active=true):\n";
$visibleProducts = Product::where('status', 'approved')
    ->where('is_active', true)
    ->orderBy('catering_stock', 'desc')
    ->orderBy('name')
    ->get();

if ($visibleProducts->count() > 0) {
    foreach ($visibleProducts as $p) {
        echo "   ✅ {$p->name}: {$p->catering_stock} units\n";
    }
} else {
    echo "   ❌ NO PRODUCTS VISIBLE! All products are either:\n";
    echo "      - Not approved (status != 'approved')\n";
    echo "      - Not active (is_active = false)\n";
}

echo "\n💡 Fix Suggestion:\n";
$needsApproval = Product::where('status', '!=', 'approved')->orWhereNull('status')->get();
if ($needsApproval->count() > 0) {
    echo "   These products need status='approved':\n";
    foreach ($needsApproval as $p) {
        echo "      - {$p->name} (current status: " . ($p->status ?? 'NULL') . ")\n";
    }
}
