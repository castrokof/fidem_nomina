<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

    /**
     * Cargar imagen de firma digital
     */
    public function cargarImagenFirma(Request $request, $id)
    {
        $profesional = Profesional::findOrFail($id);

        $request->validate([
            'firma_imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('firma_imagen')) {
            // Eliminar firma anterior si existe
            if ($profesional->firma_imagen_path && file_exists(public_path($profesional->firma_imagen_path))) {
                unlink(public_path($profesional->firma_imagen_path));
            }

            $file = $request->file('firma_imagen');
            $fileName = 'firma_profesional_' . $id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/firmas');

            // Crear directorio si no existe
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $fileName);

            $profesional->update([
                'firma_imagen_path' => 'uploads/firmas/' . $fileName,
                'firma_actualizada_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Imagen de firma cargada exitosamente',
                'path' => 'uploads/firmas/' . $fileName
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No se recibió ninguna imagen'
        ], 400);
    }

    /**
     * Eliminar imagen de firma digital
     */
    public function eliminarImagenFirma($id)
    {
        $profesional = Profesional::findOrFail($id);

        if ($profesional->firma_imagen_path && file_exists(public_path($profesional->firma_imagen_path))) {
            unlink(public_path($profesional->firma_imagen_path));
        }

        $profesional->update([
            'firma_imagen_path' => null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Imagen de firma eliminada exitosamente'
        ]);
    }
}
