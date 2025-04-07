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
        Schema::create('alquilers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->string('image_path_garantia')->nullable();
            $table->string('tipo_garantia');
            $table->string('detalles_garantia')->nullable();
            $table->decimal('valor_garantia', 10, 2);
            $table->date('fecha_alquiler');
            $table->date('fecha_devolucion');
            $table->enum('status', ['alquilado', 'finalizado'])->default('alquilado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alquilers');
    }
};
