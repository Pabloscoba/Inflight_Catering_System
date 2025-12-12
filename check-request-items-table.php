<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Checking request_items table structure:\n\n";

$columns = DB::select("DESCRIBE request_items");

foreach ($columns as $column) {
    echo "- {$column->Field}: {$column->Type}";
    if ($column->Null === 'YES') {
        echo " (nullable)";
    }
    if ($column->Default !== null) {
        echo " DEFAULT {$column->Default}";
    }
    echo "\n";
}
