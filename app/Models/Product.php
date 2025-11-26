<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Product extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'sku',
        'category_id',
        'description',
        'currency',
        'unit_price',
        'quantity_in_stock',
        'reorder_level',
        'unit_of_measure',
        'is_active',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'is_active' => 'boolean',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the category that owns the product
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the user who approved this product
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Check if product is low on stock
     */
    public function isLowStock()
    {
        return $this->quantity_in_stock <= $this->reorder_level;
    }

    /**
     * Check if product is out of stock
     */
    public function isOutOfStock()
    {
        return $this->quantity_in_stock <= 0;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'sku', 'unit_price', 'quantity_in_stock', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
