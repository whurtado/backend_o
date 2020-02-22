<?php

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*Route::any('{all}', function () {
    return view('index');
})
	->where(['all' => '.*']); */


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group(function () {



    Route::get('dashboard', function () {
        return response()->json(['data' => 'Test Data']);
    });
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    /*
    if (request()->ajax() != 1) {
        return redirect()->route('ClienteController@errorLoginJao');
        Route::get('/clientes/login', 'ClienteController@index');

    }
    */

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');


    //USUARIO

    Route::post('/usuarios', 'UserController@index');
    Route::get('/usuarios/create', 'UserController@create');
    Route::post('/usuarios/registrar', 'UserController@store');
    Route::get('/usuarios/edit/{user}', 'UserController@edit');
    Route::post('/usuarios/actualizar', 'UserController@update');
    Route::post('/usuarios/mostrarSedesDelUsuario', 'UserController@mostrarSedesDelUsuario');
    Route::get('/usuarios/traerSedes', 'UserController@traerSedes');


    //ROLES USUARIO
    Route::post('/usuariosRoles/actualizar', 'UserRolesController@update');

    //PERMISOS USUARIO
    Route::post('/usuariosPermisos/actualizar', 'UserPermissionsController@update');




    //ROLES
    Route::post('/roles', 'RolesController@index');
    Route::get('/roles/create', 'RolesController@create');
    Route::post('/roles/registrar', 'RolesController@store');
    Route::get('/roles/edit/{user}', 'RolesController@edit');
    Route::post('/roles/actualizar', 'RolesController@update');

    //CLIENTE
    Route::post('/clientes', 'ClienteController@index');
    Route::get('/clientes/create', 'ClienteController@create');
    Route::post('/clientes/registrar', 'ClienteController@store');
    Route::get('/clientes/edit/{cliente}', 'ClienteController@edit');
    Route::put('/clientes/actualizar', 'ClienteController@update');
    Route::get('/clientes/cargarEstadoCliente/{cliente}', 'ClienteController@cargarEstadoCliente');
    Route::post('/clientes/cambioEstado', 'ClienteController@cambioEstado');
    Route::get('/clientes/cargarNovedadCliente/{cliente}', 'ClienteController@cargarNovedadCliente');
    Route::post('/clientes/asignacionNovedad', 'ClienteController@asignacionNovedad');


    //VENDEDOR
    Route::post('/vendedores', 'VendedorController@index');
    Route::get('/vendedores/create', 'VendedorController@create');
    Route::post('/vendedores/registrar', 'VendedorController@store');
    Route::get('/vendedores/edit/{vendedor}', 'VendedorController@edit');
    Route::post('/vendedores/actualizar', 'VendedorController@update');


    //CATEGORIA
    Route::post('/categorias', 'CategoriaController@index');
    Route::get('/categorias/create', 'CategoriaController@create');
    Route::post('/categorias/registrar', 'CategoriaController@store');
    Route::get('/categorias/edit/{categoria}', 'CategoriaController@edit');
    Route::post('/categorias/actualizar', 'CategoriaController@update');
    Route::get('/categorias/categoriasSinFiltros', 'CategoriaController@categoriasSinFiltros');

    //ARTICULO
    Route::post('/articulos', 'ArticuloController@index');
    Route::get('/articulos/create', 'ArticuloController@create');
    Route::post('/articulos/registrar', 'ArticuloController@store');
    Route::get('/articulos/edit/{articulo}', 'ArticuloController@edit');
    Route::post('/articulos/actualizar', 'ArticuloController@update');


    //PAGO
    Route::post('/pagos', 'PagoController@index');
    Route::get('/pagos/create', 'PagoController@create');
    Route::post('/pagos/registrar', 'PagoController@store');
    Route::get('/pagos/edit/{pago}', 'PagoController@edit');
    Route::post('/pagos/actualizar', 'PagoController@update');

    //AUTOCOMPLETE
    Route::get('/autocomplete', 'AutocompleteController@autocomplete');
    Route::post('/autocomplete/search', 'AutocompleteController@autocompleteSearch');


    //FACTURA
    Route::post('/ordenservicios', 'FacturaController@index');
    Route::get('/ordenservicios/create', 'FacturaController@create');
    Route::post('/ordenservicios/registrar', 'FacturaController@store');
    Route::get('/ordenservicios/edit/{orden}', 'FacturaController@edit');
    Route::post('/ordenservicios/actualizar', 'FacturaController@update');
    Route::get('/ordenservicios/listarArticulos/{genero}', 'FacturaController@listarArticulos');
    Route::get('/ordenservicios/cargarAbonosOrdenServicio/{orden}', 'FacturaController@cargarAbonosOrdenServicio');
    Route::post('/ordenservicios/realizarAbono', 'FacturaController@realizarAbono');


    //AUTORIZACION
    Route::post('/autorizaciones', 'AutorizacionController@index');
    Route::get('/autorizaciones/create', 'AutorizacionController@create');
    Route::post('/autorizaciones/registrar', 'AutorizacionController@store');
    Route::get('/autorizaciones/edit/{autorizacion}', 'AutorizacionController@edit');
    Route::post('/autorizaciones/actualizar', 'AutorizacionController@update');

    //TIPO AUTORIZACION
    Route::post('/tipoAutorizaciones', 'TipoAutorizacionController@index');
    Route::get('/tipoAutorizaciones/mostrarTipoAutorizacion', 'TipoAutorizacionController@mostrarTipoAutorizacion');
    Route::get('/tipoAutorizaciones/create', 'TipoAutorizacionController@create');
    Route::post('/tipoAutorizaciones/registrar', 'TipoAutorizacionController@store');
    Route::get('/tipoAutorizaciones/edit/{tipoautorizacion}', 'TipoAutorizacionController@edit');
    Route::post('/tipoAutorizaciones/actualizar', 'TipoAutorizacionController@update');

    //SEDE
    Route::post('/sedes', 'SedeController@index');
    Route::get('/sedes/mostrarSede', 'SedeController@mostrarSede');
    Route::get('/sedes/create', 'SedeController@create');
    Route::post('/sedes/registrar', 'SedeController@store');
    Route::get('/sedes/edit/{sede}', 'SedeController@edit');
    Route::post('/sedes/actualizar', 'SedeController@update');

    //CLASIFICACION PAGO
    Route::post('/clasificacionPagos', 'ClasificacionPagoController@index');
    Route::get('/clasificacionPagos/create', 'ClasificacionPagoController@create');
    Route::post('/clasificacionPagos/registrar', 'ClasificacionPagoController@store');
    Route::get('/clasificacionPagos/edit/{clasificacionpago}', 'ClasificacionPagoController@edit');
    Route::post('/clasificacionPagos/actualizar', 'ClasificacionPagoController@update');

    //REGISTRO PAGO
    Route::post('/registroPagos', 'RegistroPagoController@index');
    Route::get('/registroPagos/create', 'RegistroPagoController@create');
    Route::post('/registroPagos/registrar', 'RegistroPagoController@store');
    Route::get('/registroPagos/edit/{registropago}', 'RegistroPagoController@edit');
    Route::post('/registroPagos/actualizar', 'RegistroPagoController@update');


    // }

    /*Route::get('/clientes/{cliente}/cargarEstadoCliente', 'ClienteController@cargarEstadoCliente');
    Route::post('/clientes/cambioEstado', 'ClienteController@cambioEstado');
    Route::get('/clientes/{cliente}/cargarNovedadCliente', 'ClienteController@cargarNovedadCliente');
    Route::post('/clientes/asignacionNovedad', 'ClienteController@asignacionNovedad');*/

});
