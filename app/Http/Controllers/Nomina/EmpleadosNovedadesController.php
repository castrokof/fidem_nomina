<?php

namespace App\Http\Controllers\Nomina;

use App\Http\Controllers\Controller;
use App\Models\Nomina\EmpleadosNovedades;
use App\Models\Nomina\Empleados;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmpleadosNovedadesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            $empleado_id = $request->empleado_id;

            $novedades = EmpleadosNovedades::where('empleado_id', $empleado_id)
                ->orderBy('id', 'desc')
                ->get();

            return DataTables()->of($novedades)
                ->addColumn('action', function($novedad){
                    $button = '<button type="button" name="edit" id="'.$novedad->id.'"
                    class = "editnovedad btn-float bg-gradient-primary btn-sm tooltipsC" title="Editar novedad"><i class="fas fa-edit"></i></button>';
                    $button .='&nbsp;<button type="button" name="delete" id="'.$novedad->id.'"
                    class = "deletenovedad btn-float bg-gradient-danger btn-sm tooltipsC" title="Eliminar novedad"><i class="fas fa-trash"></i></button>';

                    return $button;
                })
                ->addColumn('estado_badge', function($novedad){
                    $badge = '';
                    switch($novedad->estado) {
                        case 'activo':
                            $badge = '<span class="badge badge-success">Activo</span>';
                            break;
                        case 'finalizado':
                            $badge = '<span class="badge badge-secondary">Finalizado</span>';
                            break;
                        case 'cancelado':
                            $badge = '<span class="badge badge-danger">Cancelado</span>';
                            break;
                        default:
                            $badge = '<span class="badge badge-info">Pendiente</span>';
                    }
                    return $badge;
                })
                ->addColumn('tipo_badge', function($novedad){
                    $badge = '';
                    switch($novedad->tipo_novedad) {
                        case 'incapacidad':
                            $badge = '<span class="badge badge-warning">Incapacidad</span>';
                            break;
                        case 'licencia':
                            $badge = '<span class="badge badge-info">Licencia</span>';
                            break;
                        case 'vacaciones':
                            $badge = '<span class="badge badge-success">Vacaciones</span>';
                            break;
                        case 'suspension':
                            $badge = '<span class="badge badge-danger">Suspensión</span>';
                            break;
                        case 'permiso':
                            $badge = '<span class="badge badge-primary">Permiso</span>';
                            break;
                        default:
                            $badge = '<span class="badge badge-secondary">Otro</span>';
                    }
                    return $badge;
                })
                ->rawColumns(['action', 'estado_badge', 'tipo_badge'])
                ->make(true);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tipo_novedad' => 'required|string|max:50',
            'fecha_inicio' => 'required|date',
            'empleado_id' => 'required|exists:empleados,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Calcular días automáticamente si se proporcionan ambas fechas
        $dias = null;
        if ($request->fecha_inicio && $request->fecha_fin) {
            $fecha_inicio = \Carbon\Carbon::parse($request->fecha_inicio);
            $fecha_fin = \Carbon\Carbon::parse($request->fecha_fin);
            $dias = $fecha_fin->diffInDays($fecha_inicio) + 1;
        }

        EmpleadosNovedades::create([
            'tipo_novedad' => $request->tipo_novedad,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'dias' => $dias ?? $request->dias,
            'valor' => $request->valor,
            'observacion' => $request->observacion,
            'documento_soporte' => $request->documento_soporte,
            'estado' => $request->estado ?? 'activo',
            'empleado_id' => $request->empleado_id,
            'user_id' => $request->session()->get('usuario_id')
        ]);

        return response()->json(['success' => 'ok']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if($request->ajax()){
            $novedad = EmpleadosNovedades::findOrFail($id);
            return response()->json(['novedad' => $novedad]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tipo_novedad' => 'required|string|max:50',
            'fecha_inicio' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Calcular días automáticamente si se proporcionan ambas fechas
        $dias = null;
        if ($request->fecha_inicio && $request->fecha_fin) {
            $fecha_inicio = \Carbon\Carbon::parse($request->fecha_inicio);
            $fecha_fin = \Carbon\Carbon::parse($request->fecha_fin);
            $dias = $fecha_fin->diffInDays($fecha_inicio) + 1;
        }

        if($request->ajax()){
            EmpleadosNovedades::findOrFail($id)->update([
                'tipo_novedad' => $request->tipo_novedad,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'dias' => $dias ?? $request->dias,
                'valor' => $request->valor,
                'observacion' => $request->observacion,
                'documento_soporte' => $request->documento_soporte,
                'estado' => $request->estado,
            ]);
        }

        return response()->json(['success' => 'ok1']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        EmpleadosNovedades::findOrFail($id)->delete();
        return response()->json(['success' => 'deleted']);
    }
}
