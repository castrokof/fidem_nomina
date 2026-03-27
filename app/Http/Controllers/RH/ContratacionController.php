<?php

namespace App\Http\Controllers\RH;

use App\Http\Controllers\Controller;
use App\Models\ContratacionCandidato;
use App\Models\ContratacionChecklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * ContratacionController
 * Fidem Clínica del Dolor — Coordinación RRHH
 * Laravel 5.7
 *
 * Vistas en: resources/views/rh/
 *
 * Rutas en routes/web.php:
 * ─────────────────────────────────────────────────
 * Route::prefix('rh/contratacion')->middleware('auth')->group(function () {
 *     Route::get('/',                      'RH\ContratacionController@index')    ->name('rh.contratacion.index');
 *     Route::post('/',                     'RH\ContratacionController@store')    ->name('rh.contratacion.store');
 *     Route::get('/{candidato}',           'RH\ContratacionController@show')     ->name('rh.contratacion.show');
 *     Route::post('/checklist/toggle',     'RH\ContratacionController@toggleItem')->name('rh.contratacion.toggle');
 *     Route::post('/{candidato}/fase',     'RH\ContratacionController@avanzarFase')->name('rh.contratacion.fase');
 *     Route::delete('/{candidato}',        'RH\ContratacionController@destroy')  ->name('rh.contratacion.destroy');
 * });
 * ─────────────────────────────────────────────────
 */
