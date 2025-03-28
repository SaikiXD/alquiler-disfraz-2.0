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
        Schema::create('devolucions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alquiler_id')->constrained('alquilers')->onDelete('cascade'); // Relación con alquileres
            $table->datetime('fecha_devolucion_real'); // Fecha en la que se realizó la devolución
            $table->decimal('multa', 10, 2)->default(0); // Monto de la multa por retraso
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devolucions');
    }
};
