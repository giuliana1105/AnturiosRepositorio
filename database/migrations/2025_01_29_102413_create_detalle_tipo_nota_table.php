<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        Schema::create('detalle_tipo_nota', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_nota_id', 50);
            $table->foreign('tipo_nota_id')->references('codigo')->on('tipo_nota')->onDelete('cascade');
            $table->string('codigoproducto', 50);
            $table->foreign('codigoproducto')->references('codigo')->on('productos')->onDelete('cascade');
            $table->integer('cantidad');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detalle_tipo_nota');
    }
};
