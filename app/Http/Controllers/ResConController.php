<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateContraRequest;
use App\Http\Requests\CreateContraCambiarRequest;
//para el mail
use Illuminate\Support\Facades\Mail;
use App\Mail\ResContra;
//para crear un objeto json
use Illuminate\Support\Collection as Collection;
//para try y catch
Use Exception;
//tipos
use App\User;
use App\Encargado;
use App\Empresa;
use App\Codigo;

class ResConController extends Controller
{
    public function codigo(CreateContraRequest $request){
        
        $tipo = trim(strip_tags($request->input('tipo')));
        $usuario = trim(strip_tags($request->input('usuario')));
        $email = trim(strip_tags($request->input('email')));
        
        //===================================================
        //codigo de usuario
        //===================================================
        if($tipo == "usuario"){
            try {
               $usuarioDB = User::where('usuario', $usuario)->orWhere('email', $usuario)->first();
            } catch (Exception $e) {
                return $this->Error('Error del servidor', 500);
            }
            //si da una respuesta vacia es un error del servidor sino todo ok
            if (!$usuarioDB) {
                return $this->Error('Mail o Usuario no encontrado', 404);
            }else{
                $codigoExistente = Codigo::where('user_id', $usuarioDB->id)->first();
                if($codigoExistente){
                    $codigoExistente->delete();
                }
                
                $codigo = New Codigo;
                $codigo->user_id = $usuarioDB->id;
                $codigo->codigo = time();
                $codigo->save();
                
                //envio de mail       
                try {
                   Mail::to($email)->send(new ResContra($codigo->codigo));
                } catch (Exception $e) {
                    return $this->Error('Error al envio de mail', 500);
                }
                $requestObj = new Request(array('ok' => true, "respuesta" => "Mail enviado", "usuario"=>$usuario));
                return response($requestObj, 200);
            }
        }
        
        //===================================================
        //codigo de encargado
        //===================================================
        if($tipo == "encargado"){
            try {
                $encargadoDB = Encargado::where('usuario', $usuario)->first();
            } catch (Exception $e) {
                return $this->Error('Error del servidor', 500);
            }
            //si da una respuesta vacia es un error del servidor sino todo ok
            if (!$encargadoDB) {
                return $this->Error('Encargado no encontrado', 404);
            }else{
                $codigoExistente = Codigo::where('encargado_id', $encargadoDB->id)->first();
                if($codigoExistente){
                    $codigoExistente->delete();
                }
                
                $codigo = New Codigo;
                $codigo->encargado_id = $encargadoDB->id;
                $codigo->codigo = time();
                $codigo->save();
                
                //envio de mail       
                try {
                   Mail::to($email)->send(new ResContra($codigo->codigo));
                } catch (Exception $e) {
                    return $this->Error('Error al envio de mail', 500);
                }
                $requestObj = new Request(array('ok' => true, "respuesta" => "Mail enviado", "usuario"=>$usuario));
                return response($requestObj, 200);
            }
        }
        
        //===================================================
        //codigo de empresa
        //===================================================
        if($tipo == "empresa"){
            try {
                $empresaDB = Empresa::where('documento', $usuario)->first();
            } catch (Exception $e) {
                return $this->Error('Error del servidor', 500);
            }
            //si da una respuesta vacia es un error del servidor sino todo ok
            if (!$empresaDB) {
                return $this->Error('Documento no encontrado', 404);
            }else{
                $codigoExistente = Codigo::where('empresa_id', $empresaDB->id)->first();
                if($codigoExistente){
                    $codigoExistente->delete();
                }
                
                $codigo = New Codigo;
                $codigo->empresa_id = $empresaDB->id;
                $codigo->codigo = time();
                $codigo->save();
                
                //envio de mail       
                try {
                   Mail::to($email)->send(new ResContra($codigo->codigo));
                } catch (Exception $e) {
                    return $this->Error('Error al envio de mail', 500);
                }
                $requestObj = new Request(array('ok' => true, "respuesta" => "Mail enviado", "documento"=>$usuario));
                return response($requestObj, 200);
            }
        }
    }
    
    public function ResConra(CreateContraCambiarRequest $request){
        $usuario = trim(strip_tags($request->input('usuario')));
        $tipo = trim(strip_tags($request->input('tipo')));
        $codigo = trim(strip_tags($request->input('codigo')));
        $contraseña = trim(strip_tags($request->input('contraseña')));
        
        //===================================================
        //codigo de usuario
        //===================================================
        if($tipo == "usuario"){
            try {
               $usuarioDB = User::where('usuario', $usuario)->orWhere('email', $usuario)->first();
            } catch (Exception $e) {
                return $this->Error('Error del servidor', 500);
            }
            //si da una respuesta vacia es un error del servidor sino todo ok
            if (!$usuarioDB) {
                return $this->Error('Mail o Usuario no encontrado', 404);
            }else{
                $codigoExistente = Codigo::where('user_id', $usuarioDB->id)->first();
          
                if($codigoExistente and $codigoExistente->codigo == $codigo){
                    $usuarioDB->password = $contraseña;
                    $usuarioDB->update();
                    $codigoExistente->delete();
                }else{                
                    return $this->Error('Codigo invalido', 403);
                }
                $requestObj = new Request(array('ok' => true, "respuesta" => "Contraseña cambiada"));
                return response($requestObj, 200);
            }
        }
        
        //===================================================
        //codigo de encargado
        //===================================================
        if($tipo == "encargado"){
            try {
                $encargadoDB = Encargado::where('usuario', $usuario)->first();
            } catch (Exception $e) {
                return $this->Error('Error del servidor', 500);
            }
            //si da una respuesta vacia es un error del servidor sino todo ok
            if (!$encargadoDB) {
                return $this->Error('Encargado no encontrado', 404);
            }else{
                $codigoExistente = Codigo::where('encargado_id', $encargadoDB->id)->first();
                
                if($codigoExistente and $codigoExistente->codigo == $codigo){       
                    $encargadoDB->password = $contraseña;
                    $encargadoDB->update();
                    $codigoExistente->delete();
                }else{                
                    return $this->Error('Codigo invalido', 403);
                }
                $requestObj = new Request(array('ok' => true, "respuesta" => "Contraseña cambiada"));
                return response($requestObj, 200);
            }
        }
        
        //===================================================
        //codigo de empresa
        //===================================================
        if($tipo == "empresa"){
            try {
                $empresaDB = Empresa::where('documento', $usuario)->first();
            } catch (Exception $e) {
                return $this->Error('Error del servidor', 500);
            }
            //si da una respuesta vacia es un error del servidor sino todo ok
            if (!$empresaDB) {
                return $this->Error('Documento no encontrado', 404);
            }else{
                $codigoExistente = Codigo::where('empresa_id', $empresaDB->id)->first();
                
                if($codigoExistente and $codigoExistente->codigo == $codigo){       
                    $empresaDB->password = $contraseña;
                    $empresaDB->update();
                    $codigoExistente->delete();
                }else{                
                    return $this->Error('Codigo invalido', 403);
                }
                $requestObj = new Request(array('ok' => true, "respuesta" => "Contraseña cambiada"));
                return response($requestObj, 200);
            }
        }
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
