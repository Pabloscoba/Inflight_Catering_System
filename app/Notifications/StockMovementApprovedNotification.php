<?php

namespace App\Notifications;

use App\Models\StockMovement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StockMovementApprovedNotification extends Notification
{
    use Queueable;

    protected $movement;

    public function __construct(StockMovement $movement)
    {
        $this->movement = $movement;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $typeLabels = [
            'incoming' => 'Incoming Stock',
            'issued' => 'Stock Issue',
            'returned' => 'Stock Return',
            'transfer_to_catering' => 'Transfer to Catering'
        ];

        $typeLabel = $typeLabels[$this->movement->type] ?? 'Stock Movement';

        return [
            'title' => 'Stock Movement Approved',
            'message' => "Your {$typeLabel} for {$this->movement->product->name} ({$this->movement->quantity} units) has been approved",
            'movement_id' => $this->movement->id,
            'product_name' => $this->movement->product->name,
            'type' => $this->movement->type,
            'quantity' => $this->movement->quantity,
            'approved_by' => $this->movement->approvedBy->name ?? 'Supervisor',
            'action_url' => route('inventory-personnel.stock-movements.index'),
            'icon' => 'check',
            'color' => 'green'
        ];
    }
}
