<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ValidacionBoxalud;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ValidacionBoxaludWebController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax() && $request->has('draw')) {
            return $this->ajaxListar($request);
        }

        $stats = [
            'total'    => ValidacionBoxalud::count(),
            'hoy'      => ValidacionBoxalud::hoy()->count(),
            'vigentes' => ValidacionBoxalud::where('vigencia', 'Vigente')->count(),
            'con_foto' => ValidacionBoxalud::whereNotNull('screenshot_path')->count(),
        ];

        $planes = ValidacionBoxalud::select('plan')
            ->whereNotNull('plan')
            ->distinct()
            ->orderBy('plan')
            ->pluck('plan');

        return view('admin.validaciones-boxalud.index', compact('stats', 'planes'));
    }

    private function ajaxListar(Request $request)
    {
        $query = ValidacionBoxalud::query();

        if ($request->filled('documento')) {
            $query->where('numero_documento', 'like', '%' . $request->documento . '%');
        }
        if ($request->filled('nombre')) {
            $n = $request->nombre;
            $query->where(function ($q) use ($n) {
                $q->where('primer_nombre',    'like', "%$n%")
                  ->orWhere('segundo_nombre',  'like', "%$n%")
                  ->orWhere('primer_apellido', 'like', "%$n%")
                  ->orWhere('segundo_apellido','like', "%$n%");
            });
        }
        if ($request->filled('vigencia')) {
            $query->where('vigencia', $request->vigencia);
        }
        if ($request->filled('plan')) {
            $query->where('plan', 'like', '%' . $request->plan . '%');
        }
        if ($request->filled('ips')) {
            $query->where('ips_nombre_oferta', 'like', '%' . $request->ips . '%');
        }
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_consulta', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_consulta', '<=', $request->fecha_hasta);
        }

        $totalRegistros = ValidacionBoxalud::count();
        $totalFiltrado  = (clone $query)->count();

        $columnas  = ['id', 'numero_documento', 'primer_nombre', 'primer_apellido', 'vigencia', 'plan', 'ips_nombre_oferta', 'fecha_consulta'];
        $colIdx    = (int) $request->input('order.0.column', 7);
        $orderCol  = $columnas[$colIdx] ?? 'fecha_consulta';
        $orderDir  = $request->input('order.0.dir', 'desc');

        $registros = $query
            ->with('usuario')
            ->orderBy($orderCol, $orderDir)
            ->skip((int) $request->input('start', 0))
            ->take((int) $request->input('length', 25))
            ->get();

        $data = $registros->map(function ($r) {
            return [
                'id'                => $r->id,
                'numero_documento'  => $r->numero_documento,
                'tipo_documento'    => $r->tipo_documento ?? '',
                'nombre_completo'   => $r->nombre_completo,
                'fecha_nacimiento'  => $r->fecha_nacimiento  ? Carbon::parse($r->fecha_nacimiento)->format('d/m/Y')    : '-',
                'tipo_afiliado'     => $r->tipo_afiliado     ?? '-',
                'plan'              => $r->plan              ?? '-',
                'vigencia'          => $r->vigencia          ?? '-',
                'estado_pagos'      => $r->estado_pagos      ?? '-',
                'ips_nombre_oferta' => $r->ips_nombre_oferta ?? '-',
                'municipio_atencion'=> $r->municipio_atencion ?? '-',
                'fecha_consulta'    => $r->fecha_consulta    ? Carbon::parse($r->fecha_consulta)->format('d/m/Y H:i') : '-',
                'tiene_foto'        => !empty($r->screenshot_path),
                'screenshot_url'    => route('boxalud.screenshot', $r->id),
                'consultado_por'    => $r->usuario ? trim($r->usuario->pnombre . ' ' . $r->usuario->papellido) : '-',
            ];
        });

        return response()->json([
            'draw'            => (int) $request->input('draw', 1),
            'recordsTotal'    => $totalRegistros,
            'recordsFiltered' => $totalFiltrado,
            'data'            => $data,
        ]);
    }

    public function historialPaciente($documento)
    {
        $registros = ValidacionBoxalud::with('usuario')
            ->where('numero_documento', $documento)
            ->orderBy('fecha_consulta', 'asc')
            ->get();

        $data = $registros->map(function ($r) {
            return [
                'id'                => $r->id,
                'fecha_consulta'    => $r->fecha_consulta    ? Carbon::parse($r->fecha_consulta)->format('d/m/Y H:i')  : '-',
                'vigencia'          => $r->vigencia          ?? '-',
                'plan'              => $r->plan              ?? '-',
                'estado_pagos'      => $r->estado_pagos      ?? '-',
                'estado_documentos' => $r->estado_documentos ?? '-',
                'tipo_afiliado'     => $r->tipo_afiliado     ?? '-',
                'ips_nombre_oferta' => $r->ips_nombre_oferta ?? '-',
                'ips_sede'          => $r->ips_sede          ?? '-',
                'municipio_atencion'=> $r->municipio_atencion ?? '-',
                'tiene_foto'        => !empty($r->screenshot_path),
                'screenshot_url'    => route('boxalud.screenshot', $r->id),
                'consultado_por'    => $r->usuario ? trim($r->usuario->pnombre . ' ' . $r->usuario->papellido) : '-',
            ];
        });

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function detalle($id)
    {
        $r = ValidacionBoxalud::with('usuario')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id'                      => $r->id,
                'tipo_documento'          => $r->tipo_documento          ?? '-',
                'numero_documento'        => $r->numero_documento,
                'nombre_completo'         => $r->nombre_completo,
                'fecha_nacimiento'        => $r->fecha_nacimiento        ? Carbon::parse($r->fecha_nacimiento)->format('d/m/Y')       : '-',
                'sexo_biologico'          => $r->sexo_biologico          ?? '-',
                'sexo_identificacion'     => $r->sexo_identificacion     ?? '-',
                'tipo_afiliado'           => $r->tipo_afiliado           ?? '-',
                'plan'                    => $r->plan                    ?? '-',
                'vigencia'                => $r->vigencia                ?? '-',
                'estado_pagos'            => $r->estado_pagos            ?? '-',
                'estado_documentos'       => $r->estado_documentos       ?? '-',
                'rango_salarial'          => $r->rango_salarial          ?? '-',
                'nacionalidad'            => $r->nacionalidad            ?? '-',
                'pais_nacimiento'         => $r->pais_nacimiento         ?? '-',
                'departamento_nacimiento' => $r->departamento_nacimiento ?? '-',
                'municipio_nacimiento'    => $r->municipio_nacimiento    ?? '-',
                'departamento_atencion'   => $r->departamento_atencion   ?? '-',
                'municipio_atencion'      => $r->municipio_atencion      ?? '-',
                'localidad'               => $r->localidad               ?? '-',
                'barrio'                  => $r->barrio                  ?? '-',
                'direccion'               => $r->direccion               ?? '-',
                'telefono'                => $r->telefono                ?? '-',
                'celular'                 => $r->celular                 ?? '-',
                'correo_electronico'      => $r->correo_electronico      ?? '-',
                'fecha_inicio_atencion'   => $r->fecha_inicio_atencion   ? Carbon::parse($r->fecha_inicio_atencion)->format('d/m/Y')  : '-',
                'fecha_fin_atencion'      => $r->fecha_fin_atencion      ? Carbon::parse($r->fecha_fin_atencion)->format('d/m/Y')     : '-',
                'ips_nombre_oferta'       => $r->ips_nombre_oferta       ?? '-',
                'ips_codigo'              => $r->ips_codigo              ?? '-',
                'ips_sede'                => $r->ips_sede                ?? '-',
                'ips_servicio'            => $r->ips_servicio            ?? '-',
                'fecha_consulta'          => $r->fecha_consulta          ? Carbon::parse($r->fecha_consulta)->format('d/m/Y H:i')     : '-',
                'tiene_foto'              => !empty($r->screenshot_path),
                'screenshot_url'          => route('boxalud.screenshot', $r->id),
                'consultado_por'          => $r->usuario ? trim($r->usuario->pnombre . ' ' . $r->usuario->papellido) : '-',
                'consultado_por_usuario'  => $r->usuario->usuario ?? '-',
            ],
        ]);
    }

    public function screenshot($id)
    {
        $r    = ValidacionBoxalud::findOrFail($id);
        $ruta = storage_path('app/' . $r->screenshot_path);

        if (empty($r->screenshot_path) || !file_exists($ruta)) {
            abort(404, 'Imagen no encontrada.');
        }

        return response()->file($ruta, [
            'Content-Type'        => 'image/png',
            'Content-Disposition' => 'inline',
        ]);
    }
}
