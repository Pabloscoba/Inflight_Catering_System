<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

echo "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🔧 Creating Flight Dispatcher User\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Delete existing user if exists
$existing = User::where('email', 'flightdispatcher@inflight.com')->first();
if ($existing) {
    echo "🗑️ Deleting existing user...\n";
    $existing->delete();
}

// Create new user
echo "✨ Creating new Flight Dispatcher user...\n";
$user = User::create([
    'name' => 'Flight Dispatcher',
    'email' => 'flightdispatcher@inflight.com',
    'password' => Hash::make('password123'),
    'email_verified_at' => now(),
]);

// Assign role
echo "🎭 Assigning Flight Dispatcher role...\n";
$role = Role::where('name', 'Flight Dispatcher')->first();
if ($role) {
    $user->assignRole($role);
    echo "✅ Role assigned successfully\n\n";
} else {
    echo "❌ Flight Dispatcher role not found\n";
    echo "Run: php artisan db:seed --class=RoleAndPermissionSeeder\n\n";
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🔐 NEW FLIGHT DISPATCHER CREDENTIALS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📧 Email:    flightdispatcher@inflight.com\n";
echo "🔑 Password: password123\n";
echo "\n";
echo "🌐 Login URL: http://127.0.0.1:8000/login\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

echo "\nWorkflow note: Place `Flight Dispatcher` between `Ramp Dispatcher` and `Flight Purser` in workflow checks.\n";

echo "\n════════════════════════════════════════════════════════════════\n";

