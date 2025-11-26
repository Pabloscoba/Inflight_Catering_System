<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class CateringStock extends Model
{
    use LogsActivity;

    protected $table = 'catering_stock';

    protected $fillable = [
        'product_id',
        'quantity_received',
        'quantity_available',
        'reference_number',
        'notes',
        'received_by',
        'catering_incharge_id',
        'status',
        'received_date',
        'approved_date',
        'rejection_reason',
    ];

    protected $casts = [
        'received_date' => 'datetime',
        'approved_date' => 'datetime',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function cateringIncharge()
    {
        return $this->belongsTo(User::class, 'catering_incharge_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['product_id', 'quantity_received', 'quantity_available', 'status', 'reference_number'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
