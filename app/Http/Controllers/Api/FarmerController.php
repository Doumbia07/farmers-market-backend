<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Farmer;

class FarmerController extends Controller
{
    /**
     * Formate un agriculteur avec sa dette totale.
     */
    private function formatFarmer(Farmer $farmer)
    {
        return [
            'id' => $farmer->id,
            'identifier' => $farmer->identifier,
            'firstname' => $farmer->firstname,
            'lastname' => $farmer->lastname,
            'phone' => $farmer->phone,
            'credit_limit' => $farmer->credit_limit,
            'total_outstanding' => $farmer->totalOutstandingDebt(),
            'created_at' => $farmer->created_at,
            'updated_at' => $farmer->updated_at,
        ];
    }

    public function index()
    {
        $farmers = Farmer::all();
        return response()->json($farmers->map(fn($f) => $this->formatFarmer($f)));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'identifier' => 'required|string|unique:farmers',
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'phone' => 'required|string',
            'credit_limit' => 'required|integer|min:0',
        ]);

        $farmer = Farmer::create($data);
        return response()->json($this->formatFarmer($farmer), 201);
    }

    public function show(Farmer $farmer)
    {
        return response()->json($this->formatFarmer($farmer));
    }

    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string',
        ]);

        $q = $request->q;
        $farmer = Farmer::where('identifier', $q)
            ->orWhere('phone', $q)
            ->first();

        if (!$farmer) {
            return response()->json(['message' => 'Agriculteur non trouvé'], 404);
        }

        return response()->json($this->formatFarmer($farmer));
    }
}
