Eres un experto en Laravel 5.7, Blade, jQuery y Bootstrap 4. Vas a implementar el módulo "CI-Fidem" (Consentimientos Informados con Firma Electrónica) dentro de un proyecto Laravel 5.7 existente.

---

## CONTEXTO DEL PROYECTO

Clínica Fidem (Manizales, Colombia) necesita digitalizar sus consentimientos informados. El sistema actual usa una tabla llamada "Usuarios" solo para login. NO modificar esa tabla. Se crearán tablas nuevas independientes para profesionales y pacientes.

El objetivo es reemplazar los consentimientos en papel con:
- FIRMA A MANO ALZADA del paciente sobre pantalla táctil
- FIRMA A MANO ALZADA del familiar/tutor/representante legal
- FIRMA DEL PROFESIONAL precargada desde su perfil (dibuja una vez, se estampa automáticamente)

REGLA CRÍTICA PARA FECHAS EN SQL SERVER (conexión sqlsrv1):
- NUNCA usar whereBetween con fechas
- NUNCA usar where con fechas como parámetros bind
- SIEMPRE usar whereRaw("FECHA >= CONVERT(datetime, '$fecha', 120)") interpolando el string

---

## ESTRUCTURA REAL DE LOS CONSENTIMIENTOS

Analicé los documentos Word reales de Clínica Fidem. Todos tienen exactamente esta estructura:

CABECERA:
- Nombre del procedimiento (título)
- Servicio: PROCEDIMIENTOS
- Nº Identificación del paciente
- Nombre del paciente
- Edad y Género

SECCIÓN INICIAL — Voluntad de información:
- "¿Deseo ser informado sobre mi enfermedad?"
- "Deseo que la información sea proporcionada a mi familiar/tutor/representante"
  → Nombre, identificación, firma, fecha (del paciente que delega)
- "Manifiesto mi deseo de NO ser informado y presto mi consentimiento"
  → Nombre, identificación, firma, fecha

CUERPO DEL DOCUMENTO:
- Identificación y descripción del procedimiento
- Objetivos del procedimiento y beneficios esperados
- Alternativas razonables al procedimiento
- Consecuencias previsibles de su realización
- Consecuencias previsibles de su NO realización
- Riesgos frecuentes
- Riesgos poco frecuentes de especial gravedad
- Riesgos según situación clínica del paciente
- Contraindicaciones

SECCIÓN DE FIRMAS — 3 bloques:

BLOQUE 1 — PACIENTE:
"DECLARO que he comprendido adecuadamente la información que contiene este
documento, que firmo el consentimiento para la realización del procedimiento
que se describe en el mismo, que he recibido copia del mismo y que conozco
que el consentimiento puede ser revocado por escrito en cualquier momento"
→ Campos: NOMBRE/APELLIDOS, IDENTIFICACION, FIRMA, FECHA

BLOQUE 2 — FAMILIAR/TUTOR/REPRESENTANTE:
Mismo texto de declaración
→ Campos: TUTOR/FAMILIAR/REPRESENTANTE, IDENTIFICACION, FIRMA, FECHA

BLOQUE 3 — MÉDICO RESPONSABLE:
"DECLARO haber informado al paciente y al familiar, tutor o representante
del mismo del objeto y naturaleza del procedimiento que se le va a realizar,
explicándole los riesgos y complicaciones posibles del mismo."
→ Campos: MÉDICO RESPONSABLE (nombre precargado), IDENTIFICACION (RM precargado), FIRMA (imagen precargada), FECHA

OBSERVACIÓN IMPORTANTE:
En los documentos Word actuales, el nombre del médico y el RM aparecen
hardcodeados ("SANTIAGO SANCHEZ / RM: 1107034356").
En el sistema digital estos deben ser variables que se autocompletan:
{{profesional_nombre}} y {{registro_medico}}

---

## TABLAS NUEVAS — NO tocar la tabla "Usuarios" existente

### 1. Tabla especialidades:
```sql
CREATE TABLE especialidades (
    id          BIGINT       PRIMARY KEY AUTO_INCREMENT,
    nombre      VARCHAR(150) NOT NULL UNIQUE,
    codigo      VARCHAR(30)  NULL UNIQUE,
    descripcion TEXT         NULL,
    activo      BOOLEAN      DEFAULT TRUE,
    created_at  TIMESTAMP    NULL,
    updated_at  TIMESTAMP    NULL
);
```

### 2. Tabla profesionales:
Nueva tabla independiente. NO usa la tabla Usuarios existente.
Los profesionales pueden o no tener acceso al sistema de login.
```sql
CREATE TABLE profesionales (
    id                    BIGINT       PRIMARY KEY AUTO_INCREMENT,
    usuario_id            BIGINT       NULL UNIQUE, -- FK a Usuarios solo si tiene login
    especialidad_id       BIGINT       NULL,        -- FK a especialidades
    codigo_usuario        VARCHAR(50)  NULL UNIQUE, -- relaciona con CODIGO_USUARIO de fac_m_citas (trim)
    nombres               VARCHAR(100) NOT NULL,
    apellidos             VARCHAR(100) NOT NULL,
    nombre_completo       VARCHAR(200) AS (CONCAT(nombres, ' ', apellidos)) STORED,
    tipo_documento        VARCHAR(5)   DEFAULT 'CC',
    numero_documento      VARCHAR(20)  NULL,
    registro_medico       VARCHAR(50)  NULL,
    tarjeta_profesional   VARCHAR(50)  NULL,
    telefono              VARCHAR(20)  NULL,
    email                 VARCHAR(150) NULL,
    firma_base64          LONGTEXT     NULL,        -- firma a mano alzada precargada
    firma_actualizada_at  TIMESTAMP    NULL,
    activo                BOOLEAN      DEFAULT TRUE,
    created_at            TIMESTAMP    NULL,
    updated_at            TIMESTAMP    NULL
);
```

### 3. Tabla pacientes:
Nueva tabla independiente para datos de pacientes.
```sql
CREATE TABLE pacientes (
    id               BIGINT       PRIMARY KEY AUTO_INCREMENT,
    tipo_documento   VARCHAR(5)   DEFAULT 'CC',
    numero_documento VARCHAR(20)  NOT NULL,
    nombres          VARCHAR(100) NOT NULL,
    apellidos        VARCHAR(100) NOT NULL,
    nombre_completo  VARCHAR(200) AS (CONCAT(nombres, ' ', apellidos)) STORED,
    fecha_nacimiento DATE         NULL,
    edad             TINYINT      NULL,
    genero           ENUM('M','F','O') NULL,
    telefono         VARCHAR(20)  NULL,
    historia_clinica VARCHAR(50)  NULL,
    email            VARCHAR(150) NULL,
    created_at       TIMESTAMP    NULL,
    updated_at       TIMESTAMP    NULL,
    UNIQUE KEY uk_paciente_doc (tipo_documento, numero_documento)
);
```

