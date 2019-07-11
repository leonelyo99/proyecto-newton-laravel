<?php

namespace App\Http\Controllers;

//peticiones http
use Illuminate\Http\Request;
//validacion del request para crear usuario
use App\Http\Requests\CreateUserRequest;
//para crear un objeto json
use Illuminate\Support\Collection as Collection;
//para try y catch
Use Exception;
//subir imagenes
use Illuminate\Support\Facades\Storage; //guardar en el disco
use Illuminate\Support\Facades\File; //tratar con archivos
//para la respuesta
use Illuminate\Http\Response;
//modelo de usuario
use App\User;

class UsersController extends Controller {

    //=======================================
    //muestra todos los usuarios con sus pedidos
    //=======================================
    public function usuarios() {
        try {
            $usuariosDB = User::where('estado', 'true')->with('pedidos')->get();

            //si el pedido lo tomo una empresa se devuelve la empresa y si lo hizo un encargado
            //se devuelve un encargado
            foreach ($usuariosDB as $usuario => $usuarioDB) {
                foreach ($usuarioDB->pedidos as $pedido => $pedidoDB) {
                    if ($pedidoDB->empresa_id) {
                        $usuariosDB[$usuario]->pedidos[$pedido]->empresa;
                    } else if ($pedidoDB->encargado_id) {
                        $usuariosDB[$usuario]->pedidos[$pedido]->encargado;
                    }
                }
            }
        } catch (Exception $e) { //si viene no  funciona larga error
            $mensaje = ['mensaje' => 'Error del servidor disculpe'];
            $mensajeJson = Collection::make($mensaje);
            $mensajeJson->toJson();

            $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
            return response($requestObj, 500);
        }

        if (!$usuariosDB) { //si esta vacio, no lo encontro
            $mensaje = ['mensaje' => 'No se encontron usuarios'];
            $mensajeJson = Collection::make($mensaje);
            $mensajeJson->toJson();

            $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
            return response($requestObj, 404);
        } else { //si no esta vacio lo mando al front
            $requestObj = new Request(array('ok' => true, "respuesta" => $usuariosDB));
            return response($requestObj, 200);
        }
    }

    //=======================================
    //muestra un usuario especifico
    //=======================================
    public function usuario($id) {
        //busca el usuario si no lo encuentra avisa 
        try {
            $usuarioDB = User::where('id', $id)->where('estado', 'true')->with('pedidos')->first();
            //dentro de cada pedido veo si es de encargado o de empresa
            foreach ($usuarioDB->pedidos as $pedido => $pedidoDB) {
                if ($pedidoDB->empresa_id) {
                    $usuarioDB->pedidos[$pedido]->empresa;
                } else if ($pedidoDB->encargado_id) {
                    $usuarioDB->pedidos[$pedido]->encargado;
                }
            }
        } catch (Exception $e) {
            $mensaje = ['mensaje' => 'Error del servidor disculpe'];
            $mensajeJson = Collection::make($mensaje);
            $mensajeJson->toJson();

            $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
            return response($requestObj, 500);
        }
        
        //si da una respuesta vacia es un error del servidor sino todo ok
        if (!$usuarioDB) {
            $mensaje = ['mensaje' => 'Id no encontrado en la base de datos'];
            $mensajeJson = Collection::make($mensaje);
            $mensajeJson->toJson();

            $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
            return response($requestObj, 404);
        } else {
            $requestObj = new Request(array('ok' => true, "respuesta" => $usuarioDB));
            return response($requestObj, 200);
        }
    }

    //=======================================
    //Crea un usuario mediante el post
    //=======================================
    public function crearUsuario(CreateUserRequest $request) {
        //instancio el usuario
        $usuario = new User;
        
        //si la peticion tiene una imagen tomo el archivo lo guardo con otro nombre
        //en el disco imagen y paso el nombre de esta al usuario si no dejo la imagen null
        if ($request->hasFile('img')) {
            $image_path = $request->file('img'); //tomo la imagen

            $imagen_guardar = time() . $image_path->getClientOriginalName(); //le cambio el nombre
            Storage::disk('images')->put($imagen_guardar, File::get($image_path)); //lo guardo en el disco imagenes
            $usuario->img = $imagen_guardar; //y el nombre en el campo img del usuario
        } else {
            $usuario->img = NULL; //si no viene el campo mando null
        }
        //declaro estas variables en el modelo del usuario
        $usuario->usuario = strtoupper(trim(strip_tags($request->input('usuario')))); //limpio los espacios, limpio xss, lo paso a mayusculas
        $usuario->email = trim(strip_tags($request->input('email'))); //limio de xss, limpio los espacios
        $usuario->password = trim($request->input('password'));
        $usuario->role = "usuario";
        $usuario->estado = "true";

        //compruebo si esta duplicado, si lo esta mando el response
        $comprovacionUsuario = User::where('usuario', $usuario->usuario)->first();
        $comprovacionEmail = User::where('email', $usuario->email)->first();

        if (!empty($comprovacionUsuario)) {
            $mensaje = ['mensaje' => 'Campo usuario se encuentra duplicado'];
            $mensajeJson = Collection::make($mensaje);
            $mensajeJson->toJson();

            $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
            return response($requestObj, 409);
        }
        if (!empty($comprovacionEmail)) {
            $mensaje = ['mensaje' => 'Campo email se encuentra duplicado'];
            $mensajeJson = Collection::make($mensaje);
            $mensajeJson->toJson();

            $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
            return response($requestObj, 409);
        }
        
        //si no esta duplicado lo guardo, si pasa algo lo aviso
        try {
            $usuario->save();
        } catch (Exception $e) {
            $mensaje = ['mensaje' => 'Algo sucedio'];
            $mensajeJson = Collection::make($mensaje);
            $mensajeJson->toJson();

            $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
            return response($requestObj, 500);
        }
        //si todo sale bien mando la respuesta
        $requestObj = new Request(array('ok' => true, "respuesta" => $usuario));
        return response($requestObj, 201);
    }

