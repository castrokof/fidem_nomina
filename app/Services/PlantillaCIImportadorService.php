<?php

namespace App\Services;

use App\ImportacionPlantillaCI;
use App\PlantillaCI;
use App\Especialidad;
use Illuminate\Support\Str;

class PlantillaCIImportadorService
{
    /**
     * Convierte texto plano a HTML con párrafos
     *
     * @param string $texto
     * @return string
     */
    public function textoAHtml($texto)
    {
        // Limpiar el texto
        $texto = trim($texto);

        // Dividir por líneas
        $lineas = explode("\n", $texto);
        $html = '';

        foreach ($lineas as $linea) {
            $linea = trim($linea);

            if (empty($linea)) {
                continue;
            }

            // Detectar si es un título (todo en mayúsculas o termina en :)
            if (strtoupper($linea) === $linea || Str::endsWith($linea, ':')) {
                $html .= '<h3>' . $linea . '</h3>' . "\n";
            } else {
                $html .= '<p>' . $linea . '</p>' . "\n";
            }
        }

        return $html;
    }

    /**
     * Procesa una importación de plantilla
     *
     * @param ImportacionPlantillaCI $importacion
     * @return PlantillaCI
     * @throws \Exception
     */
    public function procesarImportacion(ImportacionPlantillaCI $importacion)
    {
        try {
            // Obtener la plantilla HTML base
            $plantillaBase = $this->getPlantillaBase();

            // Convertir el contenido de texto a HTML
            $contenidoHtml = $this->textoAHtml($importacion->contenido_texto);

            // Reemplazar el marcador de contenido en la plantilla base
            $htmlCompleto = str_replace('<!-- CONTENIDO_PROCEDIMIENTO -->', $contenidoHtml, $plantillaBase);

            // Guardar en importacion
            $importacion->update(['contenido_html' => $htmlCompleto]);

            // Crear la plantilla
            $plantilla = PlantillaCI::create([
                'nombre'                => $importacion->nombre,
                'descripcion'           => 'Importado desde el sistema de carga masiva',
                'cups_codigo'           => $importacion->cups_codigo,
                'contenido_html'        => $htmlCompleto,
                'variables_disponibles' => PlantillaCI::variablesDisponibles(),
                'activo'                => true,
                'uso_general'           => $importacion->uso_general,
            ]);

            // Asociar especialidades si las hay
            if (!empty($importacion->especialidades)) {
                $nombresEspecialidades = array_map('trim', explode(',', $importacion->especialidades));

                foreach ($nombresEspecialidades as $nombreEsp) {
                    $especialidad = Especialidad::where('nombre', 'like', "%{$nombreEsp}%")->first();

                    if ($especialidad) {
                        $plantilla->especialidades()->attach($especialidad->id);
                    }
                }
            }

            // Marcar como procesado
            $importacion->marcarProcesado();

            return $plantilla;

        } catch (\Exception $e) {
            $importacion->marcarError($e->getMessage());
            throw $e;
        }
    }

    /**
     * Procesa todas las importaciones pendientes
     *
     * @return array
     */
    public function procesarTodas()
    {
        $importaciones = ImportacionPlantillaCI::pendiente()->get();

        $procesadas = 0;
        $errores = 0;

        foreach ($importaciones as $importacion) {
            try {
                $this->procesarImportacion($importacion);
                $procesadas++;
            } catch (\Exception $e) {
                $errores++;
            }
        }

        return [
            'procesadas' => $procesadas,
            'errores'    => $errores
        ];
    }

