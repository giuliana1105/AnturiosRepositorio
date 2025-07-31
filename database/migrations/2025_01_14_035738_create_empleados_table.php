<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->string('nro_identificacion')->primary();
            $table->string('nombreemp');
            $table->string('apellidoemp');
            $table->string('email')->unique();
            $table->string('nro_telefono', 10);
            $table->string('direccionemp', 100);
            $table->unsignedBigInteger('idbodega');
            $table->enum('tipo_identificacion', ['Cedula', 'RUC', 'Pasaporte']);
            $table->unsignedBigInteger('codigocargo'); // Se cambia idcargo a codigocargo
            $table->timestamps();

            // Relaciones
            $table->foreign('idbodega')->references('idbodega')->on('bodegas');
            $table->foreign('codigocargo')->references('codigocargo')->on('cargos'); // Cambio en la clave foránea
        });

        // Creación del procedimiento para validar datos de empleados
        DB::unprepared("CREATE OR REPLACE FUNCTION validar_empleado() RETURNS TRIGGER AS $$
        DECLARE
            provincia INTEGER;
            tercer_digito INTEGER;
            coeficientes INTEGER[] := ARRAY[2, 1, 2, 1, 2, 1, 2, 1, 2];  -- Coeficientes usados en validación de cédula
            suma INTEGER := 0;
            resultado INTEGER;
            digito_verificador INTEGER;
            i INTEGER;
            digito INTEGER;
            ultimos_tres_digitos TEXT;
            primeros_diez_digitos TEXT;
            primer_digito CHAR;
            digitos_iguales BOOLEAN;
        BEGIN
            -- Validación del nombre y apellido (solo letras y espacios)
            IF NEW.nombreemp !~ '^[a-zA-ZÁÉÍÓÚáéíóúÑñ ]+$' THEN
                RAISE EXCEPTION 'El nombre solo puede contener letras y espacios';
            END IF;
            
            IF NEW.apellidoemp !~ '^[a-zA-ZÁÉÍÓÚáéíóúÑñ ]+$' THEN
                RAISE EXCEPTION 'El apellido solo puede contener letras y espacios';
            END IF;

            -- Validación del email
            IF NEW.email !~ '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$' THEN
                RAISE EXCEPTION 'El email no es válido: ejemplo@gmail.com';
            END IF;

            -- Validación del número de teléfono
            IF NEW.nro_telefono !~ '^0[2-9][0-9]{8}$' THEN
                RAISE EXCEPTION 'El número de teléfono debe comenzar con 0, el segundo dígito entre 2 y 9 y tener 10 dígitos en total';
            END IF;

            -- Validación de identificaciones
            IF NEW.tipo_identificacion = 'Cedula' OR NEW.tipo_identificacion = 'RUC' THEN
                -- Extraer los primeros 10 dígitos (para cédula o base del RUC)
                primeros_diez_digitos := SUBSTRING(NEW.nro_identificacion FROM 1 FOR 10);

                -- Verificar que tiene 10 dígitos numéricos
                IF LENGTH(primeros_diez_digitos) <> 10 OR primeros_diez_digitos !~ '[0-9]+' THEN
                    RAISE EXCEPTION 'La cédula debe contener 10 dígitos numéricos';
                END IF;

                -- Validación del dígito verificador
                suma := 0;
                FOR i IN 1..9 LOOP
                    digito := CAST(SUBSTRING(primeros_diez_digitos FROM i FOR 1) AS INTEGER);
                    suma := suma + (digito * coeficientes[i-1]);
                END LOOP;

                digito_verificador := (suma % 10);
                IF digito_verificador <> 0 THEN
                    RAISE EXCEPTION 'El número de identificación no es válido';
                END IF;
            END IF;

            RETURN NEW;
        END;
        $$ LANGUAGE plpgsql;");

        DB::unprepared("CREATE TRIGGER trg_validar_empleado
        BEFORE INSERT OR UPDATE ON empleados
        FOR EACH ROW EXECUTE FUNCTION validar_empleado();");
    }

    public function down(): void
{
    // Eliminar el trigger que depende de la función
    DB::unprepared("DROP TRIGGER IF EXISTS trigger_validar_empleado ON empleados;");
    
    // Eliminar la función
    DB::unprepared("DROP FUNCTION IF EXISTS validar_empleado;");

    // Eliminar la tabla empleados
    Schema::dropIfExists('empleados');
}

};
