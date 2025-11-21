<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ValidacionMedicamentoControladoMovimiento;
use App\Models\Admin\MedicamentoControlado;
use App\Models\Admin\MedicamentoControladoMovimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MedicamentoControladoMovimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $medicamento_id = $request->get('medicamento_id');
        $fecha_desde = $request->get('fecha_desde');
        $fecha_hasta = $request->get('fecha_hasta');

        $query = MedicamentoControladoMovimiento::with(['medicamentoControlado', 'usuario']);

        if ($medicamento_id) {
            $query->where('medicamento_controlado_id', $medicamento_id);
        }

        if ($fecha_desde) {
            $query->whereDate('fecha', '>=', $fecha_desde);
        }

        if ($fecha_hasta) {
            $query->whereDate('fecha', '<=', $fecha_hasta);
        }

        $movimientos = $query->orderBy('fecha', 'desc')->orderBy('id', 'desc')->get();
        $medicamentos = MedicamentoControlado::where('activo', 1)->orderBy('nombre')->get();

        return view('admin.medicamento_controlado_movimiento.index', compact('movimientos', 'medicamentos', 'medicamento_id', 'fecha_desde', 'fecha_hasta'));
    }

    /**
     * Show the form for creating a new entrada.
     *
     * @return \Illuminate\Http\Response
     */
    public function crearEntrada()
    {
        $medicamentos = MedicamentoControlado::where('activo', 1)->orderBy('nombre')->get();
        return view('admin.medicamento_controlado_movimiento.crear_entrada', compact('medicamentos'));
    }

    /**
     * Show the form for creating a new salida.
     *
     * @return \Illuminate\Http\Response
     */
    public function crearSalida()
    {
        $medicamentos = MedicamentoControlado::where('activo', 1)->orderBy('nombre')->get();
        return view('admin.medicamento_controlado_movimiento.crear_salida', compact('medicamentos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ValidacionMedicamentoControladoMovimiento  $request
     * @return \Illuminate\Http\Response
     */
    public function guardar(ValidacionMedicamentoControladoMovimiento $request)
    {
        $data = $request->all();

        // Establecer valores por defecto según tipo de movimiento
        if ($request->tipo_movimiento == 'entrada') {
            $data['salida'] = 0;
        } else {
            $data['entrada'] = 0;
        }

        // Procesar foto si existe
        if ($request->hasFile('foto_formula')) {
            $archivo = $request->file('foto_formula');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
            $ruta = $archivo->storeAs('formularios_control', $nombreArchivo, 'public');
            $data['foto_formula'] = $ruta;
        }

        // Calcular saldo
        $ultimoMovimiento = MedicamentoControladoMovimiento::where('medicamento_controlado_id', $request->medicamento_controlado_id)
            ->orderBy('id', 'desc')
            ->first();

        $saldoAnterior = $ultimoMovimiento ? $ultimoMovimiento->saldo : 0;

        if ($request->tipo_movimiento == 'entrada') {
            $data['saldo'] = $saldoAnterior + $request->entrada;
        } else {
            $nuevoSaldo = $saldoAnterior - $request->salida;

            // Validar que no quede negativo
            if ($nuevoSaldo < 0) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No hay suficiente stock. Saldo actual: ' . $saldoAnterior,
                        'errors' => [
                            'salida' => ['No hay suficiente stock. Saldo actual: ' . $saldoAnterior]
                        ]
                    ], 422);
                }
                return back()->withInput()->withErrors(['salida' => 'No hay suficiente stock. Saldo actual: ' . $saldoAnterior]);
            }

            $data['saldo'] = $nuevoSaldo;
        }

        // Guardar usuario que registra
        $data['user_id'] = Auth::id();

        // Crear movimiento
        $movimiento = MedicamentoControladoMovimiento::create($data);

        // Actualizar saldo del medicamento
        $medicamento = MedicamentoControlado::findOrFail($request->medicamento_controlado_id);
        $medicamento->saldo_actual = $data['saldo'];
        $medicamento->save();

        // Responder con JSON si es AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Movimiento registrado con éxito',
                'saldo' => $data['saldo'],
                'medicamento_id' => $medicamento->id,
                'medicamento_nombre' => $medicamento->nombre,
                'movimiento_id' => $movimiento->id
            ]);
        }

        $redireccion = $request->tipo_movimiento == 'entrada'
            ? 'admin/medicamento-controlado-movimiento/crear-entrada'
            : 'admin/medicamento-controlado-movimiento/crear-salida';

        return redirect($redireccion)
            ->with('mensaje', 'Movimiento registrado con éxito. Nuevo saldo: ' . $data['saldo']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mostrar($id)
    {
        $movimiento = MedicamentoControladoMovimiento::with(['medicamentoControlado', 'usuario'])->findOrFail($id);
        return view('admin.medicamento_controlado_movimiento.mostrar', compact('movimiento'));
    }

    /**
     * Obtener el saldo actual de un medicamento via AJAX
     *
     * @param  int  $medicamento_id
     * @return \Illuminate\Http\Response
     */
    public function obtenerSaldo($medicamento_id)
    {
        $medicamento = MedicamentoControlado::findOrFail($medicamento_id);
        return response()->json([
            'saldo' => $medicamento->saldo_actual,
            'nombre' => $medicamento->nombre
        ]);
    }
}
