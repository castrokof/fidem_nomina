<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\PagoFactura;
use App\PagoNotificacion;
use App\PagoRegistro;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PagoFacturaController extends Controller
{
    /** Vista principal: calendario de pagos */
    public function index(Request $request)
    {
        $anio   = (int) ($request->get('anio', date('Y')));
        $hoy    = Carbon::today();
        $meses  = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];

        $facturas = PagoFactura::where('activo', true)
            ->orderBy('categoria')
            ->orderBy('nombre')
            ->get();

        // Asegurar que existan registros para los 12 meses del año
        foreach ($facturas as $factura) {
            for ($m = 1; $m <= 12; $m++) {
                PagoRegistro::firstOrCreate(
                    ['factura_id' => $factura->id, 'mes' => $m, 'anio' => $anio],
                    ['estado' => 'pendiente']
                );
            }
        }

        // Cargar registros indexados [factura_id][mes]
        $registrosRaw = PagoRegistro::with('factura')
            ->whereHas('factura', fn($q) => $q->where('activo', true))
            ->where('anio', $anio)
            ->get();

        $registros = [];
        foreach ($registrosRaw as $r) {
            $registros[$r->factura_id][$r->mes] = $r;
        }

        // Conteo de notificaciones no leídas
        $totalNoLeidas = PagoNotificacion::where('leido', false)->count();

        return view('pagos.index', compact('facturas', 'registros', 'anio', 'meses', 'hoy', 'totalNoLeidas'));
    }

    /** Crear factura */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'               => 'required|string|max:100',
            'categoria'            => 'nullable|string|max:60',
            'descripcion'          => 'nullable|string',
            'dia_vencimiento'      => 'required|integer|min:1|max:31',
            'monto_estimado'       => 'nullable|numeric|min:0',
            'correo_notificacion'  => 'nullable|email|max:150',
            'dias_aviso'           => 'nullable|integer|min:1|max:30',
        ]);

        $data['dias_aviso']    = $data['dias_aviso'] ?? 3;
        $data['monto_estimado']= $data['monto_estimado'] ?? 0;
        $data['created_by']    = auth()->id();

        $factura = PagoFactura::create($data);

        return response()->json(['success' => true, 'id' => $factura->id, 'message' => 'Factura creada correctamente.']);
    }

    /** Actualizar factura */
    public function update(Request $request, $id)
    {
        $factura = PagoFactura::findOrFail($id);

        $data = $request->validate([
            'nombre'               => 'required|string|max:100',
            'categoria'            => 'nullable|string|max:60',
            'descripcion'          => 'nullable|string',
            'dia_vencimiento'      => 'required|integer|min:1|max:31',
            'monto_estimado'       => 'nullable|numeric|min:0',
            'correo_notificacion'  => 'nullable|email|max:150',
            'dias_aviso'           => 'nullable|integer|min:1|max:30',
        ]);

        $factura->update($data);

        return response()->json(['success' => true, 'message' => 'Factura actualizada correctamente.']);
    }

    /** Desactivar factura (soft-delete lógico) */
    public function destroy($id)
    {
        $factura = PagoFactura::findOrFail($id);
        $factura->update(['activo' => false]);

        return response()->json(['success' => true, 'message' => 'Factura eliminada.']);
    }

    /** Marcar registro mensual como pagado */
    public function marcarPagado(Request $request, $id)
    {
        $registro = PagoRegistro::findOrFail($id);

        $data = $request->validate([
            'fecha_pago'   => 'nullable|date',
            'monto_pagado' => 'nullable|numeric|min:0',
            'notas'        => 'nullable|string|max:500',
        ]);

        $registro->update([
            'estado'      => 'pagado',
            'fecha_pago'  => $data['fecha_pago']   ?? Carbon::today(),
            'monto_pagado'=> $data['monto_pagado'] ?? $registro->factura->monto_estimado,
            'notas'       => $data['notas']        ?? null,
        ]);

        return response()->json(['success' => true, 'message' => 'Pago registrado correctamente.']);
    }

    /** Revertir pago a pendiente */
    public function revertirPago($id)
    {
        $registro = PagoRegistro::findOrFail($id);
        $registro->update([
            'estado'       => 'pendiente',
            'fecha_pago'   => null,
            'monto_pagado' => null,
        ]);

        return response()->json(['success' => true, 'message' => 'Pago revertido a pendiente.']);
    }

    /** Notificaciones no leídas (AJAX) */
    public function notificaciones()
    {
        $notifs = PagoNotificacion::with('factura')
            ->where('leido', false)
            ->orderByDesc('created_at')
            ->take(20)
            ->get()
            ->map(fn($n) => [
                'id'      => $n->id,
                'titulo'  => $n->titulo,
                'mensaje' => $n->mensaje,
                'tipo'    => $n->tipo,
                'fecha'   => $n->created_at->diffForHumans(),
            ]);

        return response()->json([
            'total' => $notifs->count(),
            'items' => $notifs,
        ]);
    }

    /** Marcar notificación como leída */
    public function marcarNotificacionLeida($id)
    {
        if ($id === 'all') {
            PagoNotificacion::where('leido', false)->update(['leido' => true]);
        } else {
            PagoNotificacion::findOrFail($id)->update(['leido' => true]);
        }

        return response()->json(['success' => true]);
    }
}
