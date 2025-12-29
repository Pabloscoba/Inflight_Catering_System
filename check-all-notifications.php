<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "\n=== CHECKING ALL NOTIFICATIONS ===\n\n";

// Get all users
$users = User::all();

foreach ($users as $user) {
    $notificationCount = $user->notifications()->count();
    $unreadCount = $user->unreadNotifications()->count();
    
    if ($notificationCount > 0) {
        echo "User: {$user->name} ({$user->roles->pluck('name')->join(', ')})\n";
        echo "  Total: $notificationCount | Unread: $unreadCount\n";
        
        foreach ($user->notifications as $notification) {
            $data = $notification->data;
            echo "  - " . ($notification->read_at ? '✓' : '○') . " " . ($data['title'] ?? 'N/A') . " | URL: " . ($data['action_url'] ?? 'N/A') . "\n";
            
            // Update notification URL if needed
            $requestId = $data['request_id'] ?? null;
            if ($requestId) {
                $request = \App\Models\Request::find($requestId);
                if ($request) {
                    $correctUrl = '#';
                    
                    if ($user->hasRole('Inventory Supervisor')) {
                        if ($request->status == 'catering_approved') {
                            $correctUrl = route('inventory-supervisor.requests.show', $requestId);
                        } else {
                            $correctUrl = route('inventory-supervisor.dashboard');
                        }
                    } elseif ($user->hasRole('Catering Incharge')) {
                        if ($request->status == 'pending_catering_incharge') {
                            $correctUrl = route('catering-incharge.requests.pending');
                        } elseif ($request->status == 'pending_final_approval') {
                            $correctUrl = route('catering-incharge.requests.pending-final');
                        } else {
                            $correctUrl = route('catering-incharge.dashboard');
                        }
                    } elseif ($user->hasRole('Catering Staff')) {
                        $correctUrl = route('catering-staff.requests.show', $requestId);
                    } elseif ($user->hasRole('Inventory Personnel')) {
                        if ($request->status == 'supervisor_approved') {
                            $correctUrl = route('inventory-personnel.requests.pending');
                        } else {
                            $correctUrl = route('inventory-personnel.dashboard');
                        }
                    }
                    
                    if ($correctUrl != '#' && ($data['action_url'] ?? '#') != $correctUrl) {
                        $data['action_url'] = $correctUrl;
                        DB::table('notifications')
                            ->where('id', $notification->id)
                            ->update(['data' => json_encode($data)]);
                        echo "    → FIXED URL to: $correctUrl\n";
                    }
                }
            }
        }
        echo "\n";
    }
}

echo "✓ Done!\n\n";
