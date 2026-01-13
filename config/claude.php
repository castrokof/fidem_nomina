<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Claude AI API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuración para la integración con la API de Claude AI de Anthropic.
    |
    */

    'api_key' => env('CLAUDE_API_KEY', ''),

    'api_version' => env('CLAUDE_API_VERSION', '2023-06-01'),

    'api_url' => env('CLAUDE_API_URL', 'https://api.anthropic.com/v1/messages'),

    'model' => env('CLAUDE_MODEL', 'claude-3-5-sonnet-20241022'),

    'max_tokens' => env('CLAUDE_MAX_TOKENS', 4096),

    'temperature' => env('CLAUDE_TEMPERATURE', 0.7),

    'timeout' => env('CLAUDE_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Chat Configuration
    |--------------------------------------------------------------------------
    */

    'chat' => [
        // Contexto del sistema sobre los datos de pacientes
        'system_context' => 'Eres un asistente médico AI para el sistema FIDEM. Tienes acceso a información de pacientes, historias clínicas, diagnósticos y tratamientos. Debes proporcionar información precisa y útil basada en los datos disponibles. Siempre mantén la confidencialidad y privacidad de la información médica.',

        // Límite de mensajes históricos a incluir en el contexto
        'context_message_limit' => env('CLAUDE_CONTEXT_MESSAGES', 10),

        // Datos de paciente a incluir en el contexto
        'patient_data_fields' => [
            'nombres_completos' => true,
            'documento' => true,
            'edad' => true,
            'sexo' => true,
            'direccion' => true,
            'telefono' => true,
            'historias_clinicas' => true,
            'diagnosticos' => true,
            'medicamentos' => false, // Por seguridad, opcional
        ],
    ],
];
