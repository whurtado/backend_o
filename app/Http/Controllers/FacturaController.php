<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Articulo;


class FacturaController extends Controller
{
    //RUTA INDEX
    public function index(Request $request){

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $factura = Factura::orderBy('id', 'asc')->paginate(7);
        }
        else{
            $factura = Factura::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(7);
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

        //validacion formulario
        $validator = Validator::make($request->all(), [

            'nombre' => 'required|max:30|min:3',
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


            $factura = new Factura();
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

            $factura->save();



            /* REALIZAR PAGO DE ABONO*/

            if ($request->abono > 0){

                $pagoFactura = new PagoFactura();
                $pagoFactura->fvcfactura_id          = $factura->id;
                $pagoFactura->flngvalor              = $request->abono;
                $pagoFactura->fdtfecha               = date("Y-m-d");
                $pagoFactura->fvcformapago           = trim($request->formapago);
                $pagoFactura->fvcnotarjeta           = trim($request->no_tarjeta);
                $pagoFactura->fvcdescripciontarjeta  = trim($request->descripcion_tarjeta);
                //$pagoFactura->fvccodigo              = $request->codigo;
                $pagoFactura->fvcusuario_id          = $request->usuario_sesion;


                $pagoFactura->save();

            }

            /* FIN PAGO ABONO*/




            /* REALIZAR GRABADO DE DETALLE */
            $detalles = $request->data;//Array de detalles
            //Recorro todos los elementos

            foreach($detalles as $ep=>$det)
            {
                $detalle = new DetalleFactura();
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
                $pagoFactura->fvcusuario_id          = $request->usuario_sesion;


                /* if($det['nota']!= ''){
                     $detalle->fvcnota    = $det['nota'];
                 }

                 if($det['estado']!= ''){
                     $detalle->fvcestadoprenda  = 'POR ENTREGAR';
                 }*/
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

        //$factura       = Factura::find($id);

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



        $pagofactura = PagoFactura::join('tblfactura', 'tblfactura.fvccodigo', '=', 'tblpagofactura.fvccodigo')
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


        $pagoFactura = new PagoFactura();
        $pagoFactura->fvcfactura_id          = 10;
        $pagoFactura->flngvalor              = $request->abono;
        $pagoFactura->fdtfecha               = date("Y-m-d");
        $pagoFactura->fvcformapago           = trim($request->formapago);
        $pagoFactura->fvcnotarjeta           = trim($request->no_tarjeta);
        $pagoFactura->fvcdescripciontarjeta  = trim($request->descripcion_tarjeta);
        $pagoFactura->fvccodigo              = $request->codigo;
        $pagoFactura->fvcusuario_id          = $request->usuario_sesion;
        $pagoFactura->save();

        $response = array(
            'status' => 'success',
            'response_code' => 200
        );

        return $response;

    }
}
