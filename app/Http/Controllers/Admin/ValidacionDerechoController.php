<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ValidacionDerecho;
use App\AgendaCI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidacionDerechoController extends Controller
{
    // ── Listado ─────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->ajaxListar($request);
        }
        return view('validaciones_derechos.index');
    }

    private function ajaxListar(Request $request)
    {
        $query = ValidacionDerecho::query();

        if ($request->filled('cedula')) {
            $query->where('paciente_cedula', 'like', '%' . $request->cedula . '%');
        }
        if ($request->filled('factura')) {
            $query->where('numero_factura', 'like', '%' . $request->factura . '%');
        }
        if ($request->filled('empresa')) {
            $query->where('empresafac', 'like', '%' . $request->empresa . '%');
        }
        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        $registros = $query->orderByDesc('created_at')->limit(500)->get();

        $data = $registros->map(function ($r) {
            return [
                'id'               => $r->id,
                'paciente_nombre'  => $r->paciente_nombre ?? '-',
                'paciente_cedula'  => $r->paciente_cedula ?? '-',
                'numero_factura'   => $r->numero_factura ?? '-',
                'empresafac'       => $r->empresafac ?? '-',
                'fecha_atencion'   => $r->fecha_atencion ? $r->fecha_atencion->format('d/m/Y') : '-',
                'created_at_sort'  => $r->created_at ? $r->created_at->timestamp : 0,
                'created_by_nombre'=> $r->created_by_nombre ?? '-',
                'imagen_url'       => route('validaciones.imagen', $r->id),
                'eliminar_url'     => route('validaciones.destroy', $r->id),
            ];
        });

        return response()->json($data);
    }

    // ── Formulario de creación ───────────────────────────────────────────────

    public function create()
    {
        return view('validaciones_derechos.create');
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

        // Validar que sea una imagen base64 válida
        if (!preg_match('#^data:image/(png|jpeg|jpg|gif|webp);base64,#i', $imagenBase64)) {
            return back()->withErrors(['imagen_base64' => 'El archivo adjunto no es una imagen válida.'])->withInput();
        }

        // Decodificar y guardar la imagen
        $imagenData = preg_replace('#^data:image/\w+;base64,#i', '', $imagenBase64);
        $imagenData = base64_decode($imagenData);

        if ($imagenData === false) {
            return back()->withErrors(['imagen_base64' => 'No se pudo procesar la imagen.'])->withInput();
        }

        $carpeta = storage_path('app/validaciones_derechos/' . now()->format('Y/m'));
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0755, true);
        }

        $cedula        = preg_replace('/[^a-zA-Z0-9]/', '', $request->input('paciente_cedula', 'sin_cedula'));
        $nombreArchivo = ($request->input('agenda_ci_id', 'sa')) . '_' . $cedula . '_' . time() . '.png';
        $rutaAbsoluta  = $carpeta . '/' . $nombreArchivo;
        file_put_contents($rutaAbsoluta, $imagenData);

        $imagenPath = 'validaciones_derechos/' . now()->format('Y/m') . '/' . $nombreArchivo;

        // Datos del usuario actual
        $usuario       = Auth::user();
        $usuarioNombre = trim(
            ($usuario->pnombre ?? '') . ' ' .
            ($usuario->papellido ?? '') . ' ' .
            ($usuario->sapellido ?? '')
        ) ?: ($usuario->usuario ?? 'Sistema');

        ValidacionDerecho::create([
            'agenda_ci_id'      => $request->input('agenda_ci_id') ?: null,
            'paciente_nombre'   => $request->input('paciente_nombre'),
            'paciente_cedula'   => $request->input('paciente_cedula'),
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

        return redirect()->route('validaciones.index')
            ->with('success', 'Validación de derechos guardada correctamente.');
    }

    // ── Ver imagen ──────────────────────────────────────────────────────────

    public function imagen($id)
    {
        $registro = ValidacionDerecho::findOrFail($id);

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
        $registro = ValidacionDerecho::findOrFail($id);

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

    // ── AJAX: buscar agenda por cédula o nombre ──────────────────────────────

    public function ajaxBuscarAgenda(Request $request)
    {
        $q     = trim($request->input('q', ''));
        $fecha = $request->input('fecha', '');

        if (strlen($q) < 3 && empty($fecha)) {
            return response()->json([]);
        }

        $query = AgendaCI::query();

        if (!empty($q)) {
            $query->where(function ($sub) use ($q) {
                $sub->where('paciente_cedula', 'like', '%' . $q . '%')
                    ->orWhere('paciente_nombre', 'like', '%' . $q . '%')
                    ->orWhere('numero_factura',  'like', '%' . $q . '%');
            });
        }

        if (!empty($fecha)) {
            $query->whereDate('fecha', $fecha);
        }

        $citas = $query->orderByDesc('fecha')->limit(30)->get([
            'id', 'id_registro', 'paciente_nombre', 'paciente_cedula',
            'fecha', 'numero_factura', 'atencion_factura', 'contrato',
            'empresafac', 'cups_codigo', 'cups_descripcion',
        ]);

        return response()->json($citas->map(function ($c) {
            $fecha = $c->fecha ? \Carbon\Carbon::parse($c->fecha)->format('d/m/Y H:i') : '';
            return [
                'id'               => $c->id,
                'label'            => $c->paciente_nombre . ' — ' . $c->paciente_cedula . ' — ' . $fecha,
                'paciente_nombre'  => $c->paciente_nombre,
                'paciente_cedula'  => $c->paciente_cedula,
                'fecha'            => $c->fecha ? \Carbon\Carbon::parse($c->fecha)->format('Y-m-d') : '',
                'numero_factura'   => $c->numero_factura ?? '',
                'atencion_factura' => $c->atencion_factura ?? '',
                'contrato'         => $c->contrato ?? '',
                'empresafac'       => $c->empresafac ?? '',
                'cups_codigo'      => $c->cups_codigo ?? '',
                'cups_descripcion' => $c->cups_descripcion ?? '',
            ];
        }));
    }
}
