<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Attempting to Repair InnoDB Tables ===\n\n";

$tables = [
    'users',
    'requests',
    'products',
    'categories',
    'flights',
    'stock_movements',
    'activity_log',
    'cache',
    'sessions'
];

foreach ($tables as $table) {
    echo "Checking table: {$table}...\n";
    try {
        // Try to access table
        DB::statement("CHECK TABLE {$table}");
        echo "  ✅ {$table} is OK\n";
    } catch (Exception $e) {
        echo "  ⚠️  {$table} has issues: " . $e->getMessage() . "\n";
        
        // Try to repair
        try {
            echo "  🔧 Attempting to repair {$table}...\n";
            DB::statement("REPAIR TABLE {$table}");
            echo "  ✅ {$table} repaired!\n";
        } catch (Exception $e2) {
            echo "  ❌ Cannot repair {$table}: " . $e2->getMessage() . "\n";
        }
    }
    echo "\n";
}

echo "\n=== Repair Complete ===\n";
echo "Now run: php test-connection.php\n";
