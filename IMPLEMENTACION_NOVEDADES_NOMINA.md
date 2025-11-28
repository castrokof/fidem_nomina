# Implementación: Integración de Novedades en Nómina

## Resumen
Este documento describe la implementación completa del sistema de integración de novedades en la nómina, incluyendo validación, bloqueo y exportación.

## Componentes Implementados

### 1. Base de Datos

#### Migraciones Creadas:
- **`2025_11_25_000002_add_novedad_calculation_fields_to_nominaliquids_table.php`**
  - Agrega campos a la tabla `nominaliquids` para registrar:
    - `descuento_incapacidad`: Descuentos por incapacidad
    - `descuento_suspension`: Descuentos por suspensión
    - `pago_vacaciones`: Pagos por vacaciones
    - `otros_descuentos_novedades`: Otros descuentos
    - `otros_bonos_novedades`: Otros bonos
    - `novedades_aplicadas`: JSON con detalle de novedades aplicadas
    - `dias_trabajados`: Días efectivamente trabajados

- **`2025_11_25_000003_create_nomina_novedades_aplicadas_table.php`**
  - Crea tabla `nomina_novedades_aplicadas` para registrar:
    - Relación entre nómina y novedades aplicadas
    - Tipo de novedad y fechas
    - Días y valores aplicados
    - Tipo de afectación (descuento/bono/neutro)

### 2. Modelos

#### Modelo: `NominaNovedadesAplicadas`
**Ubicación:** `app/Models/Nomina/NominaNovedadesAplicadas.php`

Características:
- Relación con `nominaliquid`
- Relación con `EmpleadosNovedades`
- Campos fillable para novedades aplicadas

#### Modelo: `nominaliquid` (Actualizado)
**Ubicación:** `app/Models/Nomina/nominaliquid.php`

Cambios:
- Agregados nuevos campos al `$fillable`
- Nueva relación `novedadesAplicadas()`

### 3. Servicio de Cálculo de Novedades

#### Servicio: `NovedadesNominaService`
**Ubicación:** `app/Services/NovedadesNominaService.php`

Métodos principales:
- **`calcularNovedades($empleadoId, $fechaInicio, $fechaFin, $nominaliquidId = null)`**
  - Busca novedades activas del empleado en el período
  - Calcula días afectados y valores a aplicar
  - Clasifica novedades por tipo de afectación
  - Registra novedades aplicadas si se proporciona ID de nómina

- **`calcularDiasTrabajados($diasPeriodo, $diasAfectados, $novedadesDetalle)`**
  - Calcula días efectivamente trabajados
  - Considera solo suspensiones e incapacidades para descuentos

#### Lógica por Tipo de Novedad:
- **Incapacidad**: Genera descuento
- **Suspensión**: Genera descuento
- **Vacaciones**: Genera bono (se paga normalmente)
- **Licencia**: Depende del valor (puede ser bono o descuento)
- **Permiso**: Neutro (no afecta salario)
- **Otro**: Según el valor proporcionado

### 4. Controlador

#### Controlador: `NominaliquidController` (Actualizado)
**Ubicación:** `app/Http/Controllers/Nomina/NominaliquidController.php`

#### Métodos Nuevos:

1. **`validarNomina(Request $request)`**
   - Carga nómina por quincena para validación
   - Muestra resumen de totales
   - Retorna datos para vista de validación

2. **`bloquearNomina(Request $request)`**
   - Bloquea registros de nómina por quincena
   - Marca `is_locked = true`
   - Previene modificaciones posteriores

3. **`desbloquearNomina(Request $request)`**
   - Desbloquea nómina (solo admin)
   - Requiere motivo
   - Registra motivo en observaciones

4. **`exportarExcel(Request $request)`**
   - Genera archivo Excel con nómina completa
   - Incluye todos los campos de novedades
   - Formato profesional con encabezados

5. **`exportarPlano(Request $request)`**
   - Genera archivo de texto plano
   - Formato tabular con totales
   - Ideal para importación bancaria

#### Métodos Modificados:

**`store_nominaf(Request $request)`**
- Integra cálculo automático de novedades al crear nómina
- Calcula días trabajados efectivos
- Registra descuentos, bonos y novedades aplicadas
- Genera registro en `nomina_novedades_aplicadas`

### 5. Rutas

**Ubicación:** `routes/web.php`

