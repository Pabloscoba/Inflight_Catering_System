<?php

namespace App\Notifications;

use App\Models\Request as RequestModel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewRequestNotification extends Notification
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
        
        if ($notifiable->hasRole('Catering Staff')) {
            $actionUrl = route('catering-staff.requests.show', $this->request->id);
        } elseif ($notifiable->hasRole('Catering Incharge')) {
            $actionUrl = route('catering-incharge.dashboard');
        } elseif ($notifiable->hasRole('Admin')) {
            $actionUrl = route('admin.requests.show', $this->request->id);
        } elseif ($notifiable->hasRole('Inventory Personnel')) {
            $actionUrl = route('inventory-personnel.dashboard');
        } elseif ($notifiable->hasRole('Inventory Supervisor')) {
            $actionUrl = route('inventory-supervisor.dashboard');
        } else {
            $actionUrl = url('/dashboard');
        }
        
        return [
            'title' => 'New Request Created',
            'message' => "Request #{$this->request->id} for flight {$this->request->flight->flight_number} has been created",
            'request_id' => $this->request->id,
            'flight_number' => $this->request->flight->flight_number,
            'requester' => $this->request->requester->name,
            'action_url' => $actionUrl,
            'icon' => 'request',
            'color' => 'blue'
        ];
    }
}
