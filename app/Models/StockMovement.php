<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class StockMovement extends Model
{
    use LogsActivity;
    protected $fillable = [
        'type',
        'product_id',
        'quantity',
        'reference_number',
        'notes',
        'user_id',
        'movement_date',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'movement_date' => 'date',
        'approved_at' => 'datetime',
    ];

    // Relationship to Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relationship to User (who made the transaction)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship to User (who approved this movement)
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Check if movement is incoming
    public function isIncoming()
    {
        return $this->type === 'incoming';
    }

    // Check if movement is issued
    public function isIssued()
    {
        return $this->type === 'issued';
    }

    // Check if movement is returned
    public function isReturned()
    {
        return $this->type === 'returned';
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['type', 'product_id', 'quantity', 'reference_number', 'notes'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
