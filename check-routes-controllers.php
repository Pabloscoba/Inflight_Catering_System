<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== ROUTES & CONTROLLERS VERIFICATION ===\n\n";

// Get all routes
$routes = Route::getRoutes();

echo "CRITICAL ROUTES CHECK:\n";
echo str_repeat("=", 70) . "\n\n";

$criticalRoutes = [
    // Catering Staff
    'catering-staff.dashboard' => 'GET',
    'catering-staff.requests.send-to-ramp' => 'POST',
    'catering-staff.additional-requests.index' => 'GET',
    'catering-staff.additional-requests.approve' => 'POST',
    
    // Ramp Dispatcher
    'ramp-dispatcher.dashboard' => 'GET',
    'ramp-dispatcher.requests.dispatch' => 'POST',
    
    // Flight Purser
    'flight-purser.dashboard' => 'GET',
    'flight-purser.requests.load' => 'POST',
    'flight-purser.loaded' => 'GET',
    
    // Cabin Crew
    'cabin-crew.dashboard' => 'GET',
    'cabin-crew.requests.deliver' => 'POST',
    'cabin-crew.products.view' => 'GET',
    'cabin-crew.items.mark-used' => 'POST',
    'cabin-crew.items.record-defect' => 'POST',
    'cabin-crew.products.request-additional' => 'GET',
    'cabin-crew.products.store-additional' => 'POST',
    'cabin-crew.products.report' => 'GET',
];

$foundRoutes = 0;
$missingRoutes = [];

foreach ($criticalRoutes as $routeName => $method) {
    $route = $routes->getByName($routeName);
    if ($route) {
        $foundRoutes++;
        $uri = $route->uri();
        $action = class_basename($route->getAction()['controller'] ?? 'Closure');
        echo "✓ {$routeName}\n";
        echo "  Method: {$method} | URI: {$uri}\n";
        echo "  Controller: {$action}\n\n";
    } else {
        $missingRoutes[] = $routeName;
        echo "✗ {$routeName} - MISSING\n\n";
    }
}

echo str_repeat("=", 70) . "\n";
echo "Routes Found: {$foundRoutes} / " . count($criticalRoutes) . "\n";

if (count($missingRoutes) > 0) {
    echo "\n⚠ Missing Routes:\n";
    foreach ($missingRoutes as $route) {
        echo "  - {$route}\n";
    }
} else {
    echo "\n✓ All critical routes are registered!\n";
}

echo "\n" . str_repeat("=", 70) . "\n";
echo "CONTROLLER EXISTENCE CHECK:\n";
echo str_repeat("=", 70) . "\n\n";

$controllers = [
    'CateringStaff\DashboardController',
    'CateringStaff\RequestController',
    'CateringStaff\AdditionalRequestController',
    'RampDispatcher\DashboardController',
    'RampDispatcher\DispatchController',
    'FlightPurser\DashboardController',
    'FlightPurser\LoadController',
    'CabinCrew\DashboardController',
    'CabinCrew\DeliveryController',
    'CabinCrew\ProductUsageController',
];

foreach ($controllers as $controller) {
    $fullPath = "App\\Http\\Controllers\\{$controller}";
    if (class_exists($fullPath)) {
        echo "✓ {$controller}\n";
        
        // Check methods
        $reflection = new ReflectionClass($fullPath);
        $methods = array_filter(
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
            fn($m) => !$m->isConstructor() && $m->class === $fullPath
        );
        
        echo "  Methods: ";
        echo implode(', ', array_map(fn($m) => $m->name, $methods));
        echo "\n\n";
    } else {
        echo "✗ {$controller} - NOT FOUND\n\n";
    }
}

echo str_repeat("=", 70) . "\n";
echo "VIEW FILES CHECK:\n";
echo str_repeat("=", 70) . "\n\n";

$viewFiles = [
    'catering-staff.dashboard' => 'resources/views/catering-staff/dashboard.blade.php',
    'catering-staff.additional-requests.index' => 'resources/views/catering-staff/additional-requests/index.blade.php',
    'ramp-dispatcher.dashboard' => 'resources/views/ramp-dispatcher/dashboard.blade.php',
    'flight-purser.dashboard' => 'resources/views/flight-purser/dashboard.blade.php',
    'cabin-crew.dashboard' => 'resources/views/cabin-crew/dashboard.blade.php',
    'cabin-crew.products.view' => 'resources/views/cabin-crew/products/view.blade.php',
    'cabin-crew.products.request-additional' => 'resources/views/cabin-crew/products/request-additional.blade.php',
    'cabin-crew.products.report' => 'resources/views/cabin-crew/products/report.blade.php',
];

$foundViews = 0;
foreach ($viewFiles as $viewName => $filePath) {
    $fullPath = base_path($filePath);
    if (file_exists($fullPath)) {
        $foundViews++;
        echo "✓ {$viewName}\n";
        echo "  Path: {$filePath}\n\n";
    } else {
        echo "✗ {$viewName}\n";
        echo "  Path: {$filePath} - NOT FOUND\n\n";
    }
}

echo str_repeat("=", 70) . "\n";
echo "Views Found: {$foundViews} / " . count($viewFiles) . "\n";

echo "\n" . str_repeat("=", 70) . "\n";
echo "MIDDLEWARE & ROLE PROTECTION CHECK:\n";
echo str_repeat("=", 70) . "\n\n";

$roleRoutes = [
    'Catering Staff' => ['catering-staff.dashboard', 'catering-staff.requests.send-to-ramp'],
    'Ramp Dispatcher' => ['ramp-dispatcher.dashboard', 'ramp-dispatcher.requests.dispatch'],
    'Flight Purser' => ['flight-purser.dashboard', 'flight-purser.requests.load'],
    'Cabin Crew' => ['cabin-crew.dashboard', 'cabin-crew.products.view'],
];

foreach ($roleRoutes as $role => $routeNames) {
    echo "📋 {$role}:\n";
    foreach ($routeNames as $routeName) {
        $route = $routes->getByName($routeName);
        if ($route) {
            $middleware = $route->middleware();
            $hasAuth = in_array('auth', $middleware);
            $hasRole = collect($middleware)->contains(fn($m) => str_contains($m, 'role:'));
            
            $authIcon = $hasAuth ? '✓' : '✗';
            $roleIcon = $hasRole ? '✓' : '✗';
            
            echo "  {$authIcon} Auth | {$roleIcon} Role - {$routeName}\n";
        }
    }
    echo "\n";
}

echo str_repeat("=", 70) . "\n";
echo "✓ ROUTES & CONTROLLERS VERIFICATION COMPLETE!\n";
echo str_repeat("=", 70) . "\n";
