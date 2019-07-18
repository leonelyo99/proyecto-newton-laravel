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
//routas login
Route::post('/login', 'LoginController@login');
Route::get('/todo', 'LoginController@todo');

//routas usuarios
//mostrar
Route::get('/usuarios', 'UsersController@usuarios')->middleware('Check:usuario'); //mostrar todos los usuarios con sus pedidos
Route::get('/usuario/{id}', 'UsersController@usuario')->middleware('Check:usuario'); //mostrar un usuario mediante el id con sus pedidos
//Crear
Route::post('/usuario/crear', 'UsersController@crearUsuario'); //agrega un usuario nuevo
//editar
Route::post('/usuario/editar', 'UsersController@editar')->middleware('Check:usuario'); //editar un usuario
//borrar
Route::get('/usuario/borrar/{id}', 'UsersController@borrar')->middleware('Check:usuario'); //editar un usuario

//rutas encargado
//Crear
Route::post('/encargado/crear', 'EncargadoController@crearEncargado')->middleware('Check:empresa'); //agrega un encargado nuevo
//mostrar
Route::get('/encargado/{id}', 'EncargadoController@encargado')->middleware('Check:empresa1encargado'); //mostrar un encargado con empresa, pedidos, usuarios vinculados a este
//borrar
Route::get('/encargado/borrar/{id}', 'EncargadoController@borrar')->middleware('Check:empresa1encargado'); //editar un encargado
//editar
Route::post('/encargado/editar', 'EncargadoController@editar')->middleware('Check:empresa1encargado'); //editar un encargado

//rutaas empresa
//Crear
Route::post('/empresa/crear', 'EmpresaController@crearEmpresa'); //agrega un encargado nuevo
//mostrar empresa
Route::get('/empresa/{id}', 'EmpresaController@empresa')->middleware('Check:empresa'); //mostrar una empresa con encargados, pedidos, usuarios vinculados a este
//editar
Route::post('/empresa/editar', 'EmpresaController@editar')->middleware('Check:empresa'); //editar una empresa
//borrar
Route::get('/empresa/borrar/{id}', 'EmpresaController@borrar')->middleware('Check:empresa'); //borrar una empresa
//encargados
Route::get('/empresa/encargados/{id}', 'EmpresaController@encargados')->middleware('Check:empresa'); //muestra todos los encargados de una empresa
//historial
Route::get('/empresa/historial/{id}', 'EmpresaController@historial')->middleware('Check:empresa'); //muestra todos los pedidos

//rutas de pedidos
//crear
Route::post('/pedido/crear', 'PedidoController@crearPedido')->middleware('Check:empresa1encargado'); //agrega un pedido nuevo
//editar
Route::post('/pedido/editar', 'PedidoController@editar')->middleware('Check:empresa1encargado'); //editar una empresa
//borrar
Route::get('/pedido/borrar/{id}', 'PedidoController@borrar')->middleware('Check:empresa1encargado1usuario'); //borrar una empresa
//agregar imagen
Route::post('/pedido/imagen', 'PedidoController@imagen')->middleware('Check:empresa1encargado'); //editar una empresa

//ruta para traer imagenes
//imagen
Route::get('/imagen/{name}', 'ImagenController@verImgen'); //manda la imagen con el nombre

//helpers para no sacar vistas
Route::get('/error', 'HelperController@error'); //mensaje de error al validar request

