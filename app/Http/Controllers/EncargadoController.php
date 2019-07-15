<?php

namespace App\Http\Controllers;

//peticiones http
use Illuminate\Http\Request;
//validacion del request para crear encargado
use App\Http\Requests\CreateEncargadoRequest;
//validacion del request para actualizar encargado
use App\Http\Requests\UpdateEncargadoRequest;
//subir imagenes
use Illuminate\Support\Facades\Storage; //guardar en el disco
use Illuminate\Support\Facades\File; //traer el archivo
//para crear un objeto json
use Illuminate\Support\Collection as Collection;
//para try y catch
Use Exception;
//modelo de encargado
use App\User;
use App\Encargado;
use App\Empresa;

class EncargadoController extends Controller {

    //=======================================
    //Crea un encargado mediante el post
    //=======================================
    public function crearEncargado(CreateEncargadoRequest $request) {
        //instancio el encargado
        $encargado = new Encargado;

        //declaro estas variables en el modelo del encargado
        $encargado->empresa_id = $request->input('empresa_id');
        $encargado->nombre = strtoupper(trim(strip_tags($request->input('nombre')))); //limpio los espacios, limpio xss, lo paso a mayusculas
        $encargado->apellido = strtoupper(trim(strip_tags($request->input('apellido'))));
        $encargado->usuario = strtoupper(trim(strip_tags($request->input('usuario'))));
        $encargado->password = trim($request->input('password'));
        $encargado->role = "encargado";
        $encargado->estado = "true";

        //compruebo si esta duplicado y mando el response
        $comprovacionEncargado = Encargado::where('usuario', $encargado->usuario)->first();
        
        if ($comprovacionEncargado) {
            $mensaje = ['mensaje' => 'Campo usuario se encuentra duplicado'];
            $mensajeJson = Collection::make($mensaje);
            $mensajeJson->toJson();

            $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
            return response($requestObj, 409);
        }
        
        //si la peticion tiene una imagen tomo el archivo lo guardo con otro nombre
        //en el disco imagen y paso el nombre de este al encargado si no dejo la imagen null
        if ($request->hasFile('img')) {
            $image_path = $request->file('img');

            $imagen_guardar = time() . $image_path->getClientOriginalName();
            Storage::disk('images')->put($imagen_guardar, File::get($image_path));
            $encargado->img = $imagen_guardar;
        } else {
            $encargado->img = NULL;
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
    //Mando encargado con pedido,imagen y usuario perteneciente
    //=======================================
    public function encargado($id) {

        //busca el encargado si no lo encuentra avisa 
        try {
            $encargadoDB = Encargado::where('id', $id)->where('estado', 'true')->with('empresa')->with('pedidos')->first();
        } catch (Exception $e) {
            $mensaje = ['mensaje' => 'Error del servidor disculpe'];
            $mensajeJson = Collection::make($mensaje);
            $mensajeJson->toJson();

            $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
            return response($requestObj, 500);
        }
        //si da una respuesta vacia no lo encontro
        if (!$encargadoDB) {
            $mensaje = ['mensaje' => 'Id no encontrado en la base de datos'];
            $mensajeJson = Collection::make($mensaje);
            $mensajeJson->toJson();

            $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
            return response($requestObj, 404);
        } else {
            $requestObj = new Request(array('ok' => true, "respuesta" => $encargadoDB));
            return response($requestObj, 200);
        }
    }

    //=======================================
    //borrar un encargado mediante el id
    //=======================================
    public function borrar($id) {
        try {
            $encargadoDB = Encargado::where('id', $id)->where('estado', 'true')->first();
        } catch (Exception $e) {
            $mensaje = ['mensaje' => 'Error del servidor disculpe'];
            $mensajeJson = Collection::make($mensaje);
            $mensajeJson->toJson();

            $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
            return response($requestObj, 500);
        }
        if (!$encargadoDB) {
            $mensaje = ['mensaje' => 'Id no encontrado en la base de datos'];
            $mensajeJson = Collection::make($mensaje);
            $mensajeJson->toJson();

            $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
            return response($requestObj, 404);
        } else {
            $encargadoDB->estado = "false";
            $encargadoDB->save();

            $requestObj = new Request(array('ok' => true, "respuesta" => "borrado"));
            return response($requestObj, 201);
        }
    }

    //=======================================
    //modificar un usuario mediante el post
    //=======================================
    public function editar(UpdateEncargadoRequest $request) {

        try {
            $encargadoDB = Encargado::findOrFail($request->input('id'));
        } catch (Exception $e) {
            $mensaje = ['mensaje' => 'Error del servidor disculpe'];
            $mensajeJson = Collection::make($mensaje);
            $mensajeJson->toJson();

            $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
            return response($requestObj, 500);
        }

        if (!$encargadoDB) { //si el encargado esta vacio mando el response sino
            $mensaje = ['mensaje' => 'Id no encontrado en la base de datos'];
            $mensajeJson = Collection::make($mensaje);
            $mensajeJson->toJson();

            $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
            return response($requestObj, 404);
        } else {
            
            //si la request tiene un input usuario reviso que no este duplicado
            if ($request->input('usuario')) {
                
                $encargadoDB->usuario = strtoupper(trim(strip_tags($request->input('usuario')))); //limpio los espacios, limpio xss, lo paso a mayusculas

                //compruebo si esta duplicado y mando el response
                $comprovacionEncargado = Encargado::where('usuario', $encargadoDB->usuario)->first();
        
                if ($comprovacionEncargado) {
                    $mensaje = ['mensaje' => 'Campo usuario se encuentra duplicado'];
                    $mensajeJson = Collection::make($mensaje);
                    $mensajeJson->toJson();

                    $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
                    return response($requestObj, 409);
                }
            }
            
            //declaro las otras variables
            $encargadoDB->nombre = strtoupper(trim(strip_tags($request->input('nombre')))); //limpio los espacios, limpio xss, lo paso a mayusculas
            $encargadoDB->apellido = strtoupper(trim(strip_tags($request->input('apellido'))));
            $encargadoDB->password = trim($request->input('password'));

            //si tiene imagen la guardo sino sigo
            
            if ($request->hasFile('img')) {

                if ($encargadoDB->img) {
                    $file = Storage::disk('images')->delete($encargadoDB->img);
                };

                $image_path = $request->file('img');

                $imagen_guardar = time() . $image_path->getClientOriginalName();
                Storage::disk('images')->put($imagen_guardar, File::get($image_path));
                $encargadoDB->img = $imagen_guardar;
            }
            //lo guardo
            try {
                $encargadoDB->save();
            } catch (Exception $e) {
                $mensaje = ['mensaje' => 'Error al guardar intentelo mas tarde'];
                $mensajeJson = Collection::make($mensaje);
                $mensajeJson->toJson();

                $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
                return response($requestObj, 500);
            }

            $requestObj = new Request(array('ok' => true, "respuesta" => $encargadoDB));
            return response($requestObj, 201);
        }
    }

}
