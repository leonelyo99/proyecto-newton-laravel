<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmpresaRequest extends FormRequest {

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
        'nombre'=>'required|string',
        'apellido'=>'required|string',
        'nombreEmpresa' => 'required|string',
        'password' => 'required',
        'ubicacion' => 'required|string',
        'provincia' => 'required|string',
        'pais' => 'required|string',
        'img' => 'image',
        ];
    }

}
