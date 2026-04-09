# Configuración del Sistema de Colas (Queue) con Base de Datos

## Requisitos

Este proyecto utiliza Laravel Queue con driver de base de datos para procesar trabajos en segundo plano.

## Configuración

### 1. Configurar Base de Datos

Edita el archivo `.env` y configura tu conexión a base de datos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tu_base_de_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña

QUEUE_CONNECTION=database
```

### 2. Ejecutar Migraciones

Ejecuta las migraciones para crear las tablas `jobs` y `failed_jobs`:

```bash
php artisan migrate
```

Esto creará las siguientes tablas:
- `jobs` - Almacena los trabajos pendientes en la cola
- `failed_jobs` - Almacena los trabajos que fallaron durante su ejecución

### 3. Ejecutar el Worker de Colas

Para procesar los trabajos en la cola, ejecuta el siguiente comando:

```bash
php artisan queue:work
```

O para procesar solo un trabajo:

```bash
php artisan queue:work --once
```

Para ejecutar en segundo plano (producción), puedes usar Supervisor o crear un proceso daemon:

```bash
php artisan queue:work --daemon
```

## Jobs Disponibles

### SincronizarAgendaCIJob

Este job sincroniza la agenda de consentimientos informados desde la API externa.

**Parámetros:**
- `$diasAtras` (default: 2) - Días hacia atrás para sincronizar
- `$diasAdelante` (default: 3) - Días hacia adelante para sincronizar

**Ejemplo de uso:**

```php
use App\Jobs\SincronizarAgendaCIJob;

// Despachar el job con valores por defecto (2 días atrás, 3 días adelante)
SincronizarAgendaCIJob::dispatch();

// Despachar con parámetros personalizados
SincronizarAgendaCIJob::dispatch(5, 7);

// Despachar con retraso de 5 minutos
SincronizarAgendaCIJob::dispatch()->delay(now()->addMinutes(5));

// Despachar en una cola específica
SincronizarAgendaCIJob::dispatch()->onQueue('sync');
```

## Ejemplo de Implementación en Controlador

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SincronizarAgendaCIJob;
use Illuminate\Http\Request;

class AgendaSyncController extends Controller
{
    public function sincronizar(Request $request)
    {
        // Validar parámetros
        $request->validate([
            'dias_atras' => 'nullable|integer|min:0|max:30',
            'dias_adelante' => 'nullable|integer|min:0|max:30',
        ]);

        $diasAtras = $request->input('dias_atras', 2);
        $diasAdelante = $request->input('dias_adelante', 3);

        // Despachar el job a la cola
        SincronizarAgendaCIJob::dispatch($diasAtras, $diasAdelante);

        return response()->json([
            'success' => true,
            'message' => 'Sincronización programada exitosamente'
        ]);
    }
}
```

## Comandos Útiles

```bash
# Ver trabajos en cola
php artisan queue:work --tries=3

# Limpiar trabajos fallidos
php artisan queue:flush

# Ver trabajos fallidos
php artisan queue:failed

# Reintentar un trabajo fallido
php artisan queue:retry {id}

# Reintentar todos los trabajos fallidos
php artisan queue:retry all

# Monitorear la cola en tiempo real
php artisan queue:listen
```

## Configuración en Producción

### Usando Supervisor (Recomendado)

1. Instala Supervisor:
```bash
sudo apt-get install supervisor
```

2. Crea un archivo de configuración `/etc/supervisor/conf.d/laravel-queue.conf`:

```ini
[program:laravel-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /ruta/a/tu/proyecto/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/ruta/a/tu/proyecto/storage/logs/queue.log
stopwaitsecs=3600
```

3. Inicia Supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-queue:*
```

## Monitoreo y Debugging

### Ver logs del queue worker

Los logs se encuentran en `storage/logs/laravel.log`. Los trabajos registran información con:

```
[INFO] Sincronización de agenda CI completada
[ERROR] Error en sincronización de agenda CI: {mensaje}
```

### Verificar trabajos en la base de datos

```sql
-- Ver trabajos pendientes
SELECT * FROM jobs;

-- Ver trabajos fallidos
SELECT * FROM failed_jobs;

-- Contar trabajos por cola
SELECT queue, COUNT(*) FROM jobs GROUP BY queue;
```

## Troubleshooting

**Problema: Los trabajos no se procesan**
- Verifica que el worker esté ejecutándose: `ps aux | grep queue:work`
- Verifica que QUEUE_CONNECTION=database en .env
- Verifica la conexión a la base de datos

**Problema: Trabajos siempre fallan**
- Revisa `storage/logs/laravel.log`
- Revisa la tabla `failed_jobs` para ver el stack trace
- Aumenta la memoria y timeout: `php artisan queue:work --memory=512 --timeout=300`

**Problema: Worker se detiene después de un tiempo**
- Usa Supervisor para mantener el worker ejecutándose
- O configura un cron job para reiniciar el worker cada hora
