<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestItem extends Model
{
    protected $fillable = [
        'request_id',
        'product_id',
        'meal_type',
        'quantity_requested',
        'quantity_approved',
        'is_scheduled',
        'scheduled_at',
        'quantity_used',
        'quantity_defect',
        'quantity_remaining',
        'defect_notes',
        'usage_notes',
    ];

    protected $casts = [
        'is_scheduled' => 'boolean',
        'scheduled_at' => 'datetime',
    ];

    // Relationships
    public function request()
    {
        return $this->belongsTo(Request::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Helper methods
    public function isFullyApproved()
    {
        return $this->quantity_approved === $this->quantity_requested;
    }

    public function isPartiallyApproved()
    {
        return $this->quantity_approved > 0 && $this->quantity_approved < $this->quantity_requested;
    }

    public function getApprovalPercentage()
    {
        if ($this->quantity_requested == 0) {
            return 0;
        }
        return round(($this->quantity_approved / $this->quantity_requested) * 100, 1);
    }
}
