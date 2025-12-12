<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'product_id',
        'quantity_returned',
        'condition',
        'reason',
        'notes',
        'status',
        'returned_by',
        'received_by',
        'verified_by',
        'returned_at',
        'received_at',
        'verified_at',
    ];

    protected $casts = [
        'returned_at' => 'datetime',
        'received_at' => 'datetime',
        'verified_at' => 'datetime',
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

    public function returnedBy()
    {
        return $this->belongsTo(User::class, 'returned_by');
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Scopes
    public function scopePendingRamp($query)
    {
        return $query->where('status', 'pending_ramp');
    }

    public function scopePendingSecurity($query)
    {
        return $query->where('status', 'pending_security');
    }

    public function scopeAuthenticated($query)
    {
        return $query->where('status', 'authenticated');
    }
}
