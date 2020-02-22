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

        $categoria = DB::table('tblcategorias');

        if ($request->fvcnombre != '' && $request->fvcnombre != 'null' ) {
            $categoria->where('fvcnombre', 'like', '%'.$request->fvcnombre. '%');
        }

        if ($request->genero != '' && $request->genero != 'null') {

            $categoria->where('fvcgenero', '=', $request->genero);
        }

        if ($request->sede_creacion != '' && $request->sede_creacion != 'null') {

            $categoria->where('fvcsede_creacion', '=', $request->sede_creacion);
        }

        $categoria = $categoria->get();


        return [
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

                return response()->json(array(
                    'success' => false,
                    'message' => 'There are incorect values in the form!',
                    'errors' => $validator->getMessageBag()->toArray()
                ), 422);


            $this->throwValidationException(
                $request, $validator
            );
        }


        $categoria = new Categoria();
        $categoria->fvcnombre      = trim($request->fvcnombre);
        $categoria->fvcgenero      = $request->genero;
        $categoria->fvcdescripcion = trim($request->descripcion);
        $categoria->fvcusuario_id  = $request->usuario_sesion;
        $categoria->fvcsede_creacion    = $request->sede_creacion;

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

                return response()->json(array(
                    'success' => false,
                    'message' => 'There are incorect values in the form!',
                    'errors' => $validator->getMessageBag()->toArray()
                ), 422);


            $this->throwValidationException(
                $request, $validator
            );
        }


        $categoria =  Categoria::find($request->id);
        $categoria->fvcnombre      = trim($request->fvcnombre);
        $categoria->fvcgenero      = $request->genero;
        $categoria->fvcdescripcion = trim($request->descripcion);
        $categoria->fvcusuario_id  = $request->usuario_sesion;
        $categoria->fvcsede_creacion    = $request->sede_creacion;

        $categoria->save();


        $response = array(
            'status' => 'success',
            'response_code' => 200
        );

        return $response;
    }


    public function categoriasSinFiltros(Request $request){


        $categoria = Categoria::orderBy('id', 'asc')->get();

        return [
            'categoria' => $categoria
        ];

    }
}
