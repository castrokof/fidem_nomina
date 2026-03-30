<?php

namespace App\Http\Controllers;

use App\Especialidad;
use Illuminate\Http\Request;

class EspecialidadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $especialidades = Especialidad::orderBy('nombre')->paginate(20);
        return view('admin.especialidades.index', compact('especialidades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.especialidades.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:150|unique:especialidades',
            'codigo'      => 'nullable|string|max:30|unique:especialidades',
            'descripcion' => 'nullable|string',
            'activo'      => 'nullable|boolean'
        ]);

        Especialidad::create($request->all());

        return redirect()->route('especialidades.index')
            ->with('success', 'Especialidad creada exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $especialidad = Especialidad::findOrFail($id);
        return view('admin.especialidades.edit', compact('especialidad'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $especialidad = Especialidad::findOrFail($id);

        $request->validate([
            'nombre'      => 'required|string|max:150|unique:especialidades,nombre,' . $id,
            'codigo'      => 'nullable|string|max:30|unique:especialidades,codigo,' . $id,
            'descripcion' => 'nullable|string',
            'activo'      => 'nullable|boolean'
        ]);

        $especialidad->update($request->all());

        return redirect()->route('especialidades.index')
            ->with('success', 'Especialidad actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $especialidad = Especialidad::findOrFail($id);

        if ($especialidad->profesionales()->count() > 0) {
            return redirect()->route('especialidades.index')
                ->with('error', 'No se puede eliminar la especialidad porque tiene profesionales asociados.');
        }

        $especialidad->delete();

        return redirect()->route('especialidades.index')
            ->with('success', 'Especialidad eliminada exitosamente.');
    }
}
