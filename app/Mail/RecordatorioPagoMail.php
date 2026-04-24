<?php

namespace App\Mail;

use App\PagoRegistro;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecordatorioPagoMail extends Mailable
{
    use Queueable, SerializesModels;

    public PagoRegistro $registro;
    public string $tipo; // 'proximo' | 'vencido'

    public function __construct(PagoRegistro $registro, string $tipo = 'proximo')
    {
        $this->registro = $registro;
        $this->tipo     = $tipo;
    }

    public function build()
    {
        $asunto = $this->tipo === 'vencido'
            ? '⚠️ Pago VENCIDO: ' . $this->registro->factura->nombre
            : '🔔 Recordatorio de pago: ' . $this->registro->factura->nombre;

        return $this->subject($asunto)
                    ->view('emails.recordatorio_pago');
    }
}
