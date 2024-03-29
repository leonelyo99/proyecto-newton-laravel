<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
//login
use App\Http\Requests\LoginRequest;
//para crear un objeto json
use Illuminate\Support\Collection as Collection;
//modelos
use App\User;
use App\Encargado;
use App\Empresa;

class LoginController extends Controller {

//    es parte del conjunto de funciones hash criptográficas SHA-2, diseñado por la 
//    Agencia de Seguridad Nacional de los EE. UU. (NSA) y publicado en 2001 por el 
//    NIST como un Estándar Federal de Procesamiento de la Información (FIPS) de los EE. UU. 

//    public function __constructor(){
//        $this->middleware('CheckUsuario', ['except' => ['todo']]);    
//    }
    
    public function login(LoginRequest $request) {

        $tipo = trim($request->input('tipo'));
        $usuario = trim($request->input('usuario'));
        $password = trim($request->input('password'));

        //=========================================
        //Usuario
        //=========================================
        if ($tipo == "usuario") {
            try {
                $usuarioDB = User::where('usuario', $usuario)->orWhere('email', $usuario)->first();
            } catch (Exception $e) {
                return $this->Error('Error del servidor', 500);
            }
            //si da una respuesta vacia es un error del servidor sino todo ok
            if (!$usuarioDB) {
                return $this->Error('Mail o Usuario no encontrado', 404);
            } else {
                return $this->ComproContraseña($usuarioDB, $password, $usuarioDB);
            }
        }

        //=========================================
        //Empresa
        //=========================================
        if ($tipo == "empresa") {
            try {
                $empresaDB = Empresa::where('documento', $usuario)->first();
            } catch (Exception $e) {
                return $this->Error('Error del servidor', 500);
            }
            //si da una respuesta vacia es un error del servidor sino todo ok
            if (!$empresaDB) {
                return $this->Error('Documento no encontrado', 404);
            } else {
                return $this->ComproContraseña($empresaDB, $password, $empresaDB);
            }
        }

        //=========================================
        //Encargado
        //=========================================
        if ($tipo == "encargado") {
            try {
                $encargadoDB = Encargado::where('usuario', $usuario)->first();
            } catch (Exception $e) {
                return $this->Error('Error del servidor', 500);
            }
            //si da una respuesta vacia es un error del servidor sino todo ok
            if (!$encargadoDB) {
                return $this->Error('Encargado no encontrado', 404);
            } else {
                return $this->ComproContraseña($encargadoDB, $password, $encargadoDB);
            }
        } else {
            $requestObj = new Request(array('ok' => true, "respuesta" => "No encotrado"));
            return response($requestObj, 404);
        }
    }

    public function todo() {
        
        $usuariosDB = User::get();
        $empresasDB = Empresa::get();
        $encargadosDB = Encargado::get();

        $todo = array();
        foreach ($usuariosDB as $usuarioDB) { 
            array_push($todo, $usuarioDB);
        }
        foreach ($empresasDB as $empresaDB) {
            array_push($todo, $empresaDB);
        }
        foreach ($encargadosDB as $encargadoDB) { 
            array_push($todo, $encargadoDB);
        }

        $requestObj = new Request(array('ok' => true, "respuesta" => $todo));
        return response($requestObj, 200);
    }


    private function Error($mensajeMandar, $error) {
        $mensaje = ['mensaje' => $mensajeMandar];
        $mensajeJson = Collection::make($mensaje);
        $mensajeJson->toJson();

        $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
        return response($requestObj, $error);
    }

    private function cifrar($role) {
        //fecha
        $fecha = localtime();
        //encripto dia
        $encrypted = Crypt::encryptString($fecha[5]);
        //encripto role
        $hashed_role = hash("sha256", $role);
        //uno todo y lo mando
        $final = $encrypted . 'LEO' . $hashed_role;

        return $final;
    }
    
    private function ComproContraseña($usuarioDB, $password, $respuesta) {
        if (hash_equals($usuarioDB->password, $password)) {
            if ($usuarioDB->estado === 'false') {
                $usuarioDB->estado = 'true';
                $usuarioDB->save();
            }
            
            $cifrado = $this->cifrar($usuarioDB->role);

            $requestObj = new Request(array('ok' => true, "respuesta" => $respuesta, "token" => $cifrado));
            return response($requestObj, 200);
        } else {
            $requestObj = new Request(array('ok' => false, "respuesta" => "La contraseña no coincide"));
            return response($requestObj, 403);
        }
    }

}
