# Flujo de Prueba - Módulo de Consentimientos Informados

## Resumen del Sistema

El módulo de Consentimientos Informados (CI-Fidem) permite:
- ✅ Crear consentimientos informados digitales
- ✅ Capturar firmas electrónicas a mano alzada (paciente, acudiente, profesional)
- ✅ Generar PDFs con todas las firmas
- ✅ Importar plantillas desde documentos Word
- ✅ Dashboard con estadísticas y métricas
- ✅ Gestión completa de pacientes, profesionales y plantillas

---

## Requisitos Previos

### 1. Instalación del Proyecto

```bash
# Instalar dependencias de composer
composer install

# Configurar archivo .env
cp .env.example .env
php artisan key:generate

# Ejecutar migraciones
php artisan migrate

# (Opcional) Seeders si existen
php artisan db:seed
```

### 2. Base de Datos

Asegúrese de que existan las siguientes tablas:
- `especialidades`
- `profesionales`
- `pacientes`
- `plantillas_ci`
- `especialidad_plantilla_ci`
- `agenda_ci`
- `consentimientos_informados`
- `firmas_ci`
- `acudientes_ci`
- `importacion_plantillas_ci`

---

## Flujo de Prueba Completo

### PASO 1: Acceder al Dashboard

1. **Iniciar sesión** en el sistema
2. **Ir a la página principal** (Home)
3. **Buscar el módulo** "Consentimientos Informados" (icono de file-signature, color azul)
4. **Hacer clic en "Dashboard"**

**Resultado esperado:**
- Vista con tarjetas de estadísticas:
  - Total Consentimientos
  - Pendientes de Firma
  - Completados
  - Este Mes
- Gráficos:
  - Top 5 Especialidades
  - Top 5 Profesionales
  - Tendencia por mes
- Tabla de últimos 10 consentimientos

**URL:** `/consentimientos/dashboard`

---

### PASO 2: Gestionar Profesionales

#### 2.1 Crear/Verificar Profesional

1. **Ir a:** Consentimientos > Profesionales
2. **Verificar** que exista al menos un profesional activo
3. **Si no existe**, crear uno nuevo:
   - Nombres, Apellidos
   - Tipo y Número de Documento
   - Especialidad (ej: Anestesiología, Cirugía, etc.)
   - Código de Usuario (para vincular con agenda)
   - Estado: Activo

#### 2.2 Configurar Firma del Profesional

1. **Desde el listado de profesionales**, hacer clic en el botón de "Configurar Firma"
2. **Dibujar la firma** en el canvas táctil
3. **Guardar** la firma
4. Esta firma se estampará automáticamente en todos los consentimientos que genere este profesional

**Resultado esperado:**
- Profesional creado con firma precargada
- La firma debe verse en la vista de detalle del profesional

---

### PASO 3: Gestionar Pacientes

#### 3.1 Crear/Verificar Paciente

1. **Ir a:** Consentimientos > Pacientes
2. **Verificar** que exista al menos un paciente
3. **Si no existe**, crear uno nuevo:
   - Nombres, Apellidos
   - Tipo y Número de Documento
   - Fecha de Nacimiento
   - Género
   - Teléfono
   - Dirección

**Resultado esperado:**
- Paciente registrado en el sistema
- Disponible para asignar a citas de agenda

---

### PASO 4: Gestionar Plantillas

#### 4.1 Importar Plantilla desde Word (OPCIONAL)

Si tiene un documento Word del consentimiento (como el ejemplo "BLOQUEO DEL NERVIO PERIFÉRICO"):

1. **Ir a:** Plantillas > Importar desde Word
2. **Subir el archivo** .docx
3. **El sistema extraerá** el contenido HTML
4. **Asignar:**
   - Nombre de la plantilla
   - Especialidad(es) relacionadas
   - Código CUPS (si aplica)
   - Variables disponibles

#### 4.2 Crear Plantilla Manualmente

