<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vendedor;
use App\Categoria;
use App\Cliente;
use App\Factura;

class AutocompleteController extends Controller
{
    public function autocomplete()
    {
        return view('autocomplete');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function autocompleteSearch(Request $request)
    {
        $data = '';
        if ($request->tabla == 'categoria') {
            $searchquery = $request->searchquery;
            $data = Categoria::where('fvcnombre', 'like', '%' . $searchquery . '%')->get();

        }elseif ($request->tabla == 'cliente'){

            $searchquery = $request->searchquery;
            $data = Cliente::where('fvcprimernombre', 'like', '%' . $searchquery . '%')->get();

        } elseif ($request->tabla == 'vendedor'){

            $searchquery = $request->searchquery;
            $data = Vendedor::where('fvcnombre', 'like', '%' . $searchquery . '%')->get();

        }elseif ($request->tabla == 'ordenservicio'){

            $searchquery = $request->searchquery;
            $data = Factura::where('fvccodigo', 'like', '%' . $searchquery . '%')->get();

            /* $data = Factura::join('tblcliente', 'tblcliente.id', '=', 'tblfactura.fvccliente_id')
                 ->select(
                     'tblfactura.fvccodigo as ordenservicio',
                     'tblcliente.fvcnombre as cliente')
                 ->where('tblfactura.fvccodigo', 'like', '%' . $searchquery . '%')
                 ->get();*/
        }


        return response()->json($data);
    }
}
