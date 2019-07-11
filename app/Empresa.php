<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    //==================================================
    //Relaciones
    //==================================================
    
    public function encargados(){ //encargados de la empresa
        return $this->hasMany(Encargado::class)->with('pedidos'); //este encargado pertenece a la
    }
    public function encargadoSinPedido(){ //encargados de la empresa
        return $this->hasMany(Encargado::class); //este encargado pertenece a la
    }
    public function pedidos(){ //pedidos de la empresa
        return $this->hasMany(Pedido::class)->with('usuario'); //este encargado pertenece a la
    }
    
    
    //==================================================
    //configuracion de lo que se muestra lo que no 
    //y lo que se agrega y lo que no
    //==================================================
    
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
