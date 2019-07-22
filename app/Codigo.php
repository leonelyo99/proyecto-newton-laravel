<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Codigo extends Model
{
    //==================================================
    //Relaciones
    //==================================================
    
    
    
    //==================================================
    //configuracion de lo que se muestra lo que no 
    //y lo que se agrega y lo que no
    //==================================================
    
    //tabla a la que se refiere este modelo
    protected $table = 'tbcodigos';
    
    //atributos que no se guardan en una asignacion masiva
    protected $guarded = [
        'id','empresa_id','encargado_id','user_id', 'codigo'
    ];
    
    //atributos que si se pueden guardar en una asignacion masiva
    protected $fillable = [
        
    ];
    
    //atributos que no se muestran en una peticion
    protected $hidden = [
        'id', 'empresa_id', 'encargado_id', 'user_id', 'codigo'
    ];
    
}