1. **Ir a:** Consentimientos > Plantillas
2. **Hacer clic en "Nueva Plantilla"**
3. **Completar:**
   - Nombre: Ej: "BLOQUEO DEL NERVIO PERIFÉRICO"
   - Descripción
   - Código CUPS (ej: GC-SP-FO-041)
   - Contenido HTML con variables:
     - `{{paciente_nombre}}`
     - `{{paciente_cedula}}`
     - `{{paciente_edad}}`
     - `{{paciente_genero}}`
     - `{{profesional_nombre}}`
     - `{{registro_medico}}`
     - `{{fecha_procedimiento}}`
4. **Asociar a una o más especialidades**
5. **Activar** la plantilla

**Resultado esperado:**
- Plantilla creada y disponible para usar
- Se muestra solo para profesionales de las especialidades seleccionadas

---

### PASO 5: Crear Agenda de Citas

1. **Ir a:** Agenda CI (o módulo de citas)
2. **Crear una cita** con:
   - Fecha y hora del procedimiento
   - Paciente (seleccionar del listado)
   - Profesional (código de consultorio)
   - Centro productivo
   - Código CUPS
   - Observaciones

**Resultado esperado:**
- Cita registrada en `agenda_ci`
- Vinculada al paciente y profesional

---

### PASO 6: Crear Consentimiento Informado

Este es el proceso principal del módulo.

#### 6.1 Acceder al Formulario de Creación

**Opción A:** Desde el menú
1. **Ir a:** Consentimientos > Crear Nuevo
2. **Seleccionar filtros:**
   - Fecha de la cita
   - Especialista (código de usuario)
   - Centro productivo (opcional)
   - Paciente (opcional)

**Opción B:** Desde una agenda específica
1. **Ir a:** Agenda CI
2. **Hacer clic en "Crear Consentimiento"** para una cita específica
3. Los datos se pre-cargan automáticamente

#### 6.2 Completar el Formulario

1. **El sistema carga automáticamente:**
   - Pacientes disponibles con citas en esa fecha/profesional
   - Datos de cada cita (hora, centro, CUPS, observaciones)
   - Plantillas disponibles según la especialidad del profesional

2. **Seleccionar:**
   - Paciente (de la lista filtrada)
   - Agenda/Cita específica
   - Una o más plantillas de consentimiento
   - Fecha del procedimiento
   - Observaciones adicionales

3. **Marcar si requiere firma de acudiente** (para menores o incapaces)

4. **Hacer clic en "Guardar"**

**Resultado esperado:**
- Se crean tantos consentimientos como plantillas seleccionadas
- Estado inicial: "Pendiente"
- Se genera un `token_firma` único para cada consentimiento
- La firma del profesional se estampa automáticamente (si tiene firma configurada)

**URL:** `/consentimientos/crear`

---

### PASO 7: Firmar el Consentimiento (Paciente/Acudiente)

#### 7.1 Generar y Enviar el Link de Firma

1. **Desde el listado de consentimientos**, copiar el link de firma
   - Formato: `https://dominio.com/firmar-consentimiento/{token}`
2. **Enviar el link** al paciente vía:
   - Email
   - WhatsApp
   - SMS
   - QR Code (impreso)

#### 7.2 Proceso de Firma del Paciente

1. **El paciente abre el link** en su dispositivo (tablet, celular, PC con touch)
2. **Visualiza el consentimiento completo** con toda la información
3. **Secciones de firma disponibles:**

   **A. Firma del Paciente:**
   - Nombre completo (pre-llenado)
   - Número de documento (pre-llenado)
   - Canvas para dibujar firma a mano alzada
   - Botón "Guardar Firma"

   **B. Firma del Acudiente (si aplica):**
   - Nombre completo del acudiente
   - Número de documento
   - Relación con el paciente (padre/madre/tutor/cónyuge/etc.)
   - Canvas para dibujar firma
   - Botón "Guardar Firma"

4. **Al completar todas las firmas:**
   - Estado cambia a "Firmado"
   - Se genera automáticamente el PDF
   - Se bloquea el token de firma

**Resultado esperado:**
- Todas las firmas registradas en `firmas_ci`
- Datos del acudiente registrados en `acudientes_ci`
- PDF generado en `storage/consentimientos/`
- Estado del consentimiento: "Firmado"

