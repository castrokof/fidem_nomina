<?php
// ══════════════════════════════════════════════
// app/Models/ContratacionCandidato.php
// ══════════════════════════════════════════════

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContratacionCandidato extends Model
{
    use SoftDeletes;

    protected $table = 'contratacion_candidatos';

    protected $fillable = [
        'nombre_completo', 'cedula', 'cargo', 'tipo_personal', 'area',
        'correo', 'telefono', 'fase_actual', 'progreso_porcentaje',
        'estado', 'rethus_validado', 'rethus_fecha_validacion', 'rethus_numero',
        'fecha_inicio_proceso', 'fecha_vinculacion', 'observaciones',
        'creado_por', 'actualizado_por',
    ];

    protected $dates = [
        'fecha_inicio_proceso',
        'fecha_vinculacion',
        'rethus_fecha_validacion',
        'deleted_at',
    ];

    protected $casts = [
        'rethus_validado'     => 'boolean',
        'fase_actual'         => 'integer',
        'progreso_porcentaje' => 'integer',
    ];

    // ── Relaciones ──────────────────────────────
    public function checklist()
    {
        return $this->hasMany(ContratacionChecklist::class, 'candidato_id');
    }

    public function documentos()
    {
        return $this->hasMany(ContratacionDocumento::class, 'candidato_id');
    }

    // ── Scopes ──────────────────────────────────
    public function scopeEnProceso($q)    { return $q->where('estado', 'en_proceso'); }
    public function scopeVinculados($q)   { return $q->where('estado', 'vinculado'); }
    public function scopeAsistencial($q)  { return $q->where('tipo_personal', 'asistencial'); }
    public function scopeAdministrativo($q){ return $q->where('tipo_personal', 'administrativo'); }

    // ── Accessors ────────────────────────────────
    public function getInicialAttribute(): string
    {
        return strtoupper(substr($this->nombre_completo, 0, 1));
    }

    public function getTipoLabelAttribute(): string
    {
        return $this->tipo_personal === 'administrativo' ? '🗂 Administrativo' : '🩺 Asistencial';
    }

    public function getEstadoLabelAttribute(): string
    {
        return strtoupper(str_replace('_', ' ', $this->estado));
    }
}