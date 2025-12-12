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
        return [
            'title' => 'Request Approved',
            'message' => "Request #{$this->request->id} for flight {$this->request->flight->flight_number} has been approved",
            'request_id' => $this->request->id,
            'flight_number' => $this->request->flight->flight_number,
            'approved_by' => $this->request->approvedBy->name ?? 'System',
            'action_url' => route('catering-staff.requests.show', $this->request->id),
            'icon' => 'check',
            'color' => 'green'
        ];
    }
}
