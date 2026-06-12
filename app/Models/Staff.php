<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $fillable = [
        'name',
        'role',
        'phone',
        'salary_type',
        'salary_amount',
        'joining_date',
        'status',
    ];

    protected $casts = [
        'joining_date' => 'date',
    ];

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }
}
