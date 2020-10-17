<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//TOKEN
Route::get('token','BienvenidoController@getToken');
// CRUD DE EMPRESA
Route::get('empresas', 'empresaController@inicio')->middleware('token');
Route::get('empresaMostrar', 'empresaController@mostrarEmpresa')->middleware('token');
Route::get('tablaEmpresa', 'empresaController@tablaEmpresa')->middleware('token');
Route::get('empresas/{id}', 'empresaController@mostrar')->middleware('token');
Route::post('empresas', 'empresaController@registrar')->middleware('token');
Route::post('empresas/{id}', 'empresaController@actualizar')->middleware('token');
Route::delete('empresa/{id}', 'empresaController@eliminar')->middleware('token');
Route::patch('empresa/{id}', 'empresaController@cambiarEstado')->middleware('token');
// *********************************************************

use App\Http\Controllers\LocalController;

Route::post('locales','LocalController@registrar');
Route::post('locales/{Codigo}','LocalController@actualizar');
Route::get("locales/{Codigo}",'LocalController@BuscarCodigo');
Route::get("locales",'LocalController@Listar');
Route::patch("locales/{Vigencia}",[LocalController::class, 'cambiarEstadoLocal']);


// Route::get("/Local/buscar/{Nombre}",[LocalController::class, 'Buscar']);
// Route::get("/Local/buscar/empresa/{CodigoEmpresa}",[LocalController::class, 'BuscarLocalEmpresa']);
// Route::get("/Locales",[LLocalController::class, 'Listar']);
// Route::DELETE("/Local/{codigo}",[LocalController::class, 'Eliminar']);
// Route::get("Local/Empresas",[LocalController::class, 'mostrarEmpresas']);