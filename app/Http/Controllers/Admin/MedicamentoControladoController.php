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
    public function index()
    {
        $medicamentos = MedicamentoControlado::orderBy('nombre')->get();
        return view('admin.medicamento_controlado.index', compact('medicamentos'));
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
        MedicamentoControlado::create($request->all());
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
