<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\StockMovement;
use App\Models\Product;

echo "=== All Stock Movements in Database ===\n\n";

$movements = StockMovement::with('product')->orderBy('created_at', 'desc')->get();

foreach ($movements as $movement) {
    $productName = $movement->product ? $movement->product->name : 'Unknown Product';
    $productSku = $movement->product ? $movement->product->sku : 'N/A';
    
    echo "Product: {$productName} (SKU: {$productSku})\n";
    echo "  Type: {$movement->type}\n";
    echo "  Quantity: {$movement->quantity}\n";
    echo "  Date: {$movement->created_at->format('Y-m-d H:i:s')}\n";
    echo "  Age: {$movement->created_at->diffForHumans()}\n";
    echo "---\n";
}
