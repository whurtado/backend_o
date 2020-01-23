<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Articulo;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Categoria;


class ArticuloController extends Controller
{

    public function index(Request $request){

        //SOLO SE PERMITEN PETICIONES AJAX A NUESTRO CONTROLADOR,
        //DE LO CONTRARIO REDIRIGE A LA RUTA RAIZ
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $articulo = Articulo::orderBy('id', 'asc')->paginate(7);
        }
        else{
            $articulo = Articulo::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(7);
        }


        return [
            'pagination' => [
                'total'        => $articulo->total(),
                'current_page' => $articulo->currentPage(),
                'per_page'     => $articulo->perPage(),
                'last_page'    => $articulo->lastPage(),
                'from'         => $articulo->firstItem(),
                'to'           => $articulo->lastItem(),
            ],
            'articulo' => $articulo
        ];

    }

    public function store(Request $request){
        if (!$request->ajax()) return redirect('/');

        //return $request;


        //validacion formulario
        $validator = Validator::make($request->all(), [

            'fvcnombre' => 'required|max:100|min:3',
            'codigo_barras' => 'required|max:250',
            'descripcion' => 'required|max:200',
            'valor' => 'required|max:12',
            'categoria' => 'required',
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

        //validar imagen

        if($request->get('fvcimagen')){

            $image = $request->get('fvcimagen');
            $name = $request->fvccodigo_barras.'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            $ruta =\Image::make($request->get('fvcimagen'))->save(public_path('/imagenes/articulo/').$name);
            $ruta1 = '/imagenes/articulo/';
        }

        if($request->fvccantidad == '')$request->fvccantidad =0;
        if($request->flngvalorDeposito == '')$request->flngvalorDeposito =0;

        $articulo = new Articulo();
        $articulo->fvcnombre        = trim($request->fvcnombre);
        $articulo->fvccodigo_barras = trim($request->codigo_barras);
        $articulo->fvcdescripcion   = trim($request->descripcion);
        $articulo->flngvalor        = trim($request->valor);
        if($request->get('fvcimagen') !=''){
            $articulo->fvcimagen = trim($name);
        }
        $articulo->cantidad            = $request->fvccantidad;
        $articulo->flvrequieredeposito = $request->flvrequieredeposito;
        $articulo->flngvalorDeposito   = $request->flngvalorDeposito;
        $articulo->fvccategoria_id     = $request->categoria;
        $articulo->fvcusuario_id       = $request->usuario_sesion;


        $articulo->save();



        $response = array(
            'status' => 'success',
            'response_code' => 200
        );

        return $response;
    }

    public function create(Request $request){
        if (!$request->ajax()) return redirect('/');
    }


    public function edit(Request $request, $id){
        if (!$request->ajax()) return redirect('/');

        $articulo        = Articulo::find($id)->load('categorias');


        return [
            'articulo'  => $articulo,
        ];

    }

    public function update(Request $request){
        if (!$request->ajax()) return redirect('/');

        //validacion formulario
        $validator = Validator::make($request->all(), [

            'fvcnombre' => 'required|max:100|min:3',
            'codigo_barras' => 'required|max:250',
            'descripcion' => 'required|max:200',
            'valor' => 'required|max:12',
            'categoria' => 'required',
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

        if($request->fvccantidad == '')$request->fvccantidad =0;
        if($request->flngvalorDeposito == '')$request->flngvalorDeposito =0;



        $articulo =  Articulo::find($request->id);

        $articulo->fvcnombre        = trim($request->fvcnombre);
        $articulo->fvccodigo_barras = trim($request->codigo_barras);
        $articulo->fvcdescripcion   = trim($request->descripcion);
        $articulo->flngvalor        = $request->valor;
        if($request->get('fvcimagen') !=''){
            // $articulo->fvcimagen = trim($name);
        }
        $articulo->cantidad            = $request->fvccantidad;
        $articulo->flvrequieredeposito = $request->flvrequieredeposito;
        $articulo->flngvalorDeposito   = $request->flngvalorDeposito;
        $articulo->fvccategoria_id     = $request->categoria;
        $articulo->fvcusuario_id       = $request->usuario_sesion;
        $articulo->save();


        $response = array(
            'status' => 'success',
            'response_code' => 200
        );

        return $response;
    }

}

