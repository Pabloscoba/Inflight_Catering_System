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
        'catering_stock',
        'catering_reorder_level',
        'unit_of_measure',
        'unit',
        'is_active',
        'status',
        // Workflow fields
        'approved_by',
        'approved_at',
        'rejection_reason',
        'authenticated_by',
        'authenticated_at',
        'dispatched_by',
        'dispatched_at',
        'delivered_by',
        'delivered_at',
        // Meal management fields
        'meal_type',
        'ingredients',
        'allergen_info',
        'portion_size',
        'season',
        'route',
        'is_special_meal',
        'special_requirements',
        'menu_version',
        'effective_start_date',
        'effective_end_date',
        'photo',
        'preparation_instructions',
        'nutritional_info',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_special_meal' => 'boolean',
        'approved_at' => 'datetime',
        'authenticated_at' => 'datetime',
        'dispatched_at' => 'datetime',
        'delivered_at' => 'datetime',
        'effective_start_date' => 'date',
        'effective_end_date' => 'date',
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
     * Get the user who authenticated this meal
     */
    public function authenticatedBy()
    {
        return $this->belongsTo(User::class, 'authenticated_by');
    }

    /**
     * Get the user who dispatched this meal
     */
    public function dispatchedBy()
    {
        return $this->belongsTo(User::class, 'dispatched_by');
    }

    /**
     * Get the user who delivered this meal
     */
    public function deliveredBy()
    {
        return $this->belongsTo(User::class, 'delivered_by');
    }

    /**
     * Check if product is low on stock (main inventory)
     */
    public function isLowStock()
    {
        return $this->quantity_in_stock <= $this->reorder_level;
    }

    /**
     * Check if product is out of stock (main inventory)
     */
    public function isOutOfStock()
    {
        return $this->quantity_in_stock <= 0;
    }

    /**
     * Check if catering stock is low
     */
    public function isCateringStockLow()
    {
        return $this->catering_stock <= $this->catering_reorder_level;
    }

    /**
     * Check if catering stock is out
     */
    public function isCateringStockOut()
    {
        return $this->catering_stock <= 0;
    }

    /**
     * Scope to get only active products
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only inactive products
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'sku', 'unit_price', 'quantity_in_stock', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
