<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProductApprovedNotification extends Notification
{
    use Queueable;

    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $approverName = $this->product->approvedBy ? $this->product->approvedBy->name : 'Supervisor';
        
        return [
            'title' => 'Product Approved',
            'message' => "Your product '{$this->product->name}' has been approved by {$approverName}",
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'approved_by' => $approverName,
            'action_url' => route('inventory-personnel.products.show', $this->product->id),
            'icon' => 'check',
            'color' => 'green'
        ];
    }
}
