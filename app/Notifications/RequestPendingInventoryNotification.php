<?php

namespace App\Notifications;

use App\Models\Request as RequestModel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RequestPendingInventoryNotification extends Notification
{
    use Queueable;

    protected $request;

    public function __construct(RequestModel $request)
    {
        $this->request = $request;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        // Determine action URL based on user role
        $actionUrl = '#';
        
        if ($notifiable->hasRole('Inventory Personnel')) {
            $actionUrl = route('inventory-personnel.requests.pending');
        } elseif ($notifiable->hasRole('Inventory Supervisor')) {
            $actionUrl = route('inventory-supervisor.requests.pending');
        } elseif ($notifiable->hasRole('Admin')) {
            $actionUrl = route('admin.requests.pending');
        } else {
            $actionUrl = url('/dashboard');
        }
        
        return [
            'title' => 'Request Needs Review',
            'message' => "Request #{$this->request->id} for flight {$this->request->flight->flight_number} needs inventory review",
            'request_id' => $this->request->id,
            'flight_number' => $this->request->flight->flight_number,
            'requester' => $this->request->requester->name,
            'items_count' => $this->request->items->count(),
            'action_url' => $actionUrl,
            'icon' => 'review',
            'color' => 'blue'
        ];
    }
}
