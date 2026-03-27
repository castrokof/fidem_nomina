<?php
// ══════════════════════════════════════════════
// app/Models/ContratacionChecklist.php
// ══════════════════════════════════════════════

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContratacionChecklist extends Model
{
    protected $table = 'contratacion_checklist';

    protected $fillable = [
        'candidato_id', 'fase', 'item_key', 'item_nombre',
        'completado', 'completado_por', 'completado_at', 'nota',
    ];

    protected $dates = ['completado_at'];

    protected $casts = ['completado' => 'boolean', 'fase' => 'integer'];

    public function candidato()
    {
        return $this->belongsTo(ContratacionCandidato::class, 'candidato_id');
    }
}