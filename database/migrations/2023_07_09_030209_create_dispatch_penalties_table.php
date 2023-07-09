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
        Schema::create('dispatch_penalties', function (Blueprint $table) {
            $table->id();
            $table->decimal('arsenic',10,4,true);
            $table->decimal('antomony',10,4,true);
            $table->decimal('lead',10,4,true);
            $table->decimal('zinc',10,4,true);
            $table->decimal('bismuth',10,4,true);
            $table->decimal('mercury',10,4,true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispatch_penalties');
    }
};
