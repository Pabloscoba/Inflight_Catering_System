<?php

namespace App\Notifications;

use App\Models\Request as RequestModel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RequestApprovedNotification extends Notification
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
        // Determine the correct route based on user role
        $actionUrl = '#';
        
        if ($notifiable->hasRole('Catering Staff')) {
            $actionUrl = route('catering-staff.requests.show', $this->request->id);
        } elseif ($notifiable->hasRole('Catering Incharge')) {
            if ($this->request->status == 'pending_catering_incharge') {
                $actionUrl = route('catering-incharge.requests.pending');
            } elseif ($this->request->status == 'pending_final_approval') {
                $actionUrl = route('catering-incharge.requests.pending-final');
            } else {
                $actionUrl = route('catering-incharge.dashboard');
            }
        } elseif ($notifiable->hasRole('Inventory Supervisor')) {
            if ($this->request->status == 'catering_approved') {
                $actionUrl = route('inventory-supervisor.requests.show', $this->request->id);
            } else {
                $actionUrl = route('inventory-supervisor.dashboard');
            }
        } elseif ($notifiable->hasRole('Inventory Personnel')) {
            if ($this->request->status == 'supervisor_approved') {
                $actionUrl = route('inventory-personnel.requests.pending');
            } else {
                $actionUrl = route('inventory-personnel.dashboard');
            }
        } elseif ($notifiable->hasRole('Security Staff')) {
            if ($this->request->status == 'catering_final_approved') {
                $actionUrl = route('security-staff.requests.awaiting-authentication');
            } else {
                $actionUrl = route('security-staff.dashboard');
            }
        } elseif ($notifiable->hasRole('Ramp Dispatcher')) {
            $actionUrl = route('ramp-dispatcher.dashboard');
        } elseif ($notifiable->hasRole('Flight Dispatcher')) {
            // Link Flight Dispatcher directly to the request details so they can assess it
            $actionUrl = route('flight-dispatcher.requests.show', $this->request->id);
        } elseif ($notifiable->hasRole('Flight Purser')) {
            $actionUrl = route('flight-purser.dashboard');
        } elseif ($notifiable->hasRole('Cabin Crew')) {
            $actionUrl = route('cabin-crew.dashboard');
        } else {
            $actionUrl = route('dashboard');
        }
        
        // Customize message for Flight Dispatcher
        $message = "Request #{$this->request->id} for flight {$this->request->flight->flight_number} has been approved";
        if ($notifiable->hasRole('Flight Dispatcher')) {
            $message = "Request #{$this->request->id} awaiting your assessment for flight {$this->request->flight->flight_number}";
        }

        return [
            'title' => 'Request Approved',
            'message' => $message,
            'request_id' => $this->request->id,
            'flight_number' => $this->request->flight->flight_number,
            'approved_by' => $this->request->approvedBy->name ?? 'System',
            'action_url' => $actionUrl,
            'icon' => 'check',
            'color' => 'green'
        ];
    }
}
