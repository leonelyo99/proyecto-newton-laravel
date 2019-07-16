<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    
    //==================================================
    //Relaciones
    //==================================================
    
    public function pedidos(){ //un usuario muchos pedidos con las imagenes de este
        $pedidos = $this->hasMany(Pedido::class)->with('imagenes');
        return $pedidos;
    }    
    
    //==================================================
    //configuracion de lo que se muestra lo que no 
    //y lo que se agrega y lo que no
    //==================================================
    
    //tabla a la que se refiere este modelo
    protected $table = 'tbusuarios';
    
    //atributos que no se guardan en una asignacion masiva
    protected $guarded = [
        'id','estado','role', 'password'
    ];
    
    //atributos que si se guardan en una asignacion masiva
    protected $fillable = [
        'usuario', 'email', 'img'
    ];

    //atributos que no se muestran en una peticion
    protected $hidden = [
        'password', 'remember_token','created_at', 'updated_at', 'email_verified_at', 'estado'
    ];

    //verificacion de email
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
