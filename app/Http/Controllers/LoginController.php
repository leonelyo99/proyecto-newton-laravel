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

        $usuario = trim($request->input('usuario'));
        $password = trim($request->input('password'));
        
        $usuariosDB = User::where('usuario', $usuario)->orWhere('email', $usuario)->first();
        $empresaDB = Empresa::where('documento', $usuario)->first();
        $encargadoDB = Encargado::where('usuario', $usuario)->first();

        if($usuariosDB){
            return "usuario";
        }
        if($empresaDB){
            return "empresa";
        }
        if($encargadoDB){
            return "encargado";
        }
        
        if (hash_equals($usuariosDB->password, hash ("sha256" , $password ))) {     
            if($usuariosDB->estado==='false'){
                $usuariosDB->estado = 'true';
                $usuariosDB->save();
            }
            
            $fecha = localtime();
            $encrypted = Crypt::encryptString($fecha[5]);
            
            $hashed_password = hash ("sha256" , $usuariosDB->role );
            $final = $encrypted.'LEO'.$hashed_password;
            
            $requestObj = new Request(array('ok' => true, "respuesta" => $final));
            return response($requestObj, 201);
        }
        

//encriptacion de datos
        $cadena = "usuario";
        
        $encrypted = Crypt::encryptString('12/23/21');
        $hashed_password = hash ("sha256" , $cadena );
        $final = $encrypted.'LEO'.$hashed_password;
        
         
        
        //desencriptacion de datos
        $desencryptar = explode ( 'LEO', $final, 2 );
        
        $decrypted = Crypt::decryptString($desencryptar[0]);
         echo $decrypted;
        
        if (hash_equals($desencryptar[1], hash ("sha256" , $cadena ))) {
            echo "¡Contraseña verificada!";
        }
    }

}
