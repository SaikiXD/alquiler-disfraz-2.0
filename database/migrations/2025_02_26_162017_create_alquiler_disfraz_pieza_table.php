<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('alquiler_disfraz_pieza', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alquiler_disfraz_id')->constrained('alquiler_disfraz')->onDelete('cascade');
            $table->foreignId('pieza_id')->constrained()->onDelete('cascade');
            $table->integer('cantidad_reservada');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alquiler_disfraz_pieza');
    }
};
