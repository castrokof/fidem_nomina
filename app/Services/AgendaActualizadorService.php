<?php
// app/Services/AgendaActualizadorService.php

namespace App\Services;

use App\AgendaCI;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AgendaActualizadorService
{
    protected $apiService;

    public function __construct(CitasApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * Actualiza el estado de llegada de una agenda específica
     */
    public function actualizarUno(AgendaCI $agenda)
    {
        $idRegistro = $agenda->id_registro;

        try {
            // Consultar cita específica en la API
            $cita = $this->apiService->getCitaPorIdRegistro($idRegistro);

            if (!$cita) {
                Log::warning("Cita no encontrada en API: {$idRegistro}");
                return false;
            }

            $numeroFactura = trim(isset($cita['NUMERO_FACTURA']) ? $cita['NUMERO_FACTURA'] : '');
            $atencionFactura = isset($cita['ATENCION_FACTURA']) ? $cita['ATENCION_FACTURA'] : null;

            $llegadaConfirmada = !empty($numeroFactura);

            $agenda->update([
                'llegada_confirmada' => $llegadaConfirmada,
                'numero_factura'     => $llegadaConfirmada ? $numeroFactura : null,
                'atencion_factura'   => $this->parseFecha($atencionFactura),
                'sincronizado_at'    => now(),
            ]);

            Log::info("Cita {$idRegistro} actualizada: llegada=" . ($llegadaConfirmada ? 'SI' : 'NO'));

            return $llegadaConfirmada;

        } catch (\Exception $e) {
            Log::error("Error actualizando cita {$idRegistro}: " . $e->getMessage());
            return false;
        }
    }

   /**
 * Versión optimizada: Actualiza todas las citas pendientes del día en una sola llamada API
 */
public function actualizarPendientesDeHoy()
{
    $agendas = AgendaCI::whereDate('fecha', today())
        ->where('llegada_confirmada', false)
        ->get();

    if ($agendas->isEmpty()) {
        return 0;
    }

    $idsRegistros = $agendas->pluck('id_registro')->toArray();

    // Consultar todas las citas del día en una sola llamada
    $fechaHoy = today()->format('Y-m-d');
    $citasApi = $this->apiService->getCitasPorRango($fechaHoy, $fechaHoy);

    // Indexar por ID_REGISTRO para búsqueda rápida
    $citasMap = [];
    foreach ($citasApi as $cita) {
        if (isset($cita['ID_REGISTRO'])) {
            $citasMap[$cita['ID_REGISTRO']] = $cita;
        }
    }

    $actualizadas = 0;

    foreach ($agendas as $agenda) {
        $cita = isset($citasMap[$agenda->id_registro]) ? $citasMap[$agenda->id_registro] : null;

        if ($cita) {
            $numeroFactura = trim(isset($cita['NUMERO_FACTURA']) ? $cita['NUMERO_FACTURA'] : '');
            $llegadaConfirmada = !empty($numeroFactura);

            if ($llegadaConfirmada) {
                $agenda->update([
                    'llegada_confirmada' => true,
                    'numero_factura'     => $numeroFactura,
                    'atencion_factura'   => $this->parseFecha(isset($cita['ATENCION_FACTURA']) ? $cita['ATENCION_FACTURA'] : null),
                    'sincronizado_at'    => now(),
                ]);
                $actualizadas++;
            }
        }
    }

    return $actualizadas;
}

    /**
     * Parsea fecha con manejo de errores
     */
    protected function parseFecha($fecha)
    {
        if (empty($fecha)) {
            return null;
        }
        try {
            return Carbon::parse($fecha)->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            return null;
        }
    }
}