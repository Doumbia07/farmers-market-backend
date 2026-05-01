<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('repayments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained();
            $table->foreignId('operator_id')->constrained('users');
            $table->integer('kg_received');
            $table->integer('commodity_rate'); // taux configurable (FCFA par kg)
            $table->integer('fcfa_value');     // kg * rate
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repayments');
    }
};
