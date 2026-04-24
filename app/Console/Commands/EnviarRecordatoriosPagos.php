<?php

namespace App\Console\Commands;

use App\Mail\RecordatorioPagoMail;
use App\PagoFactura;
use App\PagoNotificacion;
use App\PagoRegistro;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class EnviarRecordatoriosPagos extends Command
{
    protected $signature   = 'pagos:recordatorios';
    protected $description = 'Envía recordatorios de pago próximo o vencido y actualiza estados';

    public function handle()
    {
        $hoy     = Carbon::today();
        $mes     = $hoy->month;
        $anio    = $hoy->year;
        $enviados = 0;

        $facturas = PagoFactura::where('activo', true)->get();

        foreach ($facturas as $factura) {
            $registro = PagoRegistro::firstOrCreate(
                ['factura_id' => $factura->id, 'mes' => $mes, 'anio' => $anio],
                ['estado' => 'pendiente']
            );

            if ($registro->estado === 'pagado') continue;

            $diasEnMes  = Carbon::create($anio, $mes, 1)->daysInMonth;
            $dia        = min($factura->dia_vencimiento, $diasEnMes);
            $vencimiento = Carbon::create($anio, $mes, $dia);

            $estaVencido = $hoy->gt($vencimiento);
            $esProximo   = !$estaVencido && $hoy->gte($vencimiento->copy()->subDays($factura->dias_aviso));

            // Marcar vencido en BD
            if ($estaVencido && $registro->estado !== 'vencido') {
                $registro->update(['estado' => 'vencido']);
            }

            $tipo = $estaVencido ? 'vencido' : ($esProximo ? 'proximo' : null);
            if (!$tipo) continue;

            // Crear notificación in-app si no existe para hoy
            $yaNotificado = PagoNotificacion::where('registro_id', $registro->id)
                ->where('tipo', $tipo)
                ->whereDate('created_at', $hoy)
                ->exists();

            if (!$yaNotificado) {
                PagoNotificacion::create([
                    'factura_id'  => $factura->id,
                    'registro_id' => $registro->id,
                    'tipo'        => $tipo,
                    'titulo'      => ($tipo === 'vencido' ? '⚠️ Pago vencido: ' : '🔔 Próximo vencimiento: ') . $factura->nombre,
                    'mensaje'     => 'El pago de ' . $factura->nombre . ' para ' .
                        Carbon::create($anio, $mes, 1)->translatedFormat('F Y') .
                        ' está ' . ($tipo === 'vencido' ? 'vencido.' : 'próximo a vencer el ' . $vencimiento->format('d/m/Y') . '.'),
                    'leido'       => false,
                ]);
            }

            // Enviar email si hay correo y no se envió hoy
            if ($factura->correo_notificacion && !$registro->notificacion_enviada) {
                try {
                    Mail::to($factura->correo_notificacion)
                        ->send(new RecordatorioPagoMail($registro->load('factura'), $tipo));
                    $registro->update(['notificacion_enviada' => true]);
                    $enviados++;
                } catch (\Exception $e) {
                    $this->error('Error enviando correo para ' . $factura->nombre . ': ' . $e->getMessage());
                }
            }
        }

        $this->info("Recordatorios procesados. Correos enviados: $enviados");
        return 0;
    }
}
