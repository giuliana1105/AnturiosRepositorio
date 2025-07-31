<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tipo_nota', function (Blueprint $table) {
            $table->string('codigo', 50)->primary();
            $table->string('tiponota', 10);
            $table->string('nro_identificacion');
            $table->foreign('nro_identificacion')->references('nro_identificacion')->on('empleados')->onDelete('cascade');
            
            // Asegúrate de que el idbodega esté alineado con el tipo de datos de la tabla bodegas
            $table->unsignedBigInteger('idbodega');
            $table->foreign('idbodega')->references('idbodega')->on('bodegas')->onDelete('cascade');
            
            $table->date('fechanota')->default(DB::raw('CURRENT_DATE'));
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tipo_nota');
    }
};
