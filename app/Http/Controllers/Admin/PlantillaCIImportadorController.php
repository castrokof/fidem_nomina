<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ImportacionPlantillaCI;
use App\Services\PlantillaCIImportadorService;
use Illuminate\Http\Request;

class PlantillaCIImportadorController extends Controller
{
    protected $importadorService;

    public function __construct(PlantillaCIImportadorService $importadorService)
    {
        $this->importadorService = $importadorService;
    }

    /**
     * Mostrar vista de importación
     */
    public function index()
    {
        $importaciones = ImportacionPlantillaCI::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.importador-plantillas.index', compact('importaciones'));
    }

    /**
     * Guardar importación pendiente
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'          => 'required|string|max:200',
            'especialidades'  => 'nullable|string|max:500',
            'cups_codigo'     => 'nullable|string|max:20',
            'uso_general'     => 'nullable|boolean',
            'contenido_texto' => 'required|string'
        ]);

        ImportacionPlantillaCI::create([
            'nombre'          => $request->nombre,
            'especialidades'  => $request->especialidades,
            'cups_codigo'     => $request->cups_codigo,
            'uso_general'     => $request->boolean('uso_general'),
            'contenido_texto' => $request->contenido_texto,
            'estado'          => 'pendiente'
        ]);

        return redirect()->route('importador-plantillas.index')
            ->with('success', 'Plantilla guardada para importación. Usa el botón "Procesar Todas" para convertirla.');
    }

    /**
     * Procesar una importación específica
     */
    public function procesar($id)
    {
        $importacion = ImportacionPlantillaCI::findOrFail($id);

        try {
            $plantilla = $this->importadorService->procesarImportacion($importacion);

            return redirect()->route('importador-plantillas.index')
                ->with('success', "Plantilla '{$plantilla->nombre}' procesada exitosamente.");

        } catch (\Exception $e) {
            return redirect()->route('importador-plantillas.index')
                ->with('error', "Error al procesar: " . $e->getMessage());
        }
    }

    /**
     * Procesar todas las importaciones pendientes
     */
    public function procesarTodas()
    {
        $resultado = $this->importadorService->procesarTodas();

        $mensaje = "Procesamiento completado: {$resultado['procesadas']} exitosas";

        if ($resultado['errores'] > 0) {
            $mensaje .= ", {$resultado['errores']} con error";
        }

        return redirect()->route('importador-plantillas.index')
            ->with('success', $mensaje);
    }

    /**
     * Eliminar importación
     */
    public function destroy($id)
    {
        $importacion = ImportacionPlantillaCI::findOrFail($id);
        $importacion->delete();

        return redirect()->route('importador-plantillas.index')
            ->with('success', 'Importación eliminada exitosamente.');
    }
}
