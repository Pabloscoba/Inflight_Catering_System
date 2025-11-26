<?php

use App\Models\User;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Get all users
$users = User::with('roles.permissions')->get();

echo "✅ SYSTEM USERS CREATED SUCCESSFULLY!\n\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "                    INFLIGHT CATERING SYSTEM USERS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

foreach ($users as $user) {
    echo "👤 USER: " . strtoupper($user->name) . "\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "📧 Email:    " . $user->email . "\n";
    
    // Get password based on role
    if ($user->hasRole('Admin')) {
        echo "🔑 Password: Admin@123\n";
    } elseif ($user->hasRole('Inventory Personnel')) {
        echo "🔑 Password: Inventory@123\n";
    } else {
        echo "🔑 Password: (Not specified)\n";
    }
    
    echo "🔐 Role:     " . $user->getRoleNames()->implode(', ') . "\n";
    echo "✨ Permissions (" . $user->getAllPermissions()->count() . "):\n";
    
    foreach ($user->getAllPermissions() as $permission) {
        echo "   ✓ " . $permission->name . "\n";
    }
    echo "\n";
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🔗 LOGIN URL: http://127.0.0.1:8000/login\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
