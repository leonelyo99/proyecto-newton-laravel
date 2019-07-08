<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    //tabla a la que se refiere este modelo
    protected $table = 'tbempresas';
    
    //atributos que no se guardan en una asignacion masiva
    protected $guarded = [
        'id','estado','role','documento', 'password'
    ];
    
    //atributos que si se pueden guardar en una asignacion masiva
    protected $fillable = [
        'nombre', 'apellido', 'nombreEmpresa', 'imagen','ubicacion','provincia','pais'
    ];
    
    //atributos que no se muestran en una peticion
    protected $hidden = [
        'password', 'created_at', 'updated_at', 'estado',
    ];
    
}
