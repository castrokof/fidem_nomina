<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AgendaSyncService;

class SincronizarAgendaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agenda:sincronizar {--dias-atras=2} {--dias-adelante=3}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza las citas desde fac_m_citas hacia la tabla agenda_ci';

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
    public function handle(AgendaSyncService $syncService)
    {
        $diasAtras    = $this->option('dias-atras');
        $diasAdelante = $this->option('dias-adelante');

        $this->info("Sincronizando agenda de citas...");
        $this->info("Rango: {$diasAtras} días atrás a {$diasAdelante} días adelante");

        try {
            $resultado = $syncService->sincronizarRango($diasAtras, $diasAdelante);

            $this->info("✓ Sincronización completada:");
            $this->line("  - Citas totales: {$resultado['total']}");
            $this->line("  - Creadas: {$resultado['creados']}");
            $this->line("  - Actualizadas: {$resultado['actualizados']}");

            return 0;

        } catch (\Exception $e) {
            $this->error("✗ Error en la sincronización: " . $e->getMessage());
            return 1;
        }
    }
}
