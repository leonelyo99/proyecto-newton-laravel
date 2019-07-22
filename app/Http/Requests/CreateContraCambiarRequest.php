<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateContraCambiarRequest extends FormRequest
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
            'codigo'=>'string|required',
            'tipo' => 'string|required',
            'usuario' => 'string|required',
            'contraseña' => 'string|required',
        ];
    }
}
