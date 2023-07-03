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
        Schema::create('settlement_totals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('settlement_id')->constrained();
            $table->decimal('payable_total',12,4);
            $table->decimal('deduction_total',12,4);
            $table->decimal('penalty_total',12,4);
            $table->decimal('unit_price',12,4);
            $table->decimal('batch_price',12,4);
            $table->decimal('igv',12,4);
            $table->decimal('detraccion',12,4);
            $table->decimal('total',12,4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settlement_totals');
    }
};
