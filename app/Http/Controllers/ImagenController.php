<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//para acceso al disco
use Illuminate\Support\Facades\Storage;
//para crear un objeto json
use Illuminate\Support\Collection as Collection;
//para la respuesta
use Illuminate\Http\Response;
//para try y catch
Use Exception;

class ImagenController extends Controller {

    //=======================================
    //muestra la imagen
    //=======================================
    public function verImgen($name) {

        try {
            $file = Storage::disk('images')->get($name); //busco el archivo en el disco
        } catch (Exception $ex) {
            $mensaje = ['mensaje' => 'Algo sucedio'];
            $mensajeJson = Collection::make($mensaje);
            $mensajeJson->toJson();

            $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
            return response($requestObj, 500);
        }

        if (!$file) {
            $mensaje = ['mensaje' => 'Imagen no encontrada'];
            $mensajeJson = Collection::make($mensaje);
            $mensajeJson->toJson();

            $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
            return response($requestObj, 404);
        }

        //si todo sale bien mando la respuesta
        return response($file, 200);
    }

}
