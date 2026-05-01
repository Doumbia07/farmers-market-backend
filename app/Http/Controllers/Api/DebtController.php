<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Farmer;
use Illuminate\Http\Request;

class DebtController extends Controller
{
    public function index(Farmer $farmer)
    {
        $debts = $farmer->debts()->where('remaining_amount', '>', 0)->get();
        return response()->json([
            'farmer' => $farmer,
            'total_outstanding' => $farmer->totalOutstandingDebt(),
            'debts' => $debts,
        ]);
    }
}
