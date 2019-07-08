<?php

namespace App\Http\Controllers;

//peticiones http
use Illuminate\Http\Request;
//validacion del request para crear encargado
use App\Http\Requests\CreateEncargadoRequest;
//para crear un objeto json
use Illuminate\Support\Collection as Collection;
//para try y catch
Use Exception;
//modelo de usuario
use App\Encargado;

class EncargadoController extends Controller {

    //=======================================
    //Crea un usuario mediante el post
    //=======================================
    public function crearEncargado(CreateEncargadoRequest $request) {
        //instancio el usuario
        $encargado = new Encargado;

        //si la peticion tiene una imagen tomo el archivo lo guardo con otro nombre
        //en el disco imagen y paso el nombre de esta al usuario si no dejo la imagen null
        if ($request->hasFile('img')) {
            $image_path = $request->file('img');

            $imagen_guardar = time() . $image_path->getClientOriginalName();
            Storage::disk('images')->put($imagen_guardar, File::get($image_path));
            $encargado->img = $imagen_guardar;
        } else {
            $encargado->img = NULL;
        }
        //declaro estas variables en el modelo del usuario
        $encargado->empresa_id = $request->input('empresa_id');
        $encargado->nombre = strtoupper(trim(strip_tags($request->input('nombre')))); //limpio los espacios, limpio xss, lo paso a mayusculas
        $encargado->apellido = strtoupper(trim(strip_tags($request->input('apellido'))));
        $encargado->usuario = strtoupper(trim(strip_tags($request->input('usuario'))));
        $encargado->password = trim($request->input('password'));
        $encargado->role = "encargado";
        $encargado->estado = "true";

        //compruebo si esta duplicado y mando el response
        $comprovacionUsuario = Encargado::where('usuario', $encargado->usuario)->first();

        if (!empty($comprovacionUsuario)) {
            $mensaje = ['mensaje' => 'Campo usuario se encuentra duplicado'];
            $mensajeJson = Collection::make($mensaje);
            $mensajeJson->toJson();

            $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
            return response($requestObj, 409);
        }

        //si no esta duplicado lo guardo, si pasa algo lo aviso
        try {
            $encargado->save();
        } catch (Exception $e) {
            $mensaje = ['mensaje' => 'Algo sucedio'];
            $mensajeJson = Collection::make($mensaje);
            $mensajeJson->toJson();

            $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
            return response($requestObj, 500);
        }
        //si todo sale bien mando la respuesta
        $requestObj = new Request(array('ok' => true, "respuesta" => $encargado));
        return response($requestObj, 201);
    }

    //=======================================
    //Mando encargado con pedido,imagen,materiales y usuario perteneciente
    //=======================================
    public function encargado($id) {
        
        //busca el encargado si no lo encuentra avisa 
        try {
            $encargadoDB = Encargado::where('id', $id)->where('estado', 'true')->with('empresa')->with('pedidos')->first();
        } catch (Exception $e) {
            $mensaje = ['mensaje' => 'Id no encontrado en la base de datos'];
            $mensajeJson = Collection::make($mensaje);
            $mensajeJson->toJson();

            $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
            return response($requestObj, 404);
        }
        //si da una respuesta vacia es un error del servidor sino todo ok
        if (!$encargadoDB) {
            $mensaje = ['mensaje' => 'Error del servidor disculpe'];
            $mensajeJson = Collection::make($mensaje);
            $mensajeJson->toJson();

            $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
            return response($requestObj, 500);
        } else {
            $requestObj = new Request(array('ok' => true, "respuesta" => $encargadoDB));
            return response($requestObj, 200);
        }
    }

}
