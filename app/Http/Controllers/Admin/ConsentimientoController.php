<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ConsentimientoInformado;
use App\Profesional;
use App\Paciente;
use App\AgendaCI;
use App\FirmaCI;
use App\AcudienteCI;
use App\Especialidad;
use App\Services\PdfConsentimientoService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ConsentimientoController extends Controller
{
    protected $pdfService;

    public function __construct(PdfConsentimientoService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    /**
     * Dashboard de consentimientos con estadísticas
     */
    public function dashboard()
    {
        // Estadísticas generales
        $totalConsentimientos = ConsentimientoInformado::count();
        $pendientes = ConsentimientoInformado::where('estado', 'pendiente')->count();
        $enProceso = ConsentimientoInformado::where('estado', 'en_proceso')->count();
        $firmados = ConsentimientoInformado::where('estado', 'firmado')->count();
        $cancelados = ConsentimientoInformado::where('estado', 'cancelado')->count();

        // Consentimientos del mes actual
        $consentimientosMes = ConsentimientoInformado::whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->count();

        // Últimos 10 consentimientos creados
        $ultimosConsentimientos = ConsentimientoInformado::with(['paciente', 'profesional', 'plantilla'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Consentimientos por especialidad (top 5)
        $porEspecialidad = ConsentimientoInformado::selectRaw('especialidad_id, count(*) as total')
            ->with('especialidad')
            ->groupBy('especialidad_id')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        // Consentimientos por profesional (top 5)
        $porProfesional = ConsentimientoInformado::selectRaw('profesional_id, profesional_nombre, count(*) as total')
            ->groupBy('profesional_id', 'profesional_nombre')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        // Consentimientos por mes (últimos 6 meses)
        $porMes = ConsentimientoInformado::selectRaw('MONTH(created_at) as mes, YEAR(created_at) as anio, count(*) as total')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at) DESC, MONTH(created_at) DESC')
            ->get();

        // Tasa de finalización (firmados vs total)
        $tasaFinalizacion = $totalConsentimientos > 0
            ? round(($firmados / $totalConsentimientos) * 100, 1)
            : 0;

        return view('consentimientos.dashboard', compact(
            'totalConsentimientos',
            'pendientes',
            'enProceso',
            'firmados',
            'cancelados',
            'consentimientosMes',
            'ultimosConsentimientos',
            'porEspecialidad',
            'porProfesional',
            'porMes',
            'tasaFinalizacion'
        ));
    }

    /**
     * Mostrar listado de consentimientos
     */
    public function index(Request $request)
    {
        $query = ConsentimientoInformado::with(['paciente', 'profesional', 'plantilla']);

        // Filtros
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('paciente_nombre', 'like', "%{$buscar}%")
                  ->orWhere('paciente_cedula', 'like', "%{$buscar}%")
                  ->orWhere('profesional_nombre', 'like', "%{$buscar}%");
            });
        }

        $consentimientos = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('consentimientos.index', compact('consentimientos'));
    }

   /**
 * Mostrar detalle de un consentimiento con contenido renderizado
 */
