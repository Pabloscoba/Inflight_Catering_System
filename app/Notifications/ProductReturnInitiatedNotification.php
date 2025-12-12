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
        return [
            'title' => 'Product Return Initiated',
            'message' => "{$this->return->returnedBy->name} returned {$this->return->quantity_returned} units of {$this->return->product->name}",
            'return_id' => $this->return->id,
            'product_name' => $this->return->product->name,
            'quantity' => $this->return->quantity_returned,
            'condition' => $this->return->condition,
            'action_url' => route('ramp-dispatcher.returns.index'),
            'icon' => 'return',
            'color' => 'orange'
        ];
    }
}
