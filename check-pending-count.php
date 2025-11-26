<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Request;

echo "=== PENDING REQUESTS CHECK ===\n\n";

echo "Pending Inventory only: " . Request::where('status', 'pending_inventory')->count() . "\n";
echo "Pending Supervisor only: " . Request::where('status', 'pending_supervisor')->count() . "\n";
echo "Total Pending (both): " . Request::whereIn('status', ['pending_inventory', 'pending_supervisor'])->count() . "\n";

echo "\nAll Request Statuses:\n";
foreach (Request::all() as $req) {
    echo "- Request #{$req->id}: {$req->status}\n";
}
