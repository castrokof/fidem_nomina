<?php

namespace App\Services;

use App\ConsentimientoInformado;
use Carbon\Carbon;
use PDF;

class PdfConsentimientoService
{
    /**
     * Genera el PDF del consentimiento informado
     *
     * @param ConsentimientoInformado $consentimiento
     * @return string Ruta del archivo PDF generado
     */
    public function generar(ConsentimientoInformado $consentimiento)
    {
        $profesional = $consentimiento->profesional;

        // Preparar variables para renderizar en la plantilla
        $variables = [
            'paciente_nombre'     => $consentimiento->paciente_nombre,
            'paciente_cedula'     => $consentimiento->paciente_cedula,
            'paciente_tipo_doc'   => $consentimiento->paciente_tipo_doc,
            'paciente_edad'       => $consentimiento->paciente_edad ?? '',
            'paciente_genero'     => $consentimiento->paciente_genero ?? '',
            'profesional_nombre'  => $consentimiento->profesional_nombre,
            'registro_medico'     => $profesional ? $profesional->registro_medico : '',
            'tarjeta_profesional' => $profesional ? $profesional->tarjeta_profesional : '',
            'especialidad'        => $consentimiento->especialidad ? $consentimiento->especialidad->nombre : '',
            'fecha_procedimiento' => Carbon::parse($consentimiento->fecha_procedimiento)->format('d/m/Y'),
            'cups_codigo'         => $consentimiento->cups_codigo ?? '',
            'cups_descripcion'    => $consentimiento->cups_descripcion ?? '',
            'clinica_nombre'      => config('app.clinica_nombre', 'Clínica Fidem'),
            'clinica_direccion'   => config('app.clinica_direccion', 'Manizales, Colombia'),
            'fecha_actual'        => now()->format('d/m/Y H:i'),
        ];

        // Renderizar el contenido HTML de la plantilla
        $contenidoRenderizado = $consentimiento->plantilla->renderizar($variables);

        // Cargar las firmas
        $firmaPaciente = $consentimiento->firmaPaciente;
        $firmaAcudiente = $consentimiento->firmaAcudiente;
        $firmaProfesional = $consentimiento->firmaProfesional;

        // Generar el PDF usando la vista
        $pdf = PDF::loadView('consentimientos.pdf', [
            'consentimiento'       => $consentimiento,
            'contenidoRenderizado' => $contenidoRenderizado,
            'firmaPaciente'        => $firmaPaciente,
            'firmaAcudiente'       => $firmaAcudiente,
            'firmaProfesional'     => $firmaProfesional,
        ]);

        // Crear carpeta si no existe (organizado por año/mes)
        $carpeta = storage_path('app/consentimientos/' . now()->format('Y/m'));
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0755, true);
        }

        // Guardar el PDF
        $nombreArchivo = $consentimiento->id . '_consentimiento.pdf';
        $ruta = $carpeta . '/' . $nombreArchivo;
        $pdf->save($ruta);

        // Actualizar la ruta en el registro
        $rutaRelativa = 'consentimientos/' . now()->format('Y/m') . '/' . $nombreArchivo;
        $consentimiento->update(['pdf_path' => $rutaRelativa]);

        return $ruta;
    }

    /**
     * Descarga el PDF de un consentimiento
     *
     * @param ConsentimientoInformado $consentimiento
     * @return \Illuminate\Http\Response
     */
    public function descargar(ConsentimientoInformado $consentimiento)
    {
        if (!$consentimiento->pdf_path || !file_exists(storage_path('app/' . $consentimiento->pdf_path))) {
            // Si no existe, generarlo primero
            $this->generar($consentimiento);
        }

        $rutaCompleta = storage_path('app/' . $consentimiento->pdf_path);
        $nombreArchivo = 'consentimiento_' . $consentimiento->id . '_' . $consentimiento->paciente_cedula . '.pdf';

        return response()->download($rutaCompleta, $nombreArchivo);
    }
}
