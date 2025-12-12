<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Updating Request #1 Items with Meal Type & Schedule ===\n\n";

$item = App\Models\RequestItem::find(1);

if (!$item) {
    echo "❌ Item not found!\n";
    exit;
}

echo "Current Data:\n";
echo "  Product: {$item->product->name}\n";
echo "  Meal Type: " . ($item->meal_type ?? 'NULL') . "\n";
echo "  Is Scheduled: " . ($item->is_scheduled ? 'Yes' : 'No') . "\n";
echo "  Scheduled At: " . ($item->scheduled_at ?? 'NULL') . "\n\n";

// Update with meal type and scheduling
$item->update([
    'meal_type' => 'lunch',
    'is_scheduled' => true,
    'scheduled_at' => now()->addDays(2)->setTime(12, 30, 0), // Dec 12, 2025 12:30
]);

echo "✅ Updated Data:\n";
$item->refresh();
echo "  Product: {$item->product->name}\n";
echo "  Meal Type: {$item->meal_type}\n";
echo "  Is Scheduled: " . ($item->is_scheduled ? 'Yes' : 'No') . "\n";
echo "  Scheduled At: {$item->scheduled_at}\n";

echo "\n✅ Request #1 items now have meal type and schedule information!\n";