### 4. Tabla plantillas_ci:
```sql
CREATE TABLE plantillas_ci (
    id                    BIGINT       PRIMARY KEY AUTO_INCREMENT,
    nombre                VARCHAR(200) NOT NULL,
    descripcion           TEXT         NULL,
    cups_codigo           VARCHAR(20)  NULL,
    contenido_html        LONGTEXT     NOT NULL,
    variables_disponibles JSON         NULL,
    activo                BOOLEAN      DEFAULT TRUE,
    uso_general           BOOLEAN      DEFAULT FALSE,
    created_at            TIMESTAMP    NULL,
    updated_at            TIMESTAMP    NULL
);
```

### 5. Tabla pivot especialidad_plantilla_ci:
```sql
CREATE TABLE especialidad_plantilla_ci (
    id               BIGINT PRIMARY KEY AUTO_INCREMENT,
    especialidad_id  BIGINT NOT NULL,
    plantilla_ci_id  BIGINT NOT NULL,
    created_at       TIMESTAMP NULL,
    updated_at       TIMESTAMP NULL,
    UNIQUE KEY uk_esp_plt (especialidad_id, plantilla_ci_id)
);
```

### 6. Tabla agenda_ci:
```sql
CREATE TABLE agenda_ci (
    id                  BIGINT       PRIMARY KEY AUTO_INCREMENT,
    id_registro         VARCHAR(20)  UNIQUE NOT NULL,
    fecha               DATETIME     NOT NULL,
    codigo_consultorio  VARCHAR(20)  NULL,
    historia            VARCHAR(50)  NULL,
    paciente_id         BIGINT       NULL,          -- FK a pacientes si ya existe
    paciente_nombre     VARCHAR(200) NOT NULL,
    paciente_cedula     VARCHAR(20)  NOT NULL,
    paciente_tipo_doc   VARCHAR(5)   DEFAULT 'CC',
    paciente_telefono   VARCHAR(20)  NULL,
    profesional_id      BIGINT       NULL,          -- FK a profesionales
    codigo_usuario      VARCHAR(50)  NULL,
    cups_codigo         VARCHAR(20)  NULL,
    contrato            VARCHAR(30)  NULL,
    empresafac          VARCHAR(20)  NULL,
    llegada_confirmada  BOOLEAN      DEFAULT FALSE,
    numero_factura      VARCHAR(30)  NULL,
    atencion_factura    DATETIME     NULL,
    sincronizado_at     TIMESTAMP    NULL,
    created_at          TIMESTAMP    NULL,
    updated_at          TIMESTAMP    NULL
);
```

### 7. Tabla consentimientos_informados:
```sql
CREATE TABLE consentimientos_informados (
    id                       BIGINT       PRIMARY KEY AUTO_INCREMENT,
    agenda_ci_id             BIGINT       NULL,
    paciente_id              BIGINT       NULL,          -- FK a pacientes
    paciente_nombre          VARCHAR(200) NOT NULL,
    paciente_cedula          VARCHAR(20)  NOT NULL,
    paciente_tipo_doc        VARCHAR(5)   DEFAULT 'CC',
    paciente_edad            TINYINT      NULL,
    paciente_genero          VARCHAR(5)   NULL,
    paciente_fecha_nacimiento DATE         NULL,
    profesional_id           BIGINT       NULL,          -- FK a profesionales
    profesional_nombre       VARCHAR(200) NOT NULL,
    especialidad_id          BIGINT       NULL,
    plantilla_id             BIGINT       NOT NULL,
    cups_codigo              VARCHAR(20)  NULL,
    cups_descripcion         VARCHAR(300) NULL,
    fecha_procedimiento      DATE         NOT NULL,
    estado                   ENUM('pendiente','en_proceso','firmado','cancelado') DEFAULT 'pendiente',
    requiere_acudiente       BOOLEAN      DEFAULT FALSE,
    pdf_path                 VARCHAR(500) NULL,
    token_firma              VARCHAR(64)  UNIQUE NOT NULL,
    token_expira_at          TIMESTAMP    NULL,
    ip_generacion            VARCHAR(45)  NULL,
    created_at               TIMESTAMP    NULL,
    updated_at               TIMESTAMP    NULL
);
```

### 8. Tabla firmas_ci:
```sql
CREATE TABLE firmas_ci (
    id                BIGINT    PRIMARY KEY AUTO_INCREMENT,
    consentimiento_id BIGINT    NOT NULL,
    tipo_firmante     ENUM('paciente','acudiente','profesional') NOT NULL,
    firma_base64      LONGTEXT  NOT NULL,
    firmante_nombre   VARCHAR(200) NOT NULL,
    firmante_cedula   VARCHAR(20)  NULL,
    firmante_relacion VARCHAR(100) NULL,   -- padre/madre/tutor/cónyuge/hermano/otro
    ip_firma          VARCHAR(45)  NULL,
    user_agent        VARCHAR(500) NULL,
    firmado_at        TIMESTAMP NOT NULL,
    created_at        TIMESTAMP NULL,
    updated_at        TIMESTAMP NULL
);
```

### 9. Tabla acudientes_ci:
```sql
CREATE TABLE acudientes_ci (
    id                    BIGINT       PRIMARY KEY AUTO_INCREMENT,
    consentimiento_id     BIGINT       NOT NULL,
    nombre_completo       VARCHAR(200) NOT NULL,
    cedula                VARCHAR(20)  NOT NULL,
    relacion_con_paciente VARCHAR(100) NOT NULL,
    telefono              VARCHAR(20)  NULL,
    created_at            TIMESTAMP    NULL,
    updated_at            TIMESTAMP    NULL
);
```

### 10. Tabla importacion_plantillas_ci (para la carga masiva de ~100 consentimientos):
```sql
CREATE TABLE importacion_plantillas_ci (
    id              BIGINT    PRIMARY KEY AUTO_INCREMENT,
    nombre          VARCHAR(200) NOT NULL,
    especialidades  VARCHAR(500) NULL,   -- nombres separados por coma
    cups_codigo     VARCHAR(20)  NULL,
    uso_general     BOOLEAN      DEFAULT FALSE,
    contenido_texto LONGTEXT     NOT NULL,
    contenido_html  LONGTEXT     NULL,
    estado          ENUM('pendiente','procesado','error') DEFAULT 'pendiente',
    error_mensaje   TEXT         NULL,
    created_at      TIMESTAMP    NULL,
    updated_at      TIMESTAMP    NULL
);
```

