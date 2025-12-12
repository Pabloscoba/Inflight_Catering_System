<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CHECKING DATABASE STATUS ===\n\n";

try {
    // Check if we can see tables at all
    echo "1. Checking if database exists...\n";
    $result = DB::select("SHOW DATABASES LIKE 'inflight_catering_db'");
    if (count($result) > 0) {
        echo "✅ Database 'inflight_catering_db' exists\n\n";
    } else {
        echo "❌ Database not found\n";
        exit;
    }
    
    // Check tables
    echo "2. Checking tables...\n";
    $tables = DB::select("SHOW TABLES");
    echo "✅ Found " . count($tables) . " tables\n\n";
    
    foreach ($tables as $table) {
        $tableName = array_values((array)$table)[0];
        echo "   - {$tableName}\n";
    }
    
    echo "\n3. Testing table accessibility...\n";
    
    // Try to count records (this will fail if engine issue)
    $testTables = ['users', 'requests', 'products', 'flights'];
    
    foreach ($testTables as $tableName) {
        try {
            $count = DB::table($tableName)->count();
            echo "   ✅ {$tableName}: {$count} records\n";
        } catch (Exception $e) {
            echo "   ❌ {$tableName}: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n=== SOLUTION ===\n";
    echo "Since tables exist but engine can't read them, we need to:\n";
    echo "1. Export table schemas (structures)\n";
    echo "2. Drop corrupted tables\n";
    echo "3. Run migrations fresh\n";
    echo "4. Seed with default data\n";
    echo "\n⚠️  NOTE: This will reset data to default state.\n";
    echo "Your custom data may be lost, but system will work again.\n";
    
} catch (Exception $e) {
    echo "❌ Critical Error: " . $e->getMessage() . "\n";
}
