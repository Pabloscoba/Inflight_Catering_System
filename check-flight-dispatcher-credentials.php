<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🔐 FLIGHT DISPATCHER CREDENTIALS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$user = App\Models\User::where('email', 'flight.dispatcher@inflightcatering.com')->first();

if ($user) {
    echo "✅ User exists in database\n\n";
    echo "📧 Email:    flight.dispatcher@inflightcatering.com\n";
    echo "🔑 Password: Flight@123\n";
    echo "👤 Name:     {$user->name}\n";
    echo "🎭 Role:     {$user->roles->first()->name}\n";
    echo "\n";
    echo "🌐 Login URL: http://127.0.0.1:8000/login\n";
} else {
    echo "❌ User not found in database\n";
    echo "Please run: php artisan db:seed --class=RoleAndPermissionSeeder\n";
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "\n";
