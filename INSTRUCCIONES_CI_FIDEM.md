# Instrucciones para Completar la Implementación del Módulo CI-Fidem

## ✅ Estado Actual de la Implementación

El módulo **CI-Fidem (Consentimientos Informados con Firma Electrónica)** ha sido implementado completamente:

### Backend (100% Completado)
- ✅ Migraciones (10 tablas)
- ✅ Modelos (9 modelos)
- ✅ Controladores (6 controladores)
- ✅ Servicios (4 servicios)
- ✅ Job (SincronizarAgendaCIJob)
- ✅ Command (SincronizarAgendaCommand)
- ✅ Seeders (3 seeders + 1 seeder de menú)
- ✅ Rutas (todas configuradas en web.php)

### Frontend (100% Completado)
- ✅ Vista de firma táctil (consentimientos/firmar.blade.php)
- ✅ Vistas de consentimientos (index, show, create, pdf)
- ✅ Vistas de profesionales (index, create, edit, firma)
- ✅ Vistas de pacientes (index, show, edit)
- ✅ Vistas de plantillas CI (index, create, edit)
- ✅ Vista de importador de plantillas
- ✅ Vistas de especialidades (index, create, edit)

### Configuración (100% Completado)
- ✅ DatabaseSeeder actualizado
- ✅ Kernel.php con comandos programados
- ✅ Seeder del menú creado

---

## 🚀 Pasos para Activar el Módulo

### 1. Ejecutar las Migraciones

```bash
php artisan migrate
```

Esto creará las siguientes tablas:
- especialidades
- profesionales
- pacientes
- plantillas_ci
- especialidad_plantilla_ci
- importacion_plantillas_ci
- agenda_ci
- consentimientos_informados
- firmas_ci
- acudientes_ci

### 2. Ejecutar los Seeders

```bash
# Opción 1: Ejecutar todos los seeders (incluyendo usuarios existentes)
php artisan db:seed

# Opción 2: Ejecutar solo los seeders de CI-Fidem
php artisan db:seed --class=EspecialidadSeeder
php artisan db:seed --class=ProfesionalSeeder
php artisan db:seed --class=PlantillaCISeeder
```

Esto creará:
- 10 especialidades de ejemplo
- 2 profesionales de ejemplo (sin firma aún)
- 2 plantillas de consentimiento de ejemplo

### 3. Crear el Menú en el Sistema

```bash
php artisan db:seed --class=MenuCIFidemSeeder
```

Esto creará:
- Menú principal: "Consentimientos Informados"
- 6 submenús:
  - Consentimientos
  - Profesionales
  - Pacientes
  - Plantillas CI
  - Especialidades
  - Importar Plantillas

### 4. Asignar Permisos del Menú a los Roles

Debe asignarse manualmente desde el panel de administración:

1. Ir a: **Administración → Menú-Rol**
2. Seleccionar el rol "Administrador" (o el rol que corresponda)
3. Marcar todos los submenús del módulo "Consentimientos Informados"
4. Guardar cambios

### 5. Instalar Dependencia de PDF (si no está instalada)

