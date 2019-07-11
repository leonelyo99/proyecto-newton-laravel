<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePedidoRequest extends FormRequest
{
    
    protected $redirectAction="HelperController@error";


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tipo'=>'required|string',
            'user_id'=>'required|numeric',
            'encargado_id' => 'nullable|numeric',
            'nombre' => 'required|string',
            'descripcion'=>'nullable|string',
            'progreso'=>'nullable|numeric',
            'precio'=>'nullable|numeric'
        ];
    }
}
