<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SincronizarAgendaCIJob;

class SincronizarAgendaCICommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agenda:sincronizar {--dias-atras=2 : Días hacia atrás para sincronizar} {--dias-adelante=3 : Días hacia adelante para sincronizar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincronizar agenda de consentimientos informados desde la API';

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
     * @return int
     */
    public function handle()
    {
        $diasAtras = $this->option('dias-atras');
        $diasAdelante = $this->option('dias-adelante');

        $this->info("Despachando job de sincronización...");
        $this->info("Días atrás: {$diasAtras}, Días adelante: {$diasAdelante}");

        SincronizarAgendaCIJob::dispatch($diasAtras, $diasAdelante);

        $this->info("Job despachado exitosamente a la cola.");
        $this->info("Ejecuta 'php artisan queue:work' para procesar el job.");

        return 0;
    }
}
