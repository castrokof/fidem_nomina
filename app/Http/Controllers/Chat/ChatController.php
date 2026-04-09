<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Services\ChatService;
use App\Models\Chat\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    protected $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    /**
     * Listar chats del usuario autenticado
     */
    public function index(Request $request)
    {
        try {
            $userId = Auth::user()->id;
            $chats = $this->chatService->getUserChats($userId);

            return response()->json([
                'success' => true,
                'chats' => $chats,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener chats: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Crear un nuevo chat
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'type' => 'required|in:individual,group',
            'description' => 'nullable|string',
            'participants' => 'required|array|min:1',
            'participants.*.id' => 'required|integer',
            'participants.*.type' => 'required|in:user,patient',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $data = $request->all();
            $data['created_by'] = Auth::user()->id_usuario;

            $chat = $this->chatService->createChat($data);

            return response()->json([
                'success' => true,
                'message' => 'Chat creado exitosamente',
                'chat' => $chat,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear chat: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mostrar un chat específico
     */
    public function show($id)
    {
        try {
            $chat = Chat::with([
                'messages' => function ($query) {
                    $query->orderBy('created_at', 'asc');
                },
                'messages.senderUser',
                'messages.senderPatient',
                'participants.user',
                'participants.patient',
            ])->findOrFail($id);

            // Verificar que el usuario autenticado es participante
            $userId = Auth::user()->id_usuario;
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

            // Marcar mensajes como leídos
            $this->chatService->markAsRead($chat, $userId, 'user');

            // Obtener estadísticas
            $stats = $this->chatService->getChatStats($chat);

            return response()->json([
                'success' => true,
                'chat' => $chat,
                'stats' => $stats,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener chat: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Buscar o crear chat con un paciente
     */
    public function findOrCreatePatientChat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|integer|exists:paciente,id_paciente',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $userId = Auth::user()->id_usuario;
            $patientId = $request->input('patient_id');

            $chat = $this->chatService->findOrCreatePatientChat($userId, $patientId);

            return response()->json([
                'success' => true,
                'chat' => $chat->load(['messages', 'participants']),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al buscar/crear chat: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Eliminar un chat (desactivar)
     */
    public function destroy($id)
    {
        try {
            $chat = Chat::findOrFail($id);

            // Verificar que el usuario es el creador
            $userId = Auth::user()->id_usuario;
            if ($chat->created_by !== $userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para eliminar este chat',
                ], 403);
            }

            $chat->is_active = false;
            $chat->save();

            return response()->json([
                'success' => true,
                'message' => 'Chat desactivado exitosamente',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar chat: ' . $e->getMessage(),
            ], 500);
        }
    }
}
