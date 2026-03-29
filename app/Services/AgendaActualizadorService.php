<?php

namespace App\Services;

use App\AgendaCI;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AgendaActualizadorService
{
    /**
     * Actualiza el estado de llegada de una agenda específica
     *
     * @param AgendaCI $agenda
     * @return bool True si la cita tiene llegada confirmada
     */
    public function actualizarUno(AgendaCI $agenda)
    {
        $idRegistro = $agenda->id_registro;

        // Consultar solo este ID_REGISTRO específico en fac_m_citas
        $cita = DB::connection('sqlsrv1')
            ->table('fac_m_citas')
            ->where('ID_REGISTRO', $idRegistro)
            ->first();

        if (!$cita) {
            return false;
        }

        // Actualizar campos de llegada
        $llegadaConfirmada = !empty(trim($cita->NUMERO_FACTURA ?? ''));

        $agenda->update([
            'llegada_confirmada' => $llegadaConfirmada,
            'numero_factura'     => $llegadaConfirmada ? trim($cita->NUMERO_FACTURA) : null,
            'atencion_factura'   => !empty($cita->ATENCION_FACTURA) ? Carbon::parse($cita->ATENCION_FACTURA)->format('Y-m-d H:i:s') : null,
            'sincronizado_at'    => now(),
        ]);

        return $llegadaConfirmada;
    }

    /**
     * Actualiza todas las citas pendientes del día
     *
     * @return int Cantidad de citas actualizadas
     */
    public function actualizarPendientesDeHoy()
    {
        // Obtener IDs de registros pendientes de hoy
        $agendas = AgendaCI::whereDate('fecha', today())
            ->where('llegada_confirmada', false)
            ->get();

        if ($agendas->isEmpty()) {
            return 0;
        }

        $idsRegistros = $agendas->pluck('id_registro')->toArray();

        // Una sola query a fac_m_citas con whereIn
        $citas = DB::connection('sqlsrv1')
            ->table('fac_m_citas')
            ->whereIn('ID_REGISTRO', $idsRegistros)
            ->whereNotNull('NUMERO_FACTURA')
            ->get()
            ->keyBy('ID_REGISTRO');

        $actualizadas = 0;

        foreach ($agendas as $agenda) {
            $cita = $citas->get($agenda->id_registro);

            if ($cita && !empty(trim($cita->NUMERO_FACTURA ?? ''))) {
                $agenda->update([
                    'llegada_confirmada' => true,
                    'numero_factura'     => trim($cita->NUMERO_FACTURA),
                    'atencion_factura'   => !empty($cita->ATENCION_FACTURA) ? Carbon::parse($cita->ATENCION_FACTURA)->format('Y-m-d H:i:s') : null,
                    'sincronizado_at'    => now(),
                ]);

                $actualizadas++;
            }
        }

        return $actualizadas;
    }
}
