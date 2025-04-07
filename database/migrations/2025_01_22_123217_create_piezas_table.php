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
        Schema::create('piezas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_pieza_id')->constrained('tipo_piezas')->onDelete('cascade');
            $table->foreignId('talla_id')->nullable()->constrained('tallas')->onDelete('set null');
            $table->string('name');
            $table->decimal('valor_reposicion', 10, 2)->default(0);
            $table->string('color');
            $table->string('material');
            $table->text('notas')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('piezas');
    }
};
