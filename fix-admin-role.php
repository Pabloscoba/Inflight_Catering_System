<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;

// Get current authenticated user ID (change this to your user ID)
echo "Enter your user ID or email: ";
$input = trim(fgets(STDIN));

// Find user
if (is_numeric($input)) {
    $user = User::find($input);
} else {
    $user = User::where('email', $input)->first();
}

if (!$user) {
    echo "❌ User not found!\n";
    exit(1);
}

// Ensure Admin role exists
$adminRole = Role::firstOrCreate(['name' => 'Admin']);

// Check if user already has Admin role
if ($user->hasRole('Admin')) {
    echo "✅ User '{$user->name}' already has Admin role!\n";
} else {
    // Assign Admin role
    $user->assignRole('Admin');
    echo "✅ Admin role assigned to user '{$user->name}' successfully!\n";
}

echo "\nUser roles: " . $user->roles->pluck('name')->implode(', ') . "\n";