class ContratacionController extends Controller
{
    // ─────────────────────────────────────────────
    // Definición de ítems por fase y tipo
    // Fuente única de verdad — debe coincidir con el Blade
    // ─────────────────────────────────────────────
    private function getItemsPorFase(string $tipo): array
    {
        $esAdmin = $tipo === 'administrativo';

        $fase1_comun = [
            ['key' => 'hv_recibida',        'nombre' => 'Hoja de vida recibida',               'tag' => 'obligatorio'],
            ['key' => 'titulo_revisado',     'nombre' => 'Título / Certificado de formación',   'tag' => 'obligatorio'],
            ['key' => 'entrevista_citada',   'nombre' => 'Candidato citado a entrevista',       'tag' => 'obligatorio'],
        ];
        $fase1_asist = [['key' => 'rethus_validado',  'nombre' => 'RETHUS verificado',                          'tag' => 'bloqueante']];
        $fase1_admin = [['key' => 'rethus_validado',  'nombre' => 'RETHUS verificado (si cargo aplica)',         'tag' => 'segun_cargo']];

        $fase2_comun = [
            ['key' => 'entrevista_rh',       'nombre' => 'Entrevista Coordinador RRHH',         'tag' => 'obligatorio'],
            ['key' => 'entrevista_jefe',  'nombre' => 'Entrevista Jefe Área',                'tag' => 'obligatorio'],
            ['key' => 'candidato_aprobado',  'nombre' => 'Candidato aprobado y notificado',     'tag' => 'obligatorio'],
        ];
        $fase2_admin = [['key' => 'prueba_tecnica',   'nombre' => 'Prueba técnica aplicada',                    'tag' => 'segun_cargo']];

        $fase3_comun = [
            ['key' => 'cedula',              'nombre' => 'Cédula de ciudadanía',                'tag' => 'obligatorio'],
            ['key' => 'diploma',             'nombre' => 'Diploma / Certificado de formación',  'tag' => 'obligatorio'],
            ['key' => 'acta_grado',          'nombre' => 'Acta de grado',                       'tag' => 'obligatorio'],
            ['key' => 'exp_laboral',         'nombre' => 'Soportes de experiencia laboral',     'tag' => 'obligatorio'],
            ['key' => 'ant_judicial',        'nombre' => 'Antecedentes judiciales',             'tag' => 'obligatorio'],
            ['key' => 'ant_disciplinario',   'nombre' => 'Antecedentes disciplinarios',         'tag' => 'obligatorio'],
            ['key' => 'ant_fiscal',          'nombre' => 'Antecedentes fiscales',               'tag' => 'obligatorio'],
            ['key' => 'medidas_correctivas', 'nombre' => 'RNMC — Medidas correctivas',          'tag' => 'obligatorio'],
        ];
        $fase3_asist = [
            ['key' => 'tarjeta_prof',        'nombre' => 'Tarjeta profesional vigente',         'tag' => 'bloqueante'],
            ['key' => 'rethus_soporte',      'nombre' => 'Certificado RETHUS impreso',          'tag' => 'bloqueante'],
            ['key' => 'bls_acls',            'nombre' => 'Certificación BLS / ACLS',            'tag' => 'obligatorio'],
        ];
        $fase3_admin = [
            ['key' => 'rut',                 'nombre' => 'RUT (si prestador de servicios)',     'tag' => 'segun_cargo'],
            ['key' => 'acuerdo_conf',        'nombre' => 'Acuerdo de confidencialidad firmado', 'tag' => 'bloqueante'],
        ];

        $fase4_comun = [
            ['key' => 'vax_influenza',       'nombre' => 'Influenza — dosis anual',             'tag' => 'obligatorio'],
            ['key' => 'vax_covid',           'nombre' => 'COVID-19 — esquema completo',         'tag' => 'obligatorio'],
            ['key' => 'vax_tetanos',         'nombre' => 'Tétanos / dT o Tdap',                'tag' => 'obligatorio'],
        ];
        $fase4_asist = [
            ['key' => 'vax_hep_b',           'nombre' => 'Hepatitis B — 3 dosis + refuerzo',   'tag' => 'obligatorio'],
            ['key' => 'vax_varicela',        'nombre' => 'Varicela — 2 dosis o inmunidad',     'tag' => 'obligatorio'],
            ['key' => 'vax_mmr',             'nombre' => 'Triple Viral MMR — 2 dosis',         'tag' => 'obligatorio'],
            ['key' => 'vax_fiebre_am',       'nombre' => 'Fiebre Amarilla',                    'tag' => 'opcional'],
        ];
        $fase4_admin = [
            ['key' => 'vax_mmr',             'nombre' => 'Triple Viral MMR',                   'tag' => 'opcional'],
            ['key' => 'vax_hep_b',           'nombre' => 'Hepatitis B',                        'tag' => 'segun_cargo'],
        ];

        $fase5_comun = [
            ['key' => 'exam_preocupacional', 'nombre' => 'Examen preocupacional realizado',    'tag' => 'obligatorio'],
            ['key' => 'exam_aptitud',        'nombre' => 'Concepto de aptitud laboral',        'tag' => 'bloqueante'],
        ];
        $fase5_asist = [['key' => 'exam_parclinicos',  'nombre' => 'Paraclínicos de ingreso',           'tag' => 'obligatorio']];
        $fase5_admin = [['key' => 'exam_optometria',   'nombre' => 'Optometría (si aplica)',             'tag' => 'segun_cargo']];

        $fase6 = [
            ['key' => 'afil_eps',            'nombre' => 'EPS — Afiliación o traslado',        'tag' => 'obligatorio'],
            ['key' => 'afil_afp',            'nombre' => 'AFP — Fondo de Pensiones',           'tag' => 'obligatorio'],
            ['key' => 'afil_arl',            'nombre' => 'ARL — Antes del primer día',         'tag' => 'bloqueante'],
            ['key' => 'afil_ccf',            'nombre' => 'Caja de Compensación Familiar',      'tag' => 'obligatorio'],
            ['key' => 'cuenta_banco',        'nombre' => 'Certificación bancaria',             'tag' => 'obligatorio'],
        ];

        $fase7_comun = [
            ['key' => 'contrato_firmado',    'nombre' => 'Contrato laboral firmado',           'tag' => 'bloqueante'],
            ['key' => 'induccion',           'nombre' => 'Inducción institucional',            'tag' => 'obligatorio'],
            ['key' => 'accesos_sistemas',    'nombre' => 'Accesos a sistemas creados',         'tag' => 'obligatorio'],
            ['key' => 'dotacion',            'nombre' => 'Dotación y carnet entregados',       'tag' => 'obligatorio'],
        ];
        $fase7_admin = [['key' => 'induccion_cargo',   'nombre' => 'Inducción al cargo por jefe inmediato', 'tag' => 'obligatorio']];

        return [
            1 => array_merge($fase1_comun, $esAdmin ? $fase1_admin : $fase1_asist),
            2 => array_merge($fase2_comun, $esAdmin ? $fase2_admin : []),
            3 => array_merge($fase3_comun, $esAdmin ? $fase3_admin : $fase3_asist),
            4 => array_merge($fase4_comun, $esAdmin ? $fase4_admin : $fase4_asist),
            5 => array_merge($fase5_comun, $esAdmin ? $fase5_admin : $fase5_asist),
            6 => $fase6,
            7 => array_merge($fase7_comun, $esAdmin ? $fase7_admin : []),
        ];
    }

