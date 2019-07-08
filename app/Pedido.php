<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    //==================================================
    //Relaciones
    //==================================================
    public function imagenes(){ //un pedido muchas imagenes
        return $this->hasMany(Imagen::class);
    }
    
    public function materiales(){ //un pedido muchos materiales
        return $this->hasMany(Material::class);
    }
    
    public function usuario(){ //un pedido un usuario
        return $this->belongsTo(User::class,'user_id'); //pertenece a un usuario
    }
    
    //==================================================
    //configuracion de lo que se muestra lo que no 
    //y lo que se agrega y lo que no
    //==================================================
    
    //tabla a la que se refiere este modelo
    protected $table = 'tbpedidos';
    
    //atributos que no se guardan en una asignacion masiva
    protected $guarded = [
        'id','estado','role','user_id','encargado_id','empresa_id'
    ];
    
    //atributos que si se pueden guardar en una asignacion masiva
    protected $fillable = [
        'nombre', 'descripcion', 'progreso', 'precio'
    ];
    
    //atributos que no se muestran en una peticion
    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
