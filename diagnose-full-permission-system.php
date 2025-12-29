<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Route;

echo "════════════════════════════════════════════════════════════════\n";
echo "🔍 COMPREHENSIVE PERMISSION SYSTEM DIAGNOSIS\n";
echo "════════════════════════════════════════════════════════════════\n\n";

// 1. Check all roles
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "👥 ROLES IN SYSTEM\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$roles = Role::with('permissions')->get();
foreach ($roles as $role) {
    echo "• {$role->name} ({$role->permissions->count()} permissions)\n";
}
echo "\n";

// 2. Check if 'view products' permission exists
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🔑 'view products' PERMISSION STATUS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$viewProductsPermission = Permission::where('name', 'view products')->first();
if ($viewProductsPermission) {
    echo "✅ Permission exists (ID: {$viewProductsPermission->id})\n\n";
    
    echo "Roles that have this permission:\n";
    $rolesWithPermission = $viewProductsPermission->roles;
    if ($rolesWithPermission->count() > 0) {
        foreach ($rolesWithPermission as $role) {
            echo "  ✓ {$role->name}\n";
        }
    } else {
        echo "  ⚠️  NO ROLES HAVE THIS PERMISSION!\n";
    }
    echo "\n";
} else {
    echo "❌ Permission 'view products' DOES NOT EXIST!\n\n";
}

// 3. Check routes for each role
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🛣️  PRODUCT ROUTES CHECK\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$rolePrefixes = [
    'Admin' => 'admin',
    'Cabin Crew' => 'cabin-crew',
    'Catering Staff' => 'catering-staff',
    'Inventory Personnel' => 'inventory-personnel',
    'Inventory Supervisor' => 'inventory-supervisor',
    'Catering Incharge' => 'catering-incharge',
    'Security Staff' => 'security-staff',
    'Ramp Dispatcher' => 'ramp-dispatcher',
    'Flight Purser' => 'flight-purser',
];

$allRoutes = Route::getRoutes();

foreach ($rolePrefixes as $roleName => $prefix) {
    $routeName = $prefix . '.products.index';
    $routeExists = $allRoutes->hasNamedRoute($routeName);
    
    echo "{$roleName}:\n";
    if ($routeExists) {
        $route = $allRoutes->getByName($routeName);
        $uri = $route->uri();
        $middleware = implode(', ', $route->middleware());
        echo "  ✅ Route exists: {$routeName}\n";
        echo "     URI: {$uri}\n";
        echo "     Middleware: {$middleware}\n";
    } else {
        echo "  ❌ Route MISSING: {$routeName}\n";
    }
    echo "\n";
}

// 4. Test each role's access
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🔐 ROLE ACCESS TEST\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

foreach ($roles as $role) {
    echo "{$role->name}:\n";
    
    // Get a user with this role
    $user = User::role($role->name)->first();
    
    if (!$user) {
        echo "  ⚠️  No user found with this role\n\n";
        continue;
    }
    
    echo "  User: {$user->name} ({$user->email})\n";
    
    // Check permissions
    $canView = $user->can('view products');
    $canCreate = $user->can('create products');
    
    echo "  Can view products: " . ($canView ? '✅ YES' : '❌ NO') . "\n";
    echo "  Can create products: " . ($canCreate ? '✅ YES' : '❌ NO') . "\n";
    
    // Check if route exists for this role
    $prefix = $rolePrefixes[$role->name] ?? 'unknown';
    $routeName = $prefix . '.products.index';
    $routeExists = $allRoutes->hasNamedRoute($routeName);
    
    echo "  Route available: " . ($routeExists ? '✅ YES' : '❌ NO') . "\n";
    
    // Overall status
    if ($canView && $routeExists) {
        echo "  Status: ✅ CAN ACCESS PRODUCTS\n";
    } elseif ($canView && !$routeExists) {
        echo "  Status: ⚠️  HAS PERMISSION BUT NO ROUTE\n";
    } elseif (!$canView && $routeExists) {
        echo "  Status: ⚠️  HAS ROUTE BUT NO PERMISSION\n";
    } else {
        echo "  Status: ❌ CANNOT ACCESS (no permission and no route)\n";
    }
    
    echo "\n";
}

