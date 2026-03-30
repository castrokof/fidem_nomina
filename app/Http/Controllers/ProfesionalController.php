<?php

namespace App\Http\Controllers;

use App\Profesional;
use App\Especialidad;
use Illuminate\Http\Request;

class ProfesionalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profesionales = Profesional::with('especialidad')->orderBy('apellidos')->paginate(20);
        return view('admin.profesionales.index', compact('profesionales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $especialidades = Especialidad::activo()->orderBy('nombre')->get();
        return view('admin.profesionales.create', compact('especialidades'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'especialidad_id'     => 'nullable|exists:especialidades,id',
            'codigo_usuario'      => 'nullable|string|max:50|unique:profesionales',
            'nombres'             => 'required|string|max:100',
            'apellidos'           => 'required|string|max:100',
            'tipo_documento'      => 'required|string|max:5',
            'numero_documento'    => 'nullable|string|max:20',
            'registro_medico'     => 'nullable|string|max:50',
            'tarjeta_profesional' => 'nullable|string|max:50',
            'telefono'            => 'nullable|string|max:20',
            'email'               => 'nullable|email|max:150',
            'activo'              => 'nullable|boolean'
        ]);

        $profesional = Profesional::create($request->all());

        return redirect()->route('profesionales.index')
            ->with('success', 'Profesional creado exitosamente. Ahora puedes registrar su firma.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $profesional = Profesional::findOrFail($id);
        $especialidades = Especialidad::activo()->orderBy('nombre')->get();
        return view('admin.profesionales.edit', compact('profesional', 'especialidades'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $profesional = Profesional::findOrFail($id);

        $request->validate([
            'especialidad_id'     => 'nullable|exists:especialidades,id',
            'codigo_usuario'      => 'nullable|string|max:50|unique:profesionales,codigo_usuario,' . $id,
            'nombres'             => 'required|string|max:100',
            'apellidos'           => 'required|string|max:100',
            'tipo_documento'      => 'required|string|max:5',
            'numero_documento'    => 'nullable|string|max:20',
            'registro_medico'     => 'nullable|string|max:50',
            'tarjeta_profesional' => 'nullable|string|max:50',
            'telefono'            => 'nullable|string|max:20',
            'email'               => 'nullable|email|max:150',
            'activo'              => 'nullable|boolean'
        ]);

        $profesional->update($request->all());

        return redirect()->route('profesionales.index')
            ->with('success', 'Profesional actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $profesional = Profesional::findOrFail($id);

        if ($profesional->consentimientos()->count() > 0) {
            return redirect()->route('profesionales.index')
                ->with('error', 'No se puede eliminar el profesional porque tiene consentimientos asociados.');
        }

        $profesional->delete();

        return redirect()->route('profesionales.index')
            ->with('success', 'Profesional eliminado exitosamente.');
    }

    /**
     * Mostrar vista para registrar firma
     */
    public function mostrarFirma($id)
    {
        $profesional = Profesional::findOrFail($id);
        return view('admin.profesionales.firma', compact('profesional'));
    }

    /**
     * Guardar firma del profesional
     */
    public function guardarFirma(Request $request, $id)
    {
        $profesional = Profesional::findOrFail($id);

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
