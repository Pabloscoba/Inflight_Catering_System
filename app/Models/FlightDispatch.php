<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class FlightDispatch extends Model
{
    use LogsActivity;

    protected $fillable = [
        'flight_id',
        'dispatcher_id',
        'request_id',
        'fuel_status',
        'fuel_confirmed_at',
        'fuel_notes',
        'crew_readiness',
        'crew_confirmed_at',
        'crew_notes',
        'catering_status',
        'catering_confirmed_at',
        'catering_notes',
        'baggage_status',
        'baggage_confirmed_at',
        'baggage_notes',
        'operational_notes',
        'delay_reason',
        'dispatch_recommendation',
        'recommended_at',
        'overall_status',
    ];

    protected $casts = [
        'fuel_confirmed_at' => 'datetime',
        'crew_confirmed_at' => 'datetime',
        'catering_confirmed_at' => 'datetime',
        'baggage_confirmed_at' => 'datetime',
        'recommended_at' => 'datetime',
    ];

    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

    public function dispatcher()
    {
        return $this->belongsTo(User::class, 'dispatcher_id');
    }

    public function request()
    {
        return $this->belongsTo(Request::class);
    }

    // Activity Log Configuration
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['fuel_status', 'crew_readiness', 'catering_status', 'baggage_status', 'overall_status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Helper Methods
    public function isReadyToDispatch()
    {
        return $this->fuel_status === 'confirmed' 
            && $this->crew_readiness === 'confirmed'
            && $this->catering_status === 'confirmed'
            && $this->baggage_status === 'confirmed';
    }

    public function getCompletionPercentage()
    {
        $checks = [
            $this->fuel_status === 'confirmed',
            $this->crew_readiness === 'confirmed',
            $this->catering_status === 'confirmed',
            $this->baggage_status === 'confirmed',
        ];

        $completed = count(array_filter($checks));
        return ($completed / 4) * 100;
    }
}
