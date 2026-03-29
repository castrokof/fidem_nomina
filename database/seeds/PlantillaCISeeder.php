<?php

use Illuminate\Database\Seeder;
use App\PlantillaCI;
use App\Especialidad;

class PlantillaCISeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $medicinaDelDolor = Especialidad::where('nombre', 'Medicina del Dolor')->first();

        // Plantilla 1: Bloqueo Articulaciones Sacroilíacas
        $this->crearPlantillaBloqueoSacroiliacas($medicinaDelDolor);

        // Plantilla 2: Bloqueo de Facetas Cervicales
        $this->crearPlantillaBloqueoFacetasCervicales($medicinaDelDolor);

        $this->command->info('Plantillas de consentimientos creadas exitosamente.');
    }

    private function crearPlantillaBloqueoSacroiliacas($especialidad)
    {
        $html = $this->getPlantillaBase();

        $contenido = '
<div class="seccion">
  <h3>Identificación y Descripción del Procedimiento</h3>
  <p>Esta técnica consiste en la introducción de un medicamento (anestésico local o un corticoide) en unas articulaciones grandes situadas entre el sacro (el final de la columna) y las palas ilíacas (huesos de la pelvis) para tratar dolores de carácter mecánico con esta localización. Es una técnica poco dolorosa, pero un poco incómoda. Se emplean unas agujas largas dirigidas con radioscopia (rayos X) para localizar el lugar de inyección. El bloqueo puede ser diagnóstico con anestésico local y efecto pasajero (para localizar el origen del dolor), o terapéutico con un esteroide y de mayor duración (para tratamiento del dolor). Suele durar entre 20 y 25 minutos. Se suele canalizar una vía venosa (un suero) y se emplea anestesia local. Se canalizará una vía venosa en uno de los brazos por si necesitara un sedante suave y se vigilarán sus constantes vitales (tensión arterial, pulso...).</p>
</div>

<div class="seccion">
  <h3>Objetivos del Procedimiento y Beneficios que se Esperan Alcanzar</h3>
  <p>Mejoría del dolor.</p>
</div>

<div class="seccion">
  <h3>Alternativas Razonables al Procedimiento</h3>
  <p>Tratamiento oral.</p>
</div>

<div class="seccion">
  <h3>Consecuencias Previsibles de su Realización</h3>
  <p>Mejoría del dolor.</p>
</div>

<div class="seccion">
  <h3>Consecuencias Previsibles de su NO Realización</h3>
  <p>Menor mejoría.</p>
</div>

<div class="seccion">
  <h3>Riesgos Frecuentes</h3>
  <ol>
    <li>Molestias locales en el lugar de punción. Ceden en pocas horas con analgésicos convencionales.</li>
    <li>Síncope vasovagal. Es un "mareo" que suele darse en ciertas personas ante determinadas situaciones (análisis, visión de sangre, dolor, etc.) Se acompaña de sensación de calor, sudor, y desvanecimiento. Debe avisar si nota estos síntomas. No es grave y cede con atropina (que se puede administrar de forma preventiva).</li>
  </ol>
</div>

<div class="seccion">
  <h3>Riesgos Poco Frecuentes de Especial Gravedad</h3>
  <p>Existen otras complicaciones muy poco frecuentes, pero más graves: 1. Penetración de otras estructuras de la región.</p>
</div>

<div class="seccion">
  <h3>Contraindicaciones</h3>
  <p>No se podrá realizar si hay trastornos de la coagulación o infecciones en zona de punción.</p>
</div>';

        $htmlCompleto = str_replace('<!-- CONTENIDO_PROCEDIMIENTO -->', $contenido, $html);

        $plantilla = PlantillaCI::create([
            'nombre' => 'Bloqueo Articulaciones Sacroilíacas',
            'descripcion' => 'Consentimiento informado para bloqueo de articulaciones sacroilíacas con técnica guiada por radioscopia',
            'cups_codigo' => null,
            'contenido_html' => $htmlCompleto,
            'variables_disponibles' => PlantillaCI::variablesDisponibles(),
            'activo' => true,
            'uso_general' => false
        ]);

        if ($especialidad) {
            $plantilla->especialidades()->attach($especialidad->id);
        }
    }

    private function crearPlantillaBloqueoFacetasCervicales($especialidad)
    {
        $html = $this->getPlantillaBase();

        $contenido = '
<div class="seccion">
  <h3>Identificación y Descripción del Procedimiento</h3>
  <p>Esta técnica consiste en la introducción de un medicamento (anestésico local o un corticoide) en unas pequeñas articulaciones de su columna vertebral cervical, llamadas facetas para tratar dolores de la región cervical de carácter mecánico. Es una técnica poco dolorosa, pero un poco incómoda. Se emplean unas agujas dirigidas con radioscopia (rayos X) para localizar el lugar de inyección. El bloqueo puede ser diagnóstico con anestésico local y efecto pasajero (para localizar el origen del dolor), o terapéutico con un esteroide y de mayor duración (para tratamiento del dolor). Suele durar entre 20 y 25 minutos. Se suele canalizar una vía venosa (un suero) y se emplea anestesia local. Se canalizará una vía venosa en uno de los brazos por si necesitara un sedante suave y se vigilarán sus constantes vitales (tensión arterial, pulso...). Si la patología fuera bilateral se podrá realizar el mismo procedimiento en zona contralateral en días diferentes.</p>
</div>

<div class="seccion">
  <h3>Objetivos del Procedimiento y Beneficios que se Esperan Alcanzar</h3>
  <p>Mejoría del dolor.</p>
</div>

<div class="seccion">
  <h3>Alternativas Razonables al Procedimiento</h3>
  <p>Tratamiento oral.</p>
</div>

<div class="seccion">
  <h3>Consecuencias Previsibles de su Realización</h3>
  <p>Mejoría del dolor.</p>
</div>

<div class="seccion">
  <h3>Consecuencias Previsibles de su NO Realización</h3>
  <p>No mejoría del dolor.</p>
</div>

<div class="seccion">
  <h3>Riesgos Frecuentes</h3>
  <ol>
    <li>Molestias locales en el lugar de punción. Ceden en pocas horas con analgésicos convencionales.</li>
    <li>Síncope vasovagal. Es un "mareo" que suele darse en ciertas personas ante determinadas situaciones. Se acompaña de sensación de calor, sudor y desvanecimiento. No es grave y cede con atropina.</li>
  </ol>
</div>

<div class="seccion">
  <h3>Riesgos Poco Frecuentes de Especial Gravedad</h3>
  <p>Existen otras complicaciones muy poco frecuentes, como es la penetración de otras estructuras de la región. En cualquier caso, dado el poco calibre de las agujas, no suelen ser graves.</p>
</div>

<div class="seccion">
  <h3>Contraindicaciones</h3>
  <p>No se podrá realizar si hay trastornos de la coagulación o infecciones en la zona de punción.</p>
</div>';

        $htmlCompleto = str_replace('<!-- CONTENIDO_PROCEDIMIENTO -->', $contenido, $html);

        $plantilla = PlantillaCI::create([
            'nombre' => 'Bloqueo de Facetas Cervicales',
            'descripcion' => 'Consentimiento informado para bloqueo de facetas cervicales con técnica guiada por radioscopia',
            'cups_codigo' => null,
            'contenido_html' => $htmlCompleto,
            'variables_disponibles' => PlantillaCI::variablesDisponibles(),
            'activo' => true,
            'uso_general' => false
        ]);

        if ($especialidad) {
            $plantilla->especialidades()->attach($especialidad->id);
        }
    }

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
<!-- CONTENIDO_PROCEDIMIENTO -->

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
