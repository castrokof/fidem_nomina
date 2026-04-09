<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\SincronizarAgendaCIJob;
use Illuminate\Support\Facades\DB;

class AgendaSyncController extends Controller
{
    /**
     * Mostrar la página de sincronización
     */
    public function index()
    {
        // Obtener estadísticas de la cola
        $jobsPendientes = DB::table('jobs')->count();
        $jobsFallidos = DB::table('failed_jobs')->count();

        return view('admin.agenda-sync.index', compact('jobsPendientes', 'jobsFallidos'));
    }

    /**
     * Despachar job de sincronización
     */
    public function sincronizar(Request $request)
    {
        $request->validate([
            'dias_atras' => 'nullable|integer|min:0|max:30',
            'dias_adelante' => 'nullable|integer|min:0|max:30',
        ]);

        $diasAtras = $request->input('dias_atras', 2);
        $diasAdelante = $request->input('dias_adelante', 3);

        // Despachar el job a la cola
        SincronizarAgendaCIJob::dispatch($diasAtras, $diasAdelante);

        return response()->json([
            'success' => true,
            'message' => 'Sincronización programada exitosamente. El job se procesará en la cola.'
        ]);
    }

    /**
     * Obtener estado de la cola
     */
    public function estado()
    {
        $jobsPendientes = DB::table('jobs')->count();
        $jobsFallidos = DB::table('failed_jobs')->count();

        // Obtener últimos jobs fallidos
        $ultimosFallidos = DB::table('failed_jobs')
            ->orderBy('failed_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'pendientes' => $jobsPendientes,
            'fallidos' => $jobsFallidos,
            'ultimos_fallidos' => $ultimosFallidos
        ]);
    }

    /**
     * Limpiar jobs fallidos
     */
    public function limpiarFallidos()
    {
        DB::table('failed_jobs')->truncate();

        return response()->json([
            'success' => true,
            'message' => 'Jobs fallidos limpiados exitosamente'
        ]);
    }

    /**
     * Reintentar job fallido
     */
    public function reintentarFallido($id)
    {
        $job = DB::table('failed_jobs')->where('id', $id)->first();

        if (!$job) {
            return response()->json([
                'success' => false,
                'message' => 'Job no encontrado'
            ], 404);
        }

        // Aquí normalmente usarías queue:retry
        // Por ahora solo retornamos que se debe hacer desde consola
        return response()->json([
            'success' => false,
            'message' => 'Usa: php artisan queue:retry ' . $id
        ]);
    }
}
