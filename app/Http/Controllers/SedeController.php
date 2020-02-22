<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\sede;
use Illuminate\Support\Facades\DB;
use App\User;

class sedeController extends Controller
{
    public function index(Request $request){

        //SOLO SE PERMITEN PETICIONES AJAX A NUESTRO CONTROLADOR,
        //DE LO CONTRARIO REDIRIGE A LA RUTA RAIZ

        $sede = DB::table('tblsede');

        if ($request->fvcnombre != '' && $request->fvcnombre != 'null' ) {
            $sede->where('fvcnombre', 'like', '%'.$request->fvcnombre. '%');
        }

        if ($request->estado != '' && $request->estado != 'null') {

            $sede->where('fvcestado', '=', $request->estado);
        }

        $sede = $sede->get();

        return [
            'sede' => $sede
        ];

    }

    public function mostrarSede(Request $request){

        $sede = sede::orderBy('id', 'asc')->get();

        return [
            'sede' => $sede
        ];

    }

    public function create(Request $request){
    }

    public function store(Request $request){

        //validacion formulario
        $validator = Validator::make($request->all(), [

            'fvcnombre' => 'required|unique:tblsede',
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

            $sede = new sede();
            $sede->fvcnombre     = trim($request->fvcnombre);
            $sede->fvcestado     = trim($request->estado);
            $sede->fvcusuario_id = $request->usuario_sesion;

            $sede->save();

            DB::commit();
            return [
                'id' => $sede->id
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

        $sede        = sede::find($id);


        return [
            'sede'  => $sede,
        ];

    }

    public function update(Request $request){

        //validacion formulario
        $validator = Validator::make($request->all(), [

            'fvcnombre' => 'required|max:100|min:2|unique:tblsede,fvcnombre,'.$request->id,
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


        $sede =  sede::find($request->id);
        $sede->fvcnombre     = trim($request->fvcnombre);
        $sede->fvcestado     = trim($request->estado);
        $sede->fvcusuario_id = $request->usuario_sesion;

        $sede->save();


        $response = array(
            'status' => 'success',
            'response_code' => 200
        );

        return $response;
    }
}
