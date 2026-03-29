<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AgendaActualizadorService;

class ActualizarLlegadasCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agenda:actualizar-llegadas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza el estado de llegada de las citas pendientes del día';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(AgendaActualizadorService $actualizadorService)
    {
        $this->info("Actualizando llegadas de citas pendientes del día...");

        try {
            $actualizadas = $actualizadorService->actualizarPendientesDeHoy();

            $this->info("✓ Actualización completada:");
            $this->line("  - Citas actualizadas: {$actualizadas}");

            return 0;

        } catch (\Exception $e) {
            $this->error("✗ Error en la actualización: " . $e->getMessage());
            return 1;
        }
    }
}
