<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'id_number',
        'phone',
        'date_of_birth',
        'gender',
        'allergies',
        'address',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class)->latest();
    }

    public function getOutstandingBalanceAttribute(): float
    {
        return $this->invoices()
            ->whereIn('status', ['unpaid', 'partial'])
            ->get()
            ->sum(fn ($inv) => $inv->total_amount - $inv->paid_amount);
    }
}
