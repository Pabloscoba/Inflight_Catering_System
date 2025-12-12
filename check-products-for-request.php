<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Checking Products for Request Form ===\n\n";

$total = App\Models\Product::count();
echo "1. Total products: $total\n";

$approved = App\Models\Product::where('status', 'approved')->count();
echo "2. Approved products: $approved\n";

$active = App\Models\Product::where('is_active', true)->count();
echo "3. Active products: $active\n";

$approvedAndActive = App\Models\Product::where('status', 'approved')
    ->where('is_active', true)
    ->count();
echo "4. Approved + Active: $approvedAndActive\n";

$withCateringStock = App\Models\Product::where('status', 'approved')
    ->where('is_active', true)
    ->where('catering_stock', '>', 0)
    ->count();
echo "5. Approved + Active + Catering Stock > 0: $withCateringStock\n\n";

echo "=== Products List (NEW LOGIC - No Catering Stock Filter) ===\n";
$products = App\Models\Product::all();
foreach ($products as $p) {
    echo "- {$p->name} (SKU: {$p->sku})\n";
    echo "  Status: {$p->status}, Active: " . ($p->is_active ? 'Yes' : 'No') . ", Catering Stock: {$p->catering_stock}\n";
    $visible = ($p->status === 'approved' && $p->is_active) ? '✅ VISIBLE' : '❌ HIDDEN';
    echo "  {$visible} in request form\n\n";
}
