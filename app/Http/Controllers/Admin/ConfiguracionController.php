<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ConfiguracionController extends Controller
{
    /**
     * Mostrar configuraciones generales
     */
    public function index()
    {
        $configuraciones = Configuracion::orderBy('clave')->get();
        $logoFidem = Configuracion::where('clave', 'logo_fidem_path')->first();

        return view('admin.configuraciones.index', compact('configuraciones', 'logoFidem'));
    }

    /**
     * Cargar logo de FIDEM
     */
    public function cargarLogo(Request $request)
    {
        $request->validate([
            'logo_fidem' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            if ($request->hasFile('logo_fidem')) {
                // Obtener configuración actual
                $config = Configuracion::where('clave', 'logo_fidem_path')->first();

                // Eliminar logo anterior si existe
                if ($config && $config->valor && file_exists(public_path($config->valor))) {
                    unlink(public_path($config->valor));
                }

                $file = $request->file('logo_fidem');
                $fileName = 'logo_fidem_' . time() . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('uploads/logos');

                // Crear directorio si no existe
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $file->move($destinationPath, $fileName);

                // Guardar en configuración
                Configuracion::establecer(
                    'logo_fidem_path',
                    'uploads/logos/' . $fileName,
                    'imagen',
                    'Ruta de la imagen del logo de FIDEM para consentimientos informados'
                );

                return response()->json([
                    'success' => true,
                    'message' => 'Logo de FIDEM cargado exitosamente',
                    'path' => 'uploads/logos/' . $fileName
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No se recibió ninguna imagen'
            ], 400);

        } catch (\Exception $e) {
            Log::error('Error al cargar logo FIDEM', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al cargar el logo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar logo de FIDEM
     */
    public function eliminarLogo()
    {
        try {
            $config = Configuracion::where('clave', 'logo_fidem_path')->first();

            if ($config && $config->valor && file_exists(public_path($config->valor))) {
                unlink(public_path($config->valor));
            }

            if ($config) {
                $config->update(['valor' => null]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Logo de FIDEM eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            Log::error('Error al eliminar logo FIDEM', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el logo: ' . $e->getMessage()
            ], 500);
        }
    }
}
