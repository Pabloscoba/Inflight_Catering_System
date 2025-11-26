<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Request extends Model
{
    use LogsActivity;
    protected $fillable = [
        'flight_id',
        'requester_id',
        'status',
        'notes',
        'requested_date',
        'approved_by',
        'approved_date',
        'received_by',
        'received_date',
        'rejection_reason',
    ];

    protected $casts = [
        'requested_date' => 'date',
        'approved_date' => 'datetime',
        'received_date' => 'datetime',
    ];

    // Relationships
    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function items()
    {
        return $this->hasMany(RequestItem::class);
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

    // Helper methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    // Get total items count
    public function getTotalItemsCount()
    {
        return $this->items()->sum('quantity_requested');
    }

    // Get status badge color
    public function getStatusColor()
    {
        return match($this->status) {
            'pending' => '#d97706',
            'approved' => '#059669',
            'rejected' => '#dc2626',
            default => '#64748b',
        };
    }

    // Get status badge background
    public function getStatusBackground()
    {
        return match($this->status) {
            'pending' => '#fef3c7',
            'approved' => '#d1fae5',
            'rejected' => '#fee2e2',
            default => '#f1f5f9',
        };
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['flight_id', 'status', 'notes', 'approved_by', 'approved_date', 'rejection_reason'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
