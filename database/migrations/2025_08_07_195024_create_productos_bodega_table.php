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
        Schema::create('productos_bodega', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bodega_id');
            $table->string('producto_id'); // Usa el tipo de tu clave primaria en productos (por ejemplo, string si es 'codigo')
            $table->integer('cantidad');
            $table->date('fecha');
            $table->boolean('es_devolucion')->default(false);
            $table->timestamps();

            $table->foreign('bodega_id')->references('idbodega')->on('bodegas')->onDelete('cascade');
            $table->foreign('producto_id')->references('codigo')->on('productos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos_bodega');
    }
};