---

## MODELOS

### Profesional:
```php
class Profesional extends Model
{
    protected $table    = 'profesionales';
    protected $fillable = [
        'usuario_id','especialidad_id','codigo_usuario',
        'nombres','apellidos','tipo_documento','numero_documento',
        'registro_medico','tarjeta_profesional','telefono','email',
        'firma_base64','firma_actualizada_at','activo'
    ];
    protected $casts = ['activo' => 'boolean'];

    public function especialidad()  { return $this->belongsTo(Especialidad::class); }
    public function usuario()       { return $this->belongsTo(\App\Usuarios::class, 'usuario_id'); }
    public function agendas()       { return $this->hasMany(AgendaCI::class, 'profesional_id'); }
    public function consentimientos(){ return $this->hasMany(ConsentimientoInformado::class, 'profesional_id'); }

    public function tieneFirmaRegistrada(): bool { return !empty($this->firma_base64); }

    public function getNombreCompletoAttribute(): string
    {
        return trim($this->nombres . ' ' . $this->apellidos);
    }

    // Plantillas disponibles según especialidad
    public function plantillasDisponibles()
    {
        if ($this->especialidad_id) {
            return PlantillaCI::activo()
                ->where(function($q) {
                    $q->where('uso_general', true)
                      ->orWhereHas('especialidades', function($q2) {
                          $q2->where('especialidades.id', $this->especialidad_id);
                      });
                })
                ->orderBy('nombre')
                ->get();
        }
        return PlantillaCI::activo()->where('uso_general', true)->orderBy('nombre')->get();
    }

    public function scopeActivo($q)   { return $q->where('activo', true); }
    public function scopeByCodigo($q, $codigo) {
        return $q->where('codigo_usuario', trim($codigo));
    }
}
```

### Paciente:
```php
class Paciente extends Model
{
    protected $table    = 'pacientes';
    protected $fillable = [
        'tipo_documento','numero_documento','nombres','apellidos',
        'fecha_nacimiento','edad','genero','telefono','historia_clinica','email'
    ];

    public function consentimientos() { return $this->hasMany(ConsentimientoInformado::class, 'paciente_id'); }
    public function agendas()         { return $this->hasMany(AgendaCI::class, 'paciente_id'); }

    public function getNombreCompletoAttribute(): string
    {
        return trim($this->nombres . ' ' . $this->apellidos);
    }

    /**
     * Busca o crea un paciente por tipo+número de documento
     */
    public static function buscarOCrear(array $datos): self
    {
        return static::firstOrCreate(
            ['tipo_documento' => $datos['tipo_documento'], 'numero_documento' => $datos['numero_documento']],
            $datos
        );
    }

    public function scopeByDocumento($q, $tipo, $numero)
    {
        return $q->where('tipo_documento', $tipo)->where('numero_documento', $numero);
    }
}
```

### Especialidad:
```php
class Especialidad extends Model
{
    protected $table    = 'especialidades';
    protected $fillable = ['nombre','codigo','descripcion','activo'];
    protected $casts    = ['activo' => 'boolean'];

    public function plantillas()    { return $this->belongsToMany(PlantillaCI::class, 'especialidad_plantilla_ci', 'especialidad_id', 'plantilla_ci_id'); }
    public function profesionales() { return $this->hasMany(Profesional::class, 'especialidad_id'); }
    public function scopeActivo($q) { return $q->where('activo', true); }
}
```

### PlantillaCI:
```php
class PlantillaCI extends Model
{
    protected $table    = 'plantillas_ci';
    protected $fillable = ['nombre','descripcion','cups_codigo','contenido_html','variables_disponibles','activo','uso_general'];
    protected $casts    = ['variables_disponibles' => 'array', 'activo' => 'boolean', 'uso_general' => 'boolean'];

    public function especialidades() { return $this->belongsToMany(Especialidad::class, 'especialidad_plantilla_ci', 'plantilla_ci_id', 'especialidad_id'); }
    public function scopeActivo($q)  { return $q->where('activo', true); }

    public function renderizar(array $variables): string
    {
        $contenido = $this->contenido_html;
        foreach ($variables as $clave => $valor) {
            $contenido = str_replace('{{' . $clave . '}}', $valor ?? '', $contenido);
        }
        return $contenido;
    }

    public static function variablesDisponibles(): array
    {
        return [
            '{{paciente_nombre}}'     => 'Nombre completo del paciente',
            '{{paciente_cedula}}'     => 'Número de documento',
            '{{paciente_tipo_doc}}'   => 'Tipo de documento (CC, TI, CE...)',
            '{{paciente_edad}}'       => 'Edad del paciente',
            '{{paciente_genero}}'     => 'Género del paciente',
            '{{profesional_nombre}}'  => 'Nombre del profesional de salud',
            '{{registro_medico}}'     => 'Número de registro médico',
            '{{tarjeta_profesional}}' => 'Número de tarjeta profesional',
            '{{especialidad}}'        => 'Especialidad del profesional',
            '{{fecha_procedimiento}}' => 'Fecha del procedimiento',
            '{{cups_codigo}}'         => 'Código CUPS',
            '{{cups_descripcion}}'    => 'Descripción del procedimiento',
            '{{clinica_nombre}}'      => 'Nombre de la clínica',
            '{{clinica_direccion}}'   => 'Dirección de la clínica',
            '{{fecha_actual}}'        => 'Fecha de generación del documento',
        ];
    }
}
```

### ConsentimientoInformado:
```php
class ConsentimientoInformado extends Model
{
    // fillable con todos los campos

    public function agenda()          { return $this->belongsTo(AgendaCI::class, 'agenda_ci_id'); }
    public function paciente()        { return $this->belongsTo(Paciente::class, 'paciente_id'); }
    public function profesional()     { return $this->belongsTo(Profesional::class, 'profesional_id'); }
    public function especialidad()    { return $this->belongsTo(Especialidad::class, 'especialidad_id'); }
    public function plantilla()       { return $this->belongsTo(PlantillaCI::class, 'plantilla_id'); }
    public function firmas()          { return $this->hasMany(FirmaCI::class, 'consentimiento_id'); }
    public function acudiente()       { return $this->hasOne(AcudienteCI::class, 'consentimiento_id'); }
    public function firmaPaciente()   { return $this->hasOne(FirmaCI::class,'consentimiento_id')->where('tipo_firmante','paciente'); }
    public function firmaAcudiente()  { return $this->hasOne(FirmaCI::class,'consentimiento_id')->where('tipo_firmante','acudiente'); }
    public function firmaProfesional(){ return $this->hasOne(FirmaCI::class,'consentimiento_id')->where('tipo_firmante','profesional'); }

    public function tokenEsValido(): bool
    {
        return $this->token_expira_at && $this->token_expira_at->isFuture();
    }

    public function estaCompleto(): bool
    {
        return $this->firmaPaciente()->exists()
            && $this->firmaProfesional()->exists()
            && (!$this->requiere_acudiente || $this->firmaAcudiente()->exists());
    }

    public function firmasFaltantes(): array
    {
        $f = [];
        if (!$this->firmaPaciente()->exists())    $f[] = 'paciente';
        if ($this->requiere_acudiente && !$this->firmaAcudiente()->exists()) $f[] = 'acudiente';
        if (!$this->firmaProfesional()->exists()) $f[] = 'profesional';
        return $f;
    }

    public function contadorFirmas(): string
    {
        $total    = $this->requiere_acudiente ? 3 : 2;
        $firmadas = $this->firmas()->count();
        return "$firmadas/$total";
    }
}
```

