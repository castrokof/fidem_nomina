<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Seguridad\Usuario;

class Chat extends Model
{
    use HasFactory;

    protected $table = 'chats';

    protected $fillable = [
        'name',
        'type',
        'created_by',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con el creador del chat
     */
    public function creator()
    {
        return $this->belongsTo(Usuario::class, 'created_by', 'id_usuario');
    }

    /**
     * Relación con los participantes
     */
    public function participants()
    {
        return $this->hasMany(ChatParticipant::class, 'chat_id');
    }

    /**
     * Relación con los mensajes
     */
    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'chat_id')->orderBy('created_at', 'asc');
    }

    /**
     * Último mensaje del chat
     */
    public function lastMessage()
    {
        return $this->hasOne(ChatMessage::class, 'chat_id')->latest();
    }

    /**
     * Scope para chats activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para chats individuales
     */
    public function scopeIndividual($query)
    {
        return $query->where('type', 'individual');
    }

    /**
     * Scope para chats grupales
     */
    public function scopeGroup($query)
    {
        return $query->where('type', 'group');
    }

    /**
     * Obtener chats de un usuario
     */
    public function scopeForUser($query, $userId)
    {
        return $query->whereHas('participants', function ($q) use ($userId) {
            $q->where('participant_id', $userId)
              ->where('participant_type', 'user');
        });
    }

    /**
     * Obtener chats de un paciente
     */
    public function scopeForPatient($query, $patientId)
    {
        return $query->whereHas('participants', function ($q) use ($patientId) {
            $q->where('participant_id', $patientId)
              ->where('participant_type', 'patient');
        });
    }
}
