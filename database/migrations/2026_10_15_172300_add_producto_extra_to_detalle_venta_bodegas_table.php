<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agrega soporte para "Producto Extra" en el detalle de ventas.
     * - es_extra: indica si el producto vendido es extra (no estaba en inventario)
     * - nombre_extra: nombre del producto escrito manualmente por el vendedor
     * - producto_id se vuelve nullable porque los extras no tienen código en el catálogo
     */
    public function up(): void
    {
        Schema::table('detalle_venta_bodegas', function (Blueprint $table) {
            $table->boolean('es_extra')->default(false)->after('precio_total');
            $table->string('nombre_extra')->nullable()->after('es_extra');
        });

        // Hacer producto_id nullable para permitir productos extra sin código
        Schema::table('detalle_venta_bodegas', function (Blueprint $table) {
            $table->string('producto_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_venta_bodegas', function (Blueprint $table) {
            $table->dropColumn(['es_extra', 'nombre_extra']);
        });

        Schema::table('detalle_venta_bodegas', function (Blueprint $table) {
            $table->string('producto_id')->nullable(false)->change();
        });
    }
};
