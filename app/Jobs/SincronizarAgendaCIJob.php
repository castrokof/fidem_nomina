<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\AgendaSyncService;
use Illuminate\Support\Facades\Log;

class SincronizarAgendaCIJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $diasAtras;
    protected $diasAdelante;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($diasAtras = 2, $diasAdelante = 3)
    {
        $this->diasAtras = $diasAtras;
        $this->diasAdelante = $diasAdelante;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(AgendaSyncService $syncService)
    {
        try {
            $resultado = $syncService->sincronizarRango($this->diasAtras, $this->diasAdelante);

            Log::info('Sincronización de agenda CI completada', [
                'creados'      => $resultado['creados'],
                'actualizados' => $resultado['actualizados'],
                'total'        => $resultado['total']
            ]);

        } catch (\Exception $e) {
            Log::error('Error en sincronización de agenda CI: ' . $e->getMessage());
            throw $e;
        }
    }
}
