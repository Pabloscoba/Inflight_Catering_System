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
        return [
            'title' => 'Product Return Authenticated',
            'message' => "Return of {$this->return->product->name} ({$this->return->verified_quantity} units) authenticated and stock adjusted",
            'return_id' => $this->return->id,
            'product_name' => $this->return->product->name,
            'verified_quantity' => $this->return->verified_quantity,
            'condition' => $this->return->condition,
            'action_url' => route('cabin-crew.returns.show', $this->return->id),
            'icon' => 'check',
            'color' => 'green'
        ];
    }
}
