<?php

namespace App\Http\Middleware;

use Closure;

class CheckUsuario {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        //desencriptacion de datos
        $desencryptar = explode('LEO', $final, 2);

        $decrypted = Crypt::decryptString($desencryptar[0]);
        echo $decrypted;

        if (hash_equals($desencryptar[1], hash("sha256", $cadena))) {
            echo "¡Contraseña verificada!";
        }
    }

}
