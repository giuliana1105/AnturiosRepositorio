<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateProductosTable extends Migration
{
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->string('codigo', 10)->primary(); // Clave primaria y única
            $table->string('nombre', 50);
            $table->text('descripcion');
            $table->integer('cantidad');
            $table->string('tipoempaque')->nullable();
            $table->timestamps();
        });

        // Crear función PL/pgSQL para validar restricciones antes de insertar o actualizar
        DB::unprepared("
            CREATE OR REPLACE FUNCTION validar_producto()
            RETURNS TRIGGER AS $$
            BEGIN
                -- Validar que código solo contenga letras y números, tenga al menos una letra y un número, y no más de 10 caracteres
                IF NEW.codigo !~ '^[A-Za-z0-9]{1,10}$' THEN
                    RAISE EXCEPTION 'El código del producto solo puede contener letras y números y no debe superar 10 caracteres.';
                END IF;

                -- Verificar que el código tenga al menos una letra y un número
                IF NEW.codigo !~ '[A-Za-z]' OR NEW.codigo !~ '[0-9]' THEN
                    RAISE EXCEPTION 'El código del producto debe contener al menos una letra y un número.';
                END IF;

                -- Validar que el nombre solo contenga letras y espacios
                IF NEW.nombre !~ '^[A-Za-z ]+$' THEN
                    RAISE EXCEPTION 'El nombre del producto solo puede contener letras y espacios.';
                END IF;

                -- Validar que la cantidad no sea negativa
                IF NEW.cantidad < 0 THEN
                    RAISE EXCEPTION 'La cantidad no puede ser negativa.';
                END IF;

                -- Validar que los campos obligatorios no sean NULL
                IF NEW.codigo IS NULL OR NEW.nombre IS NULL OR NEW.descripcion IS NULL OR NEW.cantidad IS NULL THEN
                    RAISE EXCEPTION 'Todos los campos obligatorios deben estar llenos.';
                END IF;

                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ");

        // Crear el trigger que ejecutará la validación antes de insertar o actualizar
        DB::unprepared("
            CREATE TRIGGER trg_validar_producto
            BEFORE INSERT OR UPDATE ON productos
            FOR EACH ROW EXECUTE FUNCTION validar_producto();
        ");
    }

    public function down()
    {
        // Eliminar el trigger y la función antes de eliminar la tabla
        DB::unprepared("DROP TRIGGER IF EXISTS trg_validar_producto ON productos;");
        DB::unprepared("DROP FUNCTION IF EXISTS validar_producto;");

        Schema::dropIfExists('productos');
    }
}
