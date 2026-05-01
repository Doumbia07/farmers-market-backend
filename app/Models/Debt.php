<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    use HasFactory;

    protected $fillable = ['transaction_id', 'farmer_id', 'initial_amount', 'remaining_amount'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }

    public function repayments()
    {
        return $this->belongsToMany(Repayment::class, 'debt_repayment')
            ->withPivot('amount_applied')
            ->withTimestamps();
    }
}