---

## PLANTILLA HTML BASE PARA CONSENTIMIENTOS

Todos los consentimientos de Clínica Fidem siguen esta estructura HTML.
Úsala como base en el seeder y en el importador:
```html
<!DOCTYPE html>
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
  <h3>Identificación y Descripción del Procedimiento</h3>
  <p><!-- AQUÍ VA EL CONTENIDO ESPECÍFICO DE CADA PROCEDIMIENTO --></p>
</div>

<div class="seccion">
  <h3>Objetivos del Procedimiento y Beneficios que se Esperan Alcanzar</h3>
  <p><!-- contenido específico --></p>
</div>

<div class="seccion">
  <h3>Alternativas Razonables al Procedimiento</h3>
  <p><!-- contenido específico --></p>
</div>

<div class="seccion">
  <h3>Consecuencias Previsibles de su Realización</h3>
  <p><!-- contenido específico --></p>
</div>

<div class="seccion">
  <h3>Consecuencias Previsibles de su NO Realización</h3>
  <p><!-- contenido específico --></p>
</div>

<div class="seccion">
  <h3>Riesgos Frecuentes</h3>
  <ol><!-- contenido específico --></ol>
</div>

<div class="seccion">
  <h3>Riesgos Poco Frecuentes de Especial Gravedad</h3>
  <p><!-- contenido específico --></p>
</div>

<div class="seccion">
  <h3>Contraindicaciones</h3>
  <p><!-- contenido específico --></p>
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
</html>
```

---

## SEEDERS — Dos consentimientos reales como ejemplo

El seeder debe crear exactamente estos dos consentimientos reales de Clínica Fidem
que servirán como ejemplo y verificación del sistema:

### CONSENTIMIENTO 1: Bloqueo Articulaciones Sacroilíacas
(especialidad: Medicina del Dolor, uso_general: false)

Descripción del procedimiento:
"Esta técnica consiste en la introducción de un medicamento (anestésico local o un
corticoide) en unas articulaciones grandes situadas entre el sacro (el final de la
columna) y las palas ilíacas (huesos de la pelvis) para tratar dolores de carácter
mecánico con esta localización. Es una técnica poco dolorosa, pero un poco incómoda.
Se emplean unas agujas largas dirigidas con radioscopia (rayos X) para localizar el
lugar de inyección. El bloqueo puede ser diagnóstico con anestésico local y efecto
pasajero (para localizar el origen del dolor), o terapéutico con un esteroide y de
mayor duración (para tratamiento del dolor). Suele durar entre 20 y 25 minutos.
Se suele canalizar una vía venosa (un suero) y se emplea anestesia local.
Se canalizará una vía venosa en uno de los brazos por si necesitara un sedante suave
y se vigilarán sus constantes vitales (tensión arterial, pulso...)."

Objetivos: "Mejoría del dolor."
Alternativas: "Tratamiento oral."
Consecuencias de realización: "Mejoría del dolor."
Consecuencias de NO realización: "Menor mejoría."
Riesgos frecuentes:
1. Molestias locales en el lugar de punción. Ceden en pocas horas con analgésicos convencionales.
2. Síncope vasovagal. Es un "mareo" que suele darse en ciertas personas ante determinadas situaciones (análisis, visión de sangre, dolor, etc.) Se acompaña de sensación de calor, sudor, y desvanecimiento. Debe avisar si nota estos síntomas. No es grave y cede con atropina (que se puede administrar de forma preventiva).
Riesgos poco frecuentes: "Existen otras complicaciones muy poco frecuentes, pero más graves: 1. Penetración de otras estructuras de la región."
Contraindicaciones: "No se podrá realizar si hay trastornos de la coagulación o infecciones en zona de punción."

### CONSENTIMIENTO 2: Bloqueo de Facetas Cervicales
(especialidad: Medicina del Dolor, uso_general: false)

Descripción del procedimiento:
"Esta técnica consiste en la introducción de un medicamento (anestésico local o un
corticoide) en unas pequeñas articulaciones de su columna vertebral cervical,
llamadas facetas para tratar dolores de la región cervical de carácter mecánico.
Es una técnica poco dolorosa, pero un poco incómoda. Se emplean unas agujas
dirigidas con radioscopia (rayos X) para localizar el lugar de inyección.
El bloqueo puede ser diagnóstico con anestésico local y efecto pasajero (para
localizar el origen del dolor), o terapéutico con un esteroide y de mayor duración
(para tratamiento del dolor). Suele durar entre 20 y 25 minutos. Se suele canalizar
una vía venosa (un suero) y se emplea anestesia local. Se canalizará una vía venosa
en uno de los brazos por si necesitara un sedante suave y se vigilarán sus constantes
vitales (tensión arterial, pulso...).
Si la patología fuera bilateral se podrá realizar el mismo procedimiento en zona
contralateral en días diferentes."

Objetivos: "Mejoría del dolor."
Alternativas: "Tratamiento oral."
Consecuencias de realización: "Mejoría del dolor."
Consecuencias de NO realización: "No mejoría del dolor."
Riesgos frecuentes:
1. Molestias locales en el lugar de punción. Ceden en pocas horas con analgésicos convencionales.
2. Síncope vasovagal. Es un "mareo" que suele darse en ciertas personas ante determinadas situaciones. Se acompaña de sensación de calor, sudor y desvanecimiento. No es grave y cede con atropina.
Riesgos poco frecuentes: "Existen otras complicaciones muy poco frecuentes, como es la penetración de otras estructuras de la región. En cualquier caso, dado el poco calibre de las agujas, no suelen ser graves."
Contraindicaciones: "No se podrá realizar si hay trastornos de la coagulación o infecciones en la zona de punción."

---

## SERVICIOS

