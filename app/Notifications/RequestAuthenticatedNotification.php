<?php

namespace App\Notifications;

use App\Models\Request as RequestModel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RequestAuthenticatedNotification extends Notification
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
        $actionUrl = url('/dashboard');
        
        if ($notifiable->hasRole('Ramp Dispatcher')) {
            $actionUrl = route('ramp-dispatcher.dashboard');
        } elseif ($notifiable->hasRole('Catering Incharge')) {
            $actionUrl = route('catering-incharge.dashboard');
        } elseif ($notifiable->hasRole('Catering Staff')) {
            $actionUrl = route('catering-staff.requests.show', $this->request->id);
        }
        
        return [
            'title' => 'Request Authenticated by Security',
            'message' => "Request #{$this->request->id} for flight {$this->request->flight->flight_number} passed security authentication",
            'request_id' => $this->request->id,
            'flight_number' => $this->request->flight->flight_number,
            'authenticated_by' => $this->request->authenticatedBy->name ?? 'Security',
            'action_url' => $actionUrl,
            'icon' => 'shield',
            'color' => 'green'
        ];
    }
}
