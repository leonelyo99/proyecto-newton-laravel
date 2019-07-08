<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    //tabla a la que se refiere este modelo
    protected $table = 'tbmateriales';
    
    //atributos que no se guardan en una asignacion masiva
    protected $guarded = [
        'id','pedido_id'
    ];
    
    //atributos que si se pueden guardar en una asignacion masiva
    protected $fillable = [
        'material', 'cantidad', 'urgencia'
    ];
    
    //atributos que no se muestran en una peticion
    protected $hidden = [
        'pedido_id', 'created_at','updated_at'
    ];
}
