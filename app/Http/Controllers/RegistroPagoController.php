<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\registroPago;
use Illuminate\Support\Facades\DB;
use App\User;

class RegistroPagoController extends Controller
{
    public function index(Request $request){


        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $registroPago = registroPago::orderBy('id', 'asc')->paginate(2);
        }
        else{
            $registroPago = registroPago::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(2);
        }


        return [
            'pagination' => [
                'total'        => $registroPago->total(),
                'current_page' => $registroPago->currentPage(),
                'per_page'     => $registroPago->perPage(),
                'last_page'    => $registroPago->lastPage(),
                'from'         => $registroPago->firstItem(),
                'to'           => $registroPago->lastItem(),
            ],
            'registroPago' => $registroPago
        ];

    }

    public function create(Request $request){
    }

    public function store(Request $request){

        //validacion formulario
        $validator = Validator::make($request->all(), [

            'fvcfactura' => 'required',
            'fvcestado' => 'required|max:12',
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

            $registroPago = new registroPago();
            $registroPago->fvcfactura          = trim($request->fvcfactura);
            $registroPago->flngvalorFactura    = trim($request->flngvalorFactura);
            $registroPago->fvcfechaPagoFactura = trim($request->fvcfechaPagoFactura);
            $registroPago->flngvalorDeduccion  = trim($request->flngvalorDeduccion);
            $registroPago->flngvalorPagar      = trim($request->flngvalorPagar);
            $registroPago->fvcobservacion      = trim($request->fvcobservacion);
            $registroPago->fvcestado           = trim($request->fvcestado);
            $registroPago->fvcusuario_id       = $request->usuario_sesion;
            $registroPago->fvcsede_creacion    = $request->sede_creacion;


            $registroPago->save();

            DB::commit();
            return [
                'id' => $registroPago->id
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

        $registroPago     = registroPago::find($id);

        return [
            'registroPago'     => $registroPago,
        ];

    }

    public function update(Request $request){

        //validacion formulario
        $validator = Validator::make($request->all(), [

            'fvcfactura' => 'required',
            'fvcestado' => 'required|max:12',
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


        $registroPago =  registroPago::find($request->id);
        $registroPago->fvcfactura          = trim($request->fvcfactura);
        $registroPago->flngvalorFactura    = trim($request->flngvalorFactura);
        $registroPago->fvcfechaPagoFactura = trim($request->fvcfechaPagoFactura);
        $registroPago->flngvalorDeduccion  = trim($request->flngvalorDeduccion);
        $registroPago->flngvalorPagar      = trim($request->flngvalorPagar);
        $registroPago->fvcobservacion      = trim($request->fvcobservacion);
        $registroPago->fvcestado           = trim($request->fvcestado);
        $registroPago->fvcusuario_id       = $request->usuario_sesion;
        $registroPago->fvcsede_creacion    = $request->sede_creacion;

        $registroPago->save();


        $response = array(
            'status' => 'success',
            'response_code' => 200
        );

        return $response;
    }
}
