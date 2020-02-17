<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\factura;
use App\Articulo;
use Validator;
use Illuminate\Support\Facades\DB;
use App\PagoFactura;
use App\DetalleFactura;



class facturaController extends Controller
{
    //RUTA INDEX
    public function index(Request $request){

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $factura = factura::orderBy('id', 'asc')->paginate(7);
        }
        else{
            $factura = factura::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(7);
        }


        return [
            'pagination' => [
                'total'        => $factura->total(),
                'current_page' => $factura->currentPage(),
                'per_page'     => $factura->perPage(),
                'last_page'    => $factura->lastPage(),
                'from'         => $factura->firstItem(),
                'to'           => $factura->lastItem(),
            ],
            'factura' => $factura
        ];

    }

    public function create(Request $request){
    }

    public function store(Request $request){

       // return $request;

        //validacion formulario
        $validator = Validator::make($request->all(), [

            /*'nombre' => 'required|max:30|min:3',*/
            'genero' => 'required|max:12',
            'sede' => 'required|min:3',
            'total' => 'required|max:10|min:3',
            'fecha_entrega' => 'required|max:12',
            'fecha_devolucion' => 'required|min:10',
            'fecha_prueba' => 'required|max:100|min:3',
           'codigo' => 'required',
            'cliente' => 'required',
            'vendedor' => 'required',
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


        try{
            DB::beginTransaction();

            //$table->unsignedInteger('fvcpagodeposito_id');
            $fechaentrega = $request->fecha_entrega." ".$request->hora_entrega;
            $fechaprueba  = $request->fecha_prueba." ".$request->hora_prueba;


            $factura = new factura();
            $factura->fvcnombre             = trim($request->usuario_referido);
            $factura->fvcgenero             = trim($request->genero);
            $factura->fvcsede               = trim($request->sede);
            $factura->flngvalor             = $request->total;
            $factura->flngabono             = $request->abono;
            $factura->fdtfechaentrega       = $fechaentrega;
            $factura->fdtfecharetorno       = trim($request->fecha_devolucion);
            $factura->fvcobservacion        = $request->observacion;
            $factura->fdtfecha              = date("Y-m-d");
            $factura->fvcestado             = 'SI';//trim($request->estado);
            $factura->fvctraje              = trim($request->traje);
            $factura->fdtfechaprueba        = $fechaprueba;
            $factura->fvccodigo             = trim($request->codigo);
            $factura->fvcconfesion          = trim($request->confesion);
            $factura->fvcformapago          = trim($request->formapago);
            $factura->fvcnotarjeta          = trim($request->no_tarjeta);
            $factura->fvcdescripciontarjeta = trim($request->descripcion_tarjeta);
            $factura->fvcficha              = trim($request->ficha);
            $factura->fvcbloqueo            = $request->bloqueo;
            $factura->fvcmotivobloqueo      = trim($request->motivo_bloqueo);
            $factura->flngdeposito          = $request->deposito;
            $factura->fvcusuario_id         = $request->usuario_sesion;
            $factura->fvccliente_id         = trim($request->cliente);
            $factura->fvcvendedor_id        = trim($request->vendedor);
            $factura->fvcpagodeposito_id    = 1;
            $factura->fvcsede_id            = 1;


            $factura->save();



            /* REALIZAR PAGO DE ABONO*/

            $detalleAbono =  json_decode($request->dataAbonos, true);//Array de detalles

            //Recorro todos los elementos

            foreach($detalleAbono as $ep=>$det)
            {

                $pagofactura = new Pagofactura();
                $pagofactura->fvcfactura_id          = $factura->id;
                $pagofactura->fvctipoAbono           = $det['tipoAbono'];
                $pagofactura->flngvalor              = $det['valor'];
                $pagofactura->fdtfecha               = date("Y-m-d");
                $pagofactura->fvcformapago           = trim($det['formaPago']);
                $pagofactura->fvcnotarjeta           = trim($det['numeroTarjeta']);
                $pagofactura->fvcdescripciontarjeta  = trim($det['descripcion']);
                //$pagofactura->fvccodigo              = $request->codigo;
                $pagofactura->fvcusuario_id          = $request->usuario_sesion;
                $pagofactura->save();

            }

            /* FIN PAGO ABONO*/




            /* REALIZAR GRABADO DE DETALLE */
            $detalles =  json_decode($request->data, true);//Array de detalles

            //Recorro todos los elementos

            foreach($detalles as $ep=>$det)
            {
                $detalle = new Detallefactura();
                $detalle->fvcfactura_id  = $factura->id;
                // $detalle->dfvid          = $ep + 1;
                $detalle->fvcarticulo_id = 1;
                $detalle->fvctalla       = $det['talla'];
                $detalle->fvcdescripcion = $det['descripcion'];
                $detalle->fvcentregado   = date("Y-m-d");
                $detalle->fvcmedicion    = $det['medicion'];
                $detalle->fvcestadoprenda  = 'POR ENTREGAR';
                $detalle->fvcnota    = 'prueba';
                $detalle->fvcestado = 'NO';
                $pagofactura->fvcusuario_id          = $request->usuario_sesion;

                $detalle->fvcusuario_id  = 1;
                $detalle->save();

            }

            /* FIN GRABADO DE DETALLE*/

            DB::commit();
            return [
                'id' => $factura->id
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

    //RUTA EDIT
    public function edit(Request $request, $id){

        //$factura       = factura::find($id);

        $factura = DB::table('tblfactura')
            ->join('tblcliente', 'tblfactura.fvccliente_id', '=', 'tblcliente.id')
            ->join('tblvendedor', 'tblfactura.fvcvendedor_id', '=', 'tblvendedor.id')
            ->select('tblfactura.*',
                'tblcliente.fvcnombre as cliente',
                'tblvendedor.fvcnombre as vendedor')
            ->where('tblfactura.id', '=', $id)
            ->first();

        $detallefactura = DB::table('tbldetallefactura')
            ->join('tblarticulos', 'tbldetallefactura.fvcarticulo_id', '=', 'tblarticulos.id')
            ->select('tbldetallefactura.*', 'tblarticulos.fvcnombre')
            ->where('tbldetallefactura.fvcfactura_id', '=', $factura->id)
            ->get();


        return [
            'factura'  => $factura,
            'detallefactura' => $detallefactura
        ];

    }

    //metodo para mostrar el historico del cambio de estados del cliente
    public function cargarAbonosOrdenServicio(Request $request, $codigo){



        $pagofactura = Pagofactura::join('tblfactura', 'tblfactura.fvccodigo', '=', 'tblpagofactura.fvccodigo')
            ->join('tblcliente', 'tblcliente.id', '=', 'tblfactura.fvccliente_id')
            ->select(
                'tblpagofactura.id',
                'tblpagofactura.flngvalor',
                'tblpagofactura.fvcformapago',
                'tblpagofactura.fvcformapago',
                'tblpagofactura.fvcnotarjeta',
                'tblpagofactura.fvcdescripciontarjeta',
                'tblpagofactura.fvccodigo',
                'tblpagofactura.fdtfecha',
                'tblcliente.fvcnombre')
            ->where('tblpagofactura.fvcfactura_id', '=', $codigo)
            ->orderBy('tblpagofactura.fdtfecha', 'asc')
            ->get();



        return [
            'pagofactura' => $pagofactura
        ];
    }

    public function listarArticulos(Request $request, $genero){

        $articulos = Articulo::join('tblcategorias', 'tblcategorias.id', '=', 'tblarticulos.fvccategoria_id')
            ->select(
                'tblarticulos.id',
                'tblarticulos.fvcnombre')
            ->where('tblcategorias.fvcgenero', '=', $genero)
            ->orderBy('tblarticulos.fvcnombre', 'asc')
            ->get();


        return [
            'articulos' => $articulos
        ];
    }


    //metodo para mostrar el historico del cambio de estados del cliente
    public function realizarAbono(Request $request){

        //validacion formulario
        $validator = Validator::make($request->all(), [

            'abono' => 'required',
            'formapago' => 'required',
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


        $pagofactura = new Pagofactura();
        $pagofactura->fvcfactura_id          = 10;
        $pagofactura->flngvalor              = $request->abono;
        $pagofactura->fdtfecha               = date("Y-m-d");
        $pagofactura->fvcformapago           = trim($request->formapago);
        $pagofactura->fvcnotarjeta           = trim($request->no_tarjeta);
        $pagofactura->fvcdescripciontarjeta  = trim($request->descripcion_tarjeta);
        $pagofactura->fvccodigo              = $request->codigo;
        $pagofactura->fvcusuario_id          = $request->usuario_sesion;
        $pagofactura->save();

        $response = array(
            'status' => 'success',
            'response_code' => 200
        );

        return $response;

    }
}
