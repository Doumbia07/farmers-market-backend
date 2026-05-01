<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SupervisorController extends Controller
{
    public function index()
    {
        $supervisors = User::where('role', 'supervisor')->get();
        return response()->json($supervisors);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);
        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'supervisor';
        $data['created_by'] = auth()->id();
        $supervisor = User::create($data);
        return response()->json($supervisor, 201);
    }

    public function destroy(User $supervisor)
    {
        if ($supervisor->role !== 'supervisor') {
            return response()->json(['message' => 'Utilisateur non valide'], 422);
        }
        $supervisor->delete();
        return response()->json(null, 204);
    }
}
