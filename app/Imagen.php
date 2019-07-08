<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Imagen extends Model
{
    //==================================================
    //Relaciones
    //==================================================
    

    //==================================================
    //configuracion de lo que se muestra lo que no 
    //y lo que se agrega y lo que no
    //==================================================
    
    //tabla a la que se refiere este modelo
    protected $table = 'tbimagenes';
    
    //atributos que no se guardan en una asignacion masiva
    protected $guarded = [
        'id','pedido_id'
    ];
    
    //atributos que si se pueden guardar en una asignacion masiva
    protected $fillable = [
        'imagen'
    ];
    
    //atributos que no se muestran en una peticion
    protected $hidden = [
        'pedido_id', 'created_at','updated_at'
    ];
}