**URL:** `/firmar-consentimiento/{token}`

---

### PASO 8: Verificar y Descargar PDF

1. **Ir a:** Consentimientos > Listado
2. **Buscar el consentimiento** recién firmado
3. **Verificar que el estado sea "Firmado"** (badge verde)
4. **Hacer clic en "Ver Detalles"**
5. **En la vista de detalle:**
   - Ver información completa del paciente
   - Ver información del profesional
   - Ver todas las firmas registradas (paciente, acudiente, profesional)
   - Fecha y hora de cada firma
   - IP desde donde se firmó
6. **Hacer clic en "Descargar PDF"**

**Resultado esperado:**
- Se descarga un PDF completo con:
  - Cabecera del consentimiento
  - Toda la información del procedimiento
  - Las 3 firmas (paciente, acudiente si aplica, profesional)
  - Fecha y hora de firma
  - Pie de página con información legal

**URL:** `/consentimientos/{id}`
**URL PDF:** `/consentimientos/{id}/pdf`

---

### PASO 9: Validar en el Dashboard

1. **Volver al Dashboard de Consentimientos**
2. **Verificar que las estadísticas se actualizaron:**
   - Total de consentimientos aumentó
   - Firmados aumentó
   - Pendientes disminuyó
   - Consentimientos del mes se actualizó
3. **Revisar gráficos:**
   - El profesional aparece en el top 5
   - La especialidad aparece en el top 5
   - El mes actual muestra el incremento
4. **Ver en "Últimos 10 consentimientos":**
   - El consentimiento recién creado debe aparecer primero

---

## Flujos Adicionales

### Cancelar un Consentimiento

1. **Ir al detalle del consentimiento**
2. **Hacer clic en "Cancelar"**
3. **Confirmar la acción**
4. **Estado cambia a "Cancelado"** (badge rojo)

### Regenerar Token de Firma (Si expiró)

Si el token de firma expiró (24 horas por defecto):

1. **Ir al detalle del consentimiento**
2. **Hacer clic en "Regenerar Token"**
3. **Se genera un nuevo token** con 24 horas más
4. **Enviar el nuevo link** al paciente

### Buscar Consentimientos

Desde el listado, se puede buscar por:
- Nombre del paciente
- Número de documento del paciente
- Nombre del profesional
- Estado (pendiente/en_proceso/firmado/cancelado)

---

## Validaciones y Reglas de Negocio

### ✅ Validaciones Implementadas

1. **Token de Firma:**
   - Único por consentimiento
   - Válido por 24 horas desde su generación
   - Se invalida automáticamente al completar todas las firmas

2. **Firmas:**
   - Un consentimiento DEBE tener firma del paciente
   - Un consentimiento DEBE tener firma del profesional
   - Si `requiere_acudiente = true`, DEBE tener firma del acudiente
   - No se pueden duplicar firmas del mismo tipo

3. **Estados:**
   - `pendiente`: Recién creado, sin firmas
   - `en_proceso`: Tiene al menos una firma, pero faltan otras
   - `firmado`: Tiene todas las firmas requeridas
   - `cancelado`: Fue cancelado manualmente

4. **Plantillas:**
   - Solo se muestran plantillas activas
   - Solo se muestran plantillas de la especialidad del profesional
   - Las plantillas con `uso_general = true` se muestran para todas las especialidades

5. **Profesionales:**
   - Deben tener firma configurada para estamparla automáticamente
   - Deben estar activos para aparecer en selectores
   - El código de usuario vincula con la agenda

6. **PDF:**
   - Solo se genera cuando el consentimiento está completo (estado = firmado)
   - Se almacena en `storage/app/public/consentimientos/`
   - Nombre formato: `consentimiento_{id}_{timestamp}.pdf`

---

## Casos de Prueba Sugeridos

### Caso 1: Flujo Completo Exitoso
- ✅ Crear profesional con firma
- ✅ Crear paciente
- ✅ Crear plantilla
- ✅ Crear agenda
- ✅ Crear consentimiento
- ✅ Firmar como paciente
- ✅ Verificar estado "firmado"
- ✅ Descargar PDF
- ✅ Verificar dashboard actualizado

