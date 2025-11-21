<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidacionMedicamentoControlado extends FormRequest
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
            'nombre' => 'required|max:200|unique:medicamentos_controlados,nombre,' . $this->route('id'),
            'descripcion' => 'nullable',
            'activo' => 'nullable|boolean'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'nombre.required' => 'El nombre del medicamento es obligatorio',
            'nombre.max' => 'El nombre no puede exceder 200 caracteres',
            'nombre.unique' => 'Este medicamento ya está registrado'
        ];
    }
}
