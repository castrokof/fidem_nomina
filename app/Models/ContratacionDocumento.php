<?php
// ══════════════════════════════════════════════
// app/Models/ContratacionDocumento.php
// ══════════════════════════════════════════════

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContratacionDocumento extends Model
{
    protected $table = 'contratacion_documentos';

    protected $fillable = [
        'candidato_id', 'tipo_documento', 'nombre_archivo',
        'ruta_archivo', 'mime_type', 'subido_por',
    ];

    public function candidato()
    {
        return $this->belongsTo(ContratacionCandidato::class, 'candidato_id');
    }
}