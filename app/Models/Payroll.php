<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $table = 'payroll';

    protected $fillable = [
        'staff_id',
        'payment_date',
        'days_worked',
        'basic_amount',
        'bonus',
        'deduction',
        'net_amount',
        'status',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
