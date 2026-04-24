<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\PagoCategoria;
use App\PagoFactura;
use App\PagoNotificacion;
use App\PagoRegistro;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PagoFacturaController extends Controller
{
    private function validationRules()
    {
        return [
            'nombre'              => 'required|string|max:100',
            'categoria'           => 'nullable|string|max:80',
            'descripcion'         => 'nullable|string',
            'referencia'          => 'nullable|string|max:100',
            'sede'                => 'nullable|string|max:100',
            'dia_vencimiento'     => 'required|integer|min:1|max:31',
            'monto_estimado'      => 'nullable|numeric|min:0',
            'correo_notificacion' => 'nullable|string',
            'dias_aviso'          => 'nullable|integer|min:1|max:30',
        ];
    }

    /** Vista principal: calendario de pagos */
    public function index(Request $request)
    {
        $anio  = (int) ($request->get('anio', date('Y')));
        $hoy   = Carbon::today();
        $meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];

        $facturas = PagoFactura::where('activo', true)
            ->orderBy('categoria')->orderBy('nombre')->get();

        foreach ($facturas as $factura) {
            for ($m = 1; $m <= 12; $m++) {
                PagoRegistro::firstOrCreate(
                    ['factura_id' => $factura->id, 'mes' => $m, 'anio' => $anio],
                    ['estado' => 'pendiente']
                );
            }
        }

        $registrosRaw = PagoRegistro::with('factura')
            ->whereHas('factura', function ($q) { $q->where('activo', true); })
            ->where('anio', $anio)->get();

        $registros = [];
        foreach ($registrosRaw as $r) {
            $registros[$r->factura_id][$r->mes] = $r;
        }

        $categorias    = PagoCategoria::orderBy('nombre')->pluck('nombre');
        $totalNoLeidas = PagoNotificacion::where('leido', false)->count();

        return view('pagos.index', compact(
            'facturas', 'registros', 'anio', 'meses', 'hoy', 'totalNoLeidas', 'categorias'
        ));
    }

    /** Crear factura */
    public function store(Request $request)
    {
        $data = $request->validate($this->validationRules());
        $data['dias_aviso']     = $data['dias_aviso'] ?? 3;
        $data['monto_estimado'] = $data['monto_estimado'] ?? 0;
        $data['created_by']     = auth()->id();

        $factura = PagoFactura::create($data);

        return response()->json(['success' => true, 'id' => $factura->id, 'message' => 'Factura creada correctamente.']);
    }

    /** Actualizar factura */
    public function update(Request $request, $id)
    {
        $factura = PagoFactura::findOrFail($id);
        $factura->update($request->validate($this->validationRules()));

        return response()->json(['success' => true, 'message' => 'Factura actualizada correctamente.']);
    }

    /** Desactivar factura */
    public function destroy($id)
    {
        PagoFactura::findOrFail($id)->update(['activo' => false]);
        return response()->json(['success' => true, 'message' => 'Factura eliminada.']);
    }

    /** Marcar pago mensual */
    public function marcarPagado(Request $request, $id)
    {
        $registro = PagoRegistro::findOrFail($id);
        $data = $request->validate([
            'fecha_pago'   => 'nullable|date',
            'monto_pagado' => 'nullable|numeric|min:0',
            'notas'        => 'nullable|string|max:500',
        ]);

        $registro->update([
            'estado'       => 'pagado',
            'fecha_pago'   => $data['fecha_pago']   ?? Carbon::today(),
            'monto_pagado' => $data['monto_pagado'] ?? $registro->factura->monto_estimado,
            'notas'        => $data['notas']        ?? null,
        ]);

        return response()->json(['success' => true, 'message' => 'Pago registrado correctamente.']);
    }

    /** Revertir pago */
    public function revertirPago($id)
    {
        PagoRegistro::findOrFail($id)->update([
            'estado' => 'pendiente', 'fecha_pago' => null, 'monto_pagado' => null,
        ]);
        return response()->json(['success' => true, 'message' => 'Pago revertido a pendiente.']);
    }

    /** Crear categoría (AJAX) */
    public function storeCategoria(Request $request)
    {
        $request->validate(['nombre' => 'required|string|max:80|unique:pagos_categorias,nombre']);
        $cat = PagoCategoria::create(['nombre' => trim($request->nombre)]);
        return response()->json(['success' => true, 'nombre' => $cat->nombre]);
    }

    /** Notificaciones no leídas */
    public function notificaciones()
    {
        $notifs = PagoNotificacion::with('factura')
            ->where('leido', false)->orderByDesc('created_at')->take(20)->get()
            ->map(function ($n) {
                return [
                    'id'      => $n->id,
                    'titulo'  => $n->titulo,
                    'mensaje' => $n->mensaje,
                    'tipo'    => $n->tipo,
                    'fecha'   => $n->created_at->diffForHumans(),
                ];
            });

        return response()->json(['total' => $notifs->count(), 'items' => $notifs]);
    }

    /** Marcar notificación leída */
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
