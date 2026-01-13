<?php

namespace App\Notifications;

use App\Models\Request as RequestModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FlightClearedNotification extends Notification
{
    use Queueable;

    protected $request;

    /**
     * Create a new notification instance.
     */
    public function __construct(RequestModel $request)
    {
        $this->request = $request;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        // Default to flight-purser dashboard, fallback to home
        $actionUrl = '#';
        
        if ($notifiable->hasRole('Flight Purser')) {
            $actionUrl = route('flight-purser.dashboard');
        } elseif ($notifiable->hasRole('Cabin Crew')) {
            $actionUrl = route('cabin-crew.dashboard');
        }
        
        return [
            'title' => '✈️ Flight Cleared for Departure',
            'message' => "Flight {$this->request->flight->flight_number} has been cleared for departure by Flight Dispatcher. Request #{$this->request->id} is ready for operations.",
            'request_id' => $this->request->id,
            'flight_number' => $this->request->flight->flight_number,
            'cleared_by' => $this->request->flightDispatcherAssessor->name ?? 'Flight Dispatcher',
            'action_url' => $actionUrl,
            'icon' => 'plane-departure',
            'color' => 'green'
        ];
    }
}
