<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Checking Routes ===\n\n";

$routes = app('router')->getRoutes();

echo "Catering Incharge routes with 'final' in name:\n";
foreach ($routes as $route) {
    $name = $route->getName();
    if ($name && strpos($name, 'catering-incharge') !== false && strpos($name, 'final') !== false) {
        echo "  {$name}\n";
        echo "    URI: {$route->uri()}\n";
        echo "    Methods: " . implode(', ', $route->methods()) . "\n\n";
    }
}

echo "\nChecking if Request #8 is at pending_final_approval:\n";
$request = App\Models\Request::find(8);
if ($request) {
    echo "Request #8 status: {$request->status}\n";
    if ($request->status === 'pending_final_approval') {
        echo "✓ Ready for final approval\n";
    } else {
        echo "⚠️ Not at pending_final_approval status yet\n";
    }
}
