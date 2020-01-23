<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Vendedor;
use Illuminate\Support\Facades\DB;
use App\User;

class VendedorController extends Controller
{
    public function index(Request $request){

        //SOLO SE PERMITEN PETICIONES AJAX A NUESTRO CONTROLADOR,
        //DE LO CONTRARIO REDIRIGE A LA RUTA RAIZ
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $vendedor = Vendedor::orderBy('id', 'asc')->paginate(2);
        }
        else{
            $vendedor = Vendedor::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(2);
        }


        return [
            'pagination' => [
                'total'        => $vendedor->total(),
                'current_page' => $vendedor->currentPage(),
                'per_page'     => $vendedor->perPage(),
                'last_page'    => $vendedor->lastPage(),
                'from'         => $vendedor->firstItem(),
                'to'           => $vendedor->lastItem(),
            ],
            'vendedor' => $vendedor
        ];

    }

    public function create(Request $request){
        if (!$request->ajax()) return redirect('/');
    }

    public function store(Request $request){
        if (!$request->ajax()) return redirect('/');

        //validacion formulario
        $validator = Validator::make($request->all(), [

            'fvcnombre' => 'required|max:100|min:10|unique:tblvendedor',
            'estado' => 'required|max:12',
            'usuario_sesion' => 'required'

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


        try{
            DB::beginTransaction();

            $vendedor = new Vendedor();
            $vendedor->fvcnombre     = trim($request->fvcnombre);
            $vendedor->fvcestado     = trim($request->estado);
            $vendedor->fvcusuario_id = $request->usuario_sesion;



            $vendedor->save();

            DB::commit();
            return [
                'id' => $vendedor->id
            ];

        } catch (Exception $e){
            DB::rollBack();
        }

        $response = array(
            'status' => 'success',
            'response_code' => 200
        );

        return $response;
    }



    public function edit(Request $request, $id){
        if (!$request->ajax()) return redirect('/');

        $vendedor        = Vendedor::find($id);


        return [
            'vendedor'  => $vendedor,
        ];

    }

    public function update(Request $request){
        if (!$request->ajax()) return redirect('/');

        //validacion formulario
        $validator = Validator::make($request->all(), [

            'fvcnombre' => 'required|max:100|min:4|unique:tblvendedor',
            'estado' => 'required|max:12',
            'usuario_sesion' => 'required'

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


        $vendedor =  Vendedor::find($request->id);
        $vendedor->fvcnombre     = trim($request->fvcnombre);
        $vendedor->fvcestado     = trim($request->estado);
        $vendedor->fvcusuario_id = $request->usuario_sesion;

        $vendedor->save();


        $response = array(
            'status' => 'success',
            'response_code' => 200
        );

        return $response;
    }
}
