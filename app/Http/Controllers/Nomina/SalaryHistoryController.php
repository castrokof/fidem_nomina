<?php

namespace App\Http\Controllers\Nomina;

use App\Http\Controllers\Controller;
use App\Models\Nomina\Empleados;
use App\Models\Nomina\SalaryHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalaryHistoryController extends Controller
{
    /**
     * Muestra el historial de salarios de un empleado
     */
    public function index($empleadoId)
    {
        $empleado = Empleados::with(['salaryHistory.createdBy'])->findOrFail($empleadoId);

        return view('nomina.empleados.salary_history.index', compact('empleado'));
    }

    /**
     * Muestra el formulario para crear un nuevo registro de salario
     */
    public function create($empleadoId)
    {
        $empleado = Empleados::findOrFail($empleadoId);

        return view('nomina.empleados.salary_history.create', compact('empleado'));
    }

    /**
     * Almacena un nuevo registro de salario
     */
    public function store(Request $request, $empleadoId)
    {
        $request->validate([
            'salary' => 'nullable|numeric|min:0',
            'salary_ps' => 'nullable|numeric|min:0',
            'fecha_inicio' => 'required|date',
            'motivo' => 'nullable|string|max:500',
        ], [
            'salary.numeric' => 'El salario fijo debe ser un número válido',
            'salary_ps.numeric' => 'El salario prestación de servicios debe ser un número válido',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria',
            'fecha_inicio.date' => 'La fecha de inicio debe ser válida',
        ]);

        // Validar que al menos uno de los salarios esté presente
        if (empty($request->salary) && empty($request->salary_ps)) {
            return back()->withErrors(['salary' => 'Debe ingresar al menos un tipo de salario'])->withInput();
        }

        $empleado = Empleados::findOrFail($empleadoId);

        DB::beginTransaction();
        try {
            // Desactivar todos los registros anteriores de este empleado
            SalaryHistory::where('empleado_id', $empleadoId)
                        ->where('activo', true)
                        ->update([
                            'activo' => false,
                            'fecha_fin' => Carbon::parse($request->fecha_inicio)->subDay()->format('Y-m-d')
                        ]);

            // Crear el nuevo registro de salario
            $salaryHistory = SalaryHistory::create([
                'empleado_id' => $empleadoId,
                'salary' => $request->salary,
                'salary_ps' => $request->salary_ps,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => null,
                'created_by' => Auth::id(),
                'motivo' => $request->motivo,
                'activo' => true
            ]);

            // Actualizar los campos de salario en la tabla empleados (por compatibilidad)
            $empleado->update([
                'salary' => $request->salary,
                'salary_ps' => $request->salary_ps
            ]);

            DB::commit();

            return redirect()
                ->route('empleados.salary-history.index', $empleadoId)
                ->with('success', 'Registro de salario creado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => 'Error al crear el registro: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Muestra los detalles de un registro de salario
     */
    public function show($empleadoId, $id)
    {
        $empleado = Empleados::findOrFail($empleadoId);
        $salaryRecord = SalaryHistory::with('createdBy')->findOrFail($id);

        return view('nomina.empleados.salary_history.show', compact('empleado', 'salaryRecord'));
    }

    /**
     * Obtiene el historial de salarios en formato JSON (para AJAX)
     */
    public function getHistory($empleadoId)
    {
        $historial = SalaryHistory::where('empleado_id', $empleadoId)
                                  ->with('createdBy')
                                  ->ordenadoPorFecha()
                                  ->get();

        return response()->json([
            'success' => true,
            'data' => $historial->map(function($item) {
                return [
                    'id' => $item->id,
                    'salary' => $item->salary ? number_format($item->salary, 0, ',', '.') : '-',
                    'salary_ps' => $item->salary_ps ? number_format($item->salary_ps, 0, ',', '.') : '-',
                    'fecha_inicio' => $item->fecha_inicio->format('d/m/Y'),
                    'fecha_fin' => $item->fecha_fin ? $item->fecha_fin->format('d/m/Y') : 'Actual',
                    'motivo' => $item->motivo ?? '-',
                    'created_by' => $item->createdBy ? $item->createdBy->usuario : '-',
                    'activo' => $item->activo,
                    'created_at' => $item->created_at->format('d/m/Y H:i')
                ];
            })
        ]);
    }

    /**
     * Obtiene el salario vigente en una fecha específica
     */
    public function getSalaryAtDate(Request $request, $empleadoId)
    {
        $request->validate([
            'fecha' => 'required|date'
        ]);

        $empleado = Empleados::findOrFail($empleadoId);
        $salario = $empleado->salarioEnFecha($request->fecha);

        if (!$salario) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró un registro de salario para la fecha especificada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'salary' => $salario->salary,
                'salary_ps' => $salario->salary_ps,
                'fecha_inicio' => $salario->fecha_inicio->format('d/m/Y'),
                'fecha_fin' => $salario->fecha_fin ? $salario->fecha_fin->format('d/m/Y') : 'Actual',
                'motivo' => $salario->motivo
            ]
        ]);
    }
}
