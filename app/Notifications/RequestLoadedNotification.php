<?php

namespace App\Notifications;

use App\Models\Request as RequestModel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RequestLoadedNotification extends Notification
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
        
        if ($notifiable->hasRole('Flight Purser')) {
            $actionUrl = route('flight-purser.requests.show', $this->request->id);
        } elseif ($notifiable->hasRole('Cabin Crew')) {
            $actionUrl = route('cabin-crew.dashboard');
        } elseif ($notifiable->hasRole('Ramp Dispatcher')) {
            $actionUrl = route('ramp-dispatcher.dashboard');
        } elseif ($notifiable->hasRole('Admin')) {
            $actionUrl = route('admin.requests.show', $this->request->id);
        } else {
            $actionUrl = url('/dashboard');
        }
        
        return [
            'title' => 'Request Loaded',
            'message' => "Request #{$this->request->id} for flight {$this->request->flight->flight_number} has been loaded onto aircraft",
            'request_id' => $this->request->id,
            'flight_number' => $this->request->flight->flight_number,
            'loaded_by' => $this->request->loadedBy->name ?? 'Ramp Dispatcher',
            'action_url' => $actionUrl,
            'icon' => 'truck',
            'color' => 'purple'
        ];
    }
}
