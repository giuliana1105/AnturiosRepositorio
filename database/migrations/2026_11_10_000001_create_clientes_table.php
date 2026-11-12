<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->string('cedula', 13)->unique(); // Cédula (10) o RUC (13)
            $table->string('direccion', 255);
            $table->string('correo', 150)->nullable();
            $table->string('celular', 15);
            $table->string('nombre_empresa', 200)->nullable(); // Opcional
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};

