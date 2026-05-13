<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ValidacionBoxalud;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ValidacionBoxaludController extends Controller
{
    // =========================================================================
    //  POST /api/validaciones-boxalud
    //  Recibe datos + screenshot desde el plugin Chrome
    // =========================================================================
    public function store(Request $request)
    {
        // ── Validación de entrada ─────────────────────────────────────────────
        $validator = Validator::make($request->all(), [
            'datos'      => 'required|string',
            'screenshot' => 'nullable|image|mimes:png,jpeg|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        // ── Decodificar JSON enviado por el plugin ────────────────────────────
        $datos = json_decode($request->input('datos'), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'success' => false,
                'message' => 'JSON de datos inválido.',
            ], 422);
        }

        $doc = trim($datos['numero_documento'] ?? '');

        if (empty($doc)) {
            return response()->json([
                'success' => false,
                'message' => 'Número de documento requerido.',
            ], 422);
        }

        // ── Verificación de duplicado (server-side) ───────────────────────────
        $yaExiste = ValidacionBoxalud::where('numero_documento', $doc)
            ->whereRaw('DATE(fecha_consulta) = ?', [Carbon::today()->toDateString()])
            ->exists();

        if ($yaExiste) {
            return response()->json([
                'success' => false,
                'existe'  => true,
                'message' => 'Consulta ya registrada hoy para este documento.',
            ], 409);
        }

        // ── Guardar screenshot ────────────────────────────────────────────────
        $screenshotPath = null;

        if ($request->hasFile('screenshot') && $request->file('screenshot')->isValid()) {
            $fecha  = Carbon::now()->format('Y/m/d');
            $nombre = $doc . '_' . time() . '.png';

            // Se guarda en storage/app/validaciones_boxalud/YYYY/MM/DD/
            $screenshotPath = $request->file('screenshot')
                ->storeAs("validaciones_boxalud/{$fecha}", $nombre);
        }

        // ── Parser de fechas colombianas dd/mm/yyyy ───────────────────────────
        $parseFecha = function ($valor) {
            if (empty($valor)) return null;
            try {
                return Carbon::createFromFormat('d/m/Y', trim($valor))->toDateString();
            } catch (\Exception $e) {
                return null;
            }
        };

        // ── Crear registro ────────────────────────────────────────────────────
        $registro = ValidacionBoxalud::create([
            // Identificación
            'tipo_documento'          => $datos['tipo_documento']          ?? null,
            'numero_documento'        => $doc,
            'primer_nombre'           => $datos['primer_nombre']           ?? null,
            'segundo_nombre'          => $datos['segundo_nombre']          ?? null,
            'primer_apellido'         => $datos['primer_apellido']         ?? null,
            'segundo_apellido'        => $datos['segundo_apellido']        ?? null,
            'fecha_nacimiento'        => $parseFecha($datos['fecha_nacimiento']     ?? null),
            'tipo_afiliado'           => $datos['tipo_afiliado']           ?? null,
            // Plan y estados
            'plan'                    => $datos['plan']                    ?? null,
            'vigencia'                => $datos['vigencia']                ?? null,
            'estado_pagos'            => $datos['estado_pagos']            ?? null,
            'estado_documentos'       => $datos['estado_documentos']       ?? null,
            // Datos biológicos
            'sexo_biologico'          => $datos['sexo_biologico']          ?? null,
            'sexo_identificacion'     => $datos['sexo_identificacion']     ?? null,
            'rango_salarial'          => $datos['rango_salarial']          ?? null,
            // Origen
            'nacionalidad'            => $datos['nacionalidad']            ?? null,
            'pais_nacimiento'         => $datos['pais_nacimiento']         ?? null,
            'departamento_nacimiento' => $datos['departamento_nacimiento'] ?? null,
            'municipio_nacimiento'    => $datos['municipio_nacimiento']    ?? null,
            // Atención
            'departamento_atencion'   => $datos['departamento_atencion']   ?? null,
            'municipio_atencion'      => $datos['municipio_atencion']      ?? null,
            'localidad'               => $datos['localidad']               ?? null,
            'barrio'                  => $datos['barrio']                  ?? null,
            'direccion'               => $datos['direccion']               ?? null,
            'telefono'                => $datos['telefono']                ?? null,
            'celular'                 => $datos['celular']                 ?? null,
            'correo_electronico'      => $datos['correo_electronico']      ?? null,
            'fecha_inicio_atencion'   => $parseFecha($datos['fecha_inicio_atencion'] ?? null),
            'fecha_fin_atencion'      => $parseFecha($datos['fecha_fin_atencion']    ?? null),
            // IPS
            'ips_nombre_oferta'       => $datos['ips_nombre_oferta']       ?? null,
            'ips_codigo'              => $datos['ips_codigo']              ?? null,
            'ips_sede'                => $datos['ips_sede']                ?? null,
            'ips_servicio'            => $datos['ips_servicio']            ?? null,
            // Trazabilidad
            'screenshot_path'         => $screenshotPath,
            'datos_raw'               => json_encode($datos),
            'url_consultada'          => $datos['url_consultada']          ?? null,
            'ip_origen'               => $request->ip(),
            'user_agent'              => $request->header('User-Agent'),
            'user_id'                 => Auth::id(),
            'fecha_consulta'          => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'id'      => $registro->id,
            'message' => 'Consulta registrada correctamente.',
        ], 201);
    }

    // =========================================================================
    //  GET /api/validaciones-boxalud/hoy
    //  Lista todas las consultas del día actual
    // =========================================================================
    public function hoy()
    {
        $consultas = ValidacionBoxalud::hoy()
            ->orderBy('fecha_consulta', 'desc')
            ->get([
                'id',
                'tipo_documento',
                'numero_documento',
                'primer_nombre',
                'segundo_nombre',
                'primer_apellido',
                'segundo_apellido',
                'plan',
                'vigencia',
                'estado_pagos',
                'ips_nombre_oferta',
                'screenshot_path',
                'user_id',
                'fecha_consulta',
            ]);

        return response()->json([
            'success' => true,
            'fecha'   => Carbon::today()->toDateString(),
            'total'   => $consultas->count(),
            'data'    => $consultas,
        ]);
    }

    // =========================================================================
    //  GET /api/validaciones-boxalud/existe-hoy/{documento}
    //  Verifica si ya existe un registro hoy para ese documento
    // =========================================================================
    public function existeHoy($documento)
    {
        $existe = ValidacionBoxalud::where('numero_documento', $documento)
            ->whereRaw('DATE(fecha_consulta) = ?', [Carbon::today()->toDateString()])
            ->exists();

        return response()->json(['existe' => $existe]);
    }

    // =========================================================================
    //  GET /api/validaciones-boxalud/historial/{documento}
    //  Historial completo de consultas de un documento
    // =========================================================================
    public function historial($documento)
    {
        $registros = ValidacionBoxalud::where('numero_documento', $documento)
            ->orderBy('fecha_consulta', 'desc')
            ->get([
                'id',
                'tipo_documento',
                'numero_documento',
                'primer_nombre',
                'primer_apellido',
                'plan',
                'vigencia',
                'estado_pagos',
                'ips_nombre_oferta',
                'screenshot_path',
                'ip_origen',
                'user_id',
                'fecha_consulta',
            ]);

        return response()->json([
            'success' => true,
            'total'   => $registros->count(),
            'data'    => $registros,
        ]);
    }

    // =========================================================================
    //  GET /api/validaciones-boxalud/{id}
    //  Detalle completo de una consulta
    // =========================================================================
    public function show($id)
    {
        $registro = ValidacionBoxalud::findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $registro,
        ]);
    }

    // =========================================================================
    //  GET /api/validaciones-boxalud/{id}/screenshot
    //  Descarga el screenshot de una consulta (protegido)
    // =========================================================================
    public function descargarScreenshot($id)
    {
        $validacion = ValidacionBoxalud::findOrFail($id);

        if (empty($validacion->screenshot_path)) {
            return response()->json(['message' => 'Esta consulta no tiene screenshot.'], 404);
        }

        $rutaAbsoluta = storage_path('app/' . $validacion->screenshot_path);

        if (!file_exists($rutaAbsoluta)) {
            return response()->json(['message' => 'Archivo no encontrado en el servidor.'], 404);
        }

        $nombreDescarga = implode('_', array_filter([
            'validacion',
            $validacion->numero_documento,
            $validacion->primer_apellido,
            Carbon::parse($validacion->fecha_consulta)->format('Ymd_His'),
        ])) . '.png';

        return response()->download($rutaAbsoluta, $nombreDescarga, [
            'Content-Type'        => 'image/png',
            'Content-Disposition' => "inline; filename=\"{$nombreDescarga}\"",
        ]);
    }

    // =========================================================================
    //  GET /api/validaciones-boxalud/reporte
    //  Reporte resumido por rango de fechas
    // =========================================================================
    public function reporte(Request $request)
    {
        $desde = $request->get('desde', Carbon::today()->toDateString());
        $hasta = $request->get('hasta', Carbon::today()->toDateString());

        $query = ValidacionBoxalud::whereRaw('DATE(fecha_consulta) BETWEEN ? AND ?', [$desde, $hasta]);

        $total    = $query->count();
        $vigentes = (clone $query)->where('vigencia', 'Vigente')->count();
        $porPlan  = (clone $query)->select('plan')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('plan')
            ->get();

        return response()->json([
            'success'  => true,
            'desde'    => $desde,
            'hasta'    => $hasta,
            'total'    => $total,
            'vigentes' => $vigentes,
            'no_vigentes' => $total - $vigentes,
            'por_plan' => $porPlan,
        ]);
    }
}
