<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Farmer;
use App\Models\Repayment;
use Illuminate\Http\Request;

class RepaymentController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'farmer_id' => 'required|exists:farmers,id',
            'kg_received' => 'required|integer|min:1',
            'commodity_rate' => 'required|integer|min:1',
        ]);

        $farmer = Farmer::findOrFail($data['farmer_id']);
        $fcfaValue = $data['kg_received'] * $data['commodity_rate'];

        $repayment = Repayment::create([
            'farmer_id' => $farmer->id,
            'operator_id' => auth()->id(),
            'kg_received' => $data['kg_received'],
            'commodity_rate' => $data['commodity_rate'],
            'fcfa_value' => $fcfaValue,
        ]);

        $debts = $farmer->debts()
            ->where('remaining_amount', '>', 0)
            ->orderBy('created_at', 'asc')
            ->get();

        $remaining = $fcfaValue;

        foreach ($debts as $debt) {
            if ($remaining <= 0) break;

            $apply = min($remaining, $debt->remaining_amount);
            $debt->remaining_amount -= $apply;
            $debt->save();

            $repayment->debts()->attach($debt->id, ['amount_applied' => $apply]);
            $remaining -= $apply;
        }

        return response()->json([
            'repayment' => $repayment,
            'fcfa_value' => $fcfaValue,
            'remaining_unapplied' => $remaining,
        ], 201);
    }
}
