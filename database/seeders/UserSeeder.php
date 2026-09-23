<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear Permisos (Agrupados por Módulos del Sistema)
        $permisos = [
            // Dashboard
            'ver dashboard general',
            'ver dashboard vendedor',

            // Productos
            'ver productos',
            'crear producto',
            'editar producto',
            'eliminar producto',

            // Empleados
            'ver empleados',
            'crear empleado',
            'editar empleado',
            'eliminar empleado',

            // Bodegas
            'ver bodegas',
            'crear bodega',
            'editar bodega',
            'eliminar bodega',

            // Notas / Solicitudes
            'ver notas',
            'crear nota',
            'editar nota',
            'eliminar nota',

            // Transacciones / Movimientos
            'ver transacciones',
            'gestionar mis solicitudes',
            'aprobar solicitudes',
            'ver historial transferencias',

            // Ventas y Recaudación
            'ver ventas',
            'registrar ventas',
            'gestionar cuentas cobrar',
            'generar liquidacion',

            // Usuarios
            'ver usuarios',
            'crear usuario',
            'editar usuario',
            'eliminar usuario',

            // Roles
            'ver roles',
            'crear rol',
            'editar rol',
            'eliminar rol',
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        // 2. Crear Roles (protegidos del sistema)
        $roleAdmin = Role::firstOrCreate(['name' => 'Administrador']);
        $roleJefeBodega = Role::firstOrCreate(['name' => 'Jefe de Bodega']);
        $roleVendedor = Role::firstOrCreate(['name' => 'Vendedor']);

        // 3. Asignar Permisos a Roles

        // Administrador: Tiene todos los permisos
        $roleAdmin->syncPermissions(Permission::all());

        // Jefe de Bodega
        $roleJefeBodega->syncPermissions([
            'ver dashboard general',
            'ver productos',
            'crear producto',
            'editar producto',
            'ver bodegas',
            'ver notas',
            'crear nota',
            'editar nota',
            'ver transacciones',
            'aprobar solicitudes',
            'ver historial transferencias',
            'ver empleados',
        ]);

        // Vendedor (Mostrador o Camión)
        $roleVendedor->syncPermissions([
            'ver dashboard vendedor',
            'ver productos',
            'ver bodegas',
            'ver notas',
            'crear nota',
            'ver transacciones',
            'gestionar mis solicitudes',
            'ver ventas',
            'registrar ventas',
            'gestionar cuentas cobrar',
            'generar liquidacion',
        ]);

        // 4. Crear Usuarios de Prueba y Asignar Roles

        $adminUser = User::query()->firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Test',
                'username' => 'admin_user',
                'password' => 'Administrator55@',
                'email_verified_at' => now(),
            ]
        );
        $adminUser->syncRoles([$roleAdmin]);

        // Usuario Jefe de Bodega
        $jefeBodegaUser = User::query()->firstOrCreate(
            ['email' => 'jefebodega@gmail.com'],
            [
                'name' => 'Jefe Bodega Test',
                'username' => 'jefe_bodega',
                'password' => 'Jefebodega55@',
                'email_verified_at' => now(),
            ]
        );
        $jefeBodegaUser->syncRoles([$roleJefeBodega]);

        // Usuario Vendedor
        $vendedorUser = User::query()->firstOrCreate(
            ['email' => 'vendedor@vendedor.com'],
            [
                'name' => 'Vendedor Test',
                'username' => 'saler_user',
                'password' => 'Saler11@',
                'email_verified_at' => now(),
            ]
        );
        $vendedorUser->syncRoles([$roleVendedor]);
    }
}
