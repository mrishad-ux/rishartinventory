<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Expense extends Model
{
    protected $fillable = [
        'title',
        'category',
        'amount',
        'paid_amount',
        'expense_date',
        'supplier_id',
        'notes',
    ];

    protected $casts = [
        'expense_date' => 'date',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getPendingAmountAttribute()
    {
        return $this->amount - $this->paid_amount;
    }

    public function getPaymentStatusAttribute()
    {
        if ($this->paid_amount >= $this->amount) {
            return 'paid';
        }
        if ($this->paid_amount > 0) {
            return 'partial';
        }
        return 'pending';
    }

    public function scopePending($query)
    {
        return $query->where('paid_amount', '<', DB::raw('amount'));
    }

    public function scopePartial($query)
    {
        return $query->where('paid_amount', '>', 0);
    }
}