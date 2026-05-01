<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Debt;
use App\Models\Farmer;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('farmer')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($tx) {
                return [
                    'id' => $tx->id,
                    'farmer_name' => $tx->farmer->firstname . ' ' . $tx->farmer->lastname,
                    'amount' => $tx->payment_method === 'cash' ? $tx->total_cash : $tx->total_credit,
                    'payment_method' => $tx->payment_method,
                    'timestamp' => $tx->created_at->toISOString(),
                    'created_at' => $tx->created_at->toDateTimeString(),
                ];
            });

        return response()->json($transactions);
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'farmer_id' => 'required|exists:farmers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,credit',
            'interest_rate' => 'required_if:payment_method,credit|nullable|integer|min:0',
        ]);

        $farmer = Farmer::findOrFail($data['farmer_id']);
        $totalCash = 0;
        $itemsData = [];

        foreach ($data['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);
            $subtotal = $product->price_fcfa * $item['quantity'];
            $totalCash += $subtotal;
            $itemsData[] = [
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'unit_price' => $product->price_fcfa,
            ];
        }

        $totalCredit = null;
        if ($data['payment_method'] === 'credit') {
            $interestRate = $data['interest_rate'];
            $totalCredit = $totalCash * (1 + $interestRate / 100);
            $currentDebt = $farmer->debts()->sum('remaining_amount');
            if ($currentDebt + $totalCredit > $farmer->credit_limit) {
                return response()->json([
                    'message' => 'Limite de crédit dépassée',
                    'current_debt' => $currentDebt,
                    'credit_limit' => $farmer->credit_limit,
                    'requested_credit' => $totalCredit,
                ], 422);
            }
        }

        $transaction = Transaction::create([
            'farmer_id' => $farmer->id,
            'operator_id' => auth()->id(),
            'payment_method' => $data['payment_method'],
            'total_cash' => $totalCash,
            'interest_rate' => $data['payment_method'] === 'credit' ? $data['interest_rate'] : null,
            'total_credit' => $totalCredit,
        ]);

        foreach ($itemsData as $item) {
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
            ]);
        }

        if ($data['payment_method'] === 'credit') {
            Debt::create([
                'transaction_id' => $transaction->id,
                'farmer_id' => $farmer->id,
                'initial_amount' => $totalCredit,
                'remaining_amount' => $totalCredit,
            ]);
        }

        return response()->json($transaction->load('items'), 201);
    }

    public function recent(Request $request)
    {
        $limit = $request->get('limit', 5);
        $transactions = Transaction::with('farmer')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($tx) {
                return [
                    'id' => $tx->id,
                    'farmer_name' => $tx->farmer->firstname . ' ' . $tx->farmer->lastname,
                    'amount' => $tx->payment_method === 'cash' ? $tx->total_cash : $tx->total_credit,
                    'payment_method' => $tx->payment_method,
                    'timestamp' => $tx->created_at->diffForHumans(),
                ];
            });

        return response()->json($transactions);
    }
}
