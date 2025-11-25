# Sistema de Bloqueo de Nómina

## Descripción

Se ha implementado un sistema de bloqueo de nómina que garantiza la integridad de los datos una vez que han sido guardados y supervisados. Esto previene modificaciones accidentales o no autorizadas después de que una nómina ha sido procesada.

## Funcionamiento

### Creación de Nómina
- Cuando se crea una nueva nómina (ya sea individual o de nómina fija), se crea en estado **desbloqueado** (`is_locked = false`)
- En este estado, la nómina puede ser editada y modificada normalmente

### Supervisión y Bloqueo Automático
- Cuando un supervisor revisa y aprueba una nómina (método `supervisar()`), la nómina se **bloquea automáticamente** (`is_locked = true`)
- Una vez bloqueada, la nómina **no puede ser modificada ni eliminada**

### Protecciones Implementadas

#### 1. Edición (método `update()`)
- Antes de permitir cualquier edición, se verifica si la nómina está bloqueada
- Si está bloqueada, se retorna un error: *"Esta nómina está bloqueada y no puede ser modificada. Contacte al administrador si necesita hacer cambios."*

#### 2. Eliminación (método `destroy()`)
- Antes de permitir la eliminación, se verifica si la nómina está bloqueada
- Si está bloqueada, se retorna un error: *"Esta nómina está bloqueada y no puede ser eliminada. Contacte al administrador si necesita hacer cambios."*

#### 3. Supervisión (método `supervisar()`)
- Al supervisar una nómina, automáticamente se establece `is_locked = true`
- Esto garantiza que una vez supervisada, no se puedan hacer cambios

## Migración de Base de Datos

Se ha creado la migración `2025_11_25_000001_add_is_locked_to_nominaliquids_table.php` que agrega el campo:
- **Campo**: `is_locked` (boolean)
- **Default**: `false`
- **Ubicación**: Después del campo `activo`

## Archivos Modificados

1. **Modelo**: `app/Models/Nomina/nominaliquid.php`
   - Se agregó `is_locked` al array `$fillable`

2. **Controlador**: `app/Http/Controllers/Nomina/NominaliquidController.php`
   - Método `store()`: Inicializa `is_locked = false`
   - Método `store_nominaf()`: Inicializa `is_locked = false`
   - Método `update()`: Verifica bloqueo antes de editar
   - Método `supervisar()`: Establece `is_locked = true`
   - Método `destroy()`: Verifica bloqueo antes de eliminar

3. **Migración**: `database/migrations/2025_11_25_000001_add_is_locked_to_nominaliquids_table.php`
   - Agrega columna `is_locked` a la tabla `nominaliquids`

## Instrucciones de Instalación

1. Ejecutar la migración:
```bash
php artisan migrate
```

2. La funcionalidad estará activa inmediatamente después de ejecutar la migración

## Consideraciones

- Las nóminas existentes en la base de datos tendrán `is_locked = false` por defecto
- Si necesita desbloquear una nómina para hacer correcciones, esto debe hacerse manualmente a través de la base de datos o mediante un panel de administración
- Se recomienda implementar un rol de "super administrador" que pueda desbloquear nóminas en casos excepcionales

## Próximos Pasos Recomendados

1. Agregar indicador visual en las vistas para mostrar si una nómina está bloqueada
2. Implementar un panel de administración para desbloquear nóminas en casos especiales
3. Agregar logs/auditoría para registrar quién y cuándo se desbloquea una nómina
4. Considerar agregar permisos basados en roles para controlar quién puede supervisar nóminas