    // Tags que NO bloquean el avance de fase
    private const TAGS_NO_BLOQUEANTES = ['opcional', 'segun_cargo'];

    // ─────────────────────────────────────────────
    // INDEX — lista de candidatos en proceso
    // ─────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = ContratacionCandidato::orderBy('created_at', 'desc');

        // Filtros opcionales
        if ($request->filled('tipo')) {
            $query->where('tipo_personal', $request->tipo);
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('nombre_completo', 'like', "%{$q}%")
                    ->orWhere('cedula', 'like', "%{$q}%")
                    ->orWhere('cargo',  'like', "%{$q}%");
            });
        }

        $candidatos = $query->paginate(15);

        return view('rh.contratacion.index', compact('candidatos'));
    }

    // ─────────────────────────────────────────────
    // STORE — crear nuevo candidato
    // ─────────────────────────────────────────────
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre_completo'       => 'required|string|max:180',
            'cedula'                => 'required|string|max:20|unique:contratacion_candidatos,cedula',
            'cargo'                 => 'required|string|max:120',
            'tipo_personal'         => 'required|in:asistencial,administrativo',
            'area'                  => 'nullable|string|max:100',
            'fecha_inicio_proceso'  => 'nullable|date',
            'observaciones'         => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $candidato = ContratacionCandidato::create([
                'nombre_completo'      => $data['nombre_completo'],
                'cedula'               => $data['cedula'],
                'cargo'                => $data['cargo'],
                'tipo_personal'        => $data['tipo_personal'],
                'area'                 => $data['area'] ?? null,
                'fecha_inicio_proceso' => $data['fecha_inicio_proceso'] ?? now()->toDateString(),
                'observaciones'        => $data['observaciones'] ?? null,
                'fase_actual'          => 1,
                'progreso_porcentaje'  => 0,
                'estado'               => 'en_proceso',
                'creado_por'           => Auth::id(),
            ]);

            // Pre-crear todos los ítems del checklist en false
            $items = $this->getItemsPorFase($data['tipo_personal']);
            $rows  = [];
            foreach ($items as $fase => $faseItems) {
                foreach ($faseItems as $item) {
                    $rows[] = [
                        'candidato_id' => $candidato->id,
                        'fase'         => $fase,
                        'item_key'     => $item['key'],
                        'item_nombre'  => $item['nombre'],
                        'completado'   => false,
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ];
                }
            }
            ContratacionChecklist::insert($rows);

            DB::commit();

            return response()->json([
                'success'  => true,
                'redirect' => route('rh.contratacion.show', $candidato->id),
                'message'  => 'Candidato creado correctamente',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el candidato: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ─────────────────────────────────────────────
    // SHOW — vista principal de ruta para un candidato
    // ─────────────────────────────────────────────
    public function show(ContratacionCandidato $candidato)
    {
        $checklist = $candidato->checklist()->orderBy('fase')->get();

        // Calcular progreso total
        $itemsDef = $this->getItemsPorFase($candidato->tipo_personal);
        $totalItems = 0;
        $completados = 0;
        foreach ($itemsDef as $faseItems) {
            foreach ($faseItems as $item) {
                $totalItems++;
                $ci = $checklist->where('item_key', $item['key'])->first();
                if ($ci && $ci->completado) $completados++;
            }
        }
        $progresoPct = $totalItems > 0 ? intval(round($completados / $totalItems * 100)) : 0;

        return view('rh.contratacion.ruta-contratacion-dinamica', [
            'candidato'    => $candidato,
            'checklist'    => $checklist,
            'faseActual'   => $candidato->fase_actual,
            'tipoPersonal' => $candidato->tipo_personal,
            'progresoPct'  => $progresoPct,
        ]);
    }

    // ─────────────────────────────────────────────
    // TOGGLE ITEM — marcar / desmarcar ítem del checklist
    // ─────────────────────────────────────────────
    public function toggleItem(Request $request)
    {
        $data = $request->validate([
            'candidato_id' => 'required|integer|exists:contratacion_candidatos,id',
            'item_key'     => 'required|string|max:60',
            'fase'         => 'required|integer|between:1,7',
            'completado'   => 'required|boolean',
        ]);

        $candidato = ContratacionCandidato::findOrFail($data['candidato_id']);

        // Buscar o crear el ítem en el checklist
        $item = ContratacionChecklist::firstOrNew([
            'candidato_id' => $candidato->id,
            'item_key'     => $data['item_key'],
        ]);

        // Obtener nombre legible del ítem
        $itemsDef  = $this->getItemsPorFase($candidato->tipo_personal);
        $faseItems = $itemsDef[$data['fase']] ?? [];
        $itemDef   = collect($faseItems)->firstWhere('key', $data['item_key']);
        $itemNombre = $itemDef['nombre'] ?? $data['item_key'];

        if (!$item->exists) {
            $item->candidato_id = $candidato->id;
            $item->fase         = $data['fase'];
            $item->item_key     = $data['item_key'];
            $item->item_nombre  = $itemNombre;
        }

        $item->completado      = $data['completado'];
        $item->completado_por  = $data['completado'] ? Auth::user()->name ?? 'RRHH' : null;
        $item->completado_at   = $data['completado'] ? now() : null;
        $item->save();

        // Recalcular y persistir progreso general
        $progreso = $this->calcularProgreso($candidato);
        $candidato->progreso_porcentaje = $progreso;
        $candidato->save();

        return response()->json([
            'success'     => true,
            'item_nombre' => $itemNombre,
            'completado'  => $item->completado,
            'meta'        => [
                'porcentaje_general' => $progreso,
            ],
        ]);
    }

    // ─────────────────────────────────────────────
    // AVANZAR FASE — cambia la fase_actual del candidato
    // ─────────────────────────────────────────────
    public function avanzarFase(Request $request, ContratacionCandidato $candidato)
    {
        $data = $request->validate([
            'fase'   => 'required|integer|between:2,8',
            'estado' => 'nullable|string|in:en_proceso,aprobado,vinculado',
        ]);

        $faseAnterior = $candidato->fase_actual;
        $faseNueva    = $data['fase'];

        // Verificar que todos los ítems obligatorios de la fase anterior estén completos
        $itemsDef   = $this->getItemsPorFase($candidato->tipo_personal);
        $faseItems  = $itemsDef[$faseAnterior] ?? [];
        $checklist  = $candidato->checklist()->where('fase', $faseAnterior)->get();

        foreach ($faseItems as $itemDef) {
            if (in_array($itemDef['tag'], self::TAGS_NO_BLOQUEANTES)) continue;
            $ci = $checklist->where('item_key', $itemDef['key'])->first();
            if (!$ci || !$ci->completado) {
                return response()->json([
                    'success' => false,
                    'message' => "El ítem '{$itemDef['nombre']}' es obligatorio y no está completado.",
                ], 422);
            }
        }

        $candidato->fase_actual = min($faseNueva, 7);

        if ($faseNueva >= 8 || isset($data['estado'])) {
            $candidato->estado = $data['estado'] ?? 'vinculado';
            if ($candidato->estado === 'vinculado') {
                $candidato->fecha_vinculacion = now()->toDateString();
            }
        }

        $candidato->actualizado_por = Auth::id();
        $candidato->save();

        return response()->json([
            'success'      => true,
            'fase_nueva'   => $candidato->fase_actual,
            'estado'       => $candidato->estado,
            'message'      => 'Fase actualizada correctamente',
        ]);
    }

    // ─────────────────────────────────────────────
    // DESTROY — eliminar candidato (soft delete)
    // ─────────────────────────────────────────────
    public function destroy(ContratacionCandidato $candidato)
    {
        $candidato->delete();
        return response()->json(['success' => true]);
    }

    // ─────────────────────────────────────────────
    // HELPER — calcular % de progreso general
    // ─────────────────────────────────────────────
    private function calcularProgreso(ContratacionCandidato $candidato): int
    {
        $itemsDef  = $this->getItemsPorFase($candidato->tipo_personal);
        $checklist = $candidato->checklist()->get();

        $total = 0;
        $comp  = 0;
        foreach ($itemsDef as $faseItems) {
            foreach ($faseItems as $item) {
                $total++;
                $ci = $checklist->where('item_key', $item['key'])->first();
                if ($ci && $ci->completado) $comp++;
            }
        }

        return $total > 0 ? intval(round($comp / $total * 100)) : 0;
    }
}
