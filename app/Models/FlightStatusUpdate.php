<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightStatusUpdate extends Model
{
    protected $fillable = [
        'flight_id',
        'updated_by',
        'old_status',
        'new_status',
        'old_departure_time',
        'new_departure_time',
        'old_arrival_time',
        'new_arrival_time',
        'reason',
    ];

    protected $casts = [
        'old_departure_time' => 'datetime',
        'new_departure_time' => 'datetime',
        'old_arrival_time' => 'datetime',
        'new_arrival_time' => 'datetime',
    ];

    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
