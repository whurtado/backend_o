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

        $vendedor = DB::table('tblvendedor');

        if ($request->fvcnombre != '' && $request->fvcnombre != 'null' ) {
            $vendedor->where('fvcnombre', 'like', '%'.$request->fvcnombre. '%');
        }

        if ($request->estado != '' && $request->estado != 'null') {

            $vendedor->where('fvcestado', '=', $request->estado);
        }

       /* if ($request->sede_creacion != '' && $request->sede_creacion != 'null') {

            $vendedor->where('fvcsede_creacion', '=', $request->sede_creacion);
        }*/

        $vendedor = $vendedor->get();



        return [
            'vendedor' => $vendedor
        ];

    }

    public function create(Request $request){
    }

    public function store(Request $request){

        //validacion formulario
        $validator = Validator::make($request->all(), [

            'fvcnombre' => 'required|max:100|min:10|unique:tblvendedor',
            'estado' => 'required|max:12',
            'usuario_sesion' => 'required'

        ]);


        if ($validator->fails()) {

                return response()->json(array(
                    'success' => false,
                    'message' => 'El formulario posee valores incorrectos!',
                    'errors' => $validator->getMessageBag()->toArray()
                ), 422);


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
            $vendedor->fvcsede_creacion = $request->sede_creacion;

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

        $vendedor        = Vendedor::find($id);


        return [
            'vendedor'  => $vendedor,
        ];

    }

    public function update(Request $request){

        //validacion formulario
        $validator = Validator::make($request->all(), [

            'fvcnombre' => 'required|max:100|min:4|unique:tblvendedor,fvcnombre,'.$request->id,
            'estado' => 'required|max:12',
            'usuario_sesion' => 'required'

        ]);



        if ($validator->fails()) {


                return response()->json(array(
                    'success' => false,
                    'message' => 'There are incorect values in the form!',
                    'errors' => $validator->getMessageBag()->toArray()
                ), 422);


            $this->throwValidationException(
                $request, $validator
            );
        }




        $vendedor =  Vendedor::find($request->id);
        $vendedor->fvcnombre     = trim($request->fvcnombre);
        $vendedor->fvcestado     = trim($request->estado);
        $vendedor->fvcusuario_id = $request->usuario_sesion;
        $vendedor->fvcsede_creacion = $request->sede_creacion;

        $vendedor->save();


        $response = array(
            'status' => 'success',
            'response_code' => 200
        );

        return $response;
    }
}
