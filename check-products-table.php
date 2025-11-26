<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Check products table structure
$columns = \DB::select('DESCRIBE products');

echo "Products table structure:\n";
echo str_repeat("-", 50) . "\n";
foreach ($columns as $column) {
    echo "{$column->Field} | {$column->Type} | Null: {$column->Null} | Default: {$column->Default}\n";
}
