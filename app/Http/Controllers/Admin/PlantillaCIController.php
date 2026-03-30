<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\PlantillaCI;
use App\Especialidad;
use Illuminate\Http\Request;

class PlantillaCIController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plantillas = PlantillaCI::with('especialidades')->orderBy('nombre')->paginate(20);
        return view('admin.plantillas-ci.index', compact('plantillas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $especialidades = Especialidad::activo()->orderBy('nombre')->get();
        $variablesDisponibles = PlantillaCI::variablesDisponibles();

        return view('admin.plantillas-ci.create', compact('especialidades', 'variablesDisponibles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'         => 'required|string|max:200',
            'descripcion'    => 'nullable|string',
            'cups_codigo'    => 'nullable|string|max:20',
            'contenido_html' => 'required|string',
            'uso_general'    => 'nullable|boolean',
            'activo'         => 'nullable|boolean',
            'especialidades' => 'nullable|array'
        ]);

        $plantilla = PlantillaCI::create([
            'nombre'                => $request->nombre,
            'descripcion'           => $request->descripcion,
            'cups_codigo'           => $request->cups_codigo,
            'contenido_html'        => $request->contenido_html,
            'variables_disponibles' => PlantillaCI::variablesDisponibles(),
            'uso_general'           => $request->boolean('uso_general'),
            'activo'                => $request->boolean('activo', true)
        ]);

        // Asociar especialidades
        if ($request->has('especialidades')) {
            $plantilla->especialidades()->sync($request->especialidades);
        }

        return redirect()->route('plantillas-ci.index')
            ->with('success', 'Plantilla creada exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $plantilla = PlantillaCI::with('especialidades')->findOrFail($id);
        $especialidades = Especialidad::activo()->orderBy('nombre')->get();
        $variablesDisponibles = PlantillaCI::variablesDisponibles();

        return view('admin.plantillas-ci.edit', compact('plantilla', 'especialidades', 'variablesDisponibles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $plantilla = PlantillaCI::findOrFail($id);

        $request->validate([
            'nombre'         => 'required|string|max:200',
            'descripcion'    => 'nullable|string',
            'cups_codigo'    => 'nullable|string|max:20',
            'contenido_html' => 'required|string',
            'uso_general'    => 'nullable|boolean',
            'activo'         => 'nullable|boolean',
            'especialidades' => 'nullable|array'
        ]);

        $plantilla->update([
            'nombre'         => $request->nombre,
            'descripcion'    => $request->descripcion,
            'cups_codigo'    => $request->cups_codigo,
            'contenido_html' => $request->contenido_html,
            'uso_general'    => $request->boolean('uso_general'),
            'activo'         => $request->boolean('activo', true)
        ]);

        // Sincronizar especialidades
        if ($request->has('especialidades')) {
            $plantilla->especialidades()->sync($request->especialidades);
        } else {
            $plantilla->especialidades()->sync([]);
        }

        return redirect()->route('plantillas-ci.index')
            ->with('success', 'Plantilla actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $plantilla = PlantillaCI::findOrFail($id);

        // Verificar si tiene consentimientos asociados
        if ($plantilla->consentimientos()->count() > 0) {
            return redirect()->route('plantillas-ci.index')
                ->with('error', 'No se puede eliminar la plantilla porque tiene consentimientos asociados.');
        }

        $plantilla->delete();

        return redirect()->route('plantillas-ci.index')
            ->with('success', 'Plantilla eliminada exitosamente.');
    }
}
