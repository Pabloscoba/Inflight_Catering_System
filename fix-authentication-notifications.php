<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Fixing RequestAuthenticatedNotification URLs ===" . PHP_EOL . PHP_EOL;

$notifications = DB::table('notifications')
    ->whereJsonContains('data->title', 'Request Authenticated by Security')
    ->get();

echo "Found {$notifications->count()} authentication notifications" . PHP_EOL . PHP_EOL;

foreach ($notifications as $notification) {
    $data = json_decode($notification->data, true);
    $user = App\Models\User::find($notification->notifiable_id);
    
    if (!$user) continue;
    
    echo "User: {$user->name} ({$user->roles->first()->name})" . PHP_EOL;
    echo "  Old URL: {$data['action_url']}" . PHP_EOL;
    
    // Determine correct URL based on role
    $newUrl = 'http://127.0.0.1:8000/dashboard';
    if ($user->hasRole('Ramp Dispatcher')) {
        $newUrl = route('ramp-dispatcher.dashboard');
    } elseif ($user->hasRole('Catering Incharge')) {
        $newUrl = route('catering-incharge.dashboard');
    } elseif ($user->hasRole('Catering Staff')) {
        $newUrl = route('catering-staff.requests.show', $data['request_id']);
    }
    
    $data['action_url'] = $newUrl;
    
    DB::table('notifications')
        ->where('id', $notification->id)
        ->update(['data' => json_encode($data)]);
    
    echo "  New URL: {$newUrl}" . PHP_EOL . PHP_EOL;
}

echo "✓ Done!" . PHP_EOL;
