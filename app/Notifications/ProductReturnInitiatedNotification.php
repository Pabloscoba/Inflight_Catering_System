<?php

namespace App\Notifications;

use App\Models\ProductReturn;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProductReturnInitiatedNotification extends Notification
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
        
        if ($notifiable->hasRole('Ramp Dispatcher')) {
            $actionUrl = route('ramp-dispatcher.returns.index');
        } elseif ($notifiable->hasRole('Security Staff')) {
            $actionUrl = route('security-staff.dashboard');
        } elseif ($notifiable->hasRole('Admin')) {
            $actionUrl = route('admin.dashboard');
        } elseif ($notifiable->hasRole('Inventory Personnel')) {
            $actionUrl = route('inventory-personnel.dashboard');
        } else {
            $actionUrl = url('/dashboard');
        }
        
        return [
            'title' => 'Product Return Initiated',
            'message' => "{$this->return->returnedBy->name} returned {$this->return->quantity_returned} units of {$this->return->product->name}",
            'return_id' => $this->return->id,
            'product_name' => $this->return->product->name,
            'quantity' => $this->return->quantity_returned,
            'condition' => $this->return->condition,
            'action_url' => $actionUrl,
            'icon' => 'return',
            'color' => 'orange'
        ];
    }
}
