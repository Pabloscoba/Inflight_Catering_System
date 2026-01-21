<?php

namespace App\Notifications;

use App\Models\ProductReturn;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProductReturnAuthenticatedNotification extends Notification
{
    use Queueable;

    protected $return;

    public function __construct(ProductReturn $return)
    {
        $this->return = $return;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        // Determine action URL based on user role
        $actionUrl = '#';
        
        if ($notifiable->hasRole('Cabin Crew')) {
            $actionUrl = route('cabin-crew.returns.show', $this->return->id);
        } elseif ($notifiable->hasRole('Flight Purser')) {
            $actionUrl = route('flight-purser.dashboard');
        } elseif ($notifiable->hasRole('Ramp Dispatcher')) {
            $actionUrl = route('ramp-dispatcher.returns.index');
        } elseif ($notifiable->hasRole('Admin')) {
            $actionUrl = route('admin.dashboard');
        } else {
            $actionUrl = url('/dashboard');
        }
        
        return [
            'title' => 'Product Return Authenticated',
            'message' => "Return of {$this->return->product->name} ({$this->return->verified_quantity} units) authenticated and stock adjusted",
            'return_id' => $this->return->id,
            'product_name' => $this->return->product->name,
            'verified_quantity' => $this->return->verified_quantity,
            'condition' => $this->return->condition,
            'action_url' => $actionUrl,
            'icon' => 'check',
            'color' => 'green'
        ];
    }
}
