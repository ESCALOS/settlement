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
        Schema::create('dispatch_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dispatch_id')->constrained();
            $table->foreignId('settlement_id')->constrained();
            $table->decimal('wmt',12,4); // wet metric tonne
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispatch_details');
    }
};
