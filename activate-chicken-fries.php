<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

$product = Product::where('name', 'like', '%chicken%')->first();

if ($product) {
    $product->update(['is_active' => true]);
    echo "✅ Chicken fries is now ACTIVE and ready to use!\n";
    echo "- Status: {$product->status}\n";
    echo "- Is Active: Yes\n";
    echo "- Stock: {$product->quantity_in_stock}\n";
} else {
    echo "❌ Product not found!\n";
}
