<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Services\ChatService;
use App\Models\Chat\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ChatMessageController extends Controller
{
    protected $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    /**
     * Enviar un mensaje al chat
     */
    public function store(Request $request, $chatId)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $chat = Chat::findOrFail($chatId);
            $userId = Auth::user()->id_usuario;

            // Verificar que el usuario es participante
            $isParticipant = $chat->participants()
                ->where('participant_id', $userId)
                ->where('participant_type', 'user')
                ->exists();

            if (!$isParticipant) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para enviar mensajes en este chat',
                ], 403);
            }

            $messageData = [
                'sender_id' => $userId,
                'sender_type' => 'user',
                'message' => $request->input('message'),
            ];

            $message = $this->chatService->sendMessage($chat, $messageData);

            return response()->json([
                'success' => true,
                'message' => 'Mensaje enviado exitosamente',
                'data' => $message->load(['senderUser', 'childMessages']),
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar mensaje: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Polling - Obtener mensajes nuevos desde una fecha
     */
    public function poll(Request $request, $chatId)
    {
        $validator = Validator::make($request->all(), [
            'since' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $chat = Chat::findOrFail($chatId);
            $userId = Auth::user()->id_usuario;

            // Verificar que el usuario es participante
            $isParticipant = $chat->participants()
                ->where('participant_id', $userId)
                ->where('participant_type', 'user')
                ->exists();

            if (!$isParticipant) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para ver este chat',
                ], 403);
            }

            $since = $request->input('since');
            $newMessages = $this->chatService->getNewMessages($chat, $since);

            // Marcar mensajes como leídos
            if ($newMessages->count() > 0) {
                $this->chatService->markAsRead($chat, $userId, 'user');
            }

            return response()->json([
                'success' => true,
                'messages' => $newMessages,
                'count' => $newMessages->count(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener mensajes: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtener todos los mensajes de un chat
     */
    public function index(Request $request, $chatId)
    {
        try {
            $chat = Chat::findOrFail($chatId);
            $userId = Auth::user()->id_usuario;

            // Verificar que el usuario es participante
            $isParticipant = $chat->participants()
                ->where('participant_id', $userId)
                ->where('participant_type', 'user')
                ->exists();

            if (!$isParticipant) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para ver este chat',
                ], 403);
            }

            $messages = $chat->messages()
                ->with(['senderUser', 'senderPatient', 'childMessages'])
                ->orderBy('created_at', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'messages' => $messages,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener mensajes: ' . $e->getMessage(),
            ], 500);
        }
    }
}
