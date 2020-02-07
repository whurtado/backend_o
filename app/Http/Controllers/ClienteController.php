<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Cliente;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\User;
use App\DetalleClienteReferencia;
use App\ClienteEstado;
use App\ClienteNovedad;


class ClienteController extends Controller
{

    //RUTA INDEX
    public function index(Request $request){

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $cliente = Cliente::orderBy('id', 'asc')->paginate(7);
        }
        else{
            $cliente = Cliente::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(7);
        }


        return [
            'pagination' => [
                'total'        => $cliente->total(),
                'current_page' => $cliente->currentPage(),
                'per_page'     => $cliente->perPage(),
                'last_page'    => $cliente->lastPage(),
                'from'         => $cliente->firstItem(),
                'to'           => $cliente->lastItem(),
            ],
            'cliente' => $cliente
        ];

    }


    //RUTA STORE
    public function store(Request $request){

        //validacion formulario
        $validator = Validator::make($request->all(), [

            /*'primernombre' => 'required|max:30|min:3',
            'primerapellido' => 'required|max:30|min:3',
            'segundoapellido' => 'required|max:30|min:3',
            'fvcdocumento' => 'required|max:12|unique:tblcliente',
            'direccion' => 'required|min:6',
            'telefonoCasa' => 'required|max:10|min:3',
            'telefonoOficina' => 'required|max:12',
            'celular' => 'required|min:10',
            'direccionTrabajo' => 'required|max:100|min:3',
            'email' => 'required|email|max:50|unique:tblcliente',
            'fechaNacimiento' => 'required',
            'observacion' => 'required',
            'usuario_sesion' => 'required'*/

        ]);

//return $validator->fails();

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

            $cliente = new Cliente();
            $cliente->fvcprimernombre     = trim($request->primernombre);
            if (trim($request->segundonombre) != ''){
                $cliente->fvcsegundonombre    = trim($request->segundonombre);
            }
            $cliente->fvcprimerapellido   = trim($request->primerapellido);
            $cliente->fvcsegundoapellido  = trim($request->segundoapellido);

            $cliente->fvcdocumento        = trim($request->fvcdocumento);
            $cliente->fvcdireccion        = trim($request->direccion);
            $cliente->fvccelular          = trim($request->celular);
            $cliente->fvctelefono         = trim($request->telefonoCasa);
            $cliente->fvctelefonoo        = trim($request->telefonoOficina);
            $cliente->fvcobservacion      = trim($request->observacion);
            $cliente->fvcdirecciontrabajo = trim($request->direccionTrabajo);
            $cliente->fvcfechacumpleano   = $request->fechaNacimiento;
            $cliente->fvcestado           = trim('PENDIENTE');
            $cliente->email               = trim($request->email);
            $cliente->clienteestado_id    = 1;
            $cliente->fvcusuario_id       = $request->usuario_sesion;

            $cliente->save();

            $detalles =  json_decode($request->data, true);
            //Array de detalles
            //Recorro todos los elementos



            foreach($detalles as $ep=>$det)
            {

                $detalle = new DetalleClienteReferencia();
                $detalle->fvccliente_id = $cliente->id;
                $detalle->dfvid = $ep + 1;
                $detalle->dfvnombre_referencia = $det['nombre_referencia'];
                $detalle->dfvtelefono_referencia = $det['telefono_referencia'];
                $detalle->fvcusuario_id  = $request->usuario_sesion;

                $detalle->save();
            }


            DB::commit();
            return [
                'id' => $cliente->id
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

    public function create(Request $request){
    }


    //RUTA EDIT
    public function edit(Request $request, $id){

        $cliente        = Cliente::find($id);
        $detallecliente =  DetalleClienteReferencia::where('fvccliente_id', $cliente->id)
            ->get();


        return [
            'cliente'  => $cliente,
            'detallecliente' => $detallecliente
        ];

    }

    //RUTA UPDATE
    public function update(Request $request)
    {

        //validacion formulario
        $validator = Validator::make($request->all(), [

            /*'primernombre' => 'required|max:30|min:3',
            'primerapellido' => 'required|max:30|min:3',
            'segundoapellido' => 'required|max:30|min:3',
            'fvcdocumento' => 'required|max:12',
            'fvcdocumento' => [Rule::unique('tblcliente')->ignore($request->id)],
            'direccion' => 'required|min:6',
            'telefonoCasa' => 'required|max:10|min:3',
            'telefonoOficina' => 'required|max:12',
            'celular' => 'required|min:10',
            'direccionTrabajo' => 'required|max:100|min:3',
            'email' => 'required|email|unique:tblcliente,email,' . $request->id,
            'fechaNacimiento' => 'required',
            'observacion' => 'required',
            'usuario_sesion' => 'required'*/

        ]);


        if ($validator->fails()) {

            if ($request->ajax()) {
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

        try {
            DB::beginTransaction();

            $cliente = Cliente::find($request->id);
            $cliente->fvcprimernombre     = trim($request->primernombre);
            if (trim($request->segundonombre) != ''){
                $cliente->fvcsegundonombre    = trim($request->segundonombre);
            }
            $cliente->fvcprimerapellido   = trim($request->primerapellido);
            $cliente->fvcsegundoapellido  = trim($request->segundoapellido);

            $cliente->fvcdocumento = trim($request->fvcdocumento);
            $cliente->fvcdireccion = trim($request->direccion);
            $cliente->fvccelular = trim($request->celular);
            $cliente->fvctelefono = trim($request->telefonoCasa);
            $cliente->fvctelefonoo = trim($request->telefonoOficina);
            $cliente->fvcobservacion = trim($request->observacion);
            $cliente->fvcdirecciontrabajo = trim($request->direccionTrabajo);
            $cliente->fvcfechacumpleano = $request->fechaNacimiento;
            $cliente->fvcestado = trim('PENDIENTE');
            $cliente->email = trim($request->email);
            $cliente->clienteestado_id = 1;
            $cliente->fvcusuario_id  = $request->usuario_sesion;

            $cliente->save();


            //ELIMINAR DATOS DEL DETALLE Y DESPUES GRABAR DENUEVO LA INFORMACION
            DB::table('tbldetalleclientereferencias')->where('fvccliente_id', '=', $request->id)->delete();



            $detalles =  json_decode($request->data, true);//Array de detalles
            //Recorro todos los elementos

            foreach ($detalles as $ep => $det) {
                $detalle = new DetalleClienteReferencia();
                $detalle->fvccliente_id = $cliente->id;
                $detalle->dfvid = $ep + 1;
                $detalle->dfvnombre_referencia = $det['dfvnombre_referencia'];
                $detalle->dfvtelefono_referencia = $det['dfvtelefono_referencia'];
                $detalle->fvcusuario_id  = $request->usuario_sesion;

                $detalle->save();
            }

            DB::commit();
            return [
                'id' => $cliente->id
            ];

        } catch (Exception $e) {
            DB::rollBack();
        }

        $response = array(
            'status' => 'success',
            'response_code' => 200
        );

        return $response;
    }

    //metodo para mostrar el historico del cambio de estados del cliente
    public function cargarEstadoCliente(Request $request, $id){



        $estado = ClienteEstado::join('tblcliente', 'tblcliente.id', '=', 'tblclienteestado.fvccliente_id')
            ->join('users', 'users.id', '=', 'tblclienteestado.fvcusuario_id')
            ->select(
                'tblclienteestado.fdtestado',
                'tblclienteestado.fdtobservacion',
                'tblclienteestado.fdtfecha',
                'users.name')
            ->where('tblclienteestado.fvccliente_id', '=', $id)
            ->orderBy('tblclienteestado.id', 'desc')
            ->get();


        return [
            'estado' => $estado
        ];
    }


    public function cambioEstado(Request $request){


        //validacion formulario
        $validator = Validator::make($request->all(), [

            'fvccliente_id' => 'required',
            'fdtestado' => 'required',
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

        $cliente = new ClienteEstado();
        $cliente->fvccliente_id  = trim($request->fvccliente_id);
        $cliente->fdtestado      = trim($request->fdtestado);
        if($request->fvcdescripcion !=''){
            $cliente->fdtobservacion = trim($request->fvcdescripcion);
        }
        $cliente->fdtfecha       = date("Y-m-d");
        $cliente->fvcusuario_id  = $request->fvcusuario_id;
        $cliente->save();

        $response = array(
            'status' => 'success',
            'response_code' => 200
        );

        return $response;

    }

    //ASIGNACION NOVEDAD CLIENTE
    public function cargarNovedadCliente(Request $request, $id){


        $estado = ClienteNovedad::join('tblcliente', 'tblcliente.id', '=', 'tblclientenovedad.fvccliente_id')
            ->join('users', 'users.id', '=', 'tblclientenovedad.fvcusuario_id')
            ->select(
                'tblclientenovedad.fdtdescripcion',
                'tblclientenovedad.fdtfecha',
                'users.name')
            ->where('tblclientenovedad.fvccliente_id', '=', $id)
            ->orderBy('tblclientenovedad.id', 'desc')
            ->get();

        return [
            'estado' => $estado
        ];
    }


    public function asignacionNovedad(Request $request){


        //validacion formulario
        $validator = Validator::make($request->all(), [

            'fvccliente_id' => 'required',
            'fdtdescripcion' => 'required',
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

        $cliente = new ClienteNovedad();
        $cliente->fvccliente_id  = trim($request->fvccliente_id);
        $cliente->fdtdescripcion = trim($request->fdtdescripcion);
        $cliente->fdtfecha       = date("Y-m-d");
        $cliente->fvcusuario_id  = $request->fvcusuario_id;
        $cliente->save();

        $response = array(
            'status' => 'success',
            'response_code' => 200
        );

        return $response;

    }

    public function errorLoginJao(){

        die("ingrese ''''''");

        return redirect('/');
    }
}
