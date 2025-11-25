# Sistema de Historial de Salarios

## Descripción

Este sistema permite mantener un registro completo de todos los cambios salariales de los empleados, incluyendo:
- Fecha de inicio de vigencia de cada salario
- Fecha de fin (para registros históricos)
- Motivo del cambio
- Usuario que registró el cambio

## Características

### 1. Tabla de Historial (salary_history)
- Almacena todos los cambios de salario de cada empleado
- Registra tanto salario fijo como salario de prestación de servicios
- Marca automáticamente el registro activo
- Guarda quién hizo el cambio y cuándo

### 2. Modelo SalaryHistory
- Relación con el empleado
- Relación con el usuario que creó el registro
- Scopes útiles:
  - `activo()`: Obtiene solo el salario vigente
  - `enFecha($fecha)`: Obtiene el salario vigente en una fecha específica
  - `ordenadoPorFecha()`: Ordena por fecha de inicio descendente

### 3. Modelo Empleados Actualizado
- Métodos para obtener el salario actual desde el historial
- Método para obtener el salario en una fecha específica
- Getters que priorizan el historial sobre los campos directos (compatibilidad)
- Relaciones:
  - `salaryHistory()`: Todos los registros de historial
  - `salarioActual()`: Solo el registro activo

### 4. Controlador SalaryHistoryController
- `index()`: Lista el historial completo de un empleado
- `create()`: Formulario para crear un nuevo registro
- `store()`: Guarda el nuevo registro y desactiva los anteriores
- `getHistory()`: API para obtener historial en formato JSON
- `getSalaryAtDate()`: API para obtener salario en una fecha específica

### 5. Vistas
- **index.blade.php**: Lista el historial completo con información resumida del empleado
- **create.blade.php**: Formulario para registrar un nuevo salario
- **Integración en formdatossalarios.blade.php**: Enlace al historial desde el formulario del empleado

### 6. Rutas
Todas las rutas están bajo el prefijo `empleados/{empleado_id}/salary-history`:
- `GET salary-history`: Lista el historial
- `GET salary-history/create`: Formulario de creación
- `POST salary-history`: Guardar nuevo registro
- `GET salary-history-ajax`: Obtener historial en JSON
- `POST salary-at-date`: Obtener salario en fecha específica

## Instalación

### 1. Ejecutar Migraciones

```bash
php artisan migrate
```

Esto creará:
1. La tabla `salary_history`
2. Migrará los datos existentes de la tabla `empleados` al historial

### 2. Verificar Migración de Datos

La migración automáticamente:
- Toma todos los empleados activos
- Crea un registro en salary_history con sus salarios actuales
- Usa la fecha de inicio del contrato como fecha de inicio del salario
- Marca todos los registros como activos

## Uso

### Registrar un Nuevo Salario

1. Ir al perfil del empleado
2. En la pestaña "Salario", hacer clic en "Ver Historial de Salarios"
3. Hacer clic en "Nuevo Registro de Salario"
4. Llenar el formulario:
   - Salario Fijo (opcional)
   - Salario Prestación de Servicios (opcional)
   - Fecha de inicio de vigencia (requerido)
   - Motivo del cambio (opcional)
5. Guardar

El sistema automáticamente:
- Marcará el nuevo registro como activo
- Desactivará los registros anteriores
- Establecerá la fecha de fin de los registros anteriores

### Ver Historial

1. Desde el formulario del empleado, en la pestaña "Salario"
2. Hacer clic en "Ver Historial de Salarios"
3. Se mostrará una tabla con:
   - Estado (Actual/Histórico)
   - Fechas de vigencia
   - Montos de salarios
   - Motivo del cambio
   - Usuario que registró
   - Fecha de registro

### API

#### Obtener Historial (AJAX)
```javascript
GET /empleados/{empleado_id}/salary-history-ajax
```

#### Obtener Salario en Fecha Específica
```javascript
POST /empleados/{empleado_id}/salary-at-date
{
    "fecha": "2025-11-25"
}
```

## Compatibilidad con Código Existente

El sistema mantiene compatibilidad con el código existente:
- Los campos `salary` y `salary_ps` en la tabla `empleados` se mantienen
- Los getters personalizados priorizan el historial pero retornan los valores directos si no hay historial
- El cálculo de nómina usa automáticamente el salario del historial

## Cálculo de Nómina

El cálculo de nómina ahora usa automáticamente el historial de salarios:
- Al acceder a `$empleado->salary` o `$empleado->salary_ps`, se obtiene el salario activo del historial
- Si no hay historial, se usa el valor directo de la tabla (compatibilidad)
- Para obtener el salario en una fecha específica: `$empleado->salarioEnFecha($fecha)`

## Notas Importantes

1. **Al menos un salario es requerido**: Al crear un registro, debe tener valor en `salary` o `salary_ps`
2. **Solo un registro activo**: El sistema garantiza que solo haya un registro activo por empleado
3. **Fechas automáticas**: Las fechas de fin de los registros anteriores se establecen automáticamente
4. **Auditoría**: Se registra quién y cuándo se hizo cada cambio
5. **Migración automática**: Los datos existentes se migran automáticamente al ejecutar las migraciones

## Estructura de la Base de Datos

### Tabla: salary_history

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigInteger | ID único |
| empleado_id | bigInteger | ID del empleado (FK) |
| salary | bigInteger | Salario fijo mensual |
| salary_ps | bigInteger | Salario prestación de servicios |
| fecha_inicio | date | Fecha de inicio de vigencia |
| fecha_fin | date | Fecha de fin de vigencia (null = actual) |
| created_by | integer | Usuario que registró (FK) |
| motivo | string(500) | Motivo del cambio |
| activo | boolean | Indica si es el salario vigente |
| created_at | timestamp | Fecha de creación |
| updated_at | timestamp | Fecha de actualización |

## Ejemplos de Uso

### Ejemplo 1: Obtener Salario Actual
```php
$empleado = Empleados::find(1);
$salarioActual = $empleado->salarioActual;

echo "Salario Fijo: " . $salarioActual->salary;
echo "Salario PS: " . $salarioActual->salary_ps;
echo "Desde: " . $salarioActual->fecha_inicio->format('d/m/Y');
```

### Ejemplo 2: Obtener Salario en Fecha Específica
```php
$empleado = Empleados::find(1);
$salario = $empleado->salarioEnFecha('2025-01-01');

if ($salario) {
    echo "Salario en esa fecha: " . $salario->salary;
}
```

### Ejemplo 3: Ver Todo el Historial
```php
$empleado = Empleados::with('salaryHistory')->find(1);

foreach ($empleado->salaryHistory as $registro) {
    echo "Desde {$registro->fecha_inicio} hasta {$registro->fecha_fin}: {$registro->salary}";
    echo " - {$registro->motivo}";
}
```
