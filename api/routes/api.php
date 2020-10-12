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

use App\Http\Controllers\localController;

Route::post('/local/nuevo',[localController::class, 'Nuevo']);
Route::post('/local/edit/{Codigo}',[localController::class, 'EditLocal']);
Route::get("local/lista",[localController::class, 'Listar']);
Route::get("/local/buscar/{Nombre}",[localController::class, 'Buscar']);
Route::get("/local/buscar/empresa/{CodigoEmpresa}",[localController::class, 'BuscarLocalEmpresa']);
Route::get("/local/buscar/Codigo/{Codigo}",[localController::class, 'BuscarCodigo']);
Route::get("/locales",[localController::class, 'Listar']);
Route::DELETE("/local/{codigo}",[localController::class, 'Eliminar']);
Route::get("local/Empresas",[localController::class, 'mostrarEmpresas']);
Route::patch("/local/{Vigencia}",[localController::class, 'cambiarEstadoLocal']);