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
        Schema::create('settlements', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('order_id')->constrained();
            $table->string('batch',10)->unique();
            $table->boolean('with_invoice')->default(false);
            $table->unsignedDecimal('wmt_shipped',12,4)->default(0); // wet metric tonne shipped
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settlements');
    }
};
