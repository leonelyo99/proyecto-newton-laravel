<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Encargado extends Model
{
    
    //==================================================
    //Relaciones
    //==================================================
    
    public function empresa(){ //caracteristicas de la empresa asociada a este encargado
        return $this->belongsTo(Empresa::class); //este encargado pertenece a una empresa
    }
    
    public function pedidos(){ //un encargado muchos pedidos con las imagenes de este
        $pedidos = $this->hasMany(Pedido::class)->with('imagenes')->with('usuario');
        return $pedidos;
    }
    
    //==================================================
    //configuracion de lo que se muestra lo que no 
    //y lo que se agrega y lo que no
    //==================================================
    
    //tabla a la que se refiere este modelo
    protected $table = 'tbencargados';
    
    //atributos que no se guardan en una asignacion masiva
    protected $guarded = [
        'id','estado','role','password'
    ];
    
    //atributos que si se pueden guardar en una asignacion masiva
    protected $fillable = [
        'empresa_id', 'usuario', 'nombre','apellido','imagen'
    ];
    
    //atributos que no se muestran en una peticion
    protected $hidden = [
        'password','created_at', 'updated_at', 'estado'
    ];
}
