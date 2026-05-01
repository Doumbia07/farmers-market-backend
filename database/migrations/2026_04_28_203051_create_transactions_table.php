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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained();
            $table->foreignId('operator_id')->constrained('users');
            $table->enum('payment_method', ['cash', 'credit']);
            $table->integer('total_cash'); // total HT des produits
            $table->integer('interest_rate')->nullable(); // en % (ex: 30)
            $table->integer('total_credit')->nullable(); // total avec intérêt
            $table->string('status')->default('completed'); // ou 'partially_repaid' etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
