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
        Schema::create('profitabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dispatch_id')->constrained();
            $table->string('tipoDoc');
            $table->string('serie');
            $table->string('number');
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('entities')->onDelete('restrict');
            $table->decimal('imponible',8,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profitabilities');
    }
};
