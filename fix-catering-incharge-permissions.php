<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

echo "🔧 Fixing Catering Incharge Permissions...\n\n";

// Get or create permission
$permission = Permission::firstOrCreate(['name' => 'approve catering staff requests']);
echo "✓ Permission: approve catering staff requests\n";

// Get Catering Incharge role
$role = Role::where('name', 'Catering Incharge')->first();
if ($role) {
    // Give permission to role
    if (!$role->hasPermissionTo('approve catering staff requests')) {
        $role->givePermissionTo('approve catering staff requests');
        echo "✓ Permission given to Catering Incharge role\n";
    } else {
        echo "✓ Catering Incharge already has this permission\n";
    }
    
    // Find all Catering Incharge users and sync permissions
    $users = User::role('Catering Incharge')->get();
    foreach ($users as $user) {
        if (!$user->hasPermissionTo('approve catering staff requests')) {
            $user->givePermissionTo('approve catering staff requests');
            echo "✓ Permission given to user: {$user->name}\n";
        }
    }
    
    // Clear cache
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    echo "\n✅ Permissions cache cleared!\n";
} else {
    echo "❌ Catering Incharge role not found!\n";
}

echo "\n✅ Done!\n";
