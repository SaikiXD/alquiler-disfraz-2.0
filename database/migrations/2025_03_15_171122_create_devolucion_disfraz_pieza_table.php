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
        Schema::create('devolucion_disfraz_pieza', function (Blueprint $table) {
            $table->id();
            $table->foreignId('devolucion_id')->constrained('devoluciones')->onDelete('cascade'); // Relación con devoluciones
            $table->foreignId('disfraz_pieza_id')->constrained('disfraz_pieza')->onDelete('cascade'); // Relación con disfraz_pieza
            $table->integer('cantidad_devuelta'); // Cantidad de unidades devueltas
            $table->enum('estado_pieza', ['disponible', 'dañado', 'perdido']); // Estado en el que regresa la pieza
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devolucion_disfraz_pieza');
    }
};
