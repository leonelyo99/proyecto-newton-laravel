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
use Illuminate\Support\Facades\File; //traer el archivo
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
        } catch (Exception $e) {
            $mensaje = ['mensaje' => 'No se encontron usuarios'];
            $mensajeJson = Collection::make($mensaje);
            $mensajeJson->toJson();

            $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
            return response($requestObj, 404);
        }

        if (!$usuariosDB) {
            $mensaje = ['mensaje' => 'Error del servidor disculpe'];
            $mensajeJson = Collection::make($mensaje);
            $mensajeJson->toJson();

            $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
            return response($requestObj, 500);
        } else {
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
        } catch (Exception $e) {
            $mensaje = ['mensaje' => 'Id no encontrado en la base de datos'];
            $mensajeJson = Collection::make($mensaje);
            $mensajeJson->toJson();

            $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
            return response($requestObj, 404);
        }
        //si da una respuesta vacia es un error del servidor sino todo ok
        if (!$usuarioDB) {
            $mensaje = ['mensaje' => 'Error del servidor disculpe'];
            $mensajeJson = Collection::make($mensaje);
            $mensajeJson->toJson();

            $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
            return response($requestObj, 500);
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
            $image_path = $request->file('img');

            $imagen_guardar = time() . $image_path->getClientOriginalName();
            Storage::disk('images')->put($imagen_guardar, File::get($image_path));
            $usuario->img = $imagen_guardar;
        } else {
            $usuario->img = NULL;
        }
        //declaro estas variables en el modelo del usuario
        $usuario->usuario = strtoupper(trim(strip_tags($request->input('usuario')))); //limpio los espacios, limpio xss, lo paso a mayusculas
        $usuario->email = trim(strip_tags($request->input('email'))); //limio de xss, limpio los espacios
        $usuario->password = trim($request->input('password'));
        $usuario->role = "usuario";
        $usuario->estado = "true";

        //compruebo si esta duplicado y mando el response
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

        try {
            $usuarioDB = User::findOrFail($request->input('id'));
        } catch (Exception $e) {
            $mensaje = ['mensaje' => 'Id no encontrado en la base de datos'];
            $mensajeJson = Collection::make($mensaje);
            $mensajeJson->toJson();

            $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
            return response($requestObj, 404);
        }

        if (!$usuarioDB) {
            $mensaje = ['mensaje' => 'Error del servidor disculpe'];
            $mensajeJson = Collection::make($mensaje);
            $mensajeJson->toJson();

            $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
            return response($requestObj, 500);
        } else {

            $usuarioDB->usuario = strtoupper(trim(strip_tags($request->input('usuario')))); //limpio los espacios, limpio xss, lo paso a mayusculas
            $usuarioDB->email = trim(strip_tags($request->input('email'))); //limio de xss, limpio los espacios
            $usuarioDB->password = trim($request->input('password'));

            if ($request->hasFile('img')) {

                if ($usuarioDB->img) {
                    $file = Storage::disk('images')->delete($usuarioDB->img);
                };

                $image_path = $request->file('img');

                $imagen_guardar = time() . $image_path->getClientOriginalName();
                Storage::disk('images')->put($imagen_guardar, File::get($image_path));
                $usuarioDB->img = $imagen_guardar;
            }

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
            $mensaje = ['mensaje' => 'Id no encontrado en la base de datos'];
            $mensajeJson = Collection::make($mensaje);
            $mensajeJson->toJson();

            $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
            return response($requestObj, 404);
        }
        if (!$usuarioDB) {
            $mensaje = ['mensaje' => 'Error del servidor disculpe'];
            $mensajeJson = Collection::make($mensaje);
            $mensajeJson->toJson();

            $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
            return response($requestObj, 500);
        } else {
            $usuarioDB->estado = "false";
            $usuarioDB->save();
            
            $requestObj = new Request(array('ok' => true, "respuesta" => "borrado"));
            return response($requestObj, 201);
        }
    }
}
    