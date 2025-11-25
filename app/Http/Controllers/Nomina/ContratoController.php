<?php

namespace App\Http\Controllers\Nomina;

use App\Http\Controllers\Controller;
use App\Models\Models\Nomina\Contrato;
use App\Models\Nomina\Empleados;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContratoController extends Controller
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

            $contratos = Contrato::where('empleadosc_id', $empleado_id)
                ->orderBy('id', 'desc')
                ->get();

            return DataTables()->of($contratos)
                ->addColumn('action', function($contrato){
                    $button = '<button type="button" name="edit" id="'.$contrato->id.'"
                    class = "editcontrato btn-float bg-gradient-primary btn-sm tooltipsC" title="Editar contrato"><i class="fas fa-edit"></i></button>';
                    $button .='&nbsp;<button type="button" name="delete" id="'.$contrato->id.'"
                    class = "deletecontrato btn-float bg-gradient-danger btn-sm tooltipsC" title="Eliminar contrato"><i class="fas fa-trash"></i></button>';

                    return $button;
                })
                ->rawColumns(['action'])
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
            'ips' => 'required|string|max:50',
            'type_contrat' => 'required|string|max:255',
            'day_inicio' => 'required|date',
            'empleadosc_id' => 'required|exists:empleados,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        Contrato::create([
            'ips' => $request->ips,
            'type_contrat' => $request->type_contrat,
            'day_inicio' => $request->day_inicio,
            'day_fin' => $request->day_fin,
            'value_ps' => $request->value_ps,
            'value_ps_desc' => $request->value_ps_desc,
            'road_v' => $request->road_v,
            'hours' => $request->hours,
            'pac' => $request->pac,
            'contrat_observacion' => $request->contrat_observacion,
            'photo_base64' => $request->photo_base64,
            'empleadosc_id' => $request->empleadosc_id,
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
            $contrato = Contrato::findOrFail($id);
            return response()->json(['contrato' => $contrato]);
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
            'ips' => 'required|string|max:50',
            'type_contrat' => 'required|string|max:255',
            'day_inicio' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if($request->ajax()){
            Contrato::findOrFail($id)->update([
                'ips' => $request->ips,
                'type_contrat' => $request->type_contrat,
                'day_inicio' => $request->day_inicio,
                'day_fin' => $request->day_fin,
                'value_ps' => $request->value_ps,
                'value_ps_desc' => $request->value_ps_desc,
                'road_v' => $request->road_v,
                'hours' => $request->hours,
                'pac' => $request->pac,
                'contrat_observacion' => $request->contrat_observacion,
                'photo_base64' => $request->photo_base64,
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
        Contrato::findOrFail($id)->delete();
        return response()->json(['success' => 'deleted']);
    }
}
