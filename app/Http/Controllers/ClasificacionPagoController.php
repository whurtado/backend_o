<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\clasificacionPago;
use Illuminate\Support\Facades\DB;
use App\User;

class ClasificacionPagoController extends Controller
{
    public function index(Request $request){

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $clasificacionPago = clasificacionPago::orderBy('id', 'asc')->paginate(2);
        }
        else{
            $clasificacionPago = clasificacionPago::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(2);
        }


        return [
            'pagination' => [
                'total'        => $clasificacionPago->total(),
                'current_page' => $clasificacionPago->currentPage(),
                'per_page'     => $clasificacionPago->perPage(),
                'last_page'    => $clasificacionPago->lastPage(),
                'from'         => $clasificacionPago->firstItem(),
                'to'           => $clasificacionPago->lastItem(),
            ],
            'clasificacionPago' => $clasificacionPago
        ];

    }

    public function create(Request $request){
    }

    public function store(Request $request){

        //validacion formulario
        $validator = Validator::make($request->all(), [

            'fvcnombre' => 'required|unique:tblclasificacionpago',
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

            $clasificacionPago = new clasificacionPago();
            $clasificacionPago->fvcnombre      = trim($request->fvcnombre);
            $clasificacionPago->fvcdescripcion = trim($request->fvcdescripcion);
            $clasificacionPago->fvcestado      = trim($request->estado);
            $clasificacionPago->fvcusuario_id  = $request->usuario_sesion;

            $clasificacionPago->save();

            DB::commit();
            return [
                'id' => $clasificacionPago->id
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

        $clasificacionPago        = clasificacionPago::find($id);


        return [
            'clasificacionPago'  => $clasificacionPago,
        ];

    }

    public function update(Request $request){

        //validacion formulario
        $validator = Validator::make($request->all(), [

            'fvcnombre' => 'required|max:100|min:2|unique:tblclasificacionpago',
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


        $clasificacionPago =  clasificacionPago::find($request->id);
        $clasificacionPago->fvcnombre     = trim($request->fvcnombre);
        $clasificacionPago->fvcdescripcion = trim($request->fvcdescripcion);
        $clasificacionPago->fvcestado     = trim($request->estado);
        $clasificacionPago->fvcusuario_id = $request->usuario_sesion;

        $clasificacionPago->save();


        $response = array(
            'status' => 'success',
            'response_code' => 200
        );

        return $response;
    }
}
