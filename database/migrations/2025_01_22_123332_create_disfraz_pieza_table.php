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
        Schema::create('disfraz_pieza', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disfraz_id')->constrained('disfrazs')->onDelete('cascade');
            $table->foreignId('pieza_id')->constrained('piezas')->onDelete('cascade');
            $table->integer('stock');
            $table->enum('status', ['disponible', 'alquilado', 'retirado', 'perdido'])->default('disponible');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disfraz_pieza');
    }
};
