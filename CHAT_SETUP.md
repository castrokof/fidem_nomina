# Configuración del Asistente Virtual (Chat con IA)

## 📋 Descripción

El sistema incluye un módulo de Asistente Virtual que utiliza Claude AI de Anthropic para proporcionar asistencia inteligente sobre información de pacientes, historias clínicas y consultas médicas.

## 🔧 Configuración

### 1. Variables de Entorno (.env)

Asegúrate de que tu archivo `.env` contenga las siguientes variables de configuración de Claude AI:

```env
# Claude AI Configuration
CLAUDE_API_KEY=tu_api_key_real_aqui
CLAUDE_API_VERSION=2023-06-01
CLAUDE_API_URL=https://api.anthropic.com/v1/messages
CLAUDE_MODEL=claude-3-5-sonnet-20241022
CLAUDE_MAX_TOKENS=4096
CLAUDE_TEMPERATURE=0.7
CLAUDE_TIMEOUT=30
CLAUDE_CONTEXT_MESSAGES=10
```

**⚠️ IMPORTANTE:** Debes reemplazar `CLAUDE_API_KEY=tu_api_key_real_aqui` con tu API key real de Anthropic.

### 2. Obtener API Key de Claude

1. Ve a https://console.anthropic.com/
2. Inicia sesión o crea una cuenta
3. Navega a "API Keys"
4. Crea una nueva API key
5. Copia la key y pégala en tu archivo `.env`

### 3. Configuración de la Base de Datos

#### Opción A: Usando el Seeder (Recomendado)

```bash
php artisan db:seed --class=ChatMenuSeeder
```

Este comando:
- Verifica si el menú ya existe
- Crea la entrada del menú "Asistente Virtual"
- Asigna el icono y la ruta correcta

#### Opción B: Usando SQL directamente

Si prefieres ejecutar SQL manualmente, usa el archivo:
```
database/sql/insert_chat_menu.sql
```

Ejecuta el script en tu gestor de base de datos (phpMyAdmin, MySQL Workbench, etc.)

### 4. Asignar Permisos a Roles

Después de crear el menú, debes asignar permisos:

1. Inicia sesión en el panel de administración
2. Ve a **Menú-Rol**
3. Selecciona el rol al que deseas dar acceso
4. Marca el checkbox del menú "Asistente Virtual"
5. Guarda los cambios

### 5. Limpiar Caché

```bash
php artisan config:clear
php artisan cache:clear
php artisan config:cache
```

## 📱 Uso del Chat

### Acceso

Una vez configurado, los usuarios con permisos pueden acceder al chat desde:
- Menú lateral: "Asistente Virtual"
- URL directa: `/admin/chat`

### Funcionalidades

1. **Crear Nuevo Chat**: Inicia una conversación con un paciente específico
2. **Enviar Mensajes**: Escribe consultas sobre pacientes
3. **Recibir Respuestas de IA**: Claude AI responde basándose en:
   - Contexto del paciente (nombre, edad, documento, etc.)
   - Historias clínicas disponibles
   - Diagnósticos y tratamientos
   - Mensajes previos del chat (contexto conversacional)

### Ejemplos de Consultas

- "¿Cuál es el último diagnóstico del paciente?"
- "Muéstrame el historial de consultas"
- "¿Qué tratamientos ha recibido?"
- "Resume la información del paciente"

## 🔐 Seguridad y Privacidad

### Datos Incluidos en el Contexto

Por defecto, se incluye:
- ✅ Nombres completos
- ✅ Documento de identidad
- ✅ Edad y sexo
- ✅ Dirección y teléfono
- ✅ Historias clínicas (últimas 5)
- ✅ Diagnósticos
- ❌ Medicamentos (deshabilitado por defecto)

### Configurar Datos Compartidos

Edita `config/claude.php` para personalizar qué datos se comparten:

```php
'patient_data_fields' => [
    'nombres_completos' => true,
    'documento' => true,
    'edad' => true,
    'sexo' => true,
    'direccion' => true,
    'telefono' => true,
    'historias_clinicas' => true,
    'diagnosticos' => true,
    'medicamentos' => false, // Cambiar a true si se requiere
],
```

## 🐛 Solución de Problemas

### El chat no aparece en el menú

1. Verifica que ejecutaste el seeder o el script SQL
2. Verifica que asignaste permisos al rol en Menú-Rol
3. Limpia el caché con `php artisan config:clear`
4. Cierra sesión y vuelve a iniciar sesión

### Error: "Claude API key no configurada"

1. Verifica que `CLAUDE_API_KEY` esté configurada en `.env`
2. Verifica que el valor no sea `your_claude_api_key_here`
3. Ejecuta `php artisan config:clear`
4. Ejecuta `php artisan config:cache`

### Error de conexión a la API

1. Verifica tu conexión a internet
2. Verifica que la API key sea válida
3. Verifica que no hayas excedido tu límite de uso en Anthropic
4. Revisa los logs en `storage/logs/laravel.log`

### El chat no envía mensajes

1. Verifica que estés autenticado
2. Verifica que el ID del paciente exista
3. Abre la consola del navegador (F12) para ver errores de JavaScript
4. Revisa los logs del servidor

## 🗄️ Migraciones de Base de Datos

Las tablas del chat ya están migradas:
- `chats`: Almacena los chats
- `chat_participants`: Participantes de cada chat
- `chat_messages`: Mensajes del chat

Si necesitas recrearlas:

```bash
php artisan migrate:refresh --path=database/migrations/2026_01_13_000001_create_chats_table.php
php artisan migrate:refresh --path=database/migrations/2026_01_13_000002_create_chat_participants_table.php
php artisan migrate:refresh --path=database/migrations/2026_01_13_000003_create_chat_messages_table.php
```

⚠️ **ADVERTENCIA**: `migrate:refresh` eliminará los datos existentes.

## 📊 Monitoreo de Uso

Los mensajes enviados a Claude AI consumen tokens y tienen un costo. Para monitorear:

1. Revisa el dashboard de Anthropic: https://console.anthropic.com/
2. Los datos de uso se almacenan en la columna `metadata` de `chat_messages`
3. Puedes crear reportes personalizados consultando esta información

## 🔄 Actualizaciones

### Cambiar el Modelo de IA

En `.env`, modifica:
```env
CLAUDE_MODEL=claude-3-5-sonnet-20241022  # Modelo actual
# O usa otro modelo disponible:
# CLAUDE_MODEL=claude-3-opus-20240229
# CLAUDE_MODEL=claude-3-haiku-20240307
```

### Ajustar la Temperatura (Creatividad)

```env
CLAUDE_TEMPERATURE=0.7  # Valor por defecto (0.0 = determinístico, 1.0 = creativo)
```

### Cambiar el Límite de Tokens

```env
CLAUDE_MAX_TOKENS=4096  # Máximo de tokens en la respuesta
```

## 📞 Soporte

Para problemas o consultas sobre:
- **Laravel/PHP**: Revisa la documentación de Laravel
- **Claude AI**: https://docs.anthropic.com/
- **Este módulo**: Contacta al equipo de desarrollo

---

**Última actualización**: 2026-04-09
