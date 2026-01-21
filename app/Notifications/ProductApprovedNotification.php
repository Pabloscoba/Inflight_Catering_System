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
            'title' => 'Product Approved',
            'message' => "Your product '{$this->product->name}' has been approved by {$approverName}",
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'approved_by' => $approverName,
            'action_url' => $actionUrl,
            'icon' => 'check',
            'color' => 'green'
        ];
    }
}
