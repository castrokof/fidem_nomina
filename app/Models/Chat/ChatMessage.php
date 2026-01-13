<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Seguridad\Usuario;
use App\Models\Admin\Paciente;

class ChatMessage extends Model
{
    use HasFactory;

    protected $table = 'chat_messages';

    protected $fillable = [
        'chat_id',
        'sender_id',
        'sender_type',
        'message',
        'ai_response',
        'is_ai_message',
        'parent_message_id',
        'metadata',
    ];

    protected $casts = [
        'is_ai_message' => 'boolean',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con el chat
     */
    public function chat()
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }

    /**
     * Relación con el mensaje padre
     */
    public function parentMessage()
    {
        return $this->belongsTo(ChatMessage::class, 'parent_message_id');
    }

    /**
     * Relación con los mensajes hijos
     */
    public function childMessages()
    {
        return $this->hasMany(ChatMessage::class, 'parent_message_id');
    }

    /**
     * Obtener el remitente (usuario)
     */
    public function senderUser()
    {
        return $this->belongsTo(Usuario::class, 'sender_id', 'id_usuario');
    }

    /**
     * Obtener el remitente (paciente)
     */
    public function senderPatient()
    {
        return $this->belongsTo(Paciente::class, 'sender_id', 'id_paciente');
    }

    /**
     * Obtener información del remitente
     */
    public function getSenderAttribute()
    {
        if ($this->sender_type === 'ai') {
            return [
                'id' => null,
                'name' => 'Asistente IA',
                'type' => 'ai',
            ];
        }

        if ($this->sender_type === 'user') {
            $user = $this->senderUser;
            return $user ? [
                'id' => $user->id_usuario,
                'name' => $user->name ?? $user->email,
                'type' => 'user',
            ] : null;
        }

        if ($this->sender_type === 'patient') {
            $patient = $this->senderPatient;
            return $patient ? [
                'id' => $patient->id_paciente,
                'name' => trim(
                    ($patient->pnombre ?? '') . ' ' .
                    ($patient->snombre ?? '') . ' ' .
                    ($patient->papellido ?? '') . ' ' .
                    ($patient->sapellido ?? '')
                ),
                'type' => 'patient',
            ] : null;
        }

        return null;
    }

    /**
     * Scope para mensajes de IA
     */
    public function scopeAiMessages($query)
    {
        return $query->where('is_ai_message', true);
    }

    /**
     * Scope para mensajes de usuarios
     */
    public function scopeUserMessages($query)
    {
        return $query->where('sender_type', 'user');
    }

    /**
     * Scope para mensajes de pacientes
     */
    public function scopePatientMessages($query)
    {
        return $query->where('sender_type', 'patient');
    }

    /**
     * Scope para mensajes desde una fecha
     */
    public function scopeSince($query, $dateTime)
    {
        return $query->where('created_at', '>', $dateTime);
    }
}
