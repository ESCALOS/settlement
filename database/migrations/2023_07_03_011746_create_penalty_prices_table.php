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
        Schema::create('penalty_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('settlement_id')->constrained();
            $table->unsignedDecimal('arsenic',12,4);
            $table->unsignedDecimal('antomony',12,4);
            $table->unsignedDecimal('lead',12,4);
            $table->unsignedDecimal('zinc',12,4);
            $table->unsignedDecimal('bismuth',12,4);
            $table->unsignedDecimal('mercury',12,4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penalty_prices');
    }
};
