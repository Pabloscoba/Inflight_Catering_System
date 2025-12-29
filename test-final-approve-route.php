<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Request as RequestModel;

echo "Testing route generation for Request #8...\n\n";

$request = RequestModel::find(8);

if ($request) {
    echo "Request #8 found\n";
    echo "Status: {$request->status}\n\n";
    
    try {
        $url = route('catering-incharge.requests.final-approve', $request);
        echo "✓ Route generated successfully:\n";
        echo "  URL: {$url}\n\n";
    } catch (\Exception $e) {
        echo "✗ Error generating route:\n";
        echo "  {$e->getMessage()}\n\n";
    }
    
    echo "Trying with ID directly:\n";
    try {
        $url = route('catering-incharge.requests.final-approve', ['requestModel' => 8]);
        echo "✓ Route with ID:\n";
        echo "  URL: {$url}\n\n";
    } catch (\Exception $e) {
        echo "✗ Error:\n";
        echo "  {$e->getMessage()}\n\n";
    }
} else {
    echo "Request #8 not found\n";
}

echo "\nAll catering-incharge routes:\n";
$routes = app('router')->getRoutes();
foreach ($routes as $route) {
    $name = $route->getName();
    if ($name && strpos($name, 'catering-incharge') !== false) {
        echo "  {$name} => {$route->uri()}\n";
    }
}
