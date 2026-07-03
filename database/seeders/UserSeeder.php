<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Contracts\Role as ContractsRole;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear Permisos (Agrupados por Módulos del Menú)
        $permisos = [
            // Inicio
            'ver dashboard general',
            'ver dashboard vendedor',
            
            // Catálogo e Inventario
            'ver productos',
            'gestionar productos',
            'ver inventario global',
            'ver inventario local',
            
            // Movimientos
            'gestionar mis solicitudes',
            'aprobar solicitudes',
            'ver historial transferencias',
            
            // Ventas y Recaudación
            'registrar ventas',
            'gestionar cuentas cobrar',
            'generar liquidacion',
            
            // Configuración
            'gestionar usuarios',
            'gestionar bodegas',
        ];

        foreach ($permisos as $permiso) {
            Permission::create(['name' => $permiso]);
        }

        // 2. Crear Roles
        $roleAdmin = Role::create(['name' => 'Administrador']);
        $roleJefeBodega = Role::create(['name' => 'Jefe de Bodega']);
        $roleVendedor = Role::create(['name' => 'Vendedor']);

        // 3. Asignar Permisos a Roles
        
        // Administrador: Tiene todos los permisos, o los específicos de gestión global
        $roleAdmin->syncPermissions(Permission::all()); // Opcional: Dale todo. Si quieres limitarlo:
        /* $roleAdmin->syncPermissions([
            'ver dashboard general', 'gestionar productos', 'ver inventario global',
            'aprobar solicitudes', 'ver historial transferencias', 'gestionar usuarios', 'gestionar bodegas'
        ]); */

        // Jefe de Bodega
        $roleJefeBodega->syncPermissions([
            'ver productos', 
            'gestionar productos', 
            'ver inventario global', 
            'ver historial transferencias'
        ]);

        // Vendedor (Mostrador o Camión)
        $roleVendedor->syncPermissions([
            'ver dashboard vendedor', 
            'ver productos', 
            'ver inventario local', 
            'gestionar mis solicitudes', 
            'registrar ventas', 
            'gestionar cuentas cobrar', 
            'generar liquidacion'
        ]);

        // 4. Crear Usuarios de Prueba y Asignar Roles

        $adminUser = User::query()->create([
            'name' => 'Admin Test',
            'email' => 'admin@gmail.com',
            'username' => 'admin_user', 
            'password' => 'Administrator55@',
            'email_verified_at' => now()
        ]);
        $adminUser->assignRole($roleAdmin);

        // Usuario Jefe de Bodega
        $jefeBodegaUser = User::query()->create([
            'name' => 'Jefe Bodega Test',
            'email' => 'jefebodega@gmail.com',
            'username' => 'jefe_bodega', 
            'password' => 'Jefebodega55@',
            'email_verified_at' => now()
        ]);
        $jefeBodegaUser->assignRole($roleJefeBodega);

        // Usuario Vendedor
        $vendedorUser = User::query()->create([
            'name' => 'Vendedor Test',
            'email' => 'vendedor@vendedor.com',
            'username' => 'saler_user', 
            'password' => 'Saler11@',
            'email_verified_at' => now()
        ]);
        $vendedorUser->assignRole($roleVendedor);
    }
}
