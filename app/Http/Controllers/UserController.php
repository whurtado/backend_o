<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;

use Illuminate\Validation\Rule;
use Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Illuminate\Support\Facades\DB;

use App\User;
use App\sede;


class UserController extends Controller
{
    public function index(Request $request){


        $user = DB::table('users');

        if ($request->nombre != '' && $request->nombre != 'null' ) {
            $user->where('name', 'like', '%'.$request->nombre. '%');
        }

        if ($request->email != '' && $request->email != 'null' ) {
            $user->where('email', 'like', '%'.$request->email. '%');
        }

        if ($request->sede != '' && $request->sede != 'null' ) {
            $user->where('fvcsede_id', 'like', '%'.$request->sede. '%');
        }

        $user = $user->get();


        return [
            'user' => $user
        ];


      /*  $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $user = User::with('roles')->orderBy('id', 'asc')->paginate(7);


        }
        else{
            $user = User::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(2);
        }


        return [
            'pagination' => [
                'total'        => $user->total(),
                'current_page' => $user->currentPage(),
                'per_page'     => $user->perPage(),
                'last_page'    => $user->lastPage(),
                'from'         => $user->firstItem(),
                'to'           => $user->lastItem(),
            ],
            'user' => $user
        ];*/

    }

    public function create(Request $request){

        $roles       = Role::with('permissions')->get();
        $permissions = Permission::all();

        return [
            'roles' => $roles,
            'permissions' => $permissions

        ];

    }

    public function store(Request $request){

       // return $request;



        //validacion formulario
        $validator = Validator::make($request->all(), [

            'name' => 'required|max:30|min:3',
            'email' => 'required|email|max:50|unique:users',
            'password' => 'required|min:6',

        ]);

        if ($validator->fails()) {

            if($request->ajax())
            {
                return response()->json(array(
                    'success' => false,
                    'message' => 'There are incorect values in the form!',
                    'errors' => $validator->getMessageBag()->toArray()
                ), 422);
            }

            $this->throwValidationException(
                $request, $validator
            );
        }



        $usuario = new User();
        $usuario->name              = trim($request->name);
        $usuario->email             = trim($request->email);
        $usuario->password          = bcrypt($request->password);
        $usuario->fvcsede_id        = $request->sede;
        $usuario->fvcsede_creacion  = $request->sede_creacion;

        $usuario->save();


        //asignamos los roles
        $usuario->assignRole($request->rolesGuardar);

        //asignamos los permisos
        $usuario->givePermissionTo($request->permissionGuardar);


        $response = array(
            'status' => 'success',
            'response_code' => 200
        );

        return $response;


    }

    public function edit(Request $request, $id){

        $roles       = Role::with('permissions')->get();
        $permissions = Permission::all();
        $user        = User::with('roles','permissions')->find($id);
        //$sede        = sede::all();


        return [
            'user'  => $user,
            'roles' => $roles,
            'permissions' => $permissions,
            /*'sede' => $sede*/

        ];

    }

    public function update(Request $request){


        //validacion formulario
        $validator = Validator::make($request->all(), [

            'name' => 'required|max:30|min:3',
            'email' => 'required|email|unique:users,email,'.$request->id,
            //'sede' => 'required',

        ]);


        if ($validator->fails()) {

            if($request->ajax())
            {
                return response()->json(array(
                    'success' => false,
                    'message' => 'There are incorect values in the form!',
                    'errors' => $validator->getMessageBag()->toArray()
                ), 422);
            }

            $this->throwValidationException(
                $request, $validator
            );
        }

        $user =  User::find($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        if($request->password !='' && $request->password !='null'){
            $user->password = bcrypt($request->password);
        }
        $user->fvcsede_id = $request->sede;
        $user->fvcsede_creacion  = $request->sede_creacion;


        $user->save();

        $response = array(
            'status' => 'success',
            'response_code' => 200
        );

        return $response;
    }

    public function mostrarSedesDelUsuario(Request $request){

        $sedesUsuario = DB::table('users')
            ->select("fvcsede_id")
            ->where('email', '=', $request->email)
            ->get();



        return [
            'sedes'  => $sedesUsuario,

        ];

    }

    public function traerSedes(Request $request){

            $sede = sede::orderBy('id', 'asc')->get();

        return [
            'sede' => $sede
        ];

    }
}
