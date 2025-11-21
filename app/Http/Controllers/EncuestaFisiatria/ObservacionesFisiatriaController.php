<?php

namespace App\Http\Controllers\EncuestaFisiatria;
use App\Models\EncuestaFisiatria\EncuestaFisiatria;
use App\Models\EncuestaFisiatria\ObservacionesFisiatria;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ObservacionesFisiatriaController extends Controller
{
    /**
     * Mostrar todas las observaciones de una encuesta
     */
    public function index($enc_id)
    {
        $encuesta = EncuestaFisiatria::findOrFail($enc_id);
        $observaciones = $encuesta->observaciones()->latest()->get();

        return view('observaciones.index', compact('encuesta', 'observaciones'));
    }

    /**
     * Guardar una nueva observación
     */
    public function store(Request $request, $enc_id)
    {
        $request->validate([
            'addobservacion' => 'required|string',
        ]);

        ObservacionesFisiatria::create([
            'addobservacion' => $request->addobservacion,
            'enc_id' => $enc_id,
            'user_id' => $enc_id,
        ]);

        return redirect()->back()->with('success', 'Observación agregada correctamente.');
    }

    /**
     * Eliminar observación
     */
    public function destroy($id)
    {
        $observacion = ObservacionesFisiatria::findOrFail($id);
        $observacion->delete();

        return redirect()->back()->with('success', 'Observación eliminada.');
    }
}