    /**
     * Obtiene la plantilla HTML base
     *
     * @return string
     */
    private function getPlantillaBase()
    {
        return '<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
  body { font-family: Arial, sans-serif; font-size: 12px; color: #000; margin: 20px; }
  .cabecera-tabla { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
  .cabecera-tabla td { border: 1px solid #000; padding: 6px 8px; }
  .cabecera-tabla .label { font-weight: bold; width: 30%; }
  .seccion { margin: 15px 0; }
  .seccion h3 { font-size: 12px; font-weight: bold; text-transform: uppercase; margin-bottom: 5px; }
  .seccion p  { text-align: justify; margin: 6px 0; }
  .seccion ol { margin: 5px 0 5px 20px; }
  .bloque-consentimiento { border: 1px solid #000; padding: 8px; margin: 10px 0; }
  .voluntad { background: #f5f5f5; padding: 8px; margin: 10px 0; font-weight: bold; }
  .firma-tabla { width: 100%; border-collapse: collapse; margin-top: 8px; }
  .firma-tabla td { border: 1px solid #000; padding: 6px; min-height: 50px; }
  .firma-label { background: #e0e0e0; font-weight: bold; font-size: 11px; }
  .firma-espacio { height: 60px; vertical-align: bottom; }
</style>
</head>
<body>

<!-- CABECERA -->
<table class="cabecera-tabla">
  <tr>
    <td class="label">1. NOMBRE DEL PROCEDIMIENTO</td>
    <td colspan="3"><strong>{{cups_descripcion}}</strong></td>
  </tr>
  <tr>
    <td class="label">2. SERVICIO</td>
    <td>PROCEDIMIENTOS</td>
    <td class="label">Nº IDENTIFICACIÓN</td>
    <td>{{paciente_cedula}}</td>
  </tr>
  <tr>
    <td class="label">3. NOMBRE PACIENTE</td>
    <td colspan="3">{{paciente_nombre}}</td>
  </tr>
  <tr>
    <td class="label">4. EDAD</td>
    <td>{{paciente_edad}}</td>
    <td class="label">GÉNERO</td>
    <td>{{paciente_genero}}</td>
  </tr>
</table>

<!-- VOLUNTAD DE INFORMACIÓN -->
<div class="voluntad">
  <p>*¿DESEO SER INFORMADO sobre mi enfermedad y la intervención que me van a realizar?</p>
</div>

<table class="firma-tabla">
  <tr>
    <td colspan="4" class="bloque-consentimiento">
      <strong>DESEO QUE LA INFORMACIÓN</strong> de mi enfermedad y la intervención que me van
      a realizar le sea proporcionada a mi familiar / tutor / representante legal:
    </td>
  </tr>
  <tr>
    <td class="firma-label">NOMBRE APELLIDOS (Paciente)</td>
    <td class="firma-label">IDENTIFICACIÓN</td>
    <td class="firma-label">FIRMA</td>
    <td class="firma-label">FECHA</td>
  </tr>
  <tr>
    <td class="firma-espacio">{{paciente_nombre}}</td>
    <td class="firma-espacio">{{paciente_tipo_doc}} {{paciente_cedula}}</td>
    <td class="firma-espacio"></td>
    <td class="firma-espacio">{{fecha_actual}}</td>
  </tr>
</table>

<table class="firma-tabla" style="margin-top:5px;">
  <tr>
    <td colspan="4" class="bloque-consentimiento">
      <strong>"MANIFIESTO MI DESEO DE NO SER INFORMADO Y PRESTO MI CONSENTIMIENTO"</strong>
      para que se lleve a cabo el procedimiento descrito en este documento.
    </td>
  </tr>
  <tr>
    <td class="firma-label">NOMBRE APELLIDOS (Paciente)</td>
    <td class="firma-label">IDENTIFICACIÓN</td>
    <td class="firma-label">FIRMA</td>
    <td class="firma-label">FECHA</td>
  </tr>
  <tr>
    <td class="firma-espacio">{{paciente_nombre}}</td>
    <td class="firma-espacio">{{paciente_tipo_doc}} {{paciente_cedula}}</td>
    <td class="firma-espacio"></td>
    <td class="firma-espacio">{{fecha_actual}}</td>
  </tr>
</table>

<!-- CONTENIDO MÉDICO — varía por procedimiento -->
<div class="seccion">
<!-- CONTENIDO_PROCEDIMIENTO -->
</div>

<!-- FIRMA PACIENTE -->
<table class="firma-tabla" style="margin-top:15px;">
  <tr>
    <td colspan="4" class="firma-label">PACIENTE</td>
  </tr>
  <tr>
    <td colspan="4" class="bloque-consentimiento">
      <strong>DECLARO</strong> que he comprendido adecuadamente la información que contiene
      este documento, que firmo el consentimiento para la realización del procedimiento
      que se describe en el mismo, que he recibido copia del mismo y que conozco que el
      consentimiento puede ser revocado por escrito en cualquier momento.
    </td>
  </tr>
  <tr>
    <td class="firma-label">NOMBRE / APELLIDOS</td>
    <td class="firma-label">IDENTIFICACIÓN</td>
    <td class="firma-label">FIRMA</td>
    <td class="firma-label">FECHA</td>
  </tr>
  <tr>
    <td class="firma-espacio">{{paciente_nombre}}</td>
    <td class="firma-espacio">{{paciente_tipo_doc}} {{paciente_cedula}}</td>
    <td class="firma-espacio" id="firma-paciente"><!-- FIRMA A MANO ALZADA --></td>
    <td class="firma-espacio">{{fecha_actual}}</td>
  </tr>
</table>

<!-- FIRMA FAMILIAR/TUTOR/REPRESENTANTE -->
<table class="firma-tabla" style="margin-top:8px;">
  <tr>
    <td colspan="4" class="firma-label">FAMILIAR / TUTOR / REPRESENTANTE</td>
  </tr>
  <tr>
    <td colspan="4" class="bloque-consentimiento">
      <strong>DECLARO</strong> que he comprendido adecuadamente la información que contiene
      este documento, que firmo el consentimiento para la realización del procedimiento
      que se describe en el mismo, que he recibido copia del mismo y que conozco que el
      consentimiento puede ser revocado por escrito en cualquier momento.
    </td>
  </tr>
  <tr>
    <td class="firma-label">TUTOR / FAMILIAR / REPRESENTANTE</td>
    <td class="firma-label">IDENTIFICACIÓN</td>
    <td class="firma-label">FIRMA</td>
    <td class="firma-label">FECHA</td>
  </tr>
  <tr>
    <td class="firma-espacio"></td>
    <td class="firma-espacio"></td>
    <td class="firma-espacio" id="firma-acudiente"><!-- FIRMA A MANO ALZADA --></td>
    <td class="firma-espacio">{{fecha_actual}}</td>
  </tr>
</table>

<!-- FIRMA MÉDICO RESPONSABLE (PRECARGADA) -->
<table class="firma-tabla" style="margin-top:8px;">
  <tr>
    <td colspan="4" class="firma-label">MÉDICO RESPONSABLE</td>
  </tr>
  <tr>
    <td colspan="4" class="bloque-consentimiento">
      <strong>DECLARO</strong> haber informado al paciente y al familiar, tutor o
      representante del mismo del objeto y naturaleza del procedimiento que se le va a
      realizar, explicándole los riesgos y complicaciones posibles del mismo.
    </td>
  </tr>
  <tr>
    <td class="firma-label">MÉDICO RESPONSABLE</td>
    <td class="firma-label">IDENTIFICACIÓN</td>
    <td class="firma-label">FIRMA</td>
    <td class="firma-label">FECHA</td>
  </tr>
  <tr>
    <td class="firma-espacio">{{profesional_nombre}}</td>
    <td class="firma-espacio">RM: {{registro_medico}}</td>
    <td class="firma-espacio" id="firma-profesional"><!-- IMAGEN PRECARGADA DEL PERFIL --></td>
    <td class="firma-espacio">{{fecha_actual}}</td>
  </tr>
</table>

</body>
</html>';
    }
}
