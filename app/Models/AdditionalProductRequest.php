<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalProductRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_request_id',
        'requested_by',
        'product_id',
        'quantity_requested',
        'quantity_approved',
        'reason',
        'status',
        'approved_by',
        'approved_at',
        'delivered_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function originalRequest()
    {
        return $this->belongsTo(Request::class, 'original_request_id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
