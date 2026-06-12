<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    protected $fillable = [
        'name',
        'category',
        'unit',
        'current_stock',
        'minimum_stock',
        'minimum_stock_qty',
        'unit_price',
        'supplier_id',
        'is_mayo',
        'sort_order',
    ];

    protected $casts = [
        'is_mayo' => 'boolean',
    ];

    public static $categories = [
        'shawarma_marination' => 'MARINATION',
        'mayo_masala_sauces' => 'Mayo, Masala & Sauces',
        'chicken_fish' => 'Chicken & Fish',
        'bun_bakery' => 'Bun, Bakery & Grocery',
        'other' => 'Other',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function logs()
    {
        return $this->hasMany(InventoryLog::class);
    }

    public function todayLog()
    {
        return $this->hasOne(InventoryLog::class)->whereDate('log_date', today());
    }

    public function latestLog()
    {
        return $this->hasOne(InventoryLog::class)->latestOfMany('log_date');
    }

    public function isLowStock(): bool
    {
        return $this->current_stock <= $this->minimum_stock;
    }
}
