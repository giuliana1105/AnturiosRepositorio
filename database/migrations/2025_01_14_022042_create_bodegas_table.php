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
        Schema::create('bodegas', function (Blueprint $table) {
            $table->id('idbodega'); // Usamos el método `id()` para un campo autoincremental
            $table->char('nombrebodega', 10); 
            
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bodegas');
    }
};
