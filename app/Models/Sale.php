<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'sale_date',
        'sale_type',
        'platform',
        'gross_amount',
        'commission_percent',
        'commission_amount',
        'net_amount',
        'settlement_status',
        'expected_settlement_date',
        'actual_settlement_date',
        'notes',
    ];

    protected $casts = [
        'sale_date' => 'date',
        'expected_settlement_date' => 'date',
        'actual_settlement_date' => 'date',
    ];
}
