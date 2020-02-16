<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\autorizacion;
use App\tipoAutorizacion;
use Illuminate\Support\Facades\DB;
use App\User;

class AutorizacionController extends Controller
{

    public function index(Request $request){

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $autorizacion = autorizacion::orderBy('id', 'asc')->paginate(2);
        }
        else{
            $autorizacion = autorizacion::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(2);
        }


        return [
            'pagination' => [
                'total'        => $autorizacion->total(),
                'current_page' => $autorizacion->currentPage(),
                'per_page'     => $autorizacion->perPage(),
                'last_page'    => $autorizacion->lastPage(),
                'from'         => $autorizacion->firstItem(),
                'to'           => $autorizacion->lastItem(),
            ],
            'autorizacion' => $autorizacion
        ];

    }

    public function create(Request $request){
    }

    public function store(Request $request){

        //return $request;

        //validacion formulario
        $validator = Validator::make($request->all(), [

            'fvcdescripcion' => 'required',
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

            $autorizacion = new autorizacion();
            $autorizacion->fvcdescripcion         = trim($request->fvcdescripcion);
            $autorizacion->fvctipoautorizacion_id = trim($request->fvctipoautorizacion_id);
            $autorizacion->fvcfechaAutorizacion   = trim($request->fvcfechaAutorizacion);
            $autorizacion->fvcestado              = trim($request->estado);
            $autorizacion->fvcusuario_id          = $request->usuario_sesion;

            $autorizacion->save();

            DB::commit();
            return [
                'id' => $autorizacion->id
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

        $autorizacion     = autorizacion::find($id)->load('tipoAutorizacion');
        $tipoAutorizacion = DB::table('tbltipoautorizacion')
            ->select('tbltipoautorizacion.*')
            ->get();



        return [
            'autorizacion'     => $autorizacion,
            'tipoAutorizacion' => $tipoAutorizacion

        ];

    }

    public function update(Request $request){

        //validacion formulario
        $validator = Validator::make($request->all(), [

            'fvcdescripcion' => 'required',
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


        $autorizacion =  autorizacion::find($request->id);
        $autorizacion->fvcdescripcion         = trim($request->fvcdescripcion);
        $autorizacion->fvctipoautorizacion_id = trim($request->fvctipoautorizacion_id);
        $autorizacion->fvcfechaAutorizacion   = trim($request->fvcfechaAutorizacion);
        $autorizacion->fvcestado              = trim($request->estado);
        $autorizacion->fvcusuario_id          = $request->usuario_sesion;

        $autorizacion->save();


        $response = array(
            'status' => 'success',
            'response_code' => 200
        );

        return $response;
    }
}