public function show($id)
{
    $consentimiento = ConsentimientoInformado::with([
        'paciente',
        'profesional',
        'profesional.especialidad',
        'plantilla',
        'firmas',
        'acudiente',
        'agenda'
    ])->findOrFail($id);

    // ✅ Preparar variables para reemplazo en la plantilla
    $variables = [
        'cups_descripcion'      => $consentimiento->cups_descripcion ?? $consentimiento->plantilla->nombre ?? '',
        'cups_codigo'           => $consentimiento->cups_codigo ?? '',
        'paciente_nombre'       => $consentimiento->paciente->nombres . ' ' . $consentimiento->paciente->apellidos,
        'paciente_cedula'       => $consentimiento->paciente->numero_documento,
        'paciente_tipo_doc'     => $consentimiento->paciente->tipo_documento,
        'paciente_edad'         => $consentimiento->paciente->edad ?? 'N/A',
        'paciente_genero'       => $consentimiento->paciente->genero ?? 'N/A',
        'profesional_nombre'    => $consentimiento->profesional->nombres . ' ' . $consentimiento->profesional->apellidos,
        'registro_medico'       => $consentimiento->profesional->registro_medico ?? 'N/A',
        'tarjeta_profesional'   => $consentimiento->profesional->tarjeta_profesional ?? 'N/A',
        'especialidad'          => $consentimiento->profesional->especialidad->nombre ?? 'N/A',
        'fecha_procedimiento'   => \Carbon\Carbon::parse($consentimiento->fecha_procedimiento)->format('d/m/Y H:i'),
        'fecha_actual'          => \Carbon\Carbon::now()->format('d/m/Y'),
        'clinica_nombre'        => 'Clínica Fidem',
        'clinica_direccion'     => 'Manizales, Colombia',
        'token_firma'           => $consentimiento->token_firma ?? '',
    ];

    // ✅ Renderizar el contenido de la plantilla con las variables
    $contenidoRenderizado = $consentimiento->plantilla->renderizar($variables);

    // ✅ Pasar a la vista
    return view('consentimientos.show', compact(
        'consentimiento',
        'contenidoRenderizado',  // ← Contenido con variables reemplazadas
        'variables'              // ← Opcional: para usar en otras partes
    ));
}

/**
 * Mostrar formulario para crear consentimiento
 */
public function create(Request $request)
{
    $fecha = $request->input('fecha', date('Y-m-d'));
    $codigoUsuario = $request->input('codigo_usuario');
    $centroprod = $request->input('centroprod');
    $pacienteId = $request->input('paciente_id');
    
    // Cargar especialistas para el select
    $especialistas = Profesional::where('activo', true)
        ->whereNotNull('codigo_usuario')
        ->orderBy('apellidos')->orderBy('nombres')
        ->get(['id', 'nombres', 'apellidos', 'codigo_usuario', 'especialidad_id']);
    
    // Cargar centros productivos únicos
    $centrosProd = AgendaCI::whereNotNull('centroprod')
        ->distinct()->pluck('centroprod')->sort()->values();
    
    // ✅ Colecciones vacías por defecto
    $agendasFiltradas = collect();
    $pacientesDisponibles = collect();
    $plantillas = collect();
    
    // Si hay fecha y especialista, cargar agendas con TODOS los campos necesarios
    if ($fecha && $codigoUsuario) {
        $query = AgendaCI::with(['paciente', 'profesional.especialidad'])
            ->whereDate('fecha', $fecha)
            ->where('codigo_consultorio', $codigoUsuario);
        
        if ($centroprod) $query->where('centroprod', $centroprod);
        if ($pacienteId) $query->where('paciente_id', $pacienteId);
        
        // ✅ Seleccionar explícitamente todos los campos que necesitas mostrar
        $agendasFiltradas = $query->orderBy('fecha')->get([
            'id',
            'fecha',
            'codigo_consultorio',
            'centroprod',
            'cups_codigo',
            'observaciones',
            'historia',
            'contrato',
            'empresafac',
            'paciente_id',
            'paciente_nombre',
            'paciente_cedula',
            'paciente_tipo_doc',
            'paciente_telefono',
            'profesional_id',
            'codigo_usuario',
            // Campos adicionales que quieras mostrar
            'estado',
            'atendido',
            'llegada_confirmada',
        ]);
        
        $pacientesDisponibles = $agendasFiltradas->pluck('paciente')
            ->filter()->unique('id')
            ->sortBy(fn($p) => ($p->apellidos ?? '') . ' ' . ($p->nombres ?? ''));
    }
    
    // Cargar plantillas si hay especialista seleccionado
    if ($codigoUsuario) {
        $profesional = Profesional::where('codigo_usuario', $codigoUsuario)->first();
        $especialidadId = $profesional->especialidad_id ?? null;
        
        $plantillasQuery = \App\PlantillaCI::with('especialidades')->where('activo', true);
        
        if ($especialidadId) {
            $plantillasQuery->whereHas('especialidades', function($q) use ($especialidadId) {
                $q->where('especialidades.id', $especialidadId);
            });
        }
        
        $plantillas = $plantillasQuery->orderBy('nombre')->get();
    }
    
    return view('consentimientos.create', compact(
        'fecha', 'codigoUsuario', 'centroprod', 'pacienteId',
        'especialistas', 'centrosProd',
        'agendasFiltradas',
        'pacientesDisponibles',
        'plantillas'
    ));
}