// 5. Check sidebar code
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📱 SIDEBAR CONFIGURATION\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$sidebarFile = __DIR__ . '/resources/views/layouts/app.blade.php';
$sidebarContent = file_get_contents($sidebarFile);

if (strpos($sidebarContent, '@can(\'view products\')') !== false) {
    echo "✅ Sidebar has @can('view products') check\n";
} else {
    echo "❌ Sidebar missing @can('view products') check\n";
}

if (strpos($sidebarContent, 'products-submenu') !== false) {
    echo "⚠️  WARNING: Sidebar still has products dropdown (products-submenu)\n";
    echo "   This might cause empty dropdown issue!\n";
} else {
    echo "✅ Sidebar using direct link (no dropdown)\n";
}

if (strpos($sidebarContent, '$rolePrefix') !== false) {
    echo "✅ Sidebar has dynamic route detection (\$rolePrefix)\n";
} else {
    echo "❌ Sidebar missing dynamic route detection\n";
}

echo "\n";

// 6. Summary and recommendations
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📋 SUMMARY & RECOMMENDATIONS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$issues = [];
$rolesWithoutRoutes = [];
$rolesWithPermission = [];

foreach ($roles as $role) {
    $user = User::role($role->name)->first();
    if ($user && $user->can('view products')) {
        $rolesWithPermission[] = $role->name;
        
        $prefix = $rolePrefixes[$role->name] ?? null;
        if ($prefix) {
            $routeName = $prefix . '.products.index';
            if (!$allRoutes->hasNamedRoute($routeName)) {
                $rolesWithoutRoutes[] = $role->name;
            }
        }
    }
}

if (count($rolesWithPermission) == 0) {
    $issues[] = "No roles have 'view products' permission assigned";
}

if (count($rolesWithoutRoutes) > 0) {
    $issues[] = "These roles have permission but missing routes: " . implode(', ', $rolesWithoutRoutes);
}

if (strpos($sidebarContent, 'products-submenu') !== false) {
    $issues[] = "Sidebar still using dropdown instead of direct link";
}

if (count($issues) > 0) {
    echo "❌ ISSUES FOUND:\n\n";
    foreach ($issues as $i => $issue) {
        echo ($i + 1) . ". {$issue}\n";
    }
    echo "\n";
    
    echo "🔧 HOW TO FIX:\n\n";
    
    if (count($rolesWithPermission) == 0) {
        echo "1. Add 'view products' permission to roles:\n";
        echo "   - Go to http://127.0.0.1:8000/admin/roles\n";
        echo "   - Edit each role\n";
        echo "   - Check 'view products' permission\n";
        echo "   - Save\n\n";
    }
    
    if (count($rolesWithoutRoutes) > 0) {
        echo "2. Missing routes need to be added in routes/web.php\n";
        echo "   Routes needed:\n";
        foreach ($rolesWithoutRoutes as $roleName) {
            $prefix = $rolePrefixes[$roleName];
            echo "   - {$prefix}.products.index\n";
            echo "   - {$prefix}.products.create\n";
            echo "   - {$prefix}.products.edit\n";
        }
        echo "\n";
    }
    
    if (strpos($sidebarContent, 'products-submenu') !== false) {
        echo "3. Sidebar needs to be updated:\n";
        echo "   - Replace dropdown with direct link\n";
        echo "   - Add dynamic route detection\n\n";
    }
    
} else {
    echo "✅ NO ISSUES FOUND!\n\n";
    echo "Everything is configured correctly:\n";
    echo "✓ Permissions exist\n";
    echo "✓ Routes are registered\n";
    echo "✓ Sidebar is configured properly\n";
    echo "✓ Dynamic routing works\n\n";
    
    echo "If you still see issues in browser:\n";
    echo "1. Clear cache: php artisan cache:clear\n";
    echo "2. Clear route cache: php artisan route:clear\n";
    echo "3. Clear config cache: php artisan config:clear\n";
    echo "4. Log out and log back in\n";
    echo "5. Clear browser cache (Ctrl+Shift+Delete)\n\n";
}

echo "════════════════════════════════════════════════════════════════\n";
