<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;
use App\Models\Category;

echo "Updating products with type based on category...\n\n";

$products = Product::with('category')->get();
$updated = 0;

foreach ($products as $product) {
    if ($product->category) {
        $type = '';
        
        switch($product->category->slug) {
            case 'food':
                $type = 'Food';
                break;
            case 'drinks':
                $type = 'Drink';
                break;
            case 'bites':
                $type = 'Food';
                break;
            case 'accessories':
                $type = 'Accessory';
                break;
            default:
                $type = 'Other';
        }
        
        $product->type = $type;
        $product->save();
        
        echo "✓ Updated: {$product->name} -> Type: {$type} (Category: {$product->category->name})\n";
        $updated++;
    }
}

echo "\n✅ Total products updated: {$updated}\n";
