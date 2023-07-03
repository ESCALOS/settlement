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
        Schema::create('deduction_totals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('settlement_id')->constrained();
            $table->decimal('unit_price_copper',12,4);
            $table->decimal('total_price_copper',12,4);
            $table->decimal('unit_price_silver',12,4);
            $table->decimal('total_price_silver',12,4);
            $table->decimal('unit_price_gold',12,4);
            $table->decimal('total_price_gold',12,4);
            $table->decimal('maquila',12,4);
            $table->decimal('analysis',12,4);
            $table->decimal('stevedore',12,4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deduction_totals');
    }
};
