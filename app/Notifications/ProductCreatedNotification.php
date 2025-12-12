<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProductCreatedNotification extends Notification
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
        $creatorName = $this->product->createdBy ? $this->product->createdBy->name : 'Someone';
        
        return [
            'title' => 'New Product Created',
            'message' => "{$creatorName} created product: {$this->product->name} (SKU: {$this->product->sku})",
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'sku' => $this->product->sku,
            'action_url' => route('inventory-supervisor.approvals.products'),
            'icon' => 'product',
            'color' => 'blue'
        ];
    }
}
