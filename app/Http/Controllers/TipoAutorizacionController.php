<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\tipoAutorizacion;
use Illuminate\Support\Facades\DB;
use App\User;

class TipoAutorizacionController extends Controller
{
    public function index(Request $request){

        $tipoAutorizacion = DB::table('tbltipoautorizacion');

        if ($request->fvcnombre != '' && $request->fvcnombre != 'null' ) {
            $tipoAutorizacion->where('fvcnombre', 'like', '%'.$request->fvcnombre. '%');
        }

        if ($request->estado != '' && $request->estado != 'null') {

            $tipoAutorizacion->where('fvcestado', '=', $request->estado);
        }

        if ($request->sede_creacion != '' && $request->sede_creacion != 'null') {

            $tipoAutorizacion->where('fvcsede_creacion', '=', $request->sede_creacion);
        }

        $tipoAutorizacion = $tipoAutorizacion->get();


        return [
            'tipoAutorizacion' => $tipoAutorizacion
        ];

    }


    public function mostrarTipoAutorizacion(Request $request){

        $tipoAutorizacion = tipoAutorizacion::orderBy('id', 'asc')->get();

        return [
            'tipoAutorizacion' => $tipoAutorizacion
        ];

    }

    public function create(Request $request){
    }

    public function store(Request $request){

        //validacion formulario
        $validator = Validator::make($request->all(), [

            'fvcnombre' => 'required',
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

            $tipoAutorizacion = new tipoAutorizacion();
            $tipoAutorizacion->fvcnombre         = trim($request->fvcnombre);
            $tipoAutorizacion->fvcestado         = trim($request->estado);
            $tipoAutorizacion->fvcusuario_id     = $request->usuario_sesion;
            $tipoAutorizacion->fvcsede_creacion  = $request->sede_creacion;



            $tipoAutorizacion->save();

            DB::commit();
            return [
                'id' => $tipoAutorizacion->id
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

        $tipoAutorizacion        = tipoAutorizacion::find($id);

        return [
            'tipoAutorizacion'  => $tipoAutorizacion,
        ];

    }

    public function update(Request $request){

        //validacion formulario
        $validator = Validator::make($request->all(), [

            'fvcnombre' => 'required',
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


        $tipoAutorizacion                    =  tipoAutorizacion::find($request->id);
        $tipoAutorizacion->fvcnombre         = trim($request->fvcnombre);
        $tipoAutorizacion->fvcestado         = trim($request->estado);
        $tipoAutorizacion->fvcusuario_id     = $request->usuario_sesion;
        $tipoAutorizacion->fvcsede_creacion  = $request->sede_creacion;

        $tipoAutorizacion->save();


        $response = array(
            'status' => 'success',
            'response_code' => 200
        );

        return $response;
    }
}