#### Rutas Nuevas:
```php
// Validación y Bloqueo
Route::get('nomina-validar', 'NominaliquidController@validarNomina')->name('nomina_validar');
Route::post('nomina-validar', 'NominaliquidController@validarNomina')->name('nomina_validar_data');
Route::post('nomina-bloquear', 'NominaliquidController@bloquearNomina')->name('nomina_bloquear');
Route::post('nomina-desbloquear', 'NominaliquidController@desbloquearNomina')->name('nomina_desbloquear');

// Exportación
Route::get('nomina-exportar-excel', 'NominaliquidController@exportarExcel')->name('nomina_exportar_excel');
Route::get('nomina-exportar-plano', 'NominaliquidController@exportarPlano')->name('nomina_exportar_plano');
```

### 6. Vistas

#### Vista: `validar.blade.php`
**Ubicación:** `resources/views/nomina/nomina_fijos/validar.blade.php`

Características:
- Filtro por quincena e IPS
- Resumen con tarjetas informativas:
  - Total empleados
  - Total salarios
  - Total descuentos
  - Total bonos
- Tabla detallada con todos los conceptos
- Botones de acción:
  - Bloquear nómina
  - Exportar a Excel
  - Exportar a archivo plano
  - Desbloquear (solo admin)
- Integración con SweetAlert2 para confirmaciones
- Select2 para selección de quincena

## Flujo de Trabajo

### 1. Creación de Nómina con Novedades

```
Usuario → Selecciona empleados → Genera nómina
    ↓
Sistema busca novedades activas del empleado
    ↓
Calcula impacto de cada novedad
    ↓
Registra en nominaliquids con descuentos/bonos
    ↓
Crea registros en nomina_novedades_aplicadas
```

### 2. Validación y Bloqueo

```
Usuario → Accede a vista de validación
    ↓
Selecciona quincena → Carga resumen
    ↓
Revisa totales y detalle
    ↓
Bloquea nómina → is_locked = true
    ↓
Nómina protegida contra modificaciones
```

### 3. Exportación

```
Usuario → Nómina validada
    ↓
Selecciona formato (Excel/Plano)
    ↓
Sistema genera archivo con todos los datos
    ↓
Descarga automática
```

## Seguridad

- **Bloqueo de nómina**: Previene ediciones no autorizadas
- **Validación en update/destroy**: Verifica `is_locked`
- **Desbloqueo restringido**: Solo roles 1 y 4
- **Registro de motivos**: Trazabilidad de cambios

## Tipos de Novedades y su Impacto

| Tipo | Afectación | Impacto en Nómina |
|------|-----------|-------------------|
| Incapacidad | Descuento | Reduce salario proporcionalmente |
| Suspensión | Descuento | Reduce salario por días no trabajados |
| Vacaciones | Bono | Se paga normalmente |
| Licencia | Variable | Según valor (remunerada o no) |
| Permiso | Neutro | No afecta salario |
| Otro | Variable | Según valor proporcionado |

## Campos Calculados Automáticamente

1. **Descuentos**:
   - Incapacidad
   - Suspensión
   - Otros descuentos

2. **Bonos**:
   - Vacaciones
   - Otros bonos

3. **Días Trabajados**:
   - Días del período - Días de suspensión/incapacidad

4. **Neto a Pagar**:
   - Salario Base - Total Descuentos + Total Bonos

## Instalación y Configuración

### 1. Ejecutar Migraciones
```bash
php artisan migrate
```

### 2. Verificar Dependencias
El sistema requiere PhpSpreadsheet para exportación a Excel:
```bash
composer require phpoffice/phpspreadsheet
```

### 3. Permisos
Asegúrese de que los roles tengan acceso a las rutas con middleware `superAnalista`.

## Uso

### Crear Nómina con Novedades
1. Acceder a "Nómina Fija"
2. Seleccionar empleados
3. El sistema calcula automáticamente las novedades
4. Guardar nómina

### Validar y Bloquear
1. Acceder a "Validar y Bloquear Nómina"
2. Seleccionar quincena
3. Revisar resumen y detalle
4. Bloquear nómina

### Exportar
1. Desde vista de validación
2. Seleccionar formato (Excel/Plano)
3. Descargar archivo generado

## Notas Técnicas

- Las novedades se buscan por cruce de fechas con el período de nómina
- Solo se aplican novedades con estado "activo"
- El cálculo es automático y se registra en cada creación de nómina
- Los archivos exportados incluyen todos los conceptos de nómina
- El bloqueo es a nivel de quincena completa o por IPS

## Mantenimiento

- Revisar periódicamente las novedades aplicadas
- Validar cálculos antes de bloquear nómina
- Mantener respaldos de exportaciones
- Documentar motivos de desbloqueo

## Autor
Sistema desarrollado para FIDEM Nómina
Fecha: 2025-11-25
