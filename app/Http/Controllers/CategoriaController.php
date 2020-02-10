<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Categoria;
use Illuminate\Support\Facades\DB;
use App\User;

class CategoriaController extends Controller
{
    public function index(Request $request){

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $categoria = Categoria::orderBy('id', 'asc')->paginate(7);
        }
        else{
            $categoria = Categoria::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(7);
        }


        return [
            'pagination' => [
                'total'        => $categoria->total(),
                'current_page' => $categoria->currentPage(),
                'per_page'     => $categoria->perPage(),
                'last_page'    => $categoria->lastPage(),
                'from'         => $categoria->firstItem(),
                'to'           => $categoria->lastItem(),
            ],
            'categoria' => $categoria
        ];

    }

    public function store(Request $request){

        //validacion formulario
        $validator = Validator::make($request->all(), [

            'fvcnombre' => 'required|max:100|min:4|unique:tblcategorias',
            'genero' =>'required',
            'descripcion' => 'required|max:120',
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


        $categoria = new Categoria();
        $categoria->fvcnombre      = trim($request->fvcnombre);
        $categoria->fvcgenero      = $request->genero;
        $categoria->fvcdescripcion = trim($request->descripcion);
        $categoria->fvcusuario_id  = $request->usuario_sesion;

        $categoria->save();


        $response = array(
            'status' => 'success',
            'response_code' => 200
        );

        return $response;
    }

    public function create(Request $request){
    }



    public function edit(Request $request, $id){

        $categoria        = Categoria::find($id);


        return [
            'categoria'  => $categoria,
        ];

    }

    public function update(Request $request){

        //validacion formulario
        $validator = Validator::make($request->all(), [

            'fvcnombre' => 'required|max:100|min:4|unique:tblcategorias,fvcnombre,'.$request->id,
            'genero' =>'required',
            'descripcion' => 'required|max:200',
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


        $categoria =  Categoria::find($request->id);
        $categoria->fvcnombre      = trim($request->fvcnombre);
        $categoria->fvcgenero      = $request->genero;
        $categoria->fvcdescripcion = trim($request->descripcion);
        $categoria->fvcusuario_id  = $request->usuario_sesion;

        $categoria->save();


        $response = array(
            'status' => 'success',
            'response_code' => 200
        );

        return $response;
    }
}
