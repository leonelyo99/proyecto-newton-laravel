<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEncargadoRequest extends FormRequest {

    protected $redirectAction = "HelperController@error";

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'empresa_id' => 'required|numeric',
            'nombre' => 'required|required',
            'apellido' => 'required|required',
            'usuario' => 'required|string',
            'img' => 'nullable|image',
            'password' => 'required|string'
        ];
    }

}
