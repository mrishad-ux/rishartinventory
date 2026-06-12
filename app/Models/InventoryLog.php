<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryLog extends Model
{
    protected $fillable = [
        'inventory_item_id',
        'log_date',
        'opening',
        'opening_source',
        'purchased',
        'consumption',
        'wastage',
        'closing',
        'mayo_oil_qty',
        'mayo_milk_qty',
        'mayo_bottles',
        'notes',
        'oil_l1_packets',
        'oil_l2_packets',
        'oil_r1_packets',
        'oil_r2_packets',
        'oil_mayo_packets',
        'oil_sauces_packets',
    ];

    protected $casts = [
        'log_date' => 'date',
    ];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    // Computed closing stock (mirrors DB stored column)
    public function getClosingAttribute($value)
    {
        // Use DB stored value if available, else calculate
        $calculated = $this->opening + $this->purchased - $this->consumption - $this->wastage;
        \Log::info('getClosingAttribute called', [
            'db_value' => $value,
            'calculated' => $calculated,
            'opening' => $this->opening,
            'purchased' => $this->purchased,
            'consumption' => $this->consumption,
            'wastage' => $this->wastage,
        ]);

        return $value ?? $calculated;
    }
}
