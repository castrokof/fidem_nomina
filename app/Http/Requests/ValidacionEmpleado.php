<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidacionEmpleado extends FormRequest
{
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
            'papellido'  => 'required|max:100',
            'pnombre'  => 'required|max:100',
            'documento' => 'numeric|required|min:10000|max:9999999999',
            'tipo_documento' => 'required',
            'email' => 'required',
            'celular' => 'required',
            'ips' => 'required',
            'position' => 'required',
            'eps' => 'required',
            'arl' => 'required',
            'afp' => 'required',
            'fc' => 'required',
            'user_id' => 'required',
            'type_contrat' => 'required',
            'type_salary' => 'required',
            'activo' => 'required'
            ];
    }
}