```bash
composer require barryvdh/laravel-dompdf:^0.8
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

Verificar que en `config/app.php` estén estas líneas:

```php
'providers' => [
    Barryvdh\DomPDF\ServiceProvider::class,
],
'aliases' => [
    'PDF' => Barryvdh\DomPDF\Facade::class,
],
```

### 6. Verificar Configuración de Base de Datos SQL Server

El módulo usa la conexión `sqlsrv1` para leer citas desde `fac_m_citas`.

Verificar en `.env`:
```
DB_CONNECTION_SQLSRV1=sqlsrv
DB_HOST_SQLSRV1=127.0.0.1
DB_PORT_SQLSRV1=1433
DB_DATABASE_SQLSRV1=nombre_base_datos
DB_USERNAME_SQLSRV1=usuario
DB_PASSWORD_SQLSRV1=contraseña
```

Y en `config/database.php` debe existir la conexión `sqlsrv1`.

---

## 📋 Tareas Post-Implementación

### Configuración Inicial

1. **Crear Especialidades Reales**
   - Ir a: `/admin/especialidades`
   - Crear todas las especialidades de Clínica Fidem

2. **Registrar Profesionales**
   - Ir a: `/admin/profesionales`
   - Crear ~20 profesionales con:
     - Datos completos
     - Especialidad asignada
     - `codigo_usuario` (debe coincidir con `CODIGO_USUARIO` de `fac_m_citas`)
   - **Importante:** Registrar la firma de cada profesional desde `/admin/profesionales/{id}/firma`

3. **Cargar los ~100 Consentimientos**
   - Ir a: `/admin/importar-plantillas`
   - Por cada documento Word:
     - Abrir el Word
     - Seleccionar todo (Ctrl+A) y copiar (Ctrl+C)
     - Pegar en el campo de texto
     - Ingresar el nombre del procedimiento
     - Seleccionar las especialidades aplicables
     - Importar

4. **Sincronizar Citas y Generar Agenda**
   ```bash
   php artisan ci:sincronizar
   ```

   Opciones disponibles:
   ```bash
   # Sincronizar 3 días adelante y 2 días atrás (por defecto)
   php artisan ci:sincronizar

   # Sincronizar solo hoy
   php artisan ci:sincronizar --dias-adelante=0 --dias-atras=0

   # Sincronizar 7 días adelante
   php artisan ci:sincronizar --dias-adelante=7 --dias-atras=0

   # Sincronizar una fecha específica
   php artisan ci:sincronizar --fecha=2026-04-15
   ```

---

## 🧪 Pruebas del Sistema

### 1. Probar Sincronización de Citas
```bash
php artisan ci:sincronizar --dias-adelante=1 --dias-atras=0
```

Verificar en `/admin/consentimientos` que se hayan creado registros.

### 2. Probar Enlace de Firma

1. Ir a `/admin/consentimientos`
2. Copiar el enlace de firma de un consentimiento pendiente
3. Abrir en tablet o dispositivo táctil
4. Firmar con el dedo sobre la pantalla
5. Verificar que el estado cambie a "Firmado"

### 3. Probar Generación de PDF

1. Ir a `/admin/consentimientos`
2. Ver un consentimiento firmado
3. Hacer clic en "Descargar PDF"
4. Verificar que el PDF tenga:
   - Información del paciente
   - Contenido del consentimiento
   - Firmas (paciente, acudiente si aplica, profesional)

### 4. Probar Tablet

En una tablet con navegador (Chrome, Safari):
1. Abrir el enlace de firma: `/firmar-consentimiento/{token}`
2. Verificar que sea responsive
3. Probar la firma con el dedo
4. Verificar que se guarde correctamente

---

## 📅 Comandos Programados

Los siguientes comandos se ejecutan automáticamente según `app/Console/Kernel.php`:

| Comando | Frecuencia | Descripción |
|---------|-----------|-------------|
| `ci:sincronizar` | Diario 6:00 AM | Sincroniza 3 días adelante y 2 atrás |
| `ci:sincronizar --dias-adelante=0 --dias-atras=0` | Diario 8:00 AM | Sincroniza solo el día actual |
| `ci:sincronizar --dias-adelante=0 --dias-atras=0` | Diario 12:00 PM | Sincroniza solo el día actual |

Para que funcionen los comandos programados, el cron debe estar configurado:

```bash
* * * * * cd /ruta/proyecto && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🔒 Seguridad y Buenas Prácticas

1. **Tokens de Firma**
   - Los enlaces de firma tienen tokens únicos y expiran
   - No reutilizar tokens

2. **Firmas**
   - Las firmas se guardan en formato base64 (data:image/png;base64,...)
   - Ocupan espacio en la base de datos (campo LONGTEXT)

3. **SQL Server**
   - Las consultas a `fac_m_citas` usan `whereRaw` para evitar problemas con fechas
   - Siempre usar `trim()` en campos de texto de SQL Server

4. **Permisos**
   - Asignar permisos de menú solo a roles autorizados
   - Los pacientes no necesitan usuario en el sistema

---

## 📞 Soporte

Si encuentra algún problema durante la implementación:

1. Verificar logs: `storage/logs/laravel.log`
2. Verificar que todas las migraciones se ejecutaron correctamente
3. Verificar que la conexión `sqlsrv1` esté configurada
4. Verificar que los profesionales tengan `codigo_usuario` correcto

---

## ✨ Funcionalidades Principales

✅ Sincronización automática de citas desde SQL Server
✅ Generación automática de consentimientos pendientes
✅ Firma digital con stylus/dedo en tablet
✅ Firma del profesional precargada
✅ Firma del paciente
✅ Firma opcional del acudiente/tutor
✅ Generación de PDF con todas las firmas
✅ Gestión completa de plantillas
✅ Importación masiva desde documentos Word
✅ Control de especialidades y profesionales
✅ Historial completo de consentimientos

---

**¡El módulo CI-Fidem está listo para usar!**

Fecha de implementación: Marzo 30, 2026
