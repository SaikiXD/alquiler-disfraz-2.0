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
        Schema::create('mantenimientos', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('devolucion_disfraz_pieza_id')
                ->nullable()
                ->constrained('devolucion_disfraz_pieza')
                ->nullOnDelete();
            $table->foreignId('pieza_id')->constrained('disfraz_pieza')->onDelete('cascade');
            $table->enum('estado', ['limpieza', 'reparacion', 'completado'])->default('limpieza');
            $table->integer('cantidad');
            $table->decimal('costo', 10, 2)->nullable();
            $table->text('detalles')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mantenimientos');
    }
};
