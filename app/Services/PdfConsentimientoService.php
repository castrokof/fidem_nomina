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
            // ✅ Asegurar que las relaciones estén cargadas
            $consentimiento->load([
                'profesional',
                'paciente',
                'plantilla',
                'firmaPaciente',
                'firmaAcudiente',
                'firmaProfesional',
                'acudiente'
            ]);

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
                'clinica_direccion'   => config('app.clinica_direccion', 'Cali, Colombia'),
                'fecha_actual'        => now()->format('d/m/Y H:i'),
            ];

            // ✅ Renderizar el contenido HTML de la plantilla
            $contenidoRenderizado = $consentimiento->plantilla->renderizar($variables);

            // ✅ Sanitizar contenido para fuente base (helvetica)
            $contenidoLimpio = $this->sanitizarParaFuenteBase($contenidoRenderizado);

            // ✅ Procesar firmas con logs para debug
            Log::info('Procesando firmas del consentimiento', [
                'consentimiento_id' => $consentimiento->id,
                'tiene_firma_paciente' => $consentimiento->firmaPaciente ? true : false,
                'tiene_firma_acudiente' => $consentimiento->firmaAcudiente ? true : false,
                'tiene_firma_profesional' => $consentimiento->firmaProfesional ? true : false,
            ]);

            $firmaPacienteBase64 = $this->sanitizarFirmaBase64($consentimiento->firmaPaciente);
            $firmaAcudienteBase64 = $this->sanitizarFirmaBase64($consentimiento->firmaAcudiente);
            $firmaProfesionalBase64 = null;

            // Priorizar firma digital (imagen) del profesional sobre base64
            if ($profesional) {
                if (!empty($profesional->firma_imagen_path) && file_exists(public_path($profesional->firma_imagen_path))) {
                    // Convertir imagen a base64
                    $firmaProfesionalBase64 = $this->convertirImagenABase64(public_path($profesional->firma_imagen_path));
                } elseif ($consentimiento->firmaProfesional && !empty($consentimiento->firmaProfesional->firma_base64)) {
                    $firmaProfesionalBase64 = $this->sanitizarFirmaBase64($consentimiento->firmaProfesional);
                } elseif (!empty($profesional->firma_base64)) {
                    $firmaProfesionalBase64 = $this->sanitizarFirmaBase64((object)['firma_base64' => $profesional->firma_base64]);
                }
            }

            // Obtener logo de FIDEM (si está configurado)
            $logoFidemBase64 = $this->obtenerLogoFidem();

            Log::info('Firmas procesadas', [
                'firma_paciente_ok' => $firmaPacienteBase64 ? true : false,
                'firma_acudiente_ok' => $firmaAcudienteBase64 ? true : false,
                'firma_profesional_ok' => $firmaProfesionalBase64 ? true : false,
            ]);

$html = view('consentimientos.pdf', [
    'consentimiento'            => $consentimiento,
    'contenidoRenderizado'      => $contenidoLimpio,
    'firmaPacienteBase64'       => $firmaPacienteBase64,
    'firmaAcudienteBase64'      => $firmaAcudienteBase64,
    'firmaProfesionalBase64'    => $firmaProfesionalBase64,
    'logoFidemBase64'           => $logoFidemBase64,
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
            $nombreArchivo = $consentimiento->id . '_consentimiento_' . $consentimiento->paciente_cedula . '.pdf';
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
    if (!$firma || empty($firma->firma_base64)) {
        Log::warning('Firma vacía o nula recibida en sanitizarFirmaBase64');
        return null;
    }

    $base64 = $firma->firma_base64;
    Log::info('Procesando firma base64', [
        'tiene_prefijo_data' => stripos($base64, 'data:') === 0,
        'longitud' => strlen($base64),
        'primeros_50_chars' => substr($base64, 0, 50)
    ]);

    // 1. Si tiene prefijo "data:", extraer solo la parte útil
    if (stripos($base64, 'data:') === 0) {
        $parts = explode(',', $base64, 2);
        if (count($parts) === 2) {
            // DomPDF necesita: data:image/png;base64,BASE64
            $base64 = 'data:image/png;base64,' . preg_replace('/\s+/', '', trim($parts[1]));
        }
    } else {
        // Si no tiene prefijo, agregarlo
        $base64 = 'data:image/png;base64,' . preg_replace('/\s+/', '', trim($base64));
    }

    // 2. Validar que es base64 válido
    $partes = explode(',', $base64);
    if (count($partes) < 2) {
        Log::error('Firma base64 mal formateada - no tiene coma separadora');
        return null;
    }

    $test = @base64_decode($partes[1]);
    if ($test === false || strlen($test) < 100) {
        Log::error('Firma base64 inválida o muy pequeña', [
            'firma_length' => strlen($base64),
            'decoded_length' => $test ? strlen($test) : 0
        ]);
        return null;
    }

    Log::info('Firma base64 procesada exitosamente', [
        'longitud_final' => strlen($base64),
        'longitud_decodificada' => strlen($test)
    ]);

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
        // ✅ Asegurar que las relaciones estén cargadas antes de descargar
        $consentimiento->load([
            'profesional',
            'paciente',
            'plantilla',
            'firmaPaciente',
            'firmaAcudiente',
            'firmaProfesional',
            'acudiente'
        ]);

        if (!$consentimiento->pdf_path || !file_exists(storage_path('app/' . $consentimiento->pdf_path))) {
            // Si no existe, generarlo primero
            $this->generar($consentimiento);
        }

        $rutaCompleta = storage_path('app/' . $consentimiento->pdf_path);
        $nombreArchivo = 'consentimiento_' . $consentimiento->id . '_' . $consentimiento->paciente_cedula . '.pdf';

        return response()->download($rutaCompleta, $nombreArchivo);
    }

    /**
     * Convierte una imagen a base64 para incluir en el PDF
     *
     * @param string $rutaImagen
     * @return string|null
     */
    protected function convertirImagenABase64($rutaImagen)
    {
        try {
            if (!file_exists($rutaImagen)) {
                Log::warning('Imagen no encontrada', ['ruta' => $rutaImagen]);
                return null;
            }

            $imageData = file_get_contents($rutaImagen);
            if ($imageData === false) {
                Log::error('No se pudo leer la imagen', ['ruta' => $rutaImagen]);
                return null;
            }

            // Detectar tipo de imagen
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $rutaImagen);
            finfo_close($finfo);

            $base64 = base64_encode($imageData);
            return "data:{$mimeType};base64,{$base64}";
        } catch (\Exception $e) {
            Log::error('Error al convertir imagen a base64', [
                'ruta' => $rutaImagen,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Obtiene el logo de FIDEM desde la configuración
     *
     * @return string|null
     */
    protected function obtenerLogoFidem()
    {
        try {
            $configuracion = \App\Configuracion::where('clave', 'logo_fidem_path')->first();

            if (!$configuracion || !$configuracion->valor) {
                return null;
            }

            $rutaLogo = public_path($configuracion->valor);

            if (!file_exists($rutaLogo)) {
                Log::warning('Logo FIDEM no encontrado', ['ruta' => $rutaLogo]);
                return null;
            }

            return $this->convertirImagenABase64($rutaLogo);
        } catch (\Exception $e) {
            Log::error('Error al obtener logo FIDEM', ['error' => $e->getMessage()]);
            return null;
        }
    }
}