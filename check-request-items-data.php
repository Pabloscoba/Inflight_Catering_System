<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Request #1 Items Data Check ===\n\n";

$request = App\Models\Request::with(['items.product'])->find(1);

if (!$request) {
    echo "❌ Request #1 not found!\n";
    exit;
}

echo "Request #1 Status: {$request->status}\n";
echo "Total Items: {$request->items->count()}\n\n";

foreach ($request->items as $item) {
    echo "📦 Item ID: {$item->id}\n";
    echo "   Product: {$item->product->name}\n";
    echo "   Meal Type: " . ($item->meal_type ?? 'NULL') . "\n";
    echo "   Is Scheduled: " . ($item->is_scheduled ? 'true' : 'false') . "\n";
    echo "   Scheduled At: " . ($item->scheduled_at ?? 'NULL') . "\n";
    echo "   Quantity Requested: {$item->quantity_requested}\n";
    echo "   Quantity Approved: " . ($item->quantity_approved ?? 'NULL') . "\n";
    echo "   ---\n";
}

echo "\n=== Database Direct Check ===\n";
$items = DB::table('request_items')->where('request_id', 1)->get();
foreach ($items as $item) {
    echo "\nItem ID: {$item->id}\n";
    echo "meal_type column value: '" . ($item->meal_type ?? 'NULL') . "'\n";
    echo "is_scheduled column value: " . ($item->is_scheduled ?? 0) . "\n";
    echo "scheduled_at column value: '" . ($item->scheduled_at ?? 'NULL') . "'\n";
}
