<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ValidacionDerecho;
use App\AgendaCI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidacionDerechoController extends Controller
{
    // ── Índice ───────────────────────────────────────────────────────────────

    public function index()
    {
        return view('validaciones_derechos.index');
    }

    // ── AJAX: agenda del día / búsqueda por paciente ─────────────────────────

    public function ajaxAgenda(Request $request)
    {
        $q     = trim($request->input('q', ''));
        $fecha = $request->input('fecha', now()->format('Y-m-d'));

        $query = AgendaCI::query();

        if (strlen($q) >= 2) {
            $query->where(function ($sub) use ($q) {
                $sub->where('paciente_cedula', 'like', '%' . $q . '%')
                    ->orWhere('paciente_nombre', 'like', '%' . $q . '%');
            });
        }

        if (!empty($fecha)) {
            $query->whereDate('fecha', $fecha);
        }

        $citas = $query->orderBy('fecha')->limit(300)->get();

        // Validaciones ya guardadas para estos registros de agenda
        $ids          = $citas->pluck('id');
        $validaciones = ValidacionDerecho::whereIn('agenda_ci_id', $ids)
            ->get()
            ->keyBy('agenda_ci_id');

        return response()->json($citas->map(function ($c) use ($validaciones) {
            $val  = isset($validaciones[$c->id]) ? $validaciones[$c->id] : null;
            $hora = $c->fecha ? \Carbon\Carbon::parse($c->fecha)->format('H:i') : '-';

            return [
                'agenda_id'         => $c->id,
                'hora'              => $hora,
                'paciente_nombre'   => $c->paciente_nombre ?? '-',
                'paciente_cedula'   => ($c->paciente_tipo_doc ?? 'CC') . ' ' . ($c->paciente_cedula ?? '-'),
                'empresafac'        => $c->empresafac ?? '-',
                'cups_descripcion'  => $c->cups_descripcion ?? '-',
                'numero_factura'    => $c->numero_factura ?? '-',
                'validado'          => $val !== null,
                'estado_afiliacion' => $val ? ($val->estado_afiliacion ?? '') : '',
                'imagen_url'        => $val ? route('validaciones.imagen', $val->id) : null,
                'validacion_id'     => $val ? $val->id : null,
                'eliminar_url'      => $val ? route('validaciones.destroy', $val->id) : null,
                'crear_url'         => route('validaciones.create') . '?agenda_id=' . $c->id,
            ];
        }));
    }

    // ── Formulario de creación ───────────────────────────────────────────────

    public function create(Request $request)
    {
        $agenda = null;
        if ($request->filled('agenda_id')) {
            $agenda = AgendaCI::find($request->agenda_id);
        }
        return view('validaciones_derechos.create', compact('agenda'));
    }

    // ── Guardar ─────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $request->validate([
            'imagen_base64' => 'required|string',
        ], [
            'imagen_base64.required' => 'Debe adjuntar un pantallazo.',
        ]);

        $imagenBase64 = $request->input('imagen_base64');

        if (!preg_match('#^data:image/(png|jpeg|jpg|gif|webp);base64,#i', $imagenBase64)) {
            return back()->withErrors(['imagen_base64' => 'El archivo adjunto no es una imagen válida.'])->withInput();
        }

        $imagenData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imagenBase64));

        if ($imagenData === false) {
            return back()->withErrors(['imagen_base64' => 'No se pudo procesar la imagen.'])->withInput();
        }

        $carpeta = storage_path('app/validaciones_derechos/' . now()->format('Y/m'));
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0755, true);
        }

        $cedula        = preg_replace('/[^a-zA-Z0-9]/', '', $request->input('paciente_cedula', 'sin_cedula'));
        $nombreArchivo = ($request->input('agenda_ci_id', 'sa')) . '_' . $cedula . '_' . time() . '.png';
        file_put_contents($carpeta . '/' . $nombreArchivo, $imagenData);

        $imagenPath = 'validaciones_derechos/' . now()->format('Y/m') . '/' . $nombreArchivo;

        $usuario       = Auth::user();
        $usuarioNombre = trim(
            ($usuario->pnombre ?? '') . ' ' .
            ($usuario->papellido ?? '') . ' ' .
            ($usuario->sapellido ?? '')
        ) ?: ($usuario->usuario ?? 'Sistema');

        ValidacionDerecho::create([
            'agenda_ci_id'      => $request->input('agenda_ci_id') ?: null,
            'paciente_nombre'   => $request->input('paciente_nombre'),
            'paciente_tipo_doc' => $request->input('paciente_tipo_doc'),
            'paciente_cedula'   => $request->input('paciente_cedula'),
            'estado_afiliacion' => $request->input('estado_afiliacion'),
            'numero_factura'    => $request->input('numero_factura'),
            'atencion_factura'  => $request->input('atencion_factura'),
            'contrato'          => $request->input('contrato'),
            'empresafac'        => $request->input('empresafac'),
            'fecha_atencion'    => $request->input('fecha_atencion') ?: null,
            'cups_codigo'       => $request->input('cups_codigo'),
            'cups_descripcion'  => $request->input('cups_descripcion'),
            'imagen_path'       => $imagenPath,
            'observaciones'     => $request->input('observaciones'),
            'created_by'        => $usuario->id,
            'created_by_nombre' => $usuarioNombre,
            'ip_registro'       => $request->ip(),
        ]);

        // Regresar a la agenda del mismo día si venimos desde ahí
        $fechaRetorno = $request->input('fecha_agenda', now()->format('Y-m-d'));
        return redirect()->route('validaciones.index', ['fecha' => $fechaRetorno])
            ->with('success', 'Validación guardada correctamente.');
    }

    // ── Ver imagen ──────────────────────────────────────────────────────────

    public function imagen($id)
    {
        $registro     = ValidacionDerecho::findOrFail($id);
        $rutaAbsoluta = storage_path('app/' . $registro->imagen_path);

        if (!file_exists($rutaAbsoluta)) {
            abort(404, 'Imagen no encontrada.');
        }

        return response()->file($rutaAbsoluta, [
            'Content-Type'        => 'image/png',
            'Content-Disposition' => 'inline',
        ]);
    }

    // ── Eliminar ────────────────────────────────────────────────────────────

    public function destroy($id)
    {
        $registro     = ValidacionDerecho::findOrFail($id);
        $rutaAbsoluta = storage_path('app/' . $registro->imagen_path);

        if (file_exists($rutaAbsoluta)) {
            unlink($rutaAbsoluta);
        }

        $registro->delete();

        if (request()->ajax()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('validaciones.index')
            ->with('success', 'Registro eliminado.');
    }
}
