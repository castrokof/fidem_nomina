<?php

namespace App\Services;

use App\Models\Chat\Chat;
use App\Models\Chat\ChatParticipant;
use App\Models\Chat\ChatMessage;
use App\Models\Admin\Paciente;
use App\Models\Seguridad\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class ChatService
{
    protected $claudeAIService;

    public function __construct(ClaudeAIService $claudeAIService)
    {
        $this->claudeAIService = $claudeAIService;
    }

    /**
     * Crear un nuevo chat
     *
     * @param array $data
     * @return Chat
     */
    public function createChat(array $data): Chat
    {
        DB::beginTransaction();

        try {
            $chat = Chat::create([
                'name' => $data['name'] ?? null,
                'type' => $data['type'] ?? 'individual',
                'created_by' => $data['created_by'],
                'description' => $data['description'] ?? null,
            ]);

            // Agregar participantes
            if (isset($data['participants']) && is_array($data['participants'])) {
                foreach ($data['participants'] as $participant) {
                    $this->addParticipant($chat, $participant);
                }
            }

            DB::commit();

            return $chat->load('participants', 'messages');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error al crear chat', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Agregar participante a un chat
     *
     * @param Chat $chat
     * @param array $participant
     * @return ChatParticipant
     */
    public function addParticipant(Chat $chat, array $participant): ChatParticipant
    {
        return ChatParticipant::firstOrCreate([
            'chat_id' => $chat->id,
            'participant_id' => $participant['id'],
            'participant_type' => $participant['type'],
        ], [
            'joined_at' => now(),
        ]);
    }

    /**
     * Enviar mensaje y obtener respuesta de IA
     *
     * @param Chat $chat
     * @param array $messageData
     * @return ChatMessage
     */
    public function sendMessage(Chat $chat, array $messageData): ChatMessage
    {
        DB::beginTransaction();

        try {
            // Crear mensaje del usuario
            $userMessage = ChatMessage::create([
                'chat_id' => $chat->id,
                'sender_id' => $messageData['sender_id'],
                'sender_type' => $messageData['sender_type'],
                'message' => $messageData['message'],
                'is_ai_message' => false,
            ]);

            // Obtener contexto del chat
            $context = $this->buildChatContext($chat);

            // Obtener respuesta de Claude AI
            $aiResponse = $this->claudeAIService->sendMessage(
                $messageData['message'],
                $context,
                $this->buildSystemPrompt($chat)
            );

            if ($aiResponse['success'] && !empty($aiResponse['response'])) {
                // Crear mensaje de respuesta de la IA
                $aiMessage = ChatMessage::create([
                    'chat_id' => $chat->id,
                    'sender_id' => null,
                    'sender_type' => 'ai',
                    'message' => $aiResponse['response'],
                    'is_ai_message' => true,
                    'parent_message_id' => $userMessage->id,
                    'metadata' => [
                        'usage' => $aiResponse['usage'] ?? null,
                    ],
                ]);

                // Actualizar el mensaje del usuario con la respuesta de IA
                $userMessage->ai_response = $aiResponse['response'];
                $userMessage->save();
            } else {
                Log::error('Error al obtener respuesta de Claude AI', $aiResponse);
            }

            DB::commit();

            return $userMessage->fresh(['childMessages']);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error al enviar mensaje', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Construir contexto del chat para la IA
     *
     * @param Chat $chat
     * @return array
     */
    protected function buildChatContext(Chat $chat): array
    {
        $contextLimit = config('claude.chat.context_message_limit', 10);

        $messages = $chat->messages()
            ->orderBy('created_at', 'desc')
            ->limit($contextLimit)
            ->get()
            ->reverse();

        $context = [];

        foreach ($messages as $message) {
            $context[] = [
                'role' => $message->is_ai_message ? 'ai' : 'user',
                'content' => $message->message,
            ];
        }

        return $context;
    }

    /**
     * Construir prompt del sistema con contexto de pacientes
     *
     * @param Chat $chat
     * @return string
     */
    protected function buildSystemPrompt(Chat $chat): string
    {
        $systemPrompt = config('claude.chat.system_context');

        // Obtener pacientes participantes del chat
        $patientParticipants = $chat->participants()
            ->where('participant_type', 'patient')
            ->get();

        if ($patientParticipants->count() > 0) {
            $patients = [];

            foreach ($patientParticipants as $participant) {
                $patient = Paciente::with('historiap')
                    ->find($participant->participant_id);

                if ($patient) {
                    $patients[] = $patient;
                }
            }

            if (count($patients) === 1) {
                $systemPrompt .= "\n\n" . $this->claudeAIService->buildPatientContext($patients[0]);
            } elseif (count($patients) > 1) {
                $systemPrompt .= "\n\n" . $this->claudeAIService->buildMultiplePatientsContext($patients);
            }
        }

        return $systemPrompt;
    }

    /**
     * Obtener mensajes nuevos desde una fecha (para polling)
     *
     * @param Chat $chat
     * @param string $since
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getNewMessages(Chat $chat, string $since)
    {
        return $chat->messages()
            ->where('created_at', '>', $since)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Obtener chats de un usuario
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserChats(int $userId)
    {
        return Chat::forUser($userId)
            ->active()
            ->with(['lastMessage', 'participants'])
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    /**
     * Obtener chats de un paciente
     *
     * @param int $patientId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPatientChats(int $patientId)
    {
        return Chat::forPatient($patientId)
            ->active()
            ->with(['lastMessage', 'participants'])
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    /**
     * Marcar mensajes como leídos
     *
     * @param Chat $chat
     * @param int $participantId
     * @param string $participantType
     * @return void
     */
    public function markAsRead(Chat $chat, int $participantId, string $participantType): void
    {
        $participant = ChatParticipant::where('chat_id', $chat->id)
            ->where('participant_id', $participantId)
            ->where('participant_type', $participantType)
            ->first();

        if ($participant) {
            $participant->markAsRead();
        }
    }

    /**
     * Obtener estadísticas de un chat
     *
     * @param Chat $chat
     * @return array
     */
    public function getChatStats(Chat $chat): array
    {
        return [
            'total_messages' => $chat->messages()->count(),
            'user_messages' => $chat->messages()->userMessages()->count(),
            'ai_messages' => $chat->messages()->aiMessages()->count(),
            'participants_count' => $chat->participants()->count(),
            'created_at' => $chat->created_at,
            'last_message_at' => $chat->messages()->latest()->value('created_at'),
        ];
    }

    /**
     * Buscar o crear chat individual con un paciente
     *
     * @param int $userId
     * @param int $patientId
     * @return Chat
     */
    public function findOrCreatePatientChat(int $userId, int $patientId): Chat
    {
        // Buscar chat existente con este usuario y paciente
        $chat = Chat::individual()
            ->whereHas('participants', function ($q) use ($userId) {
                $q->where('participant_id', $userId)
                  ->where('participant_type', 'user');
            })
            ->whereHas('participants', function ($q) use ($patientId) {
                $q->where('participant_id', $patientId)
                  ->where('participant_type', 'patient');
            })
            ->first();

        if ($chat) {
            return $chat;
        }

        // Crear nuevo chat
        $patient = Paciente::find($patientId);
        $chatName = 'Chat con ' . trim(
            ($patient->pnombre ?? '') . ' ' .
            ($patient->snombre ?? '') . ' ' .
            ($patient->papellido ?? '') . ' ' .
            ($patient->sapellido ?? '')
        );

        return $this->createChat([
            'name' => $chatName,
            'type' => 'individual',
            'created_by' => $userId,
            'participants' => [
                ['id' => $userId, 'type' => 'user'],
                ['id' => $patientId, 'type' => 'patient'],
            ],
        ]);
    }
}
