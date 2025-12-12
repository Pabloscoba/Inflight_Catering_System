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
        return [
            'title' => 'Request Authenticated by Security',
            'message' => "Request #{$this->request->id} for flight {$this->request->flight->flight_number} passed security authentication",
            'request_id' => $this->request->id,
            'flight_number' => $this->request->flight->flight_number,
            'authenticated_by' => $this->request->authenticatedBy->name ?? 'Security',
            'action_url' => route('catering-incharge.requests.show', $this->request->id),
            'icon' => 'shield',
            'color' => 'green'
        ];
    }
}
