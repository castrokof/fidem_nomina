<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Profesional;
use Illuminate\Http\Request;

class PerfilController extends Controller
{
    /**
     * Mostrar vista para registrar firma del profesional autenticado
     */
    public function mostrarFirma()
    {
        $profesional = Profesional::where('usuario_id', auth()->id())->first();

        if (!$profesional) {
            return redirect()->route('home')
                ->with('error', 'Tu usuario no tiene un perfil de profesional asignado. Contacta al administrador.');
        }

        return view('perfil.firma', compact('profesional'));
    }

    /**
     * Guardar firma del profesional autenticado
     */
    public function guardarFirma(Request $request)
    {
        $profesional = Profesional::where('usuario_id', auth()->id())->first();

        if (!$profesional) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes un perfil de profesional asignado'
            ], 403);
        }

        $request->validate([
            'firma_base64' => 'required|string'
        ]);

        $profesional->update([
            'firma_base64'        => $request->firma_base64,
            'firma_actualizada_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Firma registrada exitosamente'
        ]);
    }
}