### AgendaSyncService:
```php
public function sincronizarRango(int $diasAtras = 2, int $diasAdelante = 3): array
{
    $fechaInicio = Carbon::today()->subDays($diasAtras)->format('Y-m-d') . ' 00:00:00';
    $fechaFin    = Carbon::today()->addDays($diasAdelante)->format('Y-m-d') . ' 23:59:59';

    $citas = DB::connection('sqlsrv1')
        ->table('fac_m_citas')
        ->whereRaw("FECHA >= CONVERT(datetime, '$fechaInicio', 120)")
        ->whereRaw("FECHA <= CONVERT(datetime, '$fechaFin', 120)")
        ->whereNotNull('CODIGO_USUARIO')
        ->orderBy('FECHA')
        ->get();

    $creados = $actualizados = 0;

    foreach ($citas as $cita) {
        $idRegistro   = trim($cita->ID_REGISTRO);
        $codigoUsuario = trim($cita->CODIGO_USUARIO);

        // Buscar o crear paciente
        $tipoDoc      = $this->mapTipoDoc((int)$cita->TIPDOCUM);
        $cedula       = trim($cita->NUMDOCUM);
        $nombrePaciente = trim($cita->NOMBRE1.' '.$cita->NOMBRE2.' '.$cita->APELLIDO1.' '.$cita->APELLIDO2);

        $paciente = Paciente::firstOrCreate(
            ['tipo_documento' => $tipoDoc, 'numero_documento' => $cedula],
            [
                'nombres'          => trim($cita->NOMBRE1.' '.$cita->NOMBRE2),
                'apellidos'        => trim($cita->APELLIDO1.' '.$cita->APELLIDO2),
                'telefono'         => trim($cita->TELEFONO ?? ''),
                'historia_clinica' => trim($cita->HISTORIA ?? ''),
            ]
        );

        // Buscar profesional por codigo_usuario
        $profesional = Profesional::where('codigo_usuario', $codigoUsuario)->first();

        $datos = [
            'fecha'              => Carbon::parse($cita->FECHA)->format('Y-m-d H:i:s'),
            'codigo_consultorio' => trim($cita->CODIGO ?? ''),
            'historia'           => trim($cita->HISTORIA ?? ''),
            'paciente_id'        => $paciente->id,
            'paciente_nombre'    => $nombrePaciente,
            'paciente_cedula'    => $cedula,
            'paciente_tipo_doc'  => $tipoDoc,
            'paciente_telefono'  => trim($cita->TELEFONO ?? ''),
            'profesional_id'     => $profesional?->id,
            'codigo_usuario'     => $codigoUsuario,
            'cups_codigo'        => trim($cita->CODIGO_CUPS ?? ''),
            'contrato'           => trim($cita->CONTRATO ?? ''),
            'empresafac'         => trim($cita->EMPRESAFAC ?? ''),
            'llegada_confirmada' => !empty(trim($cita->NUMERO_FACTURA ?? '')),
            'numero_factura'     => !empty(trim($cita->NUMERO_FACTURA ?? '')) ? trim($cita->NUMERO_FACTURA) : null,
            'atencion_factura'   => !empty($cita->ATENCION_FACTURA) ? Carbon::parse($cita->ATENCION_FACTURA)->format('Y-m-d H:i:s') : null,
            'sincronizado_at'    => now(),
        ];

        $existe = AgendaCI::where('id_registro', $idRegistro)->first();
        if ($existe) { $existe->update($datos); $actualizados++; }
        else { AgendaCI::create(array_merge(['id_registro' => $idRegistro], $datos)); $creados++; }
    }

    return compact('creados', 'actualizados') + ['total' => count($citas)];
}

private function mapTipoDoc(int $t): string
{
    return match($t) { 1=>'CC', 2=>'TI', 3=>'CE', 4=>'RC', 5=>'PA', default=>'CC' };
}
```

### AgendaActualizadorService:
```php
// actualizarUno(AgendaCI $agenda): bool
// Consulta solo ID_REGISTRO específico en fac_m_citas
// Actualiza llegada_confirmada, numero_factura, atencion_factura
// Retorna true si llegó

// actualizarPendientesDeHoy(): int
// Trae IDs pendientes del día desde agenda_ci local
// Una sola query a fac_m_citas con whereIn de esos IDs
// Actualiza solo los que ahora tienen NUMERO_FACTURA
// Retorna cantidad actualizada
```

### PdfConsentimientoService:
```php
public function generar(ConsentimientoInformado $c): string
{
    $prof = $c->profesional;
    $variables = [
        'paciente_nombre'     => $c->paciente_nombre,
        'paciente_cedula'     => $c->paciente_cedula,
        'paciente_tipo_doc'   => $c->paciente_tipo_doc,
        'paciente_edad'       => $c->paciente_edad ?? '',
        'paciente_genero'     => $c->paciente_genero ?? '',
        'profesional_nombre'  => $c->profesional_nombre,
        'registro_medico'     => $prof?->registro_medico ?? '',
        'tarjeta_profesional' => $prof?->tarjeta_profesional ?? '',
        'especialidad'        => $c->especialidad?->nombre ?? '',
        'fecha_procedimiento' => Carbon::parse($c->fecha_procedimiento)->format('d/m/Y'),
        'cups_codigo'         => $c->cups_codigo ?? '',
        'cups_descripcion'    => $c->cups_descripcion ?? '',
        'clinica_nombre'      => config('app.clinica_nombre', 'Clínica Fidem'),
        'clinica_direccion'   => config('app.clinica_direccion', 'Manizales, Colombia'),
        'fecha_actual'        => now()->format('d/m/Y H:i'),
    ];

    $contenidoRenderizado = $c->plantilla->renderizar($variables);

    $pdf = PDF::loadView('consentimientos.pdf', [
        'consentimiento'       => $c,
        'contenidoRenderizado' => $contenidoRenderizado,
        'firmaPaciente'        => $c->firmaPaciente,
        'firmaAcudiente'       => $c->firmaAcudiente,
        'firmaProfesional'     => $c->firmaProfesional,
    ]);

    $carpeta = storage_path('app/consentimientos/' . now()->format('Y/m'));
    if (!file_exists($carpeta)) mkdir($carpeta, 0755, true);
    $ruta = $carpeta . '/' . $c->id . '_consentimiento.pdf';
    $pdf->save($ruta);
    $c->update(['pdf_path' => 'consentimientos/' . now()->format('Y/m') . '/' . $c->id . '_consentimiento.pdf']);
    return $ruta;
}
```

