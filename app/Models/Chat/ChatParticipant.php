<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Seguridad\Usuario;
use App\Models\Admin\Paciente;

class ChatParticipant extends Model
{
    use HasFactory;

    protected $table = 'chat_participants';

    protected $fillable = [
        'chat_id',
        'participant_id',
        'participant_type',
        'joined_at',
        'last_read_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'last_read_at' => 'datetime',
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
     * Relación polimórfica con el participante
     */
    public function participant()
    {
        if ($this->participant_type === 'user') {
            return $this->belongsTo(Usuario::class, 'participant_id', 'id_usuario');
        } elseif ($this->participant_type === 'patient') {
            return $this->belongsTo(Paciente::class, 'participant_id', 'id_paciente');
        }

        return null;
    }

    /**
     * Obtener el usuario participante
     */
    public function user()
    {
        return $this->belongsTo(Usuario::class, 'participant_id', 'id_usuario');
    }

    /**
     * Obtener el paciente participante
     */
    public function patient()
    {
        return $this->belongsTo(Paciente::class, 'participant_id', 'id_paciente');
    }

    /**
     * Scope para participantes de tipo usuario
     */
    public function scopeUsers($query)
    {
        return $query->where('participant_type', 'user');
    }

    /**
     * Scope para participantes de tipo paciente
     */
    public function scopePatients($query)
    {
        return $query->where('participant_type', 'patient');
    }

    /**
     * Marcar como leído
     */
    public function markAsRead()
    {
        $this->last_read_at = now();
        $this->save();
    }

    /**
     * Obtener mensajes no leídos
     */
    public function getUnreadMessagesCount()
    {
        return $this->chat->messages()
            ->where('created_at', '>', $this->last_read_at ?? $this->joined_at)
            ->where('sender_id', '!=', $this->participant_id)
            ->count();
    }
}
