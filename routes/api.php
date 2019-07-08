<?php

use Illuminate\Http\Request;

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

//routas usuarios
//mostrar
Route::get('/usuarios', 'UsersController@usuarios'); //mostrar todos los usuarios con sus pedidos
Route::get('/usuario/{id}', 'UsersController@usuario'); //mostrar un usuario mediante el id con sus pedidos
//Crear
Route::post('/usuario/crear', 'UsersController@crearUsuario'); //agrega un usuario nuevo
//editar
Route::post('/usuario/editar', 'UsersController@editar'); //editar un usuario
//borrar
Route::get('/usuario/borrar/{id}', 'UsersController@borrar'); //editar un usuario

//rutas encargado
//Crear
Route::get('/encargado/crear', 'EncargadoController@crearEncargado'); //agrega un encargado nuevo
Route::get('/encargado/{id}', 'EncargadoController@encargado'); //mostrar un encargado con empresa, pedidos, usuarios, asosiados


//helpers para no sacar vistas
Route::get('/imagen/{name}', 'HelperController@verImgen'); //manda la imagen con el nombre
Route::get('/error', 'HelperController@error'); //mensaje de error al validar request




