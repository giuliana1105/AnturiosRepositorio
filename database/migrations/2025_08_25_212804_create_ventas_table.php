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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id(); // Nro. venta
            $table->unsignedBigInteger('bodega_id');
            $table->string('cliente')->nullable();
            $table->decimal('total_venta', 12, 2)->default(0);
            $table->enum('tipo_pago', ['Efectivo', 'Transferencia', 'Crédito', 'Cheque'])->default('Efectivo');
            $table->dateTime('fecha');
            $table->timestamps();

            $table->foreign('bodega_id')->references('idbodega')->on('bodegas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
