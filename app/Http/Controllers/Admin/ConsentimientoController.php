<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ConsentimientoInformado;
use App\Profesional;
use App\Paciente;
use App\AgendaCI;
use App\FirmaCI;
use App\AcudienteCI;
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
     * Mostrar detalle de un consentimiento
     */
    public function show($id)
    {
        $consentimiento = ConsentimientoInformado::with([
            'paciente',
            'profesional',
            'especialidad',
            'plantilla',
            'firmas',
            'acudiente',
            'agenda'
        ])->findOrFail($id);

        return view('consentimientos.show', compact('consentimiento'));
    }

    /**
     * Mostrar formulario para crear consentimiento
     */
    public function create(Request $request)
    {
        // Obtener profesional autenticado
        $profesional = Profesional::where('usuario_id', auth()->id())->first();

        if (!$profesional) {
            return redirect()->back()
                ->with('error', 'Tu usuario no tiene un perfil de profesional asignado. Contacta al administrador.');
        }

        // Verificar que tenga firma registrada
        if (!$profesional->tieneFirmaRegistrada()) {
            return redirect()->route('perfil.firma')
                ->with('warning', 'Debes registrar tu firma digital antes de generar consentimientos.');
        }

        // Si viene desde una agenda, cargar datos
        $agenda = null;
        if ($request->has('agenda_id')) {
            $agenda = AgendaCI::with('paciente')->findOrFail($request->agenda_id);
        }

        // Plantillas disponibles según la especialidad del profesional
        $plantillas = $profesional->plantillasDisponibles();

        return view('consentimientos.create', compact('agenda', 'plantillas', 'profesional'));
    }

    /**
     * Guardar nuevo consentimiento
     */
    public function store(Request $request)
    {
        $profesional = Profesional::where('usuario_id', auth()->id())->firstOrFail();

        if (!$profesional->tieneFirmaRegistrada()) {
            return redirect()->route('perfil.firma')
                ->with('warning', 'Registra tu firma primero.');
        }

        $request->validate([
            'agenda_ci_id'        => 'nullable|exists:agenda_ci,id',
            'plantilla_id'        => 'required|exists:plantillas_ci,id',
            'paciente_nombre'     => 'required|string|max:200',
            'paciente_cedula'     => 'required|string|max:20',
            'paciente_tipo_doc'   => 'required|string|max:5',
            'paciente_edad'       => 'nullable|integer|min:0|max:120',
            'paciente_genero'     => 'nullable|in:M,F,O',
            'fecha_procedimiento' => 'required|date',
            'requiere_acudiente'  => 'nullable|boolean',
            'cups_codigo'         => 'nullable|string|max:20',
            'cups_descripcion'    => 'nullable|string|max:300',
        ]);

        // Buscar o crear paciente
        $paciente = Paciente::firstOrCreate(
            [
                'tipo_documento'   => $request->paciente_tipo_doc,
                'numero_documento' => $request->paciente_cedula
            ],
            [
                'nombres'   => explode(' ', $request->paciente_nombre, 2)[0] ?? $request->paciente_nombre,
                'apellidos' => explode(' ', $request->paciente_nombre, 2)[1] ?? '',
                'edad'      => $request->paciente_edad,
                'genero'    => $request->paciente_genero,
            ]
        );

        // Crear consentimiento
        $consentimiento = ConsentimientoInformado::create([
            'agenda_ci_id'        => $request->agenda_ci_id,
            'paciente_id'         => $paciente->id,
            'paciente_nombre'     => $request->paciente_nombre,
            'paciente_cedula'     => $request->paciente_cedula,
            'paciente_tipo_doc'   => $request->paciente_tipo_doc,
            'paciente_edad'       => $request->paciente_edad,
            'paciente_genero'     => $request->paciente_genero,
            'profesional_id'      => $profesional->id,
            'profesional_nombre'  => $profesional->nombre_completo,
            'especialidad_id'     => $profesional->especialidad_id,
            'plantilla_id'        => $request->plantilla_id,
            'cups_codigo'         => $request->cups_codigo,
            'cups_descripcion'    => $request->cups_descripcion,
            'fecha_procedimiento' => $request->fecha_procedimiento,
            'requiere_acudiente'  => $request->boolean('requiere_acudiente'),
            'estado'              => 'pendiente',
            'token_firma'         => Str::random(64),
            'token_expira_at'     => now()->addHours(24),
            'ip_generacion'       => $request->ip(),
        ]);

        // Estampar firma del profesional automáticamente
        FirmaCI::create([
            'consentimiento_id' => $consentimiento->id,
            'tipo_firmante'     => 'profesional',
            'firma_base64'      => $profesional->firma_base64,
            'firmante_nombre'   => $profesional->nombre_completo,
            'firmante_cedula'   => $profesional->numero_documento,
            'ip_firma'          => $request->ip(),
            'user_agent'        => $request->userAgent(),
            'firmado_at'        => now(),
        ]);

        return redirect()->route('consentimientos.show', $consentimiento->id)
            ->with('success', 'Consentimiento creado exitosamente. Comparte el link de firma con el paciente.');
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

        return view('consentimientos.firmar', compact('consentimiento', 'firmasFaltantes'));
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

        $request->validate([
            'tipo_firmante'     => 'required|in:paciente,acudiente',
            'firma_base64'      => 'required|string',
            'firmante_nombre'   => 'required|string|max:200',
            'firmante_cedula'   => 'nullable|string|max:20',
            'firmante_relacion' => 'required_if:tipo_firmante,acudiente|nullable|string|max:100'
        ]);

        // Crear la firma
        FirmaCI::create([
            'consentimiento_id' => $consentimiento->id,
            'tipo_firmante'     => $request->tipo_firmante,
            'firma_base64'      => $request->firma_base64,
            'firmante_nombre'   => $request->firmante_nombre,
            'firmante_cedula'   => $request->firmante_cedula,
            'firmante_relacion' => $request->firmante_relacion,
            'ip_firma'          => $request->ip(),
            'user_agent'        => $request->userAgent(),
            'firmado_at'        => now(),
        ]);

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
        $consentimiento = ConsentimientoInformado::findOrFail($id);

        return $this->pdfService->descargar($consentimiento);
    }
}
