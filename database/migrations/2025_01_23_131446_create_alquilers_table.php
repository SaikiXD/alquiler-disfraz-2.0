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
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
            $table->string('image_path_garantia')->nullable()->default('N/A');
            $table->string('tipo_garantia');
            $table->string('description')->nullable()->default('N/A');
            $table->decimal('valor_garantia', 10, 2);
            $table->dateTime('fecha_alquiler');
            $table->date('fecha_devolucion');
            $table->enum('status', ['pendiente', 'alquilado', 'finalizado', 'cancelado']);
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
