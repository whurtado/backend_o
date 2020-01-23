<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RolesController extends Controller
{
    public function index(Request $request){

        $roles= Role::all();
        return $roles;
    }

    public function create(Request $request){

        $permissions = Permission::all();

        return [
            'permissions' => $permissions
        ];

    }


    public function store(Request $request){


        //return $request;
        //validacion formulario
        $data = $request->validate([
            'name' => 'required|unique:roles',
            'guard_name' => 'required'

        ]);

        $role = Role::create($data);

        //si eligen permiso, se asigna
        if($request->has('permissionGuardar')){
            $request->permissionGuardar = explode(",", $request->permissionGuardar);

            $role->givePermissionTo($request->permissionGuardar);
        }

        $response = array(
            'status' => 'success',
            'response_code' => 200
        );

        return $response;

    }

    public function edit(Request $request, $id){

        $roles       = Role::with('permissions')->find($id);
        $permissions = Permission::all();

        return [
            'roles' => $roles,
            'permissions' => $permissions

        ];

    }

    public function update(Request $request){

        //validacion formulario
        $data = $request->validate([
            'name' => 'required|unique:roles,name,'. $request->id,
            'guard_name' => 'required'

        ]);

        $role =  Role::find($request->id);

        $role->update($data);

        //borra los permisos del que tiene el rol
        $role->permissions()->detach();

        //si eligen permiso, se asigna
        if($request->has('permissionActualizar')){
            $request->permissionActualizar = explode(",", $request->permissionActualizar);

            $role->givePermissionTo($request->permissionActualizar);
        }

        $response = array(
            'status' => 'success',
            'response_code' => 200
        );

        return $response;

    }
}
