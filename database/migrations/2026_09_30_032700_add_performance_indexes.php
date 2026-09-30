<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Índices de rendimiento para optimizar consultas con Supabase remoto.
     * Estos índices cubren las combinaciones de columnas más consultadas.
     */
    public function up(): void
    {
        // Índices para productos_bodega (tabla más consultada del sistema)
        DB::statement('CREATE INDEX IF NOT EXISTS idx_pb_bodega_producto ON productos_bodega (bodega_id, producto_id)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_pb_fecha ON productos_bodega (fecha)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_pb_bodega_fecha_devolucion ON productos_bodega (bodega_id, fecha, es_devolucion)');

        // Índices para ventas
        DB::statement('CREATE INDEX IF NOT EXISTS idx_ventas_bodega ON ventas (bodega_id)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_ventas_tipo_pago ON ventas (tipo_pago)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_ventas_fecha ON ventas (fecha)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_ventas_bodega_fecha ON ventas (bodega_id, fecha)');

        // Índices para abonos
        DB::statement('CREATE INDEX IF NOT EXISTS idx_abonos_venta ON abonos (venta_id)');

        // Índices para detalle_venta_bodegas
        DB::statement('CREATE INDEX IF NOT EXISTS idx_dvb_venta ON detalle_venta_bodegas (venta_id)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_dvb_producto ON detalle_venta_bodegas (producto_id)');

        // Índices para tipo_nota
        DB::statement('CREATE INDEX IF NOT EXISTS idx_tipo_nota_bodega ON tipo_nota (idbodega)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_tipo_nota_identificacion ON tipo_nota (nro_identificacion)');
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS idx_pb_bodega_producto');
        DB::statement('DROP INDEX IF EXISTS idx_pb_fecha');
        DB::statement('DROP INDEX IF EXISTS idx_pb_bodega_fecha_devolucion');
        DB::statement('DROP INDEX IF EXISTS idx_ventas_bodega');
        DB::statement('DROP INDEX IF EXISTS idx_ventas_tipo_pago');
        DB::statement('DROP INDEX IF EXISTS idx_ventas_fecha');
        DB::statement('DROP INDEX IF EXISTS idx_ventas_bodega_fecha');
        DB::statement('DROP INDEX IF EXISTS idx_abonos_venta');
        DB::statement('DROP INDEX IF EXISTS idx_dvb_venta');
        DB::statement('DROP INDEX IF EXISTS idx_dvb_producto');
        DB::statement('DROP INDEX IF EXISTS idx_tipo_nota_bodega');
        DB::statement('DROP INDEX IF EXISTS idx_tipo_nota_identificacion');
    }
};
