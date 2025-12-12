<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Request #1 Status Check ===\n\n";
$r = App\Models\Request::find(1);
if ($r) {
    echo "Status: " . $r->status . PHP_EOL;
    echo "Display: " . str_replace('_', ' ', ucwords($r->status)) . PHP_EOL;
    
    // Check completed count
    $completed = App\Models\Request::where('status', 'delivered')->count();
    echo "\nCompleted (delivered status): {$completed}\n";
    
    $loaded = App\Models\Request::where('status', 'loaded')->count();
    echo "Loaded status: {$loaded}\n";
} else {
    echo "Request #1 not found\n";
}
