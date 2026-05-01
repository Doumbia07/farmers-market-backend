<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Farmer extends Model
{
    use HasFactory;

    protected $fillable = ['identifier', 'firstname', 'lastname', 'phone', 'credit_limit'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function debts()
    {
        return $this->hasMany(Debt::class);
    }

    public function repayments()
    {
        return $this->hasMany(Repayment::class);
    }

    public function totalOutstandingDebt()
    {
        return $this->debts()->sum('remaining_amount');
    }
}
