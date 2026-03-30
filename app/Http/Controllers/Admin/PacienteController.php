<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Paciente;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Paciente::query();

        // Búsqueda por nombre o cédula
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('nombres', 'like', "%{$buscar}%")
                  ->orWhere('apellidos', 'like', "%{$buscar}%")
                  ->orWhere('numero_documento', 'like', "%{$buscar}%")
                  ->orWhere('historia_clinica', 'like', "%{$buscar}%");
            });
        }

        $pacientes = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.pacientes.index', compact('pacientes'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $paciente = Paciente::with(['consentimientos', 'agendas'])->findOrFail($id);
        return view('admin.pacientes.show', compact('paciente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $paciente = Paciente::findOrFail($id);
        return view('admin.pacientes.edit', compact('paciente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $paciente = Paciente::findOrFail($id);

        $request->validate([
            'nombres'          => 'required|string|max:100',
            'apellidos'        => 'required|string|max:100',
            'telefono'         => 'nullable|string|max:20',
            'email'            => 'nullable|email|max:150',
            'fecha_nacimiento' => 'nullable|date',
            'edad'             => 'nullable|integer|min:0|max:120',
            'genero'           => 'nullable|in:M,F,O'
        ]);

        $paciente->update($request->all());

        return redirect()->route('pacientes.show', $paciente->id)
            ->with('success', 'Paciente actualizado exitosamente.');
    }
}
