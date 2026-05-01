<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FarmerController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\RepaymentController;
use App\Http\Controllers\Api\DebtController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SupervisorController;
use App\Http\Controllers\Api\OperatorController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::get('/login', function() {
    return response()->json(['message' => 'Non authentifié'], 401);
})->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::get('/recent-transactions', [TransactionController::class, 'recent']);

    // Admin seulement – gestion des superviseurs
    Route::middleware('role:admin')->group(function () {
        Route::get('/supervisors', [SupervisorController::class, 'index']);
        Route::post('/supervisors', [SupervisorController::class, 'store']);
        Route::delete('/supervisors/{supervisor}', [SupervisorController::class, 'destroy']);
    });

    // Admin et superviseur – gestion des opérateurs, et écriture catégories/produits
    Route::middleware('role:admin,supervisor')->group(function () {
        Route::get('/operators', [OperatorController::class, 'index']);
        Route::post('/operators', [OperatorController::class, 'store']);
        Route::delete('/operators/{operator}', [OperatorController::class, 'destroy']);

        // Écriture (POST, PUT, DELETE) des catégories et produits
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{category}', [CategoryController::class, 'update']);
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);
    });

    // Tous les rôles (admin, superviseur, opérateur) – lecture catégories/produits et fonctionnalités POS
    Route::middleware('role:admin,supervisor,operator')->group(function () {
        // Lecture des catégories et produits
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::get('/categories/{category}', [CategoryController::class, 'show']);
        Route::get('/products', [ProductController::class, 'index']);
        Route::get('/products/{product}', [ProductController::class, 'show']);

        // Fonctionnalités POS
        Route::get('/farmers/search', [FarmerController::class, 'search']);
        Route::apiResource('farmers', FarmerController::class)->except(['destroy']);
        Route::post('/transactions', [TransactionController::class, 'store']);
        Route::get('/farmers/{farmer}/debts', [DebtController::class, 'index']);
        Route::post('/repayments', [RepaymentController::class, 'store']);
    });
});
