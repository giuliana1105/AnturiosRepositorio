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
        Schema::create('venta_bodegas', function (Blueprint $table) {
            $table->id(); // Serial
            $table->unsignedBigInteger('bodega_id');
            $table->string('producto_id');
            $table->dateTime('fecha');
            $table->integer('cantidad');
            $table->string('tipoempaque')->default('Unidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('precio_total', 12, 2);
            $table->timestamps();

            $table->foreign('bodega_id')->references('idbodega')->on('bodegas');
            $table->foreign('producto_id')->references('codigo')->on('productos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venta_bodegas');
    }
};