### PlantillaCIImportadorService:
```php
// textoAHtml(string $texto): string
// Convierte texto plano a HTML limpio con párrafos

// procesarImportacion(ImportacionPlantillaCI $imp): PlantillaCI
// Usa la plantilla HTML base del sistema
// Inserta el contenido específico en las secciones correspondientes
// Crea en plantillas_ci y asocia a especialidades por nombre

// procesarTodas(): array
// Procesa todas las pendientes, retorna ['procesadas' => X, 'errores' => Y]
```

---

## CONTROLADORES

### ProfesionalController (CRUD admin):
- index, create, store, edit, update, destroy
- Vista de registro de firma: GET /admin/profesionales/{id}/firma
- Guardar firma: POST /admin/profesionales/{id}/firma
- El profesional también puede registrar su propia firma desde su perfil si tiene login

### PacienteController (solo admin, lectura + edición):
- index con búsqueda por nombre/cédula
- show con historial de consentimientos
- edit/update para corregir datos

### ConsentimientoController:
```php
public function create(Request $request)
{
    // Obtener profesional autenticado (por usuario_id)
    $profesional = Profesional::where('usuario_id', auth()->id())->first();

    if (!$profesional) {
        return redirect()->back()->with('error', 'Tu usuario no tiene un perfil de profesional asignado. Contacta al administrador.');
    }

    if (!$profesional->tieneFirmaRegistrada()) {
        return redirect()->route('perfil.firma')
            ->with('warning', 'Debes registrar tu firma digital antes de generar consentimientos.');
    }

    $agendaId   = $request->get('agenda_id');
    $agenda     = $agendaId ? AgendaCI::with(['paciente'])->findOrFail($agendaId) : null;
    $plantillas = $profesional->plantillasDisponibles();

    return view('consentimientos.create', compact('agenda', 'plantillas', 'profesional'));
}

public function store(Request $request)
{
    $profesional = Profesional::where('usuario_id', auth()->id())->firstOrFail();

    if (!$profesional->tieneFirmaRegistrada()) {
        return redirect()->route('perfil.firma')->with('warning', 'Registra tu firma primero.');
    }

    $request->validate([
        'agenda_ci_id'        => 'nullable|exists:agenda_ci,id',
        'plantilla_id'        => 'required|exists:plantillas_ci,id',
        'paciente_nombre'     => 'required|string|max:200',
        'paciente_cedula'     => 'required|string|max:20',
        'paciente_tipo_doc'   => 'required|string|max:5',
        'paciente_edad'       => 'nullable|integer|min:0|max:120',
        'paciente_genero'     => 'nullable|in:M,F,O',
        'fecha_procedimiento' => 'required|date',
        'requiere_acudiente'  => 'nullable|boolean',
        'cups_codigo'         => 'nullable|string|max:20',
        'cups_descripcion'    => 'nullable|string|max:300',
    ]);

    // Buscar o crear paciente
    $paciente = Paciente::firstOrCreate(
        ['tipo_documento' => $request->paciente_tipo_doc, 'numero_documento' => $request->paciente_cedula],
        [
            'nombres'   => explode(' ', $request->paciente_nombre, 2)[0] ?? $request->paciente_nombre,
            'apellidos' => explode(' ', $request->paciente_nombre, 2)[1] ?? '',
            'edad'      => $request->paciente_edad,
            'genero'    => $request->paciente_genero,
        ]
    );

    $consentimiento = ConsentimientoInformado::create([
        'agenda_ci_id'        => $request->agenda_ci_id,
        'paciente_id'         => $paciente->id,
        'paciente_nombre'     => $request->paciente_nombre,
        'paciente_cedula'     => $request->paciente_cedula,
        'paciente_tipo_doc'   => $request->paciente_tipo_doc,
        'paciente_edad'       => $request->paciente_edad,
        'paciente_genero'     => $request->paciente_genero,
        'profesional_id'      => $profesional->id,
        'profesional_nombre'  => $profesional->nombre_completo,
        'especialidad_id'     => $profesional->especialidad_id,
        'plantilla_id'        => $request->plantilla_id,
        'cups_codigo'         => $request->cups_codigo,
        'cups_descripcion'    => $request->cups_descripcion,
        'fecha_procedimiento' => $request->fecha_procedimiento,
        'requiere_acudiente'  => $request->boolean('requiere_acudiente'),
        'estado'              => 'pendiente',
        'token_firma'         => \Str::random(64),
        'token_expira_at'     => now()->addHours(24),
        'ip_generacion'       => $request->ip(),
    ]);

    // Estampar firma del profesional automáticamente
    FirmaCI::create([
        'consentimiento_id' => $consentimiento->id,
        'tipo_firmante'     => 'profesional',
        'firma_base64'      => $profesional->firma_base64,
        'firmante_nombre'   => $profesional->nombre_completo,
        'firmante_cedula'   => $profesional->numero_documento ?? null,
        'ip_firma'          => $request->ip(),
        'user_agent'        => $request->userAgent(),
        'firmado_at'        => now(),
    ]);

    return redirect()->route('consentimientos.show', $consentimiento->id)
        ->with('success', 'Consentimiento generado. Comparte el enlace de firma con el paciente.');
}
```

---

## VISTA DE FIRMA — firmar.blade.php

Layout fullscreen sin sidebar. Optimizado para tablet y celular.
Fuente mínima 16px. Canvas mínimo 200px de alto, ancho 100%.
```javascript
var canvas = document.getElementById('firma-canvas');
var signaturePad = new SignaturePad(canvas, {
    minWidth: 1.5,
    maxWidth: 3.5,
    penColor: '#0A4D6B',
    backgroundColor: '#FFFFFF'
});

function redimensionarCanvas() {
    var ratio = Math.max(window.devicePixelRatio || 1, 1);
    canvas.width  = canvas.offsetWidth  * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext('2d').scale(ratio, ratio);
    signaturePad.clear();
}
window.addEventListener('resize', redimensionarCanvas);
redimensionarCanvas();

// Crítico para tablets — evita scroll mientras se firma
canvas.addEventListener('touchstart', function(e) { e.preventDefault(); }, { passive: false });
canvas.addEventListener('touchmove',  function(e) { e.preventDefault(); }, { passive: false });
```

Estructura visual:
┌──────────────────────────────────────┐
│  [Logo Fidem]          Paso X de Y   │
│  Consentimiento: [nombre]            │
├──────────────────────────────────────┤
│  [Contenido del consentimiento       │
│   en área de scroll independiente    │
│   — el canvas no se mueve]          │
├──────────────────────────────────────┤
│  Firmante: PACIENTE / ACUDIENTE      │
│                                      │
│  Nombre completo:  [________]     │
│  Tipo doc: [select] Número: []    │
│  (si acudiente) Relación: [select]   │
│  padre / madre / tutor /             │
│  cónyuge / hermano / otro            │
│                                      │
│ ┌──────────────────────────────────┐ │
│ │  Firme aquí con el dedo          │ │
│ │                                  │ │
│ │         (canvas táctil)          │ │
│ │                                  │ │
│ └──────────────────────────────────┘ │
│  [Limpiar firma]    [Confirmar ✓]   │
└──────────────────────────────────────┘

