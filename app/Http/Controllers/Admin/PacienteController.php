<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Paciente;
use App\AgendaCI;
use App\ConsentimientoInformado;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.pacientes.index');
    }

    public function ajaxBuscar(Request $request)
    {
        $query = Paciente::withCount(['consentimientos', 'agendas']);

        if ($request->filled('nombre')) {
            $n = $request->nombre;
            $query->where(function($q) use ($n) {
                $q->where('nombres', 'like', "%{$n}%")
                  ->orWhere('apellidos', 'like', "%{$n}%");
            });
        }
        if ($request->filled('documento')) {
            $query->where('numero_documento', 'like', '%' . $request->documento . '%');
        }
        if ($request->filled('historia')) {
            $query->where('historia_clinica', 'like', '%' . $request->historia . '%');
        }

        $pacientes = $query->orderBy('apellidos')->orderBy('nombres')->limit(200)->get();

        return response()->json([
            'success' => true,
            'total'   => $pacientes->count(),
            'pacientes' => $pacientes->map(fn($p) => [
                'id'                   => $p->id,
                'nombres'              => $p->nombres,
                'apellidos'            => $p->apellidos,
                'documento'            => $p->tipo_documento . '-' . $p->numero_documento,
                'historia_clinica'     => $p->historia_clinica ?? 'N/A',
                'telefono'             => $p->telefono ?? '',
                'email'                => $p->email ?? '',
                'total_citas'          => $p->agendas_count,
                'total_consentimientos'=> $p->consentimientos_count,
                'url_show'             => route('pacientes.show', $p->id),
                'url_edit'             => route('pacientes.edit', $p->id),
            ]),
        ]);
    }

    public function ajaxDetalle($id)
    {
        $paciente = Paciente::findOrFail($id);

        $agendas = AgendaCI::with('profesionalPorCodigo')
            ->where('paciente_id', $id)
            ->orderBy('fecha', 'desc')
            ->limit(100)
            ->get();

        $consentimientos = ConsentimientoInformado::with(['plantilla', 'profesional'])
            ->where('paciente_id', $id)
            ->orderBy('fecha_procedimiento', 'desc')
            ->get();

        $estadosCita = [0=>'Asignada',1=>'Atendido',2=>'Incumplido',3=>'Cancelada',4=>'Cancelada-Prestador'];

        $agendasMap = $agendas->map(function($a) use ($estadosCita) {
            $prof = $a->profesionalPorCodigo;
            return [
                'fecha'        => \Carbon\Carbon::parse($a->fecha)->format('d/m/Y H:i'),
                'fecha_sort'   => $a->fecha,
                'cups'         => $a->cups_codigo ?? '-',
                'centroprod'   => $a->centroprod ?? '-',
                'estado'       => $a->estado,
                'estado_label' => $estadosCita[$a->estado] ?? 'Desconocido',
                'profesional'  => $prof ? ($prof->nombres . ' ' . $prof->apellidos) : 'N/A',
                'observaciones'=> $a->observaciones ?? '',
                'empresafac'   => $a->empresafac ?? '',
                'contrato'     => $a->contrato ?? '',
            ];
        });

        $ciMap = $consentimientos->map(fn($c) => [
            'id'         => $c->id,
            'plantilla'  => $c->plantilla->nombre,
            'estado'     => $c->estado,
            'fecha'      => \Carbon\Carbon::parse($c->fecha_procedimiento)->format('d/m/Y H:i'),
            'fecha_sort' => $c->fecha_procedimiento,
            'profesional'=> $c->profesional->nombres . ' ' . $c->profesional->apellidos,
            'url_show'   => route('consentimientos.show', $c->id),
        ]);

        // Timeline combinado
        $timeline = collect();
        foreach ($agendasMap as $a) {
            $timeline->push(array_merge($a, ['tipo' => 'cita']));
        }
        foreach ($ciMap as $c) {
            $timeline->push(array_merge($c, ['tipo' => 'consentimiento']));
        }
        $timeline = $timeline->sortByDesc('fecha_sort')->values();

        return response()->json([
            'success'  => true,
            'paciente' => [
                'id'              => $paciente->id,
                'nombre_completo' => $paciente->nombres . ' ' . $paciente->apellidos,
                'documento'       => $paciente->tipo_documento . '-' . $paciente->numero_documento,
                'historia_clinica'=> $paciente->historia_clinica ?? 'N/A',
                'telefono'        => $paciente->telefono ?? 'N/A',
                'email'           => $paciente->email ?? 'N/A',
                'edad'            => $paciente->edad ?? 'N/A',
                'genero'          => $paciente->genero ?? 'N/A',
                'url_edit'        => route('pacientes.edit', $paciente->id),
            ],
            'stats' => [
                'total_citas'              => $agendas->count(),
                'total_consentimientos'    => $consentimientos->count(),
                'consentimientos_firmados' => $consentimientos->where('estado', 'firmado')->count(),
                'consentimientos_pendientes'=> $consentimientos->where('estado', 'pendiente')->count(),
            ],
            'agendas'          => $agendasMap,
            'consentimientos'  => $ciMap,
            'timeline'         => $timeline,
        ]);
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
            'edad'             => 'required|integer|min:0|max:120',
            'genero'           => 'required|in:M,F,O'
        ]);

        $paciente->update($request->all());

        return redirect()->route('pacientes.show', $paciente->id)
            ->with('success', 'Paciente actualizado exitosamente.');
    }
}
