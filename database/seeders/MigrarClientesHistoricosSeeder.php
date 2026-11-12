<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;
use App\Models\Venta;

class MigrarClientesHistoricosSeeder extends Seeder
{
    /**
     * Opcional: Migra los clientes en texto libre de las ventas históricas
     * hacia la nueva tabla 'clientes' y vincula 'cliente_id'.
     */
    public function run(): void
    {
        $nombresClientes = Venta::whereNotNull('cliente')
            ->where('cliente', '!=', '')
            ->distinct()
            ->pluck('cliente');

        $contador = 0;
        foreach ($nombresClientes as $nombreOriginal) {
            $nombreLimpio = trim($nombreOriginal);
            if (empty($nombreLimpio)) {
                continue;
            }

            // Separar nombres y apellidos
            $partes = preg_split('/\s+/', $nombreLimpio);
            if (count($partes) >= 2) {
                $nombres = $partes[0];
                $apellidos = implode(' ', array_slice($partes, 1));
            } else {
                $nombres = $nombreLimpio;
                $apellidos = '(Sin apellido)';
            }

            $cedulaProvisional = '99' . str_pad((string)($contador + 1), 8, '0', STR_PAD_LEFT);

            $cliente = Cliente::firstOrCreate(
                [
                    'nombres'   => $nombres,
                    'apellidos' => $apellidos,
                ],
                [
                    'cedula'         => $cedulaProvisional,
                    'direccion'      => 'Registrado históricamente',
                    'celular'        => '0999999999',
                    'nombre_empresa' => null,
                ]
            );

            Venta::where('cliente', $nombreOriginal)
                ->whereNull('cliente_id')
                ->update(['cliente_id' => $cliente->id]);

            $contador++;
        }

        $this->command->info("Se migraron/vincularon {$contador} clientes históricos.");
    }
}

