<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestCitasApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
       try {
        $this->info("🔑 Obteniendo token...");
        $token = $apiService->getToken();
        $this->info("✅ Token: " . substr($token, 0, 20) . "...");

        $this->info("📅 Consultando citas de hoy...");
        $citas = $apiService->getCitasPorRango(today()->format('Y-m-d'), today()->format('Y-m-d'));
        
        $this->info("📊 Citas encontradas: " . count($citas));
        
        if (!empty($citas)) {
            $this->line("🔍 Primera cita:");
            $this->line(json_encode($citas[0], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        return 0;
    } catch (\Exception $e) {
        $this->error("❌ Error: " . $e->getMessage());
        return 1;
    }
    }
}
