# Configuración de Queue Worker en Hostinger

## Opción 1: Usar Botón de Sincronización (Recomendado para Hostinger)

En hosting compartido como Hostinger, la forma más sencilla es usar el **botón de sincronización** que hemos agregado a la vista de consentimientos informados.

### Cómo funciona:

1. Ve a la página de **Consentimientos Informados**
2. Haz clic en el botón **"Sincronizar Agenda"**
3. El job se agregará a la cola en la base de datos
4. Debes ejecutar el queue worker manualmente o vía cron

### Ventajas:
- ✅ Control manual de cuándo sincronizar
- ✅ No requiere acceso SSH permanente
- ✅ Funciona en hosting compartido

## Opción 2: Configurar Cron Job en Hostinger

### Paso 1: Acceder al Panel de Cron Jobs

1. Inicia sesión en tu panel de Hostinger (hPanel)
2. Ve a **Avanzado** → **Cron Jobs**

### Paso 2: Crear Cron Job para Queue Worker

Crea un nuevo cron job con estos datos:

**Minuto:** `*/5` (cada 5 minutos)  
**Hora:** `*`  
**Día del mes:** `*`  
**Mes:** `*`  
**Día de la semana:** `*`

**Comando:**
```bash
cd /home/tuusuario/public_html && /usr/bin/php artisan queue:work --stop-when-empty --max-time=240
```

**Explicación del comando:**
- `--stop-when-empty`: Detiene el worker cuando no hay más jobs
- `--max-time=240`: Se ejecuta máximo 4 minutos (240 segundos)

### Alternativa: Script personalizado

Si el comando directo no funciona, crea un script:

1. Crea el archivo `/home/tuusuario/queue-worker.sh`:

```bash
#!/bin/bash
cd /home/tuusuario/public_html
/usr/bin/php artisan queue:work --stop-when-empty --max-time=240
```

2. Dale permisos de ejecución:
```bash
chmod +x /home/tuusuario/queue-worker.sh
```

3. En el cron job de Hostinger usa:
```bash
/home/tuusuario/queue-worker.sh
```

## Opción 3: Procesamiento Manual (Sin Cron)

Si no puedes configurar cron jobs, puedes procesar los jobs manualmente vía SSH:

### Conectarse por SSH (si está disponible):

```bash
ssh tuusuario@tudominio.com
```

### Ejecutar el queue worker:

```bash
cd public_html
php artisan queue:work --once
```

O para procesar todos los jobs pendientes:

```bash
php artisan queue:work --stop-when-empty
```

## Verificar que el Queue Worker funcione

### 1. Verificar que hay jobs en la cola:

Conéctate a tu base de datos (phpMyAdmin en Hostinger) y ejecuta:

```sql
SELECT COUNT(*) as total FROM jobs;
```

Si hay registros, significa que hay jobs pendientes.

### 2. Procesar un job de prueba:

1. Haz clic en **"Sincronizar Agenda"** en la interfaz
2. Verifica que se agregó a la tabla `jobs`:
   ```sql
   SELECT * FROM jobs ORDER BY id DESC LIMIT 1;
   ```

3. Ejecuta el worker manualmente vía SSH:
   ```bash
   php artisan queue:work --once
   ```

4. Verifica que se procesó:
   ```sql
   SELECT COUNT(*) as total FROM jobs;
   ```
   El contador debe reducirse en 1.

## Configuración de .env

Asegúrate que tu archivo `.env` tenga:

```env
QUEUE_CONNECTION=database
```

Y tu configuración de base de datos:

```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=tu_base_de_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

## Migraciones

Si aún no has ejecutado las migraciones, hazlo vía SSH o el ejecutor de comandos de Hostinger:

```bash
php artisan migrate
```

Esto creará las tablas `jobs` y `failed_jobs`.

## Troubleshooting en Hostinger

### Error: "Class not found"

Ejecuta:
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

### Error: "Permission denied"

Verifica los permisos de la carpeta `storage`:
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Jobs no se procesan

1. Verifica que QUEUE_CONNECTION=database en .env
2. Verifica que las migraciones se ejecutaron: `SELECT * FROM jobs LIMIT 1;`
3. Revisa los logs: `storage/logs/laravel.log`

### Cron Job no se ejecuta

1. Verifica que la ruta de PHP sea correcta. En Hostinger suele ser:
   - `/usr/bin/php`
   - `/usr/local/bin/php`
   - `/opt/alt/php80/usr/bin/php` (si usas PHP 8.0)

2. Verifica que la ruta del proyecto sea correcta:
   - `/home/tuusuario/public_html`
   - `/home/tuusuario/domains/tudominio.com/public_html`

3. Revisa los logs de cron en Hostinger (si están disponibles)

## Recomendación Final para Hostinger

**La mejor opción para Hostinger es:**

1. Usar el **botón de sincronización** cuando necesites sincronizar
2. Configurar un **cron job** que ejecute cada 5-10 minutos:
   ```bash
   cd /home/tuusuario/public_html && /usr/bin/php artisan queue:work --stop-when-empty --max-time=240
   ```

Esto asegura que:
- Los jobs se procesan automáticamente
- El worker no consume recursos permanentemente
- Funciona dentro de las limitaciones de hosting compartido

## Flujo de Trabajo Recomendado

```
1. Usuario hace clic en "Sincronizar Agenda"
   ↓
2. Se crea un job en la tabla `jobs`
   ↓
3. Cron job ejecuta cada 5 minutos
   ↓
4. Queue worker procesa el job
   ↓
5. Job se elimina de la tabla `jobs`
   ↓
6. Datos sincronizados desde la API
```

## Comandos útiles vía SSH

```bash
# Ver jobs pendientes
php artisan queue:work --once

# Limpiar jobs fallidos
php artisan queue:flush

# Ver jobs fallidos
php artisan queue:failed

# Reintentar todos los fallidos
php artisan queue:retry all

# Ver logs
tail -f storage/logs/laravel.log
```
