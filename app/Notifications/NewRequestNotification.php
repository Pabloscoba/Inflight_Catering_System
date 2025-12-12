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
        return [
            'title' => 'New Request Created',
            'message' => "Request #{$this->request->id} for flight {$this->request->flight->flight_number} has been created",
            'request_id' => $this->request->id,
            'flight_number' => $this->request->flight->flight_number,
            'requester' => $this->request->requester->name,
            'action_url' => route('catering-staff.requests.show', $this->request->id),
            'icon' => 'request',
            'color' => 'blue'
        ];
    }
}
