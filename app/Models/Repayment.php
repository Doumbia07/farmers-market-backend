<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repayment extends Model
{
    use HasFactory;

    protected $fillable = ['farmer_id', 'operator_id', 'kg_received', 'commodity_rate', 'fcfa_value'];

    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function debts()
    {
        return $this->belongsToMany(Debt::class, 'debt_repayment')
            ->withPivot('amount_applied')
            ->withTimestamps();
    }
}
