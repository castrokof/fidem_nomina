<?php

namespace App\Services;

use App\ConsentimientoInformado;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Log;

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
        try {
            $profesional = $consentimiento->profesional;

            // ✅ Preparar variables para renderizar en la plantilla
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

            // ✅ Renderizar el contenido HTML de la plantilla
            $contenidoRenderizado = $consentimiento->plantilla->renderizar($variables);

            // ✅ Sanitizar contenido para fuente base (helvetica)
            $contenidoLimpio = $this->sanitizarParaFuenteBase($contenidoRenderizado);

           // En el método generar()
$firmaPacienteBase64 = $this->sanitizarFirmaBase64($consentimiento->firmaPaciente);
$firmaAcudienteBase64 = $this->sanitizarFirmaBase64($consentimiento->firmaAcudiente);
$firmaProfesionalBase64 = null;

if ($consentimiento->firmaProfesional && !empty($consentimiento->firmaProfesional->firma_base64)) {
    $firmaProfesionalBase64 = $this->sanitizarFirmaBase64($consentimiento->firmaProfesional);
} elseif ($profesional && !empty($profesional->firma_base64)) {
    $firmaProfesionalBase64 = $this->sanitizarFirmaBase64((object)['firma_base64' => $profesional->firma_base64]);
}

$html = view('consentimientos.pdf', [
    'consentimiento'            => $consentimiento,
    'contenidoRenderizado'      => $contenidoLimpio,
    'firmaPacienteBase64'       => $firmaPacienteBase64,
    'firmaAcudienteBase64'      => $firmaAcudienteBase64,
    'firmaProfesionalBase64'    => $firmaProfesionalBase64,
    'variables'                 => $variables,
])->render();

            // ✅ Generar PDF con DomPDF directamente (sin Facade)
            $pdf = $this->generarConDompdf($html);

            // ✅ Crear carpeta si no existe (organizado por año/mes)
            $carpeta = storage_path('app/consentimientos/' . now()->format('Y/m'));
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0755, true);
            }

            // ✅ Guardar el PDF
            $nombreArchivo = $consentimiento->id . '_consentimiento.pdf';
            $ruta = $carpeta . '/' . $nombreArchivo;
            $pdf->output();  // Renderizar primero
            file_put_contents($ruta, $pdf->output());

            // ✅ Actualizar la ruta en el registro
            $rutaRelativa = 'consentimientos/' . now()->format('Y/m') . '/' . $nombreArchivo;
            $consentimiento->update(['pdf_path' => $rutaRelativa]);

            return $ruta;
            
        } catch (\Exception $e) {
            Log::error('Error generando PDF de consentimiento', [
                'consentimiento_id' => $consentimiento->id ?? null,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }

    /**
     * Sanitizar contenido para fuente base (helvetica)
     * Convierte caracteres especiales a equivalentes ASCII
     */
    protected function sanitizarParaFuenteBase($contenido)
    {
        if (!$contenido) return '';
        
        // Decodificar entidades HTML primero
        $contenido = html_entity_decode($contenido, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        // Reemplazar caracteres españoles por equivalentes ASCII para helvetica
        $replacements = [
            'ñ' => 'n', 'Ñ' => 'N',
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
            'ü' => 'u', 'Ü' => 'U',
            '¿' => '?', '¡' => '!',
            '–' => '-', '—' => '-',
            '…' => '...',
            '«' => '"', '»' => '"',
            '´' => "'", '`' => "'",
            'º' => 'o', 'ª' => 'a',
        ];
        
        $contenido = strtr($contenido, $replacements);
        
        // Remover emojis y caracteres no ASCII que helvetica no soporta
        $contenido = preg_replace('/[^\x{0009}\x{000A}\x{000D}\x{0020}-\x{007E}]/u', '', $contenido);
        
        // Remover tags script/style que pueden romper el parser
        $contenido = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/i', '', $contenido);
        $contenido = preg_replace('/<style\b[^<]*(?:(?!<\/style>)<[^<]*)*<\/style>/i', '', $contenido);
        
        return $contenido;
    }

  /**
 * Sanitizar firma base64 para DomPDF
 */
protected function sanitizarFirmaBase64($firma)
{
    if (!$firma || empty($firma->firma_base64)) return null;
    
    $base64 = $firma->firma_base64;
    
    // 1. Si tiene prefijo "data:", extraer solo la parte útil
    if (stripos($base64, 'data:') === 0) {
        $parts = explode(',', $base64, 2);
        if (count($parts) === 2) {
            // DomPDF necesita: image/png;base64,BASE64
            $base64 = 'image/png;base64,' . preg_replace('/\s+/', '', trim($parts[1]));
        }
    } else {
        // Si no tiene prefijo, agregarlo
        $base64 = 'image/png;base64,' . preg_replace('/\s+/', '', trim($base64));
    }
    
    // 2. Validar que es base64 válido
    $test = @base64_decode(explode(',', $base64)[1]);
    if ($test === false) {
        \Log::error('Firma base64 inválida', ['firma_length' => strlen($base64)]);
        return null;
    }
    
    return $base64;
}

    /**
     * Generar PDF con DomPDF directamente (sin Facade)
     */
    protected function generarConDompdf($html)
    {
        // ✅ Configurar opciones para DomPDF
        $options = new Options();
        $options->set('defaultFont', 'helvetica');              // ✅ Fuente base compatible
        $options->set('isUnicodeEnabled', true);                 // ✅ Soporte UTF-8 básico
        $options->set('isHtml5ParserEnabled', true);             // ✅ Parser HTML5
        $options->set('isRemoteEnabled', true);                  // ✅ Permitir imágenes base64
        $options->set('enable_font_subsetting', false);          // ✅ Evitar errores de métricas
        $options->set('dpi', 96);
        $options->set('logOutputFile', null);                    // ✅ Sin logs de debug
        
        // ✅ Instanciar DomPDF directamente
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        
        // ✅ Renderizar PDF
        $dompdf->render();
        
        return $dompdf;
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