    //=======================================
    //modificar un usuario mediante el post
    //=======================================
    public function editar(CreateUserRequest $request) {

        try {//traigo el usuario
            $usuarioDB = User::findOrFail($request->input('id'));
        } catch (Exception $e) {
            $mensaje = ['mensaje' => 'Error del servidor disculpe'];
            $mensajeJson = Collection::make($mensaje);
            $mensajeJson->toJson();

            $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
            return response($requestObj, 500);
        }

        if (!$usuarioDB) { //si no esta encontrado
            $mensaje = ['mensaje' => 'Id no encontrado en la base de datos'];
            $mensajeJson = Collection::make($mensaje);
            $mensajeJson->toJson();

            $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
            return response($requestObj, 404);
        } else {
            //si el request trae un usuario reviso que no este duplicado en la base de datos
            if ($request->input('usuario')) {
                $usuarioDB->usuario = strtoupper(trim(strip_tags($request->input('usuario')))); //limpio los espacios, limpio xss, lo paso a mayusculas

                $comprovacionUsuario = Encargado::where('usuario', $usuarioDB->usuario)->first();
                if (!empty($comprovacionUsuario)) {
                    $mensaje = ['mensaje' => 'Campo usuario se encuentra duplicado'];
                    $mensajeJson = Collection::make($mensaje);
                    $mensajeJson->toJson();

                    $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
                    return response($requestObj, 409);
                }
            }
            //si el request trae un email reviso que no este duplicado en la base de datos
            if ($request->input('email')) {
                $usuarioDB->email = trim(strip_tags($request->input('email'))); //limio de xss, limpio los espacios

                $comprovacionEmail = Encargado::where('email', $usuarioDB->email)->first();
                if (!empty($comprovacionEmail)) {
                    $mensaje = ['mensaje' => 'Campo usuario se encuentra duplicado'];
                    $mensajeJson = Collection::make($mensaje);
                    $mensajeJson->toJson();

                    $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
                    return response($requestObj, 409);
                }
            }
            //guardo la password
            $usuarioDB->password = trim($request->input('password'));
            //si tiene un archivo
            if ($request->hasFile('img')) {
                //me fijo si viene en la base de datos si esta lo borro
                if ($usuarioDB->img) {
                    $file = Storage::disk('images')->delete($usuarioDB->img);
                };
                //guardo la imagen
                $image_path = $request->file('img');

                $imagen_guardar = time() . $image_path->getClientOriginalName();
                Storage::disk('images')->put($imagen_guardar, File::get($image_path));
                $usuarioDB->img = $imagen_guardar;
            }
            //guardo el usuario
            try {
                $usuarioDB->save();
            } catch (Exception $e) {
                $mensaje = ['mensaje' => 'Error al guardar intentelo mas tarde'];
                $mensajeJson = Collection::make($mensaje);
                $mensajeJson->toJson();

                $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
                return response($requestObj, 500);
            }

            $requestObj = new Request(array('ok' => true, "respuesta" => $usuarioDB));
            return response($requestObj, 201);
        }
    }

    //=======================================
    //borrar un usuario mediante el id
    //=======================================
    public function borrar($id) {
        try {
            $usuarioDB = User::where('id', $id)->where('estado', 'true')->first();
        } catch (Exception $e) {
            $mensaje = ['mensaje' => 'Error del servidor disculpe'];
            $mensajeJson = Collection::make($mensaje);
            $mensajeJson->toJson();

            $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
            return response($requestObj, 500);
        }
        if (!$usuarioDB) {
            $mensaje = ['mensaje' => 'Id no encontrado en la base de datos'];
            $mensajeJson = Collection::make($mensaje);
            $mensajeJson->toJson();

            $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
            return response($requestObj, 404);
        } else {
            //cambio el estado a false
            $usuarioDB->estado = "false";
            $usuarioDB->save(); //y lo guardo

            $requestObj = new Request(array('ok' => true, "respuesta" => "borrado"));
            return response($requestObj, 201);
        }
    }

}
