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
        Schema::create('protections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('settlement_id')->constrained()->onDelete('cascade');
            $table->unsignedDecimal('copper',12,4);
            $table->unsignedDecimal('silver',12,4);
            $table->unsignedDecimal('gold',12,4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('protections');
    }
};
