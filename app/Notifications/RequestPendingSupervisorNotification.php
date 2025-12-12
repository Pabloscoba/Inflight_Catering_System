<?php

namespace App\Notifications;

use App\Models\Request as RequestModel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RequestPendingSupervisorNotification extends Notification
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
            'title' => 'Request Needs Approval',
            'message' => "Request #{$this->request->id} for flight {$this->request->flight->flight_number} forwarded for supervisor approval",
            'request_id' => $this->request->id,
            'flight_number' => $this->request->flight->flight_number,
            'requester' => $this->request->requester->name,
            'items_count' => $this->request->items->count(),
            'action_url' => route('admin.requests.pending'),
            'icon' => 'approval',
            'color' => 'purple'
        ];
    }
}
