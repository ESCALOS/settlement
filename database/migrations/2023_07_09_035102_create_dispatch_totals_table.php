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
        Schema::create('dispatch_totals', function (Blueprint $table) {
            $table->id();
            $table->decimal('wmt',10,4,true);
            $table->decimal('dnwmt',10,4,true);
            $table->decimal('amount',10,4,true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispatch_totals');
    }
};
