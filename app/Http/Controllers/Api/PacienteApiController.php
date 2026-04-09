<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin\Paciente;
use Illuminate\Http\Request;

class PacienteApiController extends Controller
{
    /**
     * Buscar paciente por documento con toda su información médica
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function buscarPorDocumento(Request $request)
    {
        $request->validate([
            'documento' => 'required|string|max:20',
            'tipo_documento' => 'nullable|string|in:CC,TI,CE,PA,RC',
            'incluir_historias' => 'nullable|boolean',
            'incluir_citas' => 'nullable|boolean',
            'limite_historias' => 'nullable|integer|min:1|max:50',
        ]);

        $documento = $request->input('documento');
        $tipoDocumento = $request->input('tipo_documento', 'CC');
        $incluirHistorias = $request->input('incluir_historias', true);
        $incluirCitas = $request->input('incluir_citas', false);
        $limiteHistorias = $request->input('limite_historias', 10);

        // Buscar paciente
        $query = Paciente::where('documento', $documento);

        if ($tipoDocumento) {
            $query->where('tipo_documento', $tipoDocumento);
        }

        // Eager loading condicional
        if ($incluirHistorias) {
            $query->with(['historiap' => function($q) use ($limiteHistorias) {
                $q->orderBy('created_at', 'desc')
                  ->limit($limiteHistorias);
            }]);
        }

        if ($incluirCitas) {
            $query->with(['citap' => function($q) {
                $q->orderBy('created_at', 'desc')
                  ->limit(10);
            }]);
        }

        $paciente = $query->first();

        if (!$paciente) {
            return response()->json([
                'success' => false,
                'message' => 'Paciente no encontrado',
                'data' => null
            ], 404);
        }

        // Formatear respuesta
        $response = [
            'id' => $paciente->id_paciente,
            'nombre_completo' => trim(
                ($paciente->pnombre ?? '') . ' ' .
                ($paciente->snombre ?? '') . ' ' .
                ($paciente->papellido ?? '') . ' ' .
                ($paciente->sapellido ?? '')
            ),
            'primer_nombre' => $paciente->pnombre,
            'segundo_nombre' => $paciente->snombre,
            'primer_apellido' => $paciente->papellido,
            'segundo_apellido' => $paciente->sapellido,
            'tipo_documento' => $paciente->tipo_documento,
            'documento' => $paciente->documento,
            'edad' => $paciente->edad,
            'sexo' => $paciente->sexo,
            'direccion' => $paciente->direccion,
            'telefono' => $paciente->telefono,
            'celular' => $paciente->celular,
            'correo' => $paciente->correo,
            'eps' => $paciente->eps,
            'plan' => $paciente->plan,
            'ciudad' => $paciente->ciudad,
            'departamento' => $paciente->dpto,
        ];

        // Agregar historias clínicas si se solicitaron
        if ($incluirHistorias && $paciente->historiap) {
            $response['historias_clinicas'] = [
                'total' => $paciente->historiap->count(),
                'datos' => $paciente->historiap->map(function($historia) {
                    return [
                        'id' => $historia->id,
                        'fecha' => $historia->created_at ? $historia->created_at->format('Y-m-d H:i:s') : null,
                        'motivo_consulta' => $historia->motivo_consulta ?? null,
                        'diagnostico_principal' => $historia->diagnostico_principal ?? null,
                        'diagnostico_secundario' => $historia->diagnostico_secundario ?? null,
                        'plan_tratamiento' => $historia->plan_tratamiento ?? null,
                        'observaciones' => $historia->observaciones ?? null,
                        'profesional' => $historia->profesional_nombre ?? null,
                    ];
                })
            ];
        }

        // Agregar citas si se solicitaron
        if ($incluirCitas && $paciente->citap) {
            $response['citas'] = [
                'total' => $paciente->citap->count(),
                'datos' => $paciente->citap->map(function($cita) {
                    return [
                        'id' => $cita->id,
                        'fecha' => $cita->fecha_cita,
                        'estado' => $cita->estado,
                        'profesional' => $cita->profesional_nombre ?? null,
                        'especialidad' => $cita->especialidad ?? null,
                    ];
                })
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Paciente encontrado',
            'data' => $response
        ]);
    }

    /**
     * Obtener contexto completo de paciente para Claude AI
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerContextoClaude(Request $request)
    {
        $request->validate([
            'documento' => 'required|string|max:20',
            'tipo_documento' => 'nullable|string',
            'limite_historias' => 'nullable|integer|min:1|max:20',
        ]);

        $documento = $request->input('documento');
        $tipoDocumento = $request->input('tipo_documento', 'CC');
        $limiteHistorias = $request->input('limite_historias', 5);

        $paciente = Paciente::where('documento', $documento)
            ->where('tipo_documento', $tipoDocumento)
            ->with(['historiap' => function($q) use ($limiteHistorias) {
                $q->orderBy('created_at', 'desc')
                  ->limit($limiteHistorias);
            }])
            ->first();

        if (!$paciente) {
            return response()->json([
                'success' => false,
                'message' => 'Paciente no encontrado',
                'contexto' => null
            ], 404);
        }

        // Generar contexto en formato texto para Claude
        $contexto = "INFORMACIÓN DEL PACIENTE\n";
        $contexto .= "========================\n\n";

        $contexto .= "Nombre: " . trim(
            ($paciente->pnombre ?? '') . ' ' .
            ($paciente->snombre ?? '') . ' ' .
            ($paciente->papellido ?? '') . ' ' .
            ($paciente->sapellido ?? '')
        ) . "\n";

        $contexto .= "Documento: {$paciente->tipo_documento} {$paciente->documento}\n";
        $contexto .= "Edad: " . ($paciente->edad ?? 'N/A') . " años\n";
        $contexto .= "Sexo: " . ($paciente->sexo ?? 'N/A') . "\n";
        $contexto .= "Teléfono: " . ($paciente->celular ?? $paciente->telefono ?? 'N/A') . "\n";
        $contexto .= "Dirección: " . ($paciente->direccion ?? 'N/A') . "\n";
        $contexto .= "EPS: " . ($paciente->eps ?? 'N/A') . "\n";
        $contexto .= "Plan: " . ($paciente->plan ?? 'N/A') . "\n\n";

        // Agregar historias clínicas
        if ($paciente->historiap && $paciente->historiap->count() > 0) {
            $contexto .= "HISTORIAS CLÍNICAS RECIENTES\n";
            $contexto .= "============================\n\n";

            foreach ($paciente->historiap as $index => $historia) {
                $contexto .= "Historia #" . ($index + 1) . ":\n";
                $contexto .= "  Fecha: " . ($historia->created_at ? $historia->created_at->format('d/m/Y H:i') : 'N/A') . "\n";
                $contexto .= "  Motivo de consulta: " . ($historia->motivo_consulta ?? 'N/A') . "\n";
                $contexto .= "  Diagnóstico principal: " . ($historia->diagnostico_principal ?? 'N/A') . "\n";

                if (!empty($historia->diagnostico_secundario)) {
                    $contexto .= "  Diagnóstico secundario: " . $historia->diagnostico_secundario . "\n";
                }

                if (!empty($historia->plan_tratamiento)) {
                    $contexto .= "  Plan de tratamiento: " . $historia->plan_tratamiento . "\n";
                }

                if (!empty($historia->observaciones)) {
                    $contexto .= "  Observaciones: " . $historia->observaciones . "\n";
                }

                $contexto .= "\n";
            }
        } else {
            $contexto .= "HISTORIAS CLÍNICAS\n";
            $contexto .= "==================\n";
            $contexto .= "No hay historias clínicas registradas.\n\n";
        }

        return response()->json([
            'success' => true,
            'message' => 'Contexto generado exitosamente',
            'contexto' => $contexto,
            'paciente_id' => $paciente->id_paciente,
            'nombre_completo' => trim(
                ($paciente->pnombre ?? '') . ' ' .
                ($paciente->snombre ?? '') . ' ' .
                ($paciente->papellido ?? '') . ' ' .
                ($paciente->sapellido ?? '')
            )
        ]);
    }
}
