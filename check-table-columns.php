<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== requests Table Columns ===\n\n";
$columns = DB::select('SHOW COLUMNS FROM requests');
echo "Available approval/timestamp columns:\n";
foreach ($columns as $col) {
    if (str_contains($col->Field, 'approved') || 
        str_contains($col->Field, 'loaded') || 
        str_contains($col->Field, 'received')) {
        echo "  • {$col->Field} ({$col->Type})\n";
    }
}
