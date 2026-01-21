<?php

namespace App\Notifications;

use App\Models\Request as RequestModel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RequestRejectedNotification extends Notification
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
        } else {
            $actionUrl = url('/dashboard');
        }
        
        return [
            'title' => 'Request Rejected',
            'message' => "Request #{$this->request->id} for flight {$this->request->flight->flight_number} has been rejected",
            'request_id' => $this->request->id,
            'flight_number' => $this->request->flight->flight_number,
            'rejected_by' => $this->request->approvedBy->name ?? 'System',
            'reason' => $this->request->rejection_reason ?? 'No reason provided',
            'action_url' => $actionUrl,
            'icon' => 'x',
            'color' => 'red'
        ];
    }
}
