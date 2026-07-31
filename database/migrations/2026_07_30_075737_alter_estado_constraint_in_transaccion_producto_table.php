<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the old check constraint and add a new one with the expanded states
        DB::statement("ALTER TABLE transaccion_producto DROP CONSTRAINT IF EXISTS transaccion_producto_estado_check");
        DB::statement("ALTER TABLE transaccion_producto ADD CONSTRAINT transaccion_producto_estado_check CHECK (estado::text = ANY (ARRAY['PENDIENTE'::character varying, 'FINALIZADA'::character varying, 'FINALIZADA_PARCIAL'::character varying, 'RECHAZADA'::character varying]::text[]))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE transaccion_producto DROP CONSTRAINT IF EXISTS transaccion_producto_estado_check");
        DB::statement("ALTER TABLE transaccion_producto ADD CONSTRAINT transaccion_producto_estado_check CHECK (estado::text = ANY (ARRAY['PENDIENTE'::character varying, 'FINALIZADA'::character varying]::text[]))");
    }
};
