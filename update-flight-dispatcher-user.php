<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

$email = 'flight.dispatcher@inflightcatering.com';
$password = 'flight@123';

$role = Role::firstOrCreate(['name' => 'Flight Dispatcher']);

$user = User::where('email', $email)->first();
if (!$user) {
    $user = User::create([
        'name' => 'Flight Dispatcher',
        'email' => $email,
        'password' => Hash::make($password),
        'email_verified_at' => now(),
    ]);
    echo "Created user $email\n";
} else {
    $user->password = Hash::make($password);
    $user->email_verified_at = $user->email_verified_at ?? now();
    $user->save();
    echo "Updated password for $email\n";
}

if (!$user->hasRole('Flight Dispatcher')) {
    $user->assignRole('Flight Dispatcher');
    echo "Assigned role 'Flight Dispatcher' to $email\n";
} else {
    echo "$email already has role 'Flight Dispatcher'\n";
}

echo "Current roles: " . implode(', ', $user->getRoleNames()->toArray()) . "\n";
