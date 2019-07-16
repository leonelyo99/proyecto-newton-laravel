<?php

namespace App\Http\Controllers;

//peticiones http
use Illuminate\Http\Request;
//validaciones
use App\Http\Requests\CreatePedidoRequest; //crear pedido
use App\Http\Requests\UpdatePedidoRequest; //actualizar pedido
use App\Http\Requests\CreateImagenRequest; //crear imagen
//para crear un objeto json
use Illuminate\Support\Collection as Collection;
//para try y catch
Use Exception;
//para la respuesta
use Illuminate\Http\Response;
//subir imagenes
use Illuminate\Support\Facades\Storage; //guardar en el disco
use Illuminate\Support\Facades\File; //traer el archivo
//modelo de pedido y imagen
use App\Pedido;
use App\Imagen;

class PedidoController extends Controller {

    //=======================================
    //crear un pedido mediante el post
    //=======================================
    public function crearPedido(CreatePedidoRequest $request) {

        $pedido = new Pedido; //instancio el pedido

        $tipo = strtolower($request->input('tipo'));

        //si el tipo es empresa creo el empresa_id con el creador_id
        if ($tipo == 'empresa') {
            $pedido->empresa_id = $request->input('creador_id');
        } else if ($tipo == 'encargado') {//si el tipo es encargado creo el encargado_id con el creador_id
            $pedido->encargado_id = $request->input('creador_id');
        } else { //si no es empresa ni encargado mando un error
            return $this->Error('El tipo no es valido', 406);
        }

        //declaro las variables
        $pedido->user_id = $request->input('user_id');
        $pedido->nombre = trim(strip_tags($request->input('nombre')));
        $pedido->descripcion = trim(strip_tags($request->input('descripcion')));
        $pedido->progreso = $request->input('progreso');
        $pedido->precio = $request->input('precio');

        //lo guardo
        try {
            $pedido->save();
        } catch (Exception $e) {
            return $this->Error('Error del servidor', 500);
        }
        //si todo sale bien mando la respuesta
        $requestObj = new Request(array('ok' => true, "respuesta" => $pedido));
        return response($requestObj, 201);
    }

    //=======================================
    //modificar un pedido mediante el post
    //=======================================
    public function editar(UpdatePedidoRequest $request) {

        try { //traigo el pedido
            $pedidoDB = Pedido::findOrFail($request->input('id'));
        } catch (Exception $e) {
            return $this->Error('Error del servidor', 500);
        }
        //si esta vacio aviso
        if (!$pedidoDB) {
            return $this->Error('Id no encontrado en la base de datos', 404);
        } else {
            //declaro las variables
            $pedidoDB->nombre = trim(strip_tags($request->input('nombre')));
            $pedidoDB->descripcion = trim(strip_tags($request->input('descripcion')));
            $pedidoDB->progreso = trim($request->input('progreso'));
            $pedidoDB->precio = trim($request->input('precio'));
            //lo guardo
            try {
                $pedidoDB->save();
            } catch (Exception $e) {
                return $this->Error('Error del servidor', 500);
            }
            //si sale bien lo aviso
            $requestObj = new Request(array('ok' => true, "respuesta" => $pedidoDB));
            return response($requestObj, 201);
        }
    }

    //=======================================
    //borrar un pedido mediante el id
    //=======================================
    public function borrar($id) {
        try {
            $pedidoDB = Pedido::where('id', $id)->with('imagenes')->first();
        } catch (Exception $e) {
            return $this->Error('Error del servidor', 500);
        }
        if (!$pedidoDB) {
            return $this->Error('Id no encontrado en la base de datos', 404);
        } else {
            if ($pedidoDB->imagenes) {
                //borrado de imagenes vinculadas a este archivo
                $borrar = array();
                
                foreach ($pedidoDB->imagenes as $imagen) { //un foreach de todas las imagenes donde guarda el nombre en un array de imagenes
                    array_push($borrar, $imagen->imagen);
                }
                Storage::disk('images')->delete($borrar);
                //las borro de la base de datos
                Imagen::where('pedido_id', $id)->delete();
            }
            //lo borro de la base de datos
            Pedido::where('id', $id)->delete();

            $requestObj = new Request(array('ok' => true, "respuesta" => "borrado"));
            return response($requestObj, 201);
        }
    }

    //=======================================
    //mandar imagenes al post
    //=======================================
    public function imagen(CreateImagenRequest $request) {
        //instancio la imagen
        $imagen = new Imagen;
        //busco el pedido
        $comprovacionPedido = Pedido::where('id', $request->input('pedido_id'))->first();
        //si no esta lo aviso
        if (!$comprovacionPedido) {
            return $this->Error('Id no encontrado en la base de datos', 404);
        }

        //si esta el pedido guarda la imagen
        $image_path = $request->file('imagen');
        $imagen_guardar = time() . $image_path->getClientOriginalName();
        Storage::disk('images')->put($imagen_guardar, File::get($image_path));
        //la imagen la que mando el usuairo
        $imagen->imagen = $imagen_guardar;
        //el pedido_id el que mando el usuario comprobado
        $imagen->pedido_id = $request->input('pedido_id');
        //guardo la imagen
        try {
            $imagen->save();
        } catch (Exception $e) {
            return $this->Error('Error del servidor', 500);
        }
        //si todo sale bien mando la respuesta
        $requestObj = new Request(array('ok' => true, "respuesta" => $imagen));
        return response($requestObj, 201);
    }

    //===============================================
    //funciones
    //===============================================

    private function Error($mensajeMandar, $error) {
        $mensaje = ['mensaje' => $mensajeMandar];
        $mensajeJson = Collection::make($mensaje);
        $mensajeJson->toJson();

        $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
        return response($requestObj, $error);
    }

}
