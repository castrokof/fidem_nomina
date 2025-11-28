<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidacionMedicamentoControladoMovimiento extends FormRequest
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
        $rules = [
            'medicamento_controlado_id' => 'required|exists:medicamentos_controlados,id',
            'fecha' => 'required|date',
            'tipo_movimiento' => 'required|in:entrada,salida'
        ];

        // Validaciones específicas para ENTRADA
        if ($this->tipo_movimiento == 'entrada') {
            $rules['proveedor'] = 'required|max:200';
            $rules['numero_factura'] = 'nullable|max:100';
            $rules['fecha_vencimiento'] = 'nullable|date|after:today';
            $rules['registro_invima'] = 'nullable|max:100';
            $rules['lote'] = 'nullable|max:100';
            $rules['observaciones'] = 'nullable|max:1000';
            $rules['entrada'] = 'required|integer|min:1';
        }

        // Validaciones específicas para SALIDA
        if ($this->tipo_movimiento == 'salida') {
            $rules['nombre_paciente'] = 'required|max:200';
            $rules['cedula_paciente'] = 'required|max:50';
            $rules['numero_formula_control'] = 'nullable|max:100';
            $rules['salida'] = 'required|integer|min:1';
            $rules['foto_formula'] = 'nullable|image|mimes:jpeg,png,jpg|max:5120'; // 5MB max
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'medicamento_controlado_id.required' => 'Debe seleccionar un medicamento',
            'medicamento_controlado_id.exists' => 'El medicamento seleccionado no existe',
            'fecha.required' => 'La fecha es obligatoria',
            'fecha.date' => 'La fecha no es válida',
            'tipo_movimiento.required' => 'El tipo de movimiento es obligatorio',
            'tipo_movimiento.in' => 'El tipo de movimiento debe ser entrada o salida',
            'proveedor.required' => 'El proveedor es obligatorio para entradas',
            'fecha_vencimiento.date' => 'La fecha de vencimiento no es válida',
            'fecha_vencimiento.after' => 'La fecha de vencimiento debe ser posterior a hoy',
            'registro_invima.max' => 'El registro INVIMA no puede exceder 100 caracteres',
            'lote.max' => 'El lote no puede exceder 100 caracteres',
            'observaciones.max' => 'Las observaciones no pueden exceder 1000 caracteres',
            'entrada.required' => 'La cantidad de entrada es obligatoria',
            'entrada.min' => 'La cantidad debe ser mayor a 0',
            'nombre_paciente.required' => 'El nombre del paciente es obligatorio para salidas',
            'cedula_paciente.required' => 'La cédula del paciente es obligatoria para salidas',
            'salida.required' => 'La cantidad de salida es obligatoria',
            'salida.min' => 'La cantidad debe ser mayor a 0',
            'foto_formula.image' => 'El archivo debe ser una imagen',
            'foto_formula.mimes' => 'La imagen debe ser JPG, JPEG o PNG',
            'foto_formula.max' => 'La imagen no puede superar 5MB'
        ];
    }
}
