<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TESTING PERMISSION SYSTEM - SHIDA YA DOUBLE MIDDLEWARE ===\n\n";

// Scenario: Admin amempa Catering Staff permission ya "view products"
echo "SCENARIO:\n";
echo "---------\n";
echo "Admin amempa Catering Staff permission: 'view products'\n";
echo "Catering Staff anataka kuview products\n";
echo "Route: /inventory-personnel/products (GET)\n\n";

// Check route middleware
echo "ROUTE MIDDLEWARE SETUP:\n";
echo "----------------------\n";
echo "Line 167: Route::middleware(['auth', 'check_role_or_permission:Inventory Personnel'])\n";
echo "Line 172: ->middleware('permission:view products')\n\n";

echo "MIDDLEWARE STACK:\n";
echo "1. auth → ✓ User is logged in\n";
echo "2. check_role_or_permission:Inventory Personnel → ❓ CHECKING...\n";
echo "3. permission:view products → Will never reach if #2 fails!\n\n";

// Test with Catering Staff user
$cateringStaff = App\Models\User::where('email', 'staff@inflightcatering.com')->first();

if (!$cateringStaff) {
    echo "❌ Catering Staff user not found! Run: php artisan db:seed\n";
    exit(1);
}

echo "=== TEST USER: Catering Staff ===\n";
echo "Email: " . $cateringStaff->email . "\n";
echo "Name: " . $cateringStaff->name . "\n";
$role = $cateringStaff->roles->first();
echo "Role: " . ($role ? $role->name : 'No role assigned') . "\n\n";

echo "CHECKING PERMISSIONS:\n";
$permissions = $cateringStaff->getAllPermissions();
echo "Total Permissions: " . $permissions->count() . "\n";
foreach ($permissions as $perm) {
    echo "  - " . $perm->name . "\n";
}
echo "\n";

// Test if user has 'view products' permission
$hasViewProducts = $cateringStaff->can('view products');
echo "Has 'view products' permission? " . ($hasViewProducts ? '✓ YES' : '❌ NO') . "\n\n";

// Now let's simulate the middleware check
echo "=== MIDDLEWARE #2: check_role_or_permission:Inventory Personnel ===\n\n";

// Step 1: Check if user has role
$hasRole = $cateringStaff->hasRole('Inventory Personnel');
echo "Step 1: Has 'Inventory Personnel' role? " . ($hasRole ? 'YES' : 'NO') . "\n";

if (!$hasRole) {
    echo "❌ FAILED - User doesn't have required role\n\n";
    
    // Step 2: Check role_permission_map
    echo "Step 2: Checking role_permission_map...\n";
    $rolePermissionMap = config('role_permission_map', []);
    
    if (isset($rolePermissionMap['Inventory Personnel'])) {
        $rolePermissions = $rolePermissionMap['Inventory Personnel'];
        echo "Inventory Personnel permissions in config:\n";
        foreach ($rolePermissions as $perm) {
            $hasPerm = $cateringStaff->can($perm);
            echo "  - " . $perm . ": " . ($hasPerm ? '✓' : '❌') . "\n";
        }
        
        // Check if user has ANY of these permissions
        $hasAnyPermission = false;
        foreach ($rolePermissions as $permission) {
            if ($cateringStaff->can($permission)) {
                $hasAnyPermission = true;
                break;
            }
        }
        
        echo "\nHas ANY Inventory Personnel permission? " . ($hasAnyPermission ? 'YES' : 'NO') . "\n";
        
        if (!$hasAnyPermission) {
            echo "\n❌❌❌ MIDDLEWARE BLOCKS ACCESS! ❌❌❌\n";
            echo "Error: 'Unauthorized - You do not have permission to access this resource'\n";
            echo "NEVER REACHES: middleware('permission:view products')\n\n";
        }
    }
}

echo "\n=== THE PROBLEM (SHIDA) ===\n\n";
echo "1. Route has DOUBLE middleware check:\n";
echo "   a) check_role_or_permission:Inventory Personnel (GROUP level)\n";
echo "   b) middleware('permission:view products') (ROUTE level)\n\n";

echo "2. Middleware #1 checks:\n";
echo "   - Does user have 'Inventory Personnel' role? NO\n";
echo "   - Does user have ANY permission from Inventory Personnel's list? NO\n";
echo "   - Result: ❌ BLOCKED!\n\n";

echo "3. Middleware #2 never runs because #1 already blocked access!\n\n";

echo "4. Even if admin gives 'view products' permission to Catering Staff,\n";
echo "   they can't access /inventory-personnel/products because:\n";
echo "   - They don't have 'Inventory Personnel' role\n";
echo "   - They don't have permissions from role_permission_map['Inventory Personnel']\n\n";

echo "=== THE SOLUTION (SULUHISHO) ===\n\n";
echo "Option 1: Remove GROUP-level check_role_or_permission middleware\n";
echo "   - Keep only route-level permission checks\n";
echo "   - More flexible, permission-based access\n\n";

echo "Option 2: Add 'view products' to role_permission_map['Catering Staff']\n";
echo "   - Update config/role_permission_map.php\n";
echo "   - Then re-sync permissions\n\n";

echo "Option 3: Create shared routes for common permissions\n";
echo "   - /products (accessible by anyone with 'view products')\n";
echo "   - Not tied to specific role prefix\n\n";

// Now let's test if we add permission to Catering Staff
echo "=== TESTING SOLUTION: Add permission to Catering Staff ===\n\n";

// Give permission directly
echo "Admin runs: php artisan db:seed (after updating config)\n\n";

// Check current config
$currentCateringPerms = config('role_permission_map.Catering Staff', []);
echo "Current Catering Staff permissions in config:\n";
foreach ($currentCateringPerms as $perm) {
    echo "  - " . $perm . "\n";
}

$hasViewProducts = in_array('view products', $currentCateringPerms);
echo "\nHas 'view products' in config? " . ($hasViewProducts ? '✓ YES' : '❌ NO') . "\n\n";

if (!$hasViewProducts) {
    echo "❌ 'view products' is NOT in Catering Staff permissions\n";
    echo "That's why access is blocked!\n\n";
}

echo "=== RECOMMENDATION ===\n\n";
echo "🔧 BEST SOLUTION: Remove role-level middleware from route groups\n";
echo "   Keep only specific permission checks on individual routes\n";
echo "   This allows flexible permission assignment across roles\n\n";

echo "✅ This will make the system truly permission-based,\n";
echo "   not role-based with permission checks on top!\n";
