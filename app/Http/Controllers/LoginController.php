<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
//login
use App\Http\Requests\LoginRequest;
//modelos
use App\User;
use App\Encargado;
use App\Empresa;

class LoginController extends Controller {

//    es parte del conjunto de funciones hash criptográficas SHA-2, diseñado por la 
//    Agencia de Seguridad Nacional de los EE. UU. (NSA) y publicado en 2001 por el 
//    NIST como un Estándar Federal de Procesamiento de la Información (FIPS) de los EE. UU. 

    public function login(LoginRequest $request) {

        
        $tipo = trim($request->input('tipo'));
        $usuario = trim($request->input('usuario'));
        $password = trim($request->input('password'));

        if ($tipo == "usuario") {

            try {
                $usuarioDB = User::where('usuario', $usuario)->orWhere('email', $usuario)->first();
            } catch (Exception $e) {
                $mensaje = ['mensaje' => 'Error del servidor disculpe'];
                $mensajeJson = Collection::make($mensaje);
                $mensajeJson->toJson();

                $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
                return response($requestObj, 500);
            }

            //si da una respuesta vacia es un error del servidor sino todo ok
            if (!$usuarioDB) {
                $mensaje = ['mensaje' => 'Mail o Usuario no encontrado'];
                $mensajeJson = Collection::make($mensaje);
                $mensajeJson->toJson();

                $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
                return response($requestObj, 404);
            } else {

                if (hash_equals($usuarioDB->password, hash("sha256", $password))) {
                    if ($usuarioDB->estado === 'false') {
                        $usuarioDB->estado = 'true';
                        $usuarioDB->save();
                    }
                    return login::cifrar();
                    $requestObj = new Request(array('ok' => true, "respuesta" => "dawd"));
                    return response($requestObj, 200);
                }
                echo 'No coinciden las contraseñas';
            }
        }
        
        if ($tipo == "empresa") {
            $empresaDB = Empresa::where('documento', $usuario)->first();
        }
        if ($tipo == "encargado") {
            $encargadoDB = Encargado::where('usuario', $usuario)->first();
        }
        return;


        if (hash_equals($usuariosDB->password, hash("sha256", $password))) {
            if ($usuariosDB->estado === 'false') {
                $usuariosDB->estado = 'true';
                $usuariosDB->save();
            }

            $fecha = localtime();
            $encrypted = Crypt::encryptString($fecha[5]);

            $hashed_password = hash("sha256", $usuariosDB->role);
            $final = $encrypted . 'LEO' . $hashed_password;

            $requestObj = new Request(array('ok' => true, "respuesta" => $final));
            return response($requestObj, 201);
        }


//encriptacion de datos
        $cadena = "usuario";

        $encrypted = Crypt::encryptString('12/23/21');
        $hashed_password = hash("sha256", $cadena);
        $final = $encrypted . 'LEO' . $hashed_password;



        //desencriptacion de datos
        $desencryptar = explode('LEO', $final, 2);

        $decrypted = Crypt::decryptString($desencryptar[0]);
        echo $decrypted;

        if (hash_equals($desencryptar[1], hash("sha256", $cadena))) {
            echo "¡Contraseña verificada!";
        }
    }

    public function cifrar() {
        return "holaa";
    }

}
