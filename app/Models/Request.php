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
        'request_type',
        'notes',
        'requested_date',
        'approved_by',
        'approved_date',
        'loaded_by',
        'loaded_at',
        'catering_approved_by',
        'catering_approved_at',
        'security_dispatched_by',
        'security_dispatched_at',
        'dispatched_by',
        'dispatched_at',
        'handed_to_flight_by',
        'handed_to_flight_at',
        'flight_received_by',
        'flight_received_at',
        'served_by',
        'served_at',
        'received_by',
        'received_date',
        'rejection_reason',
    ];

    protected $casts = [
        'requested_date' => 'date',
        'approved_date' => 'datetime',
        'catering_approved_at' => 'datetime',
        'security_dispatched_at' => 'datetime',
        'dispatched_at' => 'datetime',
        'handed_to_flight_at' => 'datetime',
        'flight_received_at' => 'datetime',
        'served_at' => 'datetime',
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

    public function cateringApprover()
    {
        return $this->belongsTo(User::class, 'catering_approved_by');
    }

    public function securityDispatcher()
    {
        return $this->belongsTo(User::class, 'security_dispatched_by');
    }

    public function dispatcher()
    {
        return $this->belongsTo(User::class, 'dispatched_by');
    }

    public function rampAgent()
    {
        return $this->belongsTo(User::class, 'handed_to_flight_by');
    }

    public function flightPurser()
    {
        return $this->belongsTo(User::class, 'loaded_by'); // Changed from flight_received_by to loaded_by
    }

    public function cabinCrew()
    {
        return $this->belongsTo(User::class, 'served_by');
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