### Caso 2: Consentimiento con Acudiente
- ✅ Crear consentimiento con `requiere_acudiente = true`
- ✅ Firmar como paciente
- ✅ Firmar como acudiente (con datos completos)
- ✅ Verificar que ambas firmas estén registradas
- ✅ Descargar PDF con 3 firmas

### Caso 3: Token Expirado
- ✅ Crear consentimiento
- ✅ Modificar `token_expira_at` a una fecha pasada
- ✅ Intentar abrir el link de firma
- ✅ Verificar mensaje "Token expirado"
- ✅ Regenerar token
- ✅ Firmar exitosamente

### Caso 4: Múltiples Plantillas
- ✅ Crear consentimiento seleccionando 3 plantillas
- ✅ Verificar que se crearon 3 registros independientes
- ✅ Cada uno con su propio token
- ✅ Firmar los 3 por separado
- ✅ Verificar 3 PDFs generados

### Caso 5: Dashboard con Datos
- ✅ Crear 20+ consentimientos
- ✅ 10 pendientes, 5 en proceso, 5 firmados
- ✅ De diferentes especialidades y profesionales
- ✅ De diferentes meses
- ✅ Verificar que los gráficos reflejen correctamente los datos

---

## Endpoints y Rutas

### Rutas Públicas (sin auth)
- `GET /firmar-consentimiento/{token}` - Vista de firma para paciente
- `POST /firmar-consentimiento/{token}` - Guardar firma

### Rutas Protegidas (con auth)
- `GET /consentimientos/dashboard` - Dashboard con estadísticas
- `GET /consentimientos` - Listado de consentimientos
- `GET /consentimientos/crear` - Formulario de creación
- `POST /consentimientos` - Guardar consentimiento(s)
- `GET /consentimientos/{id}` - Ver detalle
- `GET /consentimientos/{id}/pdf` - Descargar PDF
- `GET /consentimientos/crear-desde-agenda/{agenda_id}` - Crear desde agenda

### Rutas AJAX
- `GET /admin/ajax/pacientes-por-filtros` - Obtener pacientes filtrados
- `GET /admin/ajax/datos-paciente/{paciente_id}` - Datos completos de paciente
- `GET /admin/ajax/plantillas-por-especialidad/{especialidad_id}` - Plantillas por especialidad

---

## Troubleshooting

### Problema: No se muestran plantillas al crear consentimiento
**Causa:** No hay plantillas asociadas a la especialidad del profesional
**Solución:**
1. Ir a Plantillas
2. Editar la plantilla
3. Asociar a la especialidad correcta

### Problema: Error al generar PDF
**Causa:** Permisos en el directorio storage
**Solución:**
```bash
chmod -R 775 storage/
chown -R www-data:www-data storage/
```

### Problema: Token de firma no válido
**Causa:** El token expiró o el consentimiento ya está completo
**Solución:**
1. Verificar el estado del consentimiento
2. Si expiró, regenerar token desde el panel admin
3. Enviar nuevo link al paciente

### Problema: No se estampa la firma del profesional
**Causa:** El profesional no tiene firma configurada
**Solución:**
1. Ir a Profesionales
2. Hacer clic en "Configurar Firma"
3. Dibujar y guardar la firma
4. Crear un nuevo consentimiento (los existentes no se actualizan retroactivamente)

---

## Próximos Pasos

Una vez probado el flujo completo:

1. **Configurar envío automático de links** vía email/SMS
2. **Implementar notificaciones** cuando se completen firmas
3. **Agregar firma digital** con certificado (opcional)
4. **Implementar auditoría** de cambios
5. **Agregar reportes** exportables (Excel, CSV)
6. **Integrar con sistema de facturación** (si aplica)

---

## Contacto y Soporte

Para reportar bugs o solicitar nuevas funcionalidades:
- **Repositorio:** https://github.com/castrokof/fidem_nomina
- **Email:** [email del administrador]

---

**Última actualización:** 2026-03-31
**Versión del sistema:** 1.0.0
**Autor:** Claude Code - Asistente de Desarrollo
