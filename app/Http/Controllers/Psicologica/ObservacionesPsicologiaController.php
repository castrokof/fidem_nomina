<?php

namespace App\Http\Controllers\Psicologica;

use App\Http\Controllers\Controller;
use App\Models\Nomina\Liquidationxuser;
use App\Models\Psicologica\LineaPsicologica;
use App\Models\Psicologica\ObservacionesPsicologia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ObservacionesPsicologiaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if($request->ajax()){

        $usuario_id = $request->session()->get('usuario_id');

        $rules = array(
               'addobservacion' => 'required',
               'evo_id' => 'required',
               'user_id' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

         $Evo1 = LineaPsicologica::findOrFail($request->evo_id);

            if ($Evo1->future5 == 2) {

                ObservacionesPsicologia::create($request->all());

                return response()->json(['success' => 'ok1']);
            }

            ObservacionesPsicologia::create($request->all());

            $Evo = LineaPsicologica::findOrFail($request->evo_id);
            $Evo->future5 = 2;
            $Evo->save();

            return response()->json(['success' => 'ok']);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Psicologica\ObservacionesPsicologia  $observacionesPsicologia
     * @return \Illuminate\Http\Response
     */
    public function show(ObservacionesPsicologia $observacionesPsicologia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Psicologica\ObservacionesPsicologia  $observacionesPsicologia
     * @return \Illuminate\Http\Response
     */
    public function edit(ObservacionesPsicologia $observacionesPsicologia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Psicologica\ObservacionesPsicologia  $observacionesPsicologia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ObservacionesPsicologia $observacionesPsicologia)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Psicologica\ObservacionesPsicologia  $observacionesPsicologia
     * @return \Illuminate\Http\Response
     */
    public function destroy(ObservacionesPsicologia $observacionesPsicologia)
    {
        //
    }
}
