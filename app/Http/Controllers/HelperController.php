<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//para acceso al disco
use Illuminate\Support\Facades\Storage;
//para crear un objeto json
use Illuminate\Support\Collection as Collection;
//para la respuesta
use Illuminate\Http\Response;

class HelperController extends Controller
{
    //============================================
    //En caso de error en la validaciones de los request
    //============================================
     public function error()
    {   
        $mensaje = ['mensaje' => 'campos invalidos'];
        $mensajeJson = Collection::make($mensaje);
        $mensajeJson->toJson();
            
        $requestObj = new Request(array('ok' =>false,"error"=>$mensajeJson));
        return response($requestObj, 404);
    }
    
    //=======================================
    //muestra la imagen
    //=======================================
    public function verImgen($name) {
        $file = Storage::disk('images')->get($name);
        return $file;
    }
}