/**
 * Redirigir al formulario de creación con datos pre-cargados de la agenda
 */
public function createFromAgenda($agenda_id)
{
    $agenda = AgendaCI::with(['paciente', 'profesional.especialidad'])
        ->findOrFail($agenda_id);
    
    // Redirigir al formulario de creación con parámetros pre-seleccionados
    return redirect()->route('consentimientos.create', [
        'fecha' => \Carbon\Carbon::parse($agenda->fecha)->format('Y-m-d'),
        'codigo_usuario' => $agenda->codigo_consultorio,
        'agenda_id' => $agenda->id,  // Para pre-seleccionar en el form final
    ])->with('selected_paciente_id', $agenda->paciente_id);
}

/**
 * Guardar consentimiento(s)
 */
public function store(Request $request)
{
    $request->validate([
        'paciente_id'         => 'required|exists:pacientes,id',
        'agenda_ci_id'        => 'required|exists:agenda_ci,id',
        'profesional_id'      => 'required|exists:profesionales,id',  // ← ID real, no código
        'plantillas'          => 'required|array|min:1',
        'plantillas.*'        => 'required|exists:plantillas_ci,id',
        'fecha_procedimiento' => 'required|date',
        'cups_codigo'         => 'nullable|string|max:20',
        'observaciones'       => 'nullable|string|max:500',
    ]);
    
    // ✅ Obtener profesional por ID (el que viene del AJAX)
    $profesional = Profesional::findOrFail($request->profesional_id);

    $paciente = Paciente::findOrFail($request->paciente_id);

    $creados = 0;
    $errores = [];
    
        $consentimientosCreados = [];

        foreach ($request->input('plantillas', []) as $plantillaId) {
            try {
                $plantilla = \App\PlantillaCI::findOrFail($plantillaId);

                $consentimiento = ConsentimientoInformado::create([
                    'agenda_ci_id'        => $request->agenda_ci_id,
                    'paciente_id'         => $paciente->id,
                    'paciente_nombre'     => $paciente->nombres . ' ' . $paciente->apellidos,
                    'paciente_cedula'     => $paciente->numero_documento,
                    'paciente_tipo_doc'   => $paciente->tipo_documento,
                    'paciente_edad'       => $paciente->edad ?? null,
                    'paciente_genero'     => $paciente->genero ?? null,
                    'profesional_id'      => $profesional->id,
                    'profesional_nombre'  => $profesional->nombres . ' ' . $profesional->apellidos,
                    'especialidad_id'     => $profesional->especialidad_id,
                    'plantilla_id'        => $plantillaId,
                    'cups_codigo'         => $request->cups_codigo,
                    'observaciones'       => $request->observaciones,
                    'fecha_procedimiento' => $request->fecha_procedimiento,
                    'estado'              => 'pendiente',
                    'requiere_acudiente'  => $plantilla->requiere_acudiente_obligatorio,
                    'token_firma'         => Str::random(64),
                    'token_expira_at'     => now()->addHours(24),
                    'ip_generacion'       => $request->ip(),
                ]);

                // Estampar firma del profesional automáticamente
                if (!empty($profesional->firma_base64)) {
                    FirmaCI::create([
                        'consentimiento_id' => $consentimiento->id,
                        'tipo_firmante'     => 'profesional',
                        'firma_base64'      => $profesional->firma_base64,
                        'firmante_nombre'   => $profesional->nombres . ' ' . $profesional->apellidos,
                        'firmante_cedula'   => $profesional->numero_documento,
                        'ip_firma'          => $request->ip(),
                        'firmado_at'        => now(),
                    ]);
                }

                $consentimientosCreados[] = [
                    'id'           => $consentimiento->id,
                    'plantilla'    => $plantilla->nombre,
                    'link_firma'   => route('consentimientos.firmar', $consentimiento->token_firma),
                    'expira_at'    => now()->addHours(24)->format('d/m/Y H:i'),
                ];
                $creados++;

            } catch (\Exception $e) {
                $errores[] = "Plantilla #{$plantillaId}: " . $e->getMessage();
                \Log::error("Error: " . $e->getMessage());
            }
        }

        if ($creados > 0) {
            return response()->json([
                'success'          => true,
                'message'          => "Se crearon {$creados} consentimiento(s)",
                'consentimientos'  => $consentimientosCreados,
                'paciente_nombre'  => $paciente->nombres . ' ' . $paciente->apellidos,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al crear',
            'errors'  => $errores
        ], 422);
}

    /**
     * Mostrar vista de firma táctil (la más importante)
     */
    public function mostrarFirma($token)
    {
        $consentimiento = ConsentimientoInformado::with([
            'paciente',
            'profesional',
            'plantilla',
            'firmas',
            'acudiente'
        ])->where('token_firma', $token)->firstOrFail();

        // Verificar que el token no haya expirado
        if (!$consentimiento->tokenEsValido()) {
            return view('consentimientos.token-expirado', compact('consentimiento'));
        }

        // Verificar qué firmas faltan
        $firmasFaltantes = $consentimiento->firmasFaltantes();

        if (empty($firmasFaltantes)) {
            // Ya está completamente firmado
            $consentimiento->update(['estado' => 'firmado']);
            return view('consentimientos.ya-firmado', compact('consentimiento'));
        }

        return view('consentimientos.firmar', compact('consentimiento', 'firmasFaltantes','token'));
    }

    /**
     * Guardar firma del paciente o acudiente
     */
    public function guardarFirma(Request $request, $token)
    {
        $consentimiento = ConsentimientoInformado::where('token_firma', $token)->firstOrFail();

        if (!$consentimiento->tokenEsValido()) {
            return response()->json([
                'success' => false,
                'message' => 'El token de firma ha expirado'
            ], 403);
        }

        $noSabeFirmar = filter_var($request->no_sabe_firmar, FILTER_VALIDATE_BOOLEAN);

        $request->validate([
            'tipo_firmante'        => 'required|in:paciente,acudiente',
            'firma_base64'         => $noSabeFirmar ? 'nullable|string' : 'required|string',
            'foto_base64'          => $noSabeFirmar ? 'required|string' : 'nullable|string',
            'no_sabe_firmar'       => 'nullable|boolean',
            'firmante_nombre'      => 'required|string|max:200',
            'firmante_cedula'      => 'nullable|string|max:20',
            'firmante_edad'        => 'required_if:tipo_firmante,paciente|nullable|integer|min:0|max:150',
            'firmante_genero'      => 'required_if:tipo_firmante,paciente|nullable|in:Masculino,Femenino,Otro',
            'firmante_relacion'    => 'required_if:tipo_firmante,acudiente|nullable|string|max:100',
            'desea_ser_informado'  => 'required_if:tipo_firmante,paciente|nullable|boolean'
        ]);

        // Crear la firma
        FirmaCI::create([
            'consentimiento_id' => $consentimiento->id,
            'tipo_firmante'     => $request->tipo_firmante,
            'firma_base64'      => $noSabeFirmar ? null : $request->firma_base64,
            'foto_base64'       => $noSabeFirmar ? $request->foto_base64 : null,
            'no_sabe_firmar'    => $noSabeFirmar,
            'firmante_nombre'   => $request->firmante_nombre,
            'firmante_cedula'   => $request->firmante_cedula,
            'firmante_edad'     => $request->firmante_edad,
            'firmante_genero'   => $request->firmante_genero,
            'firmante_relacion' => $request->firmante_relacion,
            'ip_firma'          => $request->ip(),
            'user_agent'        => $request->userAgent(),
            'firmado_at'        => now(),
        ]);

        // Si es firma del paciente, actualizar datos en el consentimiento
        if ($request->tipo_firmante === 'paciente') {
            $consentimiento->update([
                'paciente_edad'        => $request->firmante_edad,
                'paciente_genero'      => $request->firmante_genero,
                'desea_ser_informado'  => $request->desea_ser_informado ?? true,
            ]);
        }

        // Si es acudiente, también crear el registro de acudiente
        if ($request->tipo_firmante === 'acudiente') {
            AcudienteCI::create([
                'consentimiento_id'     => $consentimiento->id,
                'nombre_completo'       => $request->firmante_nombre,
                'cedula'                => $request->firmante_cedula,
                'relacion_con_paciente' => $request->firmante_relacion,
            ]);
        }

        // Actualizar estado si ya está completo
        if ($consentimiento->estaCompleto()) {
            $consentimiento->update(['estado' => 'firmado']);

            // ✅ Recargar relaciones antes de generar PDF
            $consentimiento->load([
                'profesional',
                'paciente',
                'plantilla',
                'firmaPaciente',
                'firmaAcudiente',
                'firmaProfesional',
                'acudiente'
            ]);

            // Generar PDF
            try {
                $this->pdfService->generar($consentimiento);
            } catch (\Exception $e) {
                // Log pero no fallar
                \Log::error('Error generando PDF: ' . $e->getMessage());
            }
        } else {
            $consentimiento->update(['estado' => 'en_proceso']);
        }

        return response()->json([
            'success'  => true,
            'message'  => 'Firma registrada exitosamente',
            'completo' => $consentimiento->estaCompleto()
        ]);
    }

    /**
     * Descargar PDF del consentimiento
     */
    public function descargarPdf($id)
    {
        $consentimiento = ConsentimientoInformado::with([
            'paciente',
            'profesional',
            'especialidad',
            'plantilla',
            'firmas',
            'firmaPaciente',
            'firmaAcudiente',
            'firmaProfesional',
            'acudiente'
        ])->findOrFail($id);

        return $this->pdfService->descargar($consentimiento);
    }

    // Agregar al final de ConsentimientoController.php

/**
 * AJAX: Obtener pacientes filtrados con datos completos de sus citas
 */
public function ajaxPacientesPorFiltros(Request $request)
{
    $fecha = $request->input('fecha');
    $codigoUsuario = $request->input('codigo_usuario');
    $centroprod = $request->input('centroprod');
    
    if (!$fecha || !$codigoUsuario) {
        return response()->json(['success' => false, 'pacientes' => []]);
    }
    
    // ✅ Usar la relación personalizada profesionalPorCodigo y cargar consentimientos
    $query = AgendaCI::with(['paciente', 'profesionalPorCodigo', 'consentimientos.firmas'])
        ->whereDate('fecha', $fecha)
        ->where('codigo_consultorio', $codigoUsuario);  // ← Filtro por código

    if ($centroprod) {
        $query->where('centroprod', $centroprod);
    }

    $agendas = $query->orderBy('fecha', 'asc')->get([
        'id', 'fecha', 'codigo_consultorio', 'centroprod', 'cups_codigo',
        'observaciones', 'contrato', 'empresafac', 'estado', 'atendido',
        'llegada_confirmada', 'historia', 'paciente_id', 'paciente_nombre',
        'paciente_cedula', 'paciente_tipo_doc', 'paciente_telefono',
        'profesional_id', 'numero_factura', 'documento_factura'
    ]);
    
    // Agrupar por paciente
    $pacientes = $agendas->groupBy('paciente_id')
        ->map(function($agendas, $pacienteId) {
            $primera = $agendas->first();
            return [
                'id' => $primera->paciente_id,
                'nombre_completo' => $primera->paciente_nombre ?? 
                    (($primera->paciente->nombres ?? '') . ' ' . ($primera->paciente->apellidos ?? '')) ?: 'N/A',
                'documento' => ($primera->paciente_tipo_doc ?? $primera->paciente->tipo_documento ?? 'CC') . '-' . 
                              ($primera->paciente_cedula ?? $primera->paciente->numero_documento ?? 'N/A'),
                'citas_count' => $agendas->count(),
                'citas' => $agendas->map(function($a) {
                    // ✅ Obtener profesional por la relación personalizada
                    $profesional = $a->profesionalPorCodigo;

                    // ✅ Obtener información de consentimientos
                    $consentimientos = $a->consentimientos;
                    $tieneConsentimientos = $consentimientos->count() > 0;

                    // Analizar estado de consentimientos
                    $consentimientosPendientes = $consentimientos->where('estado', 'pendiente')->count();
                    $consentimientosEnProceso = $consentimientos->where('estado', 'en_proceso')->count();
                    $consentimientosFirmados = $consentimientos->where('estado', 'firmado')->count();
                    $totalConsentimientos = $consentimientos->count();

                    // Determinar estado general
                    $estadoConsentimientos = 'sin_consentimiento';
                    if ($totalConsentimientos > 0) {
                        if ($consentimientosFirmados === $totalConsentimientos) {
                            $estadoConsentimientos = 'todos_firmados';
                        } elseif ($consentimientosEnProceso > 0) {
                            $estadoConsentimientos = 'en_proceso';
                        } else {
                            $estadoConsentimientos = 'pendiente';
                        }
                    }

                    return [
                        'agenda_id' => $a->id,
                        'fecha' => $a->fecha,
                        'hora_completa' => \Carbon\Carbon::parse($a->fecha)->format('H:i:s'),
                        'hora_corta' => \Carbon\Carbon::parse($a->fecha)->format('H:i'),
                        'centroprod' => $a->centroprod,
                        'cups_codigo' => $a->cups_codigo,
                        'observaciones' => $a->observaciones,
                        'contrato' => $a->contrato,
                        'empresafac' => $a->empresafac,
                        'estado' => $a->estado,
                        'atendido' => $a->atendido,
                        'llegada_confirmada' => $a->llegada_confirmada,
                        'historia' => $a->historia,
                        // ✅ DATOS DEL PROFESIONAL (por código)
                        'profesional_id' => $profesional->id ?? $a->profesional_id,
                        'profesional_nombre' => $profesional->nombres . ' ' . $profesional->apellidos ?? '',
                        'profesional_especialidad_id' => $profesional->especialidad_id,
                        'profesional_codigo_usuario' => $a->codigo_consultorio,  // ← El código que los une
                        // ✅ INFORMACIÓN DE CONSENTIMIENTOS
                        'tiene_consentimientos' => $tieneConsentimientos,
                        'total_consentimientos' => $totalConsentimientos,
                        'consentimientos_pendientes' => $consentimientosPendientes,
                        'consentimientos_en_proceso' => $consentimientosEnProceso,
                        'consentimientos_firmados' => $consentimientosFirmados,
                        'estado_consentimientos' => $estadoConsentimientos,
                        'numero_factura'  => $a->numero_factura ?? '',
                        'documento_factura' => $a->documento_factura ?? '',
                    ];
                })->values()
            ];
        })
        ->sortBy('fecha')
        ->values();
    
    return response()->json([
        'success' => true,
        'pacientes' => $pacientes,
        'total' => $pacientes->count()
    ]);
}

/**
 * AJAX: Obtener datos completos de un paciente específico
 */
public function ajaxDatosPaciente($paciente_id, Request $request)
{
    $fecha = $request->input('fecha');
    $codigoUsuario = $request->input('codigo_usuario');
    
    // ✅ Usar relación personalizada profesionalPorCodigo
    $agenda = AgendaCI::with(['paciente', 'profesionalPorCodigo'])
        ->where('paciente_id', $paciente_id)
        ->whereDate('fecha', $fecha)
        ->where('codigo_consultorio', $codigoUsuario)  // ← Filtro por código
        ->orderBy('fecha', 'asc')
        ->first();
    
    if (!$agenda) {
        return response()->json(['success' => false, 'message' => 'Paciente no encontrado con estos filtros']);
    }
    
    // ✅ Obtener profesional por la relación personalizada
    $profesional = $agenda->profesionalPorCodigo;
    
    if (!$profesional) {
        return response()->json([
            'success' => false, 
            'message' => 'La cita no tiene profesional asignado (codigo_consultorio: ' . $agenda->codigo_consultorio . ')'
        ]);
    }
    
    // Obtener plantillas por especialidad del profesional
    $especialidadId = $profesional->especialidad_id ?? null;
    $plantillasQuery = \App\PlantillaCI::with('especialidades')->where('activo', true);
    
    if ($especialidadId) {
        $plantillasQuery->whereHas('especialidades', function($q) use ($especialidadId) {
            $q->where('especialidades.id', $especialidadId);
        });
    }
    
    $plantillas = $plantillasQuery->orderBy('nombre')->get(['id', 'nombre']);
    
    // ✅ Retornar JSON con profesional desde la relación por código
    return response()->json([
        'success' => true,
        'paciente' => [
            'id' => $agenda->paciente_id,
            'nombre' => $agenda->paciente_nombre ?? ($agenda->paciente->nombres . ' ' . $agenda->paciente->apellidos),
            'documento' => $agenda->paciente_tipo_doc ?? $agenda->paciente->tipo_documento,
            'cedula' => $agenda->paciente_cedula ?? $agenda->paciente->numero_documento,
            'telefono' => $agenda->paciente_telefono ?? $agenda->paciente->telefono ?? '',
        ],
        'cita' => [
            'agenda_id' => $agenda->id,
            'fecha' => $agenda->fecha,
            'hora_completa' => \Carbon\Carbon::parse($agenda->fecha)->format('H:i:s'),
            'hora_corta' => \Carbon\Carbon::parse($agenda->fecha)->format('H:i'),
            'centroprod' => $agenda->centroprod ?? '',
            'cups_codigo' => $agenda->cups_codigo ?? '',
            'observaciones' => $agenda->observaciones ?? '',
            'contrato' => $agenda->contrato ?? '',
            'empresafac' => $agenda->empresafac ?? '',
            'historia' => $agenda->historia ?? '',
            'codigo_consultorio' => $agenda->codigo_consultorio,
            'numero_factura'    => $agenda->numero_factura ?? '',
            'documento_factura' => $agenda->documento_factura ?? '',
        ],
        // ✅ PROFESIONAL: Desde la relación por código
        'profesional' => [
            'id' => $profesional->id,              // ← ID real del profesional
            'nombre' => $profesional->nombres . ' ' . $profesional->apellidos,
            'especialidad_id' => $profesional->especialidad_id,
            'especialidad_nombre' => $profesional->especialidad->nombre ?? '',
            'codigo_usuario' => $profesional->codigo_usuario,  // ← El código que los une
        ],
        'plantillas' => $plantillas,
        'especialidad_id' => $especialidadId
    ]);
}

/**
 * AJAX: Obtener plantillas por especialidad (endpoint directo)
 */
public function ajaxPlantillasPorEspecialidad($especialidad_id)
{
    $plantillas = \App\PlantillaCI::where('activo', true)
        ->whereHas('especialidades', function($q) use ($especialidad_id) {
            $q->where('especialidades.id', $especialidad_id);
        })
        ->orderBy('nombre')
        ->get(['id', 'nombre']);
    
    return response()->json(['success' => true, 'plantillas' => $plantillas]);
}
}
