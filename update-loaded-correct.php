<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Quick Update loaded_by Field ===\n\n";

$request = App\Models\Request::find(1);
$request->update([
    'loaded_by' => 9,
    'loaded_at' => now()->subDays(4)->setTime(13, 45, 0),
    'approved_date' => now()->subDays(4)->setTime(10, 30, 0),
]);

$request->refresh();

echo "✅ Updated Request #1:\n";
echo "  loaded_by: {$request->loaded_by}\n";
echo "  loaded_at: {$request->loaded_at}\n";
echo "  approved_date: {$request->approved_date}\n";
