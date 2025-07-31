<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Contracts\Role as ContractsRole;
//use Spatie\Permission\Contracts\Permission;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
//use Spatie\Permission\Models\User;


//Aquí se crean los roles y permisos para los usuarios 
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name'=>'ver producto']);
        Permission::create(['name'=>'crear producto']);
        Permission::create(['name'=>'editar producto ']);
        Permission::create(['name'=>'eliminar producto']);

        Permission::create(['name'=>'ver empleado']);
        Permission::create(['name'=>'crear empleado']);
        Permission::create(['name'=>'editar empleado']);
        Permission::create(['name'=>'eliminar empleado']);


        //creamos un usuario
        $adminUser = User ::query()->create([
            'name'=> 'admin',
            'email'=>'admin@admin.com',
            'username' => 'admin_user', 
            'password '=> 'Administrator55@',
            'email_verified_at'=> now()
        ]);

        
      
        $roleAdmin = Role::create(['name'=>'super-admin']);

       //creamos un rol, asignamos el rol al usuario
        $adminUser->assignRole($roleAdmin);
         
        //Damos todos los permisos
        $permissionsAdmin = Permission::query()->pluck('name');
        //Asignamos todos los permisos al rol al super usuario
        $roleAdmin -> syncPermissions($permissionsAdmin);

        //creamos un nuevo usuario
        $vendedorUser = User ::query()->create([
            'name'=> 'vendedor',
            'email'=>'vendedor@vendedor.com',
            'username' => 'saler_user', 
            'password '=> 'Saler11@',
            'email_verified_at'=> now()
        ]);
        //Se crea el rol vendedor 
        $roleVendedor= Role::create(['name'=>'vendedor']);
        //El rol vendedor se asigna al usuario vendedorUser 
        $vendedorUser->assignRole($roleVendedor);
        //Asigna el permiso ver producto al rol vendedor 
        $roleVendedor->syncPermissions(['ver producto']);
        
       

    }
}
