<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class ClaudeAIService
{
    protected $apiKey;
    protected $apiVersion;
    protected $apiUrl;
    protected $model;
    protected $maxTokens;
    protected $temperature;
    protected $timeout;

    public function __construct()
    {
        $this->apiKey = config('claude.api_key');
        $this->apiVersion = config('claude.api_version');
        $this->apiUrl = config('claude.api_url');
        $this->model = config('claude.model');
        $this->maxTokens = config('claude.max_tokens');
        $this->temperature = config('claude.temperature');
        $this->timeout = config('claude.timeout');
    }

    /**
     * Enviar mensaje a Claude AI
     *
     * @param string $message
     * @param array $context
     * @param string|null $systemPrompt
     * @return array
     * @throws Exception
     */
    public function sendMessage(string $message, array $context = [], ?string $systemPrompt = null): array
    {
        if (empty($this->apiKey)) {
            throw new Exception('Claude API key no configurada. Por favor, configura CLAUDE_API_KEY en tu archivo .env');
        }

        try {
            $messages = $this->buildMessages($message, $context);
            $system = $systemPrompt ?? config('claude.chat.system_context');

            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'x-api-key' => $this->apiKey,
                    'anthropic-version' => $this->apiVersion,
                    'content-type' => 'application/json',
                ])
                ->post($this->apiUrl, [
                    'model' => $this->model,
                    'max_tokens' => $this->maxTokens,
                    'temperature' => $this->temperature,
                    'system' => $system,
                    'messages' => $messages,
                ]);

            if (!$response->successful()) {
                Log::error('Claude AI API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                throw new Exception('Error al comunicarse con Claude AI: ' . $response->body());
            }

            $data = $response->json();

            return [
                'success' => true,
                'response' => $this->extractTextFromResponse($data),
                'usage' => $data['usage'] ?? null,
                'raw_response' => $data,
            ];

        } catch (Exception $e) {
            Log::error('Claude AI Service Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'response' => null,
            ];
        }
    }

    /**
     * Construir array de mensajes para Claude
     *
     * @param string $currentMessage
     * @param array $context
     * @return array
     */
    protected function buildMessages(string $currentMessage, array $context = []): array
    {
        $messages = [];

        // Agregar mensajes del contexto (historial del chat)
        foreach ($context as $msg) {
            $role = $msg['role'] ?? 'user';
            $content = $msg['content'] ?? '';

            if (!empty($content)) {
                $messages[] = [
                    'role' => $role === 'ai' ? 'assistant' : 'user',
                    'content' => $content,
                ];
            }
        }

        // Agregar el mensaje actual
        $messages[] = [
            'role' => 'user',
            'content' => $currentMessage,
        ];

        return $messages;
    }

    /**
     * Extraer texto de la respuesta de Claude
     *
     * @param array $response
     * @return string
     */
    protected function extractTextFromResponse(array $response): string
    {
        if (isset($response['content']) && is_array($response['content'])) {
            foreach ($response['content'] as $content) {
                if (isset($content['type']) && $content['type'] === 'text') {
                    return $content['text'] ?? '';
                }
            }
        }

        return '';
    }

    /**
     * Generar contexto de paciente para la IA
     *
     * @param object $patient
     * @return string
     */
    public function buildPatientContext($patient): string
    {
        $config = config('claude.chat.patient_data_fields');
        $context = "Información del paciente:\n\n";

        if ($config['nombres_completos'] ?? false) {
            $context .= "Nombre completo: " . trim(
                ($patient->pnombre ?? '') . ' ' .
                ($patient->snombre ?? '') . ' ' .
                ($patient->papellido ?? '') . ' ' .
                ($patient->sapellido ?? '')
            ) . "\n";
        }

        if ($config['documento'] ?? false) {
            $context .= "Documento: " . ($patient->documento ?? 'N/A') . "\n";
        }

        if ($config['edad'] ?? false) {
            $context .= "Edad: " . ($patient->edad ?? 'N/A') . " años\n";
        }

        if ($config['sexo'] ?? false) {
            $context .= "Sexo: " . ($patient->sexo ?? 'N/A') . "\n";
        }

        if ($config['direccion'] ?? false) {
            $context .= "Dirección: " . ($patient->direccion ?? 'N/A') . "\n";
        }

        if ($config['telefono'] ?? false) {
            $context .= "Teléfono: " . ($patient->celular ?? $patient->telefono ?? 'N/A') . "\n";
        }

        if ($config['historias_clinicas'] ?? false) {
            $context .= "\nHistorias clínicas recientes:\n";
            if ($patient->historiap && $patient->historiap->count() > 0) {
                foreach ($patient->historiap->take(5) as $historia) {
                    $context .= "- Fecha: " . ($historia->created_at ?? 'N/A') . "\n";
                    $context .= "  Motivo: " . ($historia->motivo_consulta ?? 'N/A') . "\n";
                    $context .= "  Diagnóstico: " . ($historia->diagnostico_principal ?? 'N/A') . "\n\n";
                }
            } else {
                $context .= "No hay historias clínicas registradas.\n";
            }
        }

        return $context;
    }

    /**
     * Generar contexto de múltiples pacientes (para chat grupal)
     *
     * @param array $patients
     * @return string
     */
    public function buildMultiplePatientsContext(array $patients): string
    {
        $context = "Información de pacientes en este chat:\n\n";

        foreach ($patients as $index => $patient) {
            $context .= "Paciente " . ($index + 1) . ":\n";
            $context .= $this->buildPatientContext($patient);
            $context .= "\n---\n\n";
        }

        return $context;
    }

    /**
     * Verificar si el servicio está configurado correctamente
     *
     * @return bool
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }
}
