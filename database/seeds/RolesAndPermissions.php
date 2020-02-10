<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // create permissions
        //USUARIO
        Permission::create(['name' => 'crear-user']);
        Permission::create(['name' => 'ver-users']);
        Permission::create(['name' => 'editar-user']);
        //Permission::create(['name' => 'delete-user']);

        //ROLES
        Permission::create(['name' => 'crear-role']);
        Permission::create(['name' => 'ver-roles']);
        Permission::create(['name' => 'editar-role']);
        //Permission::create(['name' => 'delete-role']);

        //VENDEDORES
        Permission::create(['name' => 'crear-vendedor']);
        Permission::create(['name' => 'ver-vendedors']);
        Permission::create(['name' => 'editar-vendedor']);
        //Permission::create(['name' => 'delete-vendedor']);

        //CLIENTE
        Permission::create(['name' => 'crear-cliente']);
        Permission::create(['name' => 'ver-clientes']);
        Permission::create(['name' => 'editar-cliente']);
        //Permission::create(['name' => 'delete-cliente']);

        //PAGO
        Permission::create(['name' => 'crear-pago']);
        Permission::create(['name' => 'ver-pagos']);
        Permission::create(['name' => 'editar-pago']);
        // Permission::create(['name' => 'delete-pago']);

        //ARTICULO
        Permission::create(['name' => 'crear-articulo']);
        Permission::create(['name' => 'ver-articulos']);
        Permission::create(['name' => 'editar-articulo']);
        //Permission::create(['name' => 'delete-articulo']);

        //CATEGORIA
        Permission::create(['name' => 'crear-categoria']);
        Permission::create(['name' => 'ver-categorias']);
        Permission::create(['name' => 'editar-categoria']);
        //Permission::create(['name' => 'delete-categoria']);

        //FACTURA
        Permission::create(['name' => 'crear-factura']);
        Permission::create(['name' => 'ver-facturas']);
        Permission::create(['name' => 'editar-factura']);
        //Permission::create(['name' => 'delete-factura']);

        //REGISTRO PAGOS
        Permission::create(['name' => 'crear-registropago']);
        Permission::create(['name' => 'ver-registropagos']);
        Permission::create(['name' => 'editar-registropago']);
        //Permission::create(['name' => 'delete-registropago']);

        //CLASIFICACIO PAGO
        Permission::create(['name' => 'crear-claficacionpago']);
        Permission::create(['name' => 'ver-claficacionpagos']);
        Permission::create(['name' => 'editar-claficacionpago']);
        //Permission::create(['name' => 'delete-claficacionpago']);

        //SEDE
        Permission::create(['name' => 'crear-sede']);
        Permission::create(['name' => 'ver-sedes']);
        Permission::create(['name' => 'editar-sede']);
        //Permission::create(['name' => 'delete-sede']);

        //AUTORIZACION
        Permission::create(['name' => 'crear-autorizacion']);
        Permission::create(['name' => 'ver-autorizaciones']);
        Permission::create(['name' => 'editar-autorizacion']);
        //Permission::create(['name' => 'delete-autorizacion']);

        //TIPO AUTORIZACION
        Permission::create(['name' => 'crear-tipoautorizacion']);
        Permission::create(['name' => 'ver-tipoautorizaciones']);
        Permission::create(['name' => 'editar-tipoautorizacion']);
        //Permission::create(['name' => 'delete-tipoautorizacion']);

        /*Permission::create(['name' => 'create permission']);
        Permission::create(['name' => 'read permissions']);
        Permission::create(['name' => 'update permission']);
        Permission::create(['name' => 'delete permission']);*/

        // create roles and assign created permissions

        $role = Role::create(['name' => 'editor']);
        $role->givePermissionTo('ver-users');
        $role->givePermissionTo('editar-user');

        $role = Role::create(['name' => 'moderador']);
        $role->givePermissionTo('crear-user');
        $role->givePermissionTo('ver-users');
        $role->givePermissionTo('editar-user');
        //$role->givePermissionTo('delete-user');

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());
    }
}
