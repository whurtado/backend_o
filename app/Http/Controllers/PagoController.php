<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Vendedor;
use Illuminate\Support\Facades\DB;
use App\Pago;

class PagoController extends Controller
{
    public function index(Request $request){

        $pago = DB::table('tblpago');

        if ($request->nombre != '' && $request->nombre != 'null' ) {
            $pago->where('fvcnombre', 'like', '%'.$request->nombre. '%');
        }

        if ($request->documento != '' && $request->documento != 'null') {

            $pago->where('fvcdocumento', '=', $request->documento);
        }


        $pago = $pago->get();


        return [
            'pago' => $pago
        ];


    }

    public function create(Request $request){
    }

    public function store(Request $request){

        //return $request;

        //validacion formulario
        $validator = Validator::make($request->all(), [

            'fvcnombre' => 'required|max:100|min:4',
            'observacion' => 'required|min:5',
            'valor' => 'required|min:3',
            'direccion' => 'required|min:4',
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

            $pago = new Pago();
            $pago->fvcnombre      = trim($request->fvcnombre);
            $pago->fvcdocumento   = trim($request->documento);
            $pago->fvctelefono    = trim($request->telefono);
            $pago->fvcdireccion   = trim($request->direccion);
            $pago->flngvalor      = trim($request->valor);
            $pago->fvcobservacion = trim($request->observacion);
            if($request->ahh != ''){
                $pago->fvcahh         = trim($request->ahh);
            }
            $pago->fvcfactura     = trim($request->factura);
            $pago->fdtfecha       = date("Y-m-d");
            $pago->fintfactura    = 1;
            $pago->fvcusuario_id  = $request->usuario_sesion;
            $pago->fvcclasificacionpago_id    = 1;
            $pago->fvcpagofactura_id          = 1;
            $pago->fvcautorizacion_id         = 1;
            $pago->fvcsede_id                 = 1;
            $pago->fvcsede_creacion    = $request->sede_creacion;

            $pago->save();

            DB::commit();
            return [
                'id' => $pago->id
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

        $pago        = Pago::find($id);

        return [
            'pago'  => $pago,
        ];

    }

    public function update(Request $request){

        //validacion formulario
        $validator = Validator::make($request->all(), [

            'fvcnombre' => 'required|max:100|min:4',
            'observacion' => 'required|min:5',
            'valor' => 'required|min:3',
            'direccion' => 'required|min:4',
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


        $pago =  Pago::find($request->id);
        $pago->fvcnombre      = trim($request->fvcnombre);
        $pago->fvcdocumento   = trim($request->documento);
        $pago->fvctelefono    = trim($request->telefono);
        $pago->fvcdireccion   = trim($request->direccion);
        $pago->flngvalor      = $request->valor;
        $pago->fvcobservacion = trim($request->observacion);
        $pago->fvcahh         = trim($request->ahh);
        $pago->fvcfactura     = trim($request->factura);
        $pago->fdtfecha       = date("Y-m-d");
        $pago->fintfactura    = 1;
        $pago->fvcusuario_id  = $request->usuario_sesion;

        $pago->fvcclasificacionpago_id    = 1;
        $pago->fvcpagofactura_id          = 1;
        $pago->fvcautorizacion_id         = 1;
        $pago->fvcsede_id                 = 1;
        $pago->fvcsede_creacion    = $request->sede_creacion;



        $pago->save();


        $response = array(
            'status' => 'success',
            'response_code' => 200
        );

        return $response;
    }
}
