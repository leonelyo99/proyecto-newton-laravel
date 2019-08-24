<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\HelperController; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class Check {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $arrayRoles) { 
        
        $roles = explode('1', $arrayRoles, 2); //tipos aceptados
        $token = $request->header('token'); //token
        $fecha = localtime(); //fechaa actual
        
        //desencriptacion de datos
        $desencryptar = explode('LEO', $token, 2);
        $decrypted = Crypt::decryptString($desencryptar[0]);
        
        //comprobacion
        if($decrypted==$fecha[5]){        
            $resultado=$this->tipo($roles, $desencryptar[1]);
            if ($resultado===true) {
                return $next($request);
            }else{
                return $reject = (new HelperController)->errorMidleware('No estas autorizado'); 
            }
        }else{
            return $reject = (new HelperController)->errorMidleware('Expiro el token');
        }
    }
    
    
    public function tipo($roles, $desencryptar){
        $termino = false;
        foreach ($roles as $role) {
            $resultado = hash_equals($desencryptar, hash("sha256", $role));
            if (hash_equals($desencryptar, hash("sha256", $role))) {
                $termino = true;
            }
        }
        return $termino;
    }

}
