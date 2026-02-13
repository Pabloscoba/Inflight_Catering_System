<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Flight extends Model
{
    use LogsActivity;
    protected $fillable = [
        'flight_number',
        'airline',
        'departure_time',
        'arrival_time',
        'origin',
        'destination',
        'aircraft_type',
        'passenger_capacity',
        'status',
        'notes',
    ];

    protected $casts = [
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime',
    ];

    // Relationship to requests (will be added later)
    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    // Scopes for filtering
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeBoarding($query)
    {
        return $query->where('status', 'boarding');
    }

    public function scopeDeparted($query)
    {
        return $query->where('status', 'departed');
    }

    public function scopeArrived($query)
    {
        return $query->where('status', 'arrived');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Scope to get only active/relevant flights (not old departed/completed flights)
    public function scopeActive($query)
    {
        // Show scheduled flights OR flights that departed within last 7 days
        return $query->where(function($q) {
            $q->where('status', 'scheduled')
              ->orWhere(function($q2) {
                  $q2->whereIn('status', ['departed', 'arrived', 'boarding'])
                     ->where('departure_time', '>=', now()->subDays(7));
              });
        })->where('status', '!=', 'completed');
    }

    // Scope to get upcoming flights only
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'scheduled')
                     ->where('departure_time', '>', now());
    }

    // Scope to get expired flights (old departed/completed)
    public function scopeExpired($query)
    {
        return $query->where(function($q) {
            $q->where('departure_time', '<', now()->subDays(30))
              ->whereIn('status', ['departed', 'arrived']);
        });
    }

    // Check if flight should auto-depart (departed time passed)
    public function shouldAutoDeparted()
    {
        return $this->status === 'scheduled' && $this->departure_time < now();
    }

    // Check if flight is expired (old completed flight)
    public function isExpired()
    {
        return in_array($this->status, ['departed', 'arrived']) && 
               $this->departure_time < now()->subDays(30);
    }

    // Helper methods
    public function isScheduled()
    {
        return $this->status === 'scheduled';
    }

    public function isBoarding()
    {
        return $this->status === 'boarding';
    }

    public function hasDeparted()
    {
        return $this->status === 'departed';
    }

    public function hasArrived()
    {
        return $this->status === 'arrived';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    // Get status badge color
    public function getStatusColor()
    {
        return match($this->status) {
            'scheduled' => '#0891b2',
            'boarding' => '#d97706',
            'departed' => '#7c3aed',
            'arrived' => '#059669',
            'cancelled' => '#dc2626',
            'completed' => '#6b7280',
            default => '#64748b',
        };
    }

    // Get status badge background
    public function getStatusBackground()
    {
        return match($this->status) {
            'scheduled' => '#cffafe',
            'boarding' => '#fef3c7',
            'departed' => '#f3e8ff',
            'arrived' => '#d1fae5',
            'cancelled' => '#fee2e2',
            'completed' => '#f3f4f6',
            default => '#f1f5f9',
        };
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['flight_number', 'origin', 'destination', 'departure_time', 'arrival_time', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
