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

        // Si es una petición AJAX, retornar datos para DataTables
        if ($request->ajax()) {
            $query = MedicamentoControladoMovimiento::with(['medicamentoControlado', 'usuario'])
                ->select('medicamentos_controlados_movimientos.*');

            // Aplicar filtros
            if ($medicamento_id) {
                $query->where('medicamento_controlado_id', $medicamento_id);
            }

            if ($fecha_desde) {
                $query->whereDate('fecha', '>=', $fecha_desde);
            }

            if ($fecha_hasta) {
                $query->whereDate('fecha', '<=', $fecha_hasta);
            }

            return datatables()->of($query)
                ->editColumn('fecha', function($mov) {
                    return '<strong>'.date('d/m/Y', strtotime($mov->fecha)).'</strong>';
                })
                ->editColumn('medicamento', function($mov) {
                    return '<div class="d-flex align-items-center">
                                <i class="fas fa-prescription-bottle text-primary mr-2"></i>
                                '.$mov->medicamentoControlado->nombre.'
                            </div>';
                })
                ->editColumn('tipo_movimiento', function($mov) {
                    if ($mov->tipo_movimiento == 'entrada') {
                        return '<span class="glass-badge glass-badge-success">
                                    <i class="fas fa-arrow-down"></i> ENTRADA
                                </span>';
                    } else {
                        return '<span class="glass-badge glass-badge-danger">
                                    <i class="fas fa-arrow-up"></i> SALIDA
                                </span>';
                    }
                })
                ->editColumn('proveedor', function($mov) {
                    return $mov->proveedor ?? '-';
                })
                ->editColumn('paciente', function($mov) {
                    if ($mov->nombre_paciente) {
                        return '<strong>'.$mov->nombre_paciente.'</strong><br>
                                <small class="text-muted"><i class="fas fa-id-card mr-1"></i>'.$mov->cedula_paciente.'</small>';
                    }
                    return '-';
                })
                ->editColumn('entrada', function($mov) {
                    if ($mov->entrada > 0) {
                        return '<span class="glass-badge glass-badge-success">+'.$mov->entrada.'</span>';
                    }
                    return '-';
                })
                ->editColumn('salida', function($mov) {
                    if ($mov->salida > 0) {
                        return '<span class="glass-badge glass-badge-danger">-'.$mov->salida.'</span>';
                    }
                    return '-';
                })
                ->editColumn('saldo', function($mov) {
                    return '<strong style="font-size: 1.1rem;">'.$mov->saldo.'</strong>';
                })
                ->addColumn('detalles', function($mov) {
                    $html = '';
                    if ($mov->foto_formula) {
                        $html .= '<a href="'.asset('storage/' . $mov->foto_formula).'" target="_blank"
                                     class="btn btn-info btn-sm" title="Ver foto fórmula" data-toggle="tooltip">
                                    <i class="fas fa-image"></i>
                                  </a> ';
                    }
                    if ($mov->numero_factura) {
                        $html .= '<span class="badge badge-info" title="Factura: '.$mov->numero_factura.'" data-toggle="tooltip">
                                    <i class="fas fa-file-invoice"></i> '.$mov->numero_factura.'
                                  </span> ';
                    }
                    if ($mov->numero_formula_control) {
                        $html .= '<span class="badge badge-warning" title="Fórmula Control: '.$mov->numero_formula_control.'" data-toggle="tooltip">
                                    <i class="fas fa-file-prescription"></i> '.$mov->numero_formula_control.'
                                  </span>';
                    }
                    return $html ?: '-';
                })
                ->orderColumn('fecha', function($query, $order) {
                    $query->orderBy('fecha', $order)->orderBy('id', $order);
                })
                ->rawColumns(['fecha', 'medicamento', 'tipo_movimiento', 'paciente', 'entrada', 'salida', 'saldo', 'detalles'])
                ->make(true);
        }

        // Para la vista normal, solo cargar medicamentos para los filtros
        $medicamentos = MedicamentoControlado::where('activo', 1)->orderBy('nombre')->get();

        return view('admin.medicamento_controlado_movimiento.index', compact('medicamentos', 'medicamento_id', 'fecha_desde', 'fecha_hasta'));
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

    /**
     * Obtener estadísticas de movimientos con filtros
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function obtenerEstadisticas(Request $request)
    {
        $medicamento_id = $request->get('medicamento_id');
        $fecha_desde = $request->get('fecha_desde');
        $fecha_hasta = $request->get('fecha_hasta');

        $query = MedicamentoControladoMovimiento::query();

        // Aplicar filtros
        if ($medicamento_id) {
            $query->where('medicamento_controlado_id', $medicamento_id);
        }

        if ($fecha_desde) {
            $query->whereDate('fecha', '>=', $fecha_desde);
        }

        if ($fecha_hasta) {
            $query->whereDate('fecha', '<=', $fecha_hasta);
        }

        // Obtener estadísticas
        $totalEntradas = (clone $query)->sum('entrada');
        $totalSalidas = (clone $query)->sum('salida');
        $totalMovimientos = (clone $query)->count();

        // Obtener stock actual
        if ($medicamento_id) {
            $medicamento = MedicamentoControlado::find($medicamento_id);
            $stockActual = $medicamento ? $medicamento->saldo_actual : 0;
            $nombreMedicamento = $medicamento ? $medicamento->nombre : '';
        } else {
            // Si no hay filtro de medicamento, sumar todos los saldos actuales
            $stockActual = MedicamentoControlado::where('activo', 1)->sum('saldo_actual');
            $nombreMedicamento = 'Todos los medicamentos';
        }

        return response()->json([
            'total_entradas' => $totalEntradas,
            'total_salidas' => $totalSalidas,
            'total_movimientos' => $totalMovimientos,
            'stock_actual' => $stockActual,
            'nombre_medicamento' => $nombreMedicamento
        ]);
    }
}
