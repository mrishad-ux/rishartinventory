<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformSettlement extends Model
{
    protected $fillable = [
        'platform',
        'period_from',
        'period_to',
        'expected_credit_date',
        'actual_credit_date',
        'gross_amount',
        'estimated_commission',
        'estimated_net',
        'actual_amount_received',
        'actual_commission',
        'status',
        'notes',
    ];

    protected $casts = [
        'period_from' => 'date',
        'period_to' => 'date',
        'expected_credit_date' => 'date',
        'actual_credit_date' => 'date',
    ];

    /**
     * Get the commission percentage.
     * Returns actual commission % if received, else 31%.
     */
    public function getCommissionPercentAttribute()
    {
        if ($this->status === 'received' && $this->actual_commission > 0 && $this->gross_amount > 0) {
            return round(($this->actual_commission / $this->gross_amount) * 100, 2);
        }

        return 31.0;
    }
}
