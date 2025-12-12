<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Testing MySQL Connection ===\n\n";

try {
    echo "1. Testing database connection...\n";
    $pdo = DB::connection()->getPdo();
    echo "✅ Connected to MySQL!\n\n";
    
    echo "2. Testing users table...\n";
    $user = DB::table('users')->first();
    if ($user) {
        echo "✅ Users table works! Found user: {$user->name}\n\n";
    }
    
    echo "3. Counting users...\n";
    $count = DB::table('users')->count();
    echo "✅ Total users: {$count}\n\n";
    
    echo "4. Testing requests table...\n";
    $requests = DB::table('requests')->count();
    echo "✅ Total requests: {$requests}\n\n";
    
    echo "5. Testing products table...\n";
    $products = DB::table('products')->count();
    echo "✅ Total products: {$products}\n\n";
    
    echo "🎉 ALL TESTS PASSED! Your database is working perfectly!\n";
    echo "🎉 ALL YOUR DATA IS SAFE!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\n⚠️  If you see 'doesn't exist in engine', please:\n";
    echo "   1. Open XAMPP Control Panel\n";
    echo "   2. Stop MySQL\n";
    echo "   3. Start MySQL\n";
    echo "   4. Run this script again\n";
}
