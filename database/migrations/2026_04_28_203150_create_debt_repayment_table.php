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
        Schema::create('debt_repayment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('debt_id')->constrained();
            $table->foreignId('repayment_id')->constrained();
            $table->integer('amount_applied'); // part du remboursement affectée à cette dette
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debt_repayment');
    }
};