---

## VISTA INDEX — Lista de agenda del día

Columnas: Hora | Paciente | Cédula | Profesional | CUPS | Llegada | Consentimiento | Acciones

Badges llegada:
- Rojo "Sin llegar" si llegada_confirmada = false
- Verde "En clínica HH:mm" si llegada_confirmada = true

Badges consentimiento:
- Sin consentimiento → botón azul "Generar CI"
- En proceso → naranja "Firmas X/Y"
- Firmado → verde "Firmado ✓" + botón PDF

Botón "Actualizar llegadas" + auto-refresh AJAX cada 3 minutos.

---

## RUTAS en routes/web.php
```php
// Perfil — para profesionales con login
Route::get('/perfil/firma',  'PerfilController@verFirma')    ->name('perfil.firma');
Route::post('/perfil/firma', 'PerfilController@guardarFirma')->name('perfil.firma.guardar');

// Consentimientos
Route::prefix('consentimientos')->name('consentimientos.')->group(function () {
    Route::get('/',                               'ConsentimientoController@index')               ->name('index');
    Route::get('/crear',                          'ConsentimientoController@create')              ->name('create');
    Route::post('/',                              'ConsentimientoController@store')               ->name('store');
    Route::post('/actualizar-llegadas',           'ConsentimientoController@ajaxActualizarLlegadas')->name('actualizar-llegadas');
    Route::get('/{consentimiento}',               'ConsentimientoController@show')                ->name('show');
    Route::get('/{consentimiento}/pdf',           'ConsentimientoController@generarPDF')          ->name('pdf');
    Route::delete('/{consentimiento}',            'ConsentimientoController@destroy')             ->name('destroy');
    Route::get('/{consentimiento}/firmar/{tipo}', 'ConsentimientoController@firmar')              ->name('firmar');
    Route::post('/{consentimiento}/firma',        'ConsentimientoController@guardarFirma')        ->name('guardar-firma');
});

// Admin — profesionales
Route::prefix('admin/profesionales')->name('profesionales.')->middleware('role:admin')->group(function () {
    Route::get('/',                           'ProfesionalController@index')        ->name('index');
    Route::get('/crear',                      'ProfesionalController@create')       ->name('create');
    Route::post('/',                          'ProfesionalController@store')        ->name('store');
    Route::get('/{profesional}/editar',       'ProfesionalController@edit')         ->name('edit');
    Route::put('/{profesional}',              'ProfesionalController@update')       ->name('update');
    Route::delete('/{profesional}',           'ProfesionalController@destroy')      ->name('destroy');
    Route::get('/{profesional}/firma',        'ProfesionalController@verFirma')     ->name('firma');
    Route::post('/{profesional}/firma',       'ProfesionalController@guardarFirma') ->name('firma.guardar');
});

// Admin — pacientes
Route::prefix('admin/pacientes')->name('pacientes.')->middleware('role:admin')->group(function () {
    Route::get('/',                    'PacienteController@index')  ->name('index');
    Route::get('/{paciente}',          'PacienteController@show')   ->name('show');
    Route::get('/{paciente}/editar',   'PacienteController@edit')   ->name('edit');
    Route::put('/{paciente}',          'PacienteController@update') ->name('update');
});

// Admin — especialidades
Route::prefix('admin/especialidades')->name('especialidades.')->middleware('role:admin')->group(function () {
    Route::get('/',                      'EspecialidadController@index')  ->name('index');
    Route::get('/crear',                 'EspecialidadController@create') ->name('create');
    Route::post('/',                     'EspecialidadController@store')  ->name('store');
    Route::get('/{esp}/editar',          'EspecialidadController@edit')   ->name('edit');
    Route::put('/{esp}',                 'EspecialidadController@update') ->name('update');
    Route::delete('/{esp}',              'EspecialidadController@destroy')->name('destroy');
});

// Admin — plantillas
Route::prefix('admin/plantillas-ci')->name('plantillas-ci.')->middleware('role:admin')->group(function () {
    Route::get('/',                    'PlantillaCIController@index')  ->name('index');
    Route::get('/crear',               'PlantillaCIController@create') ->name('create');
    Route::post('/',                   'PlantillaCIController@store')  ->name('store');
    Route::get('/{plt}/editar',        'PlantillaCIController@edit')   ->name('edit');
    Route::put('/{plt}',               'PlantillaCIController@update') ->name('update');
    Route::delete('/{plt}',            'PlantillaCIController@destroy')->name('destroy');
    Route::get('/{plt}/preview',       'PlantillaCIController@preview')->name('preview');
});

// Admin — importador masivo
Route::prefix('admin/importar-plantillas')->name('importar-plantillas.')->middleware('role:admin')->group(function () {
    Route::get('/',                        'PlantillaCIImportadorController@index')         ->name('index');
    Route::post('/guardar-una',            'PlantillaCIImportadorController@guardarUna')    ->name('guardar-una');
    Route::post('/procesar-todas',         'PlantillaCIImportadorController@procesarTodas') ->name('procesar-todas');
    Route::post('/{imp}/procesar',         'PlantillaCIImportadorController@procesarUna')   ->name('procesar-una');
    Route::delete('/{imp}',               'PlantillaCIImportadorController@eliminar')       ->name('eliminar');
});
```

---

## JOB, COMMAND Y SCHEDULER
```php
// SincronizarAgendasCIJob — implements ShouldQueue
// Llama AgendaSyncService::sincronizarRango()

// Artisan Command: ci:sincronizar
// {--dias-adelante=3} {--dias-atras=2} {--fecha=}

// Kernel.php:
$schedule->command('ci:sincronizar')->dailyAt('06:00');
$schedule->command('ci:sincronizar --dias-adelante=0 --dias-atras=0')->dailyAt('08:00');
$schedule->command('ci:sincronizar --dias-adelante=0 --dias-atras=0')->dailyAt('12:00');
```

---

## SEEDERS
```php
// DatabaseSeeder orden:
EspecialidadSeeder::class,
ProfesionalSeeder::class,    // 2 profesionales de ejemplo sin firma aún
PlantillaCISeeder::class,    // los 2 consentimientos reales descritos arriba
```

