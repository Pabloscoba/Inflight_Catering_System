<?php

namespace App\Notifications;

use App\Models\StockMovement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StockMovementCreatedNotification extends Notification
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
            'title' => 'New Stock Movement Pending',
            'message' => "{$typeLabel}: {$this->movement->quantity} units of {$this->movement->product->name} needs approval",
            'movement_id' => $this->movement->id,
            'product_name' => $this->movement->product->name,
            'type' => $this->movement->type,
            'quantity' => $this->movement->quantity,
            'created_by' => $this->movement->user->name ?? 'Personnel',
            'action_url' => route('inventory-supervisor.approvals.movements'),
            'icon' => 'movement',
            'color' => 'orange'
        ];
    }
}
