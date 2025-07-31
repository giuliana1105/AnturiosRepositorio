<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('transaccion_producto', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_nota_id', 50);
            $table->foreign('tipo_nota_id')->references('codigo')->on('tipo_nota')->onDelete('cascade');
            $table->enum('estado', ['PENDIENTE', 'FINALIZADA'])->default('PENDIENTE');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaccion_producto');
    }
};