### EspecialidadSeeder:
```php
$especialidades = [
    ['nombre' => 'Medicina General',            'codigo' => 'MG'],
    ['nombre' => 'Ortopedia y Traumatología',   'codigo' => 'OT'],
    ['nombre' => 'Medicina del Dolor',          'codigo' => 'MD'],
    ['nombre' => 'Medicina Deportiva',          'codigo' => 'MDE'],
    ['nombre' => 'Fisioterapia',                'codigo' => 'FT'],
    ['nombre' => 'Neurología',                  'codigo' => 'NEU'],
    ['nombre' => 'Cirugía General',             'codigo' => 'CG'],
    ['nombre' => 'Anestesiología',              'codigo' => 'ANE'],
    ['nombre' => 'Enfermería',                  'codigo' => 'ENF'],
    ['nombre' => 'Medicina Interna',            'codigo' => 'MI'],
];
```

### ProfesionalSeeder:
```php
// Crear 2 profesionales de ejemplo para pruebas
// Asociados a especialidades ya creadas
// Sin firma_base64 aún — se registrará desde el panel
// codigo_usuario que coincida con CODIGO_USUARIO de fac_m_citas
```

---

## DEPENDENCIAS
```bash
composer require barryvdh/laravel-dompdf:^0.8
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

config/app.php:
```php
'providers' => [Barryvdh\DomPDF\ServiceProvider::class],
'aliases'   => ['PDF' => Barryvdh\DomPDF\Facade::class],
```

CDN en vistas de firma y perfil:
```html
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
```

---

## RESTRICCIONES

- Laravel 5.7 estrictamente
- NO modificar la tabla Usuarios existente
- Bootstrap 4 + jQuery únicamente, sin Vue ni Alpine
- Comentarios en español
- firma_base64 siempre LONGTEXT
- Sin Route::controller() — usar notación 'Controller@metodo'
- @csrf en todos los formularios
- NUNCA whereBetween ni bind de fechas en sqlsrv1
- SIEMPRE whereRaw con CONVERT(datetime, '$fecha', 120) interpolado
- Siempre trim() en campos de fac_m_citas
- El modelo de autenticación se llama Usuarios (no User)

---

## ORDEN DE IMPLEMENTACIÓN

1.  Migraciones: especialidades → profesionales → pacientes →
    plantillas_ci → especialidad_plantilla_ci → importacion_plantillas_ci →
    agenda_ci → consentimientos_informados → firmas_ci → acudientes_ci

2.  Modelos: Especialidad, Profesional, Paciente, PlantillaCI,
    ImportacionPlantillaCI, AgendaCI, ConsentimientoInformado,
    FirmaCI, AcudienteCI

3.  Seeders: EspecialidadSeeder → ProfesionalSeeder → PlantillaCISeeder

4.  Servicios: AgendaSyncService, AgendaActualizadorService,
    PdfConsentimientoService, PlantillaCIImportadorService

5.  Job + Command + Kernel

6.  Controladores: PerfilController, EspecialidadController,
    ProfesionalController, PacienteController, PlantillaCIController,
    PlantillaCIImportadorController, ConsentimientoController

7.  Rutas en web.php

8.  Vistas:
    a. perfil/firma.blade.php
    b. consentimientos/firmar.blade.php — táctil, la más importante
    c. consentimientos/index.blade.php
    d. consentimientos/show.blade.php
    e. consentimientos/create.blade.php
    f. consentimientos/pdf.blade.php — sigue la estructura HTML real de los Word
    g. admin/importador-plantillas/index.blade.php
    h. admin/profesionales/ — CRUD + vista de registro de firma
    i. admin/pacientes/ — índice y detalle
    j. admin/plantillas-ci/ — CRUD
    k. admin/especialidades/ — CRUD

9.  Integrar "Consentimientos" en el menú existente del sistema

## ACLARACIONES FINALES ANTES DE IMPLEMENTAR

### 1. Tabla pacientes — se autollena desde la sincronización de citas

La tabla pacientes NO se llena manualmente. Se crea automáticamente
durante el AgendaSyncService al procesar cada cita de fac_m_citas.

Los únicos campos que se llenan en la sincronización son los que
vienen en fac_m_citas:
- tipo_documento  → mapeado desde TIPDOCUM
- numero_documento → trim(NUMDOCUM)
- nombres          → trim(NOMBRE1 + ' ' + NOMBRE2)
- apellidos        → trim(APELLIDO1 + ' ' + APELLIDO2)
- telefono         → trim(TELEFONO)
- historia_clinica → trim(HISTORIA)

Todos los demás campos (fecha_nacimiento, edad, genero, email)
quedan en NULL. No se piden en ningún formulario por ahora.
Se podrán completar después desde /admin/pacientes si se necesita.

El método a usar siempre es firstOrCreate con la llave
(tipo_documento, numero_documento) para no duplicar pacientes:
```php
$paciente = Paciente::firstOrCreate(
    [
        'tipo_documento'   => $tipoDoc,
        'numero_documento' => trim($cita->NUMDOCUM),
    ],
    [
        'nombres'          => trim($cita->NOMBRE1 . ' ' . $cita->NOMBRE2),
        'apellidos'        => trim($cita->APELLIDO1 . ' ' . $cita->APELLIDO2),
        'telefono'         => trim($cita->TELEFONO ?? ''),
        'historia_clinica' => trim($cita->HISTORIA ?? ''),
        // El resto queda NULL
    ]
);
```

### 2. Modelo de autenticación — se llama Usuario (no User)

El modelo de la tabla de login existente se llama Usuario.
Usar este nombre en todas las referencias:
```php
// Correcto:
use App\Usuario;
$this->belongsTo(\App\Usuario::class, 'usuario_id');
auth()->user() // retorna una instancia de Usuario

// Incorrecto — NO usar:
use App\User;
```

En el modelo Profesional la relación es:
```php
public function usuario()
{
    return $this->belongsTo(\App\Usuario::class, 'usuario_id');
}
```

Y para obtener el profesional del usuario autenticado:
```php
$profesional = Profesional::where('usuario_id', auth()->id())->first();
```
---

## FASE FINAL — CARGA MASIVA

Cuando termines todo lo anterior, responde exactamente:
"CI-Fidem implementado. Listo para cargar los 100 consentimientos."

En ese momento el usuario procederá a:
1. Crear las especialidades reales de Clínica Fidem
2. Registrar los ~20 profesionales con sus especialidades y codigo_usuario
3. Registrar la firma de cada profesional desde /admin/profesionales/{id}/firma
4. Cargar los ~100 consentimientos desde /admin/importar-plantillas
   pegando el texto de cada Word y asignando especialidades
5. Probar el flujo completo de firma en tablet

Empieza mostrando la estructura completa de archivos a crear,
luego implementa comenzando por las migraciones.