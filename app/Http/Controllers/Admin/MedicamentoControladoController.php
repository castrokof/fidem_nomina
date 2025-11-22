<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ValidacionMedicamentoControlado;
use App\Models\Admin\MedicamentoControlado;
use Illuminate\Http\Request;

class MedicamentoControladoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Si es una petición AJAX, retornar datos para DataTables
        if ($request->ajax()) {
            $medicamentos = MedicamentoControlado::select([
                'id',
                'nombre',
                'descripcion',
                'saldo_actual',
                'activo'
            ]);

            return datatables()->of($medicamentos)
                ->addColumn('action', function($medicamento) {
                    return '<div class="btn-group-ios">
                                <button type="button"
                                        class="btn-ios btn-ios-warning btn-editar"
                                        data-id="'.$medicamento->id.'"
                                        data-nombre="'.$medicamento->nombre.'"
                                        data-descripcion="'.$medicamento->descripcion.'"
                                        data-activo="'.$medicamento->activo.'"
                                        title="Editar"
                                        data-toggle="tooltip">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button"
                                        class="btn-ios btn-ios-danger btn-eliminar"
                                        data-id="'.$medicamento->id.'"
                                        title="Eliminar"
                                        data-toggle="tooltip">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>';
                })
                ->editColumn('nombre', function($medicamento) {
                    return '<div class="d-flex align-items-center">
                                <i class="fas fa-prescription-bottle text-primary mr-2"></i>
                                <strong>'.$medicamento->nombre.'</strong>
                            </div>';
                })
                ->editColumn('descripcion', function($medicamento) {
                    return $medicamento->descripcion ?? 'N/A';
                })
                ->editColumn('saldo_actual', function($medicamento) {
                    if ($medicamento->saldo_actual > 50) {
                        return '<span class="glass-badge glass-badge-success">
                                    <i class="fas fa-box-open"></i> '.$medicamento->saldo_actual.'
                                </span>';
                    } elseif ($medicamento->saldo_actual > 20) {
                        return '<span class="glass-badge glass-badge-warning">
                                    <i class="fas fa-box-open"></i> '.$medicamento->saldo_actual.'
                                </span>';
                    } else {
                        return '<span class="glass-badge glass-badge-danger">
                                    <i class="fas fa-exclamation-triangle"></i> '.$medicamento->saldo_actual.'
                                </span>';
                    }
                })
                ->editColumn('activo', function($medicamento) {
                    if ($medicamento->activo) {
                        return '<span class="glass-badge glass-badge-success">
                                    <i class="fas fa-check-circle"></i> Activo
                                </span>';
                    } else {
                        return '<span class="glass-badge glass-badge-secondary">
                                    <i class="fas fa-times-circle"></i> Inactivo
                                </span>';
                    }
                })
                ->rawColumns(['action', 'nombre', 'saldo_actual', 'activo'])
                ->make(true);
        }

        // Para las estadísticas, cargar datos mínimos
        $stats = [
            'total' => MedicamentoControlado::count(),
            'activos' => MedicamentoControlado::where('activo', 1)->count(),
            'stock_total' => MedicamentoControlado::sum('saldo_actual')
        ];

        // Los medicamentos se cargan dinámicamente vía AJAX en los modales
        return view('admin.medicamento_controlado.index', compact('stats'));
    }

    /**
     * Obtener medicamentos activos para los selects (AJAX)
     *
     * @return \Illuminate\Http\Response
     */
    public function obtenerMedicamentosActivos(Request $request)
    {
        if ($request->ajax()) {
            $medicamentos = MedicamentoControlado::where('activo', 1)
                ->orderBy('nombre')
                ->select('id', 'nombre', 'saldo_actual')
                ->get();

            return response()->json([
                'success' => true,
                'medicamentos' => $medicamentos
            ]);
        }

        return response()->json(['success' => false], 403);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crear()
    {
        return view('admin.medicamento_controlado.crear');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ValidacionMedicamentoControlado  $request
     * @return \Illuminate\Http\Response
     */
    public function guardar(ValidacionMedicamentoControlado $request)
    {
        $medicamento = MedicamentoControlado::create($request->all());

        // Responder con JSON si es AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'mensaje' => 'Medicamento controlado creado con éxito',
                'id' => $medicamento->id,
                'nombre' => $medicamento->nombre
            ]);
        }

        return redirect('admin/medicamento-controlado/crear')
            ->with('mensaje', 'Medicamento controlado creado con éxito');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editar($id)
    {
        $data = MedicamentoControlado::findOrFail($id);
        return view('admin.medicamento_controlado.editar', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ValidacionMedicamentoControlado  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualizar(ValidacionMedicamentoControlado $request, $id)
    {
        $medicamento = MedicamentoControlado::findOrFail($id);
        $medicamento->update($request->all());

        // Responder con JSON si es AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'mensaje' => 'Medicamento controlado actualizado con éxito',
                'redirect' => route('medicamento_controlado')
            ]);
        }

        return redirect('admin/medicamento-controlado')
            ->with('mensaje', 'Medicamento controlado actualizado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function eliminar(Request $request, $id)
    {
        if ($request->ajax()) {
            $medicamento = MedicamentoControlado::findOrFail($id);

            // Verificar si tiene movimientos
            if ($medicamento->movimientos()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'No se puede eliminar el medicamento porque tiene movimientos registrados'
                ]);
            }

            $medicamento->delete();
            return response()->json([
                'success' => true,
                'mensaje' => 'Medicamento eliminado con éxito'
            ]);
        }
    }
}
