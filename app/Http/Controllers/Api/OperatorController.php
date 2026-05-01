<?php

namespace App\Http\Controllers\Api;


use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OperatorController extends Controller
{
    public function index()
    {
        // Les opérateurs que l'utilisateur connecté a créés (ou tous si admin)
        if (auth()->user()->isAdmin()) {
            $operators = User::where('role', 'operator')->get();
        } else {
            $operators = User::where('role', 'operator')
                ->where('created_by', auth()->id())
                ->get();
        }
        return response()->json($operators);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);
        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'operator';
        $data['created_by'] = auth()->id();
        $operator = User::create($data);
        return response()->json($operator, 201);
    }

    public function destroy(User $operator)
    {
        if ($operator->role !== 'operator') {
            return response()->json(['message' => 'Utilisateur non valide'], 422);
        }
        // Vérifier que l'utilisateur connecté a le droit de supprimer cet opérateur
        if (auth()->user()->isAdmin() || auth()->id() === $operator->created_by) {
            $operator->delete();
            return response()->json(null, 204);
        }
        return response()->json(['message' => 'Accès interdit'], 403);
    }
}
