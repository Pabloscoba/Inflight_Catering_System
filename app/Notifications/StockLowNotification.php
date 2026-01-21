<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StockLowNotification extends Notification
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
        // Determine action URL based on user role
        $actionUrl = '#';
        
        if ($notifiable->hasRole('Inventory Personnel')) {
            $actionUrl = route('inventory-personnel.products.index');
        } elseif ($notifiable->hasRole('Inventory Supervisor')) {
            $actionUrl = route('inventory-supervisor.products.index');
        } elseif ($notifiable->hasRole('Admin')) {
            $actionUrl = route('admin.products.edit', $this->product->id);
        } else {
            $actionUrl = url('/dashboard');
        }
        
        return [
            'title' => 'Low Stock Alert',
            'message' => "{$this->product->name} stock is low ({$this->product->quantity_in_stock} units remaining)",
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'current_stock' => $this->product->quantity_in_stock,
            'reorder_level' => $this->product->reorder_level ?? 50,
            'action_url' => $actionUrl,
            'icon' => 'alert',
            'color' => 'red'
        ];
    }
}
