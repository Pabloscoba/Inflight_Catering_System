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
        
        // Determine action URL based on user role
        $actionUrl = '#';
        
        if ($notifiable->hasRole('Inventory Supervisor')) {
            $actionUrl = route('inventory-supervisor.approvals.products');
        } elseif ($notifiable->hasRole('Admin')) {
            $actionUrl = route('admin.products.index');
        } elseif ($notifiable->hasRole('Inventory Personnel')) {
            $actionUrl = route('inventory-personnel.products.index');
        } else {
            $actionUrl = url('/dashboard');
        }
        
        return [
            'title' => 'New Product Created',
            'message' => "{$creatorName} created product: {$this->product->name} (SKU: {$this->product->sku})",
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'sku' => $this->product->sku,
            'action_url' => $actionUrl,
            'icon' => 'product',
            'color' => 'blue'
        ];
    }
}
