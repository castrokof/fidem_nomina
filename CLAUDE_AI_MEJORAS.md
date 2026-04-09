# 🤖 Mejoras de Claude AI - Contexto y Persistencia

## 📋 Índice
1. [API de Pacientes para Claude](#api-de-pacientes)
2. [Persistencia de Contexto](#persistencia-de-contexto)
3. [Firma de Profesionales](#firma-de-profesionales)
4. [Casos de Uso](#casos-de-uso)

---

## 🔌 API de Pacientes para Claude

He creado una API que permite a Claude acceder a información completa de pacientes, incluyendo historias clínicas.

### Endpoints Disponibles

#### 1. Buscar Paciente por Documento

**Ruta**: `GET /api/pacientes/buscar-documento`

**Parámetros**:
```json
{
  "documento": "1234567890",           // Requerido
  "tipo_documento": "CC",              // Opcional (CC, TI, CE, PA, RC)
  "incluir_historias": true,           // Opcional (default: true)
  "incluir_citas": false,              // Opcional (default: false)
  "limite_historias": 10               // Opcional (default: 10, max: 50)
}
```

**Respuesta Exitosa** (200):
```json
{
  "success": true,
  "message": "Paciente encontrado",
  "data": {
    "id": 123,
    "nombre_completo": "Juan Carlos Pérez González",
    "primer_nombre": "Juan",
    "segundo_nombre": "Carlos",
    "primer_apellido": "Pérez",
    "segundo_apellido": "González",
    "tipo_documento": "CC",
    "documento": "1234567890",
    "edad": 45,
    "sexo": "Masculino",
    "direccion": "Calle 123 #45-67",
    "telefono": "3001234567",
    "celular": "3109876543",
    "correo": "juan@email.com",
    "eps": "Sura",
    "plan": "Subsidiado",
    "ciudad": "Manizales",
    "departamento": "Caldas",
    "historias_clinicas": {
      "total": 5,
      "datos": [
        {
          "id": 456,
          "fecha": "2026-04-05 10:30:00",
          "motivo_consulta": "Dolor abdominal",
          "diagnostico_principal": "Gastritis aguda",
          "diagnostico_secundario": null,
          "plan_tratamiento": "Omeprazol 20mg cada 12 horas",
          "observaciones": "Paciente refiere mejoría",
          "profesional": "Dr. María López"
        }
      ]
    }
  }
}
```

**Respuesta Error** (404):
```json
{
  "success": false,
  "message": "Paciente no encontrado",
  "data": null
}
```

#### 2. Obtener Contexto para Claude

**Ruta**: `GET /api/pacientes/contexto-claude`

**Parámetros**:
```json
{
  "documento": "1234567890",
  "tipo_documento": "CC",
  "limite_historias": 5
}
```

**Respuesta**:
```json
{
  "success": true,
  "message": "Contexto generado exitosamente",
  "contexto": "INFORMACIÓN DEL PACIENTE\n========================\n\nNombre: Juan Carlos Pérez González\nDocumento: CC 1234567890\nEdad: 45 años\nSexo: Masculino\n...",
  "paciente_id": 123,
  "nombre_completo": "Juan Carlos Pérez González"
}
```

### 🔧 Ejemplos de Uso

#### JavaScript (Axios)

```javascript
// Buscar paciente con historias
async function buscarPaciente(documento) {
    try {
        const response = await axios.get('/api/pacientes/buscar-documento', {
            params: {
                documento: documento,
                tipo_documento: 'CC',
                incluir_historias: true,
                limite_historias: 5
            }
        });

        if (response.data.success) {
            console.log('Paciente:', response.data.data);
            return response.data.data;
        }
    } catch (error) {
        console.error('Error:', error.response?.data?.message);
    }
}

// Obtener contexto para Claude
async function obtenerContextoClaude(documento) {
    try {
        const response = await axios.get('/api/pacientes/contexto-claude', {
            params: {
                documento: documento,
                tipo_documento: 'CC',
                limite_historias: 5
            }
        });

        if (response.data.success) {
            // Este contexto se puede usar en el chat
            console.log('Contexto:', response.data.contexto);
            return response.data.contexto;
        }
    } catch (error) {
        console.error('Error:', error.response?.data?.message);
    }
}
```

#### PHP

```php
use Illuminate\Support\Facades\Http;

// Buscar paciente
$response = Http::get('/api/pacientes/buscar-documento', [
    'documento' => '1234567890',
    'tipo_documento' => 'CC',
    'incluir_historias' => true,
    'limite_historias' => 5
]);

if ($response->successful() && $response->json('success')) {
    $paciente = $response->json('data');
    // Usar datos del paciente
}

// Obtener contexto para Claude
$response = Http::get('/api/pacientes/contexto-claude', [
    'documento' => '1234567890',
    'tipo_documento' => 'CC'
]);

$contexto = $response->json('contexto');
```

---

## 💾 Persistencia de Contexto en Conversaciones

### ¿Cómo Funciona Actualmente?

El sistema de chat **YA guarda el contexto automáticamente** en la base de datos:

1. **Tabla `chat_messages`** guarda:
   - Todos los mensajes del usuario
   - Todas las respuestas de Claude
   - Relación padre-hijo entre pregunta y respuesta

2. **Contexto automático**:
   - El `ChatService` carga automáticamente los últimos N mensajes
   - Configurado en `config/claude.php` → `context_message_limit` (default: 10)
   - Se envía a Claude en cada nueva pregunta

### Configurar Cantidad de Mensajes de Contexto

**Archivo**: `.env`

```env
# Número de mensajes históricos a incluir como contexto
CLAUDE_CONTEXT_MESSAGES=10
```

**Archivo**: `config/claude.php`

```php
'chat' => [
    // Límite de mensajes históricos a incluir en el contexto
    'context_message_limit' => env('CLAUDE_CONTEXT_MESSAGES', 10),

    // ... resto de configuración
],
```

### Flujo de Persistencia

```
Usuario envía mensaje
    ↓
ChatMessageController@store
    ↓
ChatService@sendMessage
    ↓
1. Guarda mensaje del usuario en BD
    ↓
2. Carga últimos 10 mensajes del chat
    ↓
3. Construye contexto incluyendo:
   - Información del paciente
   - Historias clínicas
   - Mensajes previos
    ↓
4. Envía todo a Claude AI
    ↓
5. Guarda respuesta de Claude en BD
    ↓
Retorna respuesta al usuario
```

### Ver Contexto Enviado a Claude

Para depurar o ver qué contexto se está enviando, puedes agregar logs:

**Archivo**: `app/Services/ChatService.php`

```php
public function sendMessage(Chat $chat, array $messageData): ChatMessage
{
    // ... código existente ...

    // Obtener contexto del chat
    $context = $this->buildChatContext($chat);

    // 🔍 DEBUG: Ver contexto enviado
    \Log::info('Contexto enviado a Claude:', [
        'chat_id' => $chat->id,
        'mensajes_contexto' => count($context),
        'contexto' => $context
    ]);

    // Obtener respuesta de Claude AI
    $aiResponse = $this->claudeAIService->sendMessage(
        $messageData['message'],
        $context,
        $this->buildSystemPrompt($chat)
    );

    // ... resto del código ...
}
```

### Aumentar Contexto de Historias Clínicas

Por defecto, Claude recibe las últimas 5 historias clínicas. Para cambiar esto:

**Archivo**: `app/Services/ClaudeAIService.php` (línea 190)

```php
if ($config['historias_clinicas'] ?? false) {
    $context .= "\nHistorias clínicas recientes:\n";
    if ($patient->historiap && $patient->historiap->count() > 0) {
        // Cambiar ->take(5) a ->take(10) para más historias
        foreach ($patient->historiap->take(10) as $historia) {
            // ... resto del código ...
        }
    }
}
```

O hacerlo configurable en `config/claude.php`:

```php
'chat' => [
    'context_message_limit' => env('CLAUDE_CONTEXT_MESSAGES', 10),
    'historias_clinicas_limit' => env('CLAUDE_HISTORIAS_LIMIT', 5), // ← Nuevo

    'patient_data_fields' => [
        // ... existente ...
    ],
],
```

Y luego en `ClaudeAIService.php`:

```php
$limiteHistorias = config('claude.chat.historias_clinicas_limit', 5);

foreach ($patient->historiap->take($limiteHistorias) as $historia) {
    // ...
}
```

---

## ✍️ Firma de Profesionales

### Estado Actual

El sistema tiene la funcionalidad implementada pero puede faltar la vista o el menú.

### Archivos Existentes

- ✅ Modelo: `app/Profesional.php` (con campo `firma_base64`)
- ✅ Controlador: `app/Http/Controllers/Admin/PerfilController.php`
- ✅ Vista: `resources/views/admin/profesionales/firma.blade.php`
- ✅ Rutas (líneas 67-68 de `routes/web.php`):
  ```php
  Route::get('perfil/firma', [PerfilController::class, 'mostrarFirma'])
  Route::post('perfil/firma', [PerfilController::class, 'guardarFirma'])
  ```

### Acceso

**URL directa**: `https://tudominio.com/admin/perfil/firma` (dentro del middleware auth)

### Problema Posible: Falta Menú

Si el profesional no puede acceder, probablemente falta la entrada en el menú lateral.

**Solución**: Agregar al menú o usar el seeder de menú que creé antes.

---

## 💡 Casos de Uso

### 1. Chat Inteligente con Contexto Completo

```javascript
// Al seleccionar un paciente en el chat
async function iniciarChatConPaciente(documento) {
    // 1. Buscar paciente con historias
    const paciente = await buscarPaciente(documento);

    // 2. Crear o encontrar chat
    const chat = await axios.post('/api/chat/find-or-create-patient-chat', {
        patient_id: paciente.id
    });

    // 3. Enviar primer mensaje
    // El sistema automáticamente incluirá el contexto del paciente
    await axios.post(`/api/chat/${chat.data.chat.id}/messages`, {
        message: '¿Cuál es el resumen de las últimas consultas del paciente?'
    });

    // Claude responderá con base en las historias clínicas cargadas
}
```

### 2. Búsqueda Rápida en Interfaz

```javascript
// Autocompletar en formularios
async function buscarPacientePorDocumento(documento) {
    if (documento.length < 6) return;

    const response = await axios.get('/api/pacientes/buscar-documento', {
        params: {
            documento: documento,
            incluir_historias: false,  // Solo datos básicos
            incluir_citas: false
        }
    });

    if (response.data.success) {
        // Mostrar en autocompletar
        mostrarSugerencias(response.data.data);
    }
}
```

### 3. Obtener Contexto Dinámico

```javascript
// Cargar contexto solo cuando el usuario lo pida
async function verHistoriasPaciente(documento) {
    const response = await axios.get('/api/pacientes/contexto-claude', {
        params: {
            documento: documento,
            limite_historias: 10
        }
    });

    // Mostrar en un modal o panel
    mostrarContextoModal(response.data.contexto);
}
```

---

## 🚀 Próximos Pasos Recomendados

1. ✅ **API Creada** - Ya puedes usar `/api/pacientes/buscar-documento`
2. ✅ **Contexto Mejorado** - Claude ahora accede a historias clínicas
3. ✅ **Persistencia Automática** - Los mensajes se guardan en BD
4. ⏳ **Agregar Menú de Firma** - Facilitar acceso a profesionales
5. ⏳ **Testing** - Probar la API con casos reales

---

## 📝 Notas Importantes

### Sobre los Dos Modelos de Paciente

El sistema tiene DOS tablas de pacientes:

| Modelo | Tabla | Uso | Historias |
|--------|-------|-----|-----------|
| `App\Paciente` | `pacientes` | Consentimientos (nuevo) | ❌ No |
| `App\Models\Admin\Paciente` | `paciente` | Sistema principal | ✅ Sí |

**Solución implementada**:
- El `ChatService` ahora busca en ambas tablas
- Si encuentra el paciente nuevo, busca por documento en la tabla antigua
- Así Claude siempre tiene acceso a las historias clínicas

### Límites de Tokens

Claude tiene límites de tokens. Si envías demasiadas historias clínicas, podrías exceder el límite. Recomendado:

- **Mensajes de contexto**: 10 (configurable)
- **Historias clínicas**: 5-10 (configurable)
- **Total aproximado**: ~3000-4000 tokens de contexto

---

**Fecha de creación**: 2026-04-09  
**Versión**: 1.0  
**Autor**: Claude Code Assistant
