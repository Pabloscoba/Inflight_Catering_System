<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== SETTINGS VERIFICATION ===\n\n";

echo "CHECKING SETTINGS CONTROLLERS:\n";
echo str_repeat("=", 70) . "\n\n";

$controllers = [
    'Catering Staff' => 'App\\Http\\Controllers\\CateringStaff\\SettingsController',
    'Inventory Personnel' => 'App\\Http\\Controllers\\InventoryPersonnel\\SettingsController',
    'Inventory Supervisor' => 'App\\Http\\Controllers\\InventorySupervisor\\SettingsController',
    'Security Staff' => 'App\\Http\\Controllers\\SecurityStaff\\SettingsController',
    'Catering Incharge' => 'App\\Http\\Controllers\\CateringIncharge\\SettingsController',
    'Ramp Dispatcher' => 'App\\Http\\Controllers\\RampDispatcher\\SettingsController',
    'Flight Purser' => 'App\\Http\\Controllers\\FlightPurser\\SettingsController',
    'Cabin Crew' => 'App\\Http\\Controllers\\CabinCrew\\SettingsController',
];

$controllerCount = 0;
foreach ($controllers as $role => $controller) {
    if (class_exists($controller)) {
        $controllerCount++;
        echo "✓ {$role} SettingsController exists\n";
        
        $reflection = new ReflectionClass($controller);
        $methods = array_filter(
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
            fn($m) => !$m->isConstructor() && $m->class === $controller
        );
        
        $methodNames = array_map(fn($m) => $m->name, $methods);
        echo "  Methods: " . implode(', ', $methodNames) . "\n\n";
    } else {
        echo "✗ {$role} SettingsController NOT FOUND\n\n";
    }
}

echo str_repeat("=", 70) . "\n";
echo "Controllers: {$controllerCount} / " . count($controllers) . "\n\n";

echo str_repeat("=", 70) . "\n";
echo "CHECKING SETTINGS VIEWS:\n";
echo str_repeat("=", 70) . "\n\n";

$views = [
    'Catering Staff' => 'resources/views/catering-staff/settings/index.blade.php',
    'Inventory Personnel' => 'resources/views/inventory-personnel/settings/index.blade.php',
    'Inventory Supervisor' => 'resources/views/inventory-supervisor/settings/index.blade.php',
    'Security Staff' => 'resources/views/security-staff/settings/index.blade.php',
    'Catering Incharge' => 'resources/views/catering-incharge/settings/index.blade.php',
    'Ramp Dispatcher' => 'resources/views/ramp-dispatcher/settings/index.blade.php',
    'Flight Purser' => 'resources/views/flight-purser/settings/index.blade.php',
    'Cabin Crew' => 'resources/views/cabin-crew/settings/index.blade.php',
];

$viewCount = 0;
foreach ($views as $role => $viewPath) {
    $fullPath = base_path($viewPath);
    if (file_exists($fullPath)) {
        $viewCount++;
        echo "✓ {$role} Settings View exists\n";
        echo "  Path: {$viewPath}\n\n";
    } else {
        echo "✗ {$role} Settings View NOT FOUND\n";
        echo "  Expected: {$viewPath}\n\n";
    }
}

echo str_repeat("=", 70) . "\n";
echo "Views: {$viewCount} / " . count($views) . "\n\n";

echo str_repeat("=", 70) . "\n";
echo "CHECKING SETTINGS ROUTES:\n";
echo str_repeat("=", 70) . "\n\n";

$routes = Route::getRoutes();

$settingsRoutes = [
    'catering-staff.settings' => 'GET',
    'inventory-personnel.settings' => 'GET',
    'inventory-supervisor.settings' => 'GET',
    'security-staff.settings' => 'GET',
    'catering-incharge.settings' => 'GET',
    'ramp-dispatcher.settings' => 'GET',
    'flight-purser.settings' => 'GET',
    'cabin-crew.settings' => 'GET',
];

$routeCount = 0;
foreach ($settingsRoutes as $routeName => $method) {
    $route = $routes->getByName($routeName);
    if ($route) {
        $routeCount++;
        $uri = $route->uri();
        echo "✓ {$routeName}\n";
        echo "  URI: {$uri}\n";
        echo "  Method: {$method}\n\n";
    } else {
        echo "✗ {$routeName} - NOT FOUND\n\n";
    }
}

echo str_repeat("=", 70) . "\n";
echo "Routes: {$routeCount} / " . count($settingsRoutes) . "\n\n";

echo str_repeat("=", 70) . "\n";
echo "CHECKING USER MODEL:\n";
echo str_repeat("=", 70) . "\n\n";

$user = new \App\Models\User();
$fillable = $user->getFillable();
$casts = $user->getCasts();

echo "Fillable fields: " . implode(', ', $fillable) . "\n";
echo "Casts: " . json_encode($casts, JSON_PRETTY_PRINT) . "\n\n";

$hasPhone = in_array('phone', $fillable);
$hasPreferences = in_array('preferences', $fillable);
$preferencesIsCast = isset($casts['preferences']) && $casts['preferences'] === 'array';

echo ($hasPhone ? "✓" : "✗") . " 'phone' field is fillable\n";
echo ($hasPreferences ? "✓" : "✗") . " 'preferences' field is fillable\n";
echo ($preferencesIsCast ? "✓" : "✗") . " 'preferences' is cast to array\n\n";

echo str_repeat("=", 70) . "\n";
echo "CHECKING DATABASE:\n";
echo str_repeat("=", 70) . "\n\n";

try {
    $hasPhoneColumn = \Illuminate\Support\Facades\Schema::hasColumn('users', 'phone');
    $hasPreferencesColumn = \Illuminate\Support\Facades\Schema::hasColumn('users', 'preferences');
    
    echo ($hasPhoneColumn ? "✓" : "✗") . " 'phone' column exists in users table\n";
    echo ($hasPreferencesColumn ? "✓" : "✗") . " 'preferences' column exists in users table\n\n";
} catch (Exception $e) {
    echo "✗ Error checking database: " . $e->getMessage() . "\n\n";
}

echo str_repeat("=", 70) . "\n";
echo "FINAL SUMMARY:\n";
echo str_repeat("=", 70) . "\n\n";

$allChecks = [
    'Controllers' => $controllerCount === count($controllers),
    'Views' => $viewCount === count($views),
    'Routes' => $routeCount === count($settingsRoutes),
    'User Model (phone)' => $hasPhone,
    'User Model (preferences)' => $hasPreferences,
    'Preferences Cast' => $preferencesIsCast,
    'Database (phone)' => $hasPhoneColumn ?? false,
    'Database (preferences)' => $hasPreferencesColumn ?? false,
];

$allPassed = true;
foreach ($allChecks as $check => $passed) {
    echo ($passed ? "✓" : "✗") . " {$check}\n";
    if (!$passed) $allPassed = false;
}

echo "\n" . str_repeat("=", 70) . "\n";
if ($allPassed) {
    echo "✓ ALL SETTINGS FEATURES ARE READY!\n";
} else {
    echo "⚠ SOME CHECKS FAILED - REVIEW ABOVE\n";
}
echo str_repeat("=", 70) . "\n";
