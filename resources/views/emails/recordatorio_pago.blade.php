<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
  body { font-family: Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 20px; }
  .card { background: #fff; border-radius: 8px; max-width: 520px; margin: 0 auto; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.12); }
  .header { padding: 24px; color: #fff; text-align: center; }
  .header.vencido  { background: #dc3545; }
  .header.proximo  { background: #fd7e14; }
  .header h2 { margin: 0; font-size: 22px; }
  .body { padding: 24px; }
  .row { display: flex; justify-content: space-between; border-bottom: 1px solid #eee; padding: 10px 0; }
  .label { color: #6c757d; font-size: 13px; }
  .value { font-weight: bold; font-size: 14px; }
  .footer { background: #f8f9fa; padding: 14px 24px; font-size: 12px; color: #aaa; text-align: center; }
  .badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: bold; color: #fff; }
  .badge.vencido { background: #dc3545; }
  .badge.proximo  { background: #fd7e14; }
</style>
</head>
<body>
<div class="card">
  <div class="header {{ $tipo }}">
    @if($tipo === 'vencido')
      <h2>⚠️ Pago Vencido</h2>
      <p style="margin:6px 0 0">Este pago no se ha registrado y ya venció.</p>
    @else
      <h2>🔔 Recordatorio de Pago</h2>
      <p style="margin:6px 0 0">Tu pago está próximo a vencer.</p>
    @endif
  </div>
  <div class="body">
    <div class="row">
      <span class="label">Factura</span>
      <span class="value">{{ $registro->factura->nombre }}</span>
    </div>
    @if($registro->factura->categoria)
    <div class="row">
      <span class="label">Categoría</span>
      <span class="value">{{ $registro->factura->categoria }}</span>
    </div>
    @endif
    <div class="row">
      <span class="label">Mes / Año</span>
      <span class="value">
        {{ \Carbon\Carbon::create($registro->anio, $registro->mes, 1)->translatedFormat('F Y') }}
      </span>
    </div>
    <div class="row">
      <span class="label">Fecha de vencimiento</span>
      <span class="value">
        {{ $registro->fechaVencimiento()->format('d/m/Y') }}
      </span>
    </div>
    @if($registro->factura->monto_estimado > 0)
    <div class="row">
      <span class="label">Monto estimado</span>
      <span class="value">$ {{ number_format($registro->factura->monto_estimado, 2, ',', '.') }}</span>
    </div>
    @endif
    <div class="row" style="border:none">
      <span class="label">Estado</span>
      <span class="badge {{ $tipo }}">{{ ucfirst($tipo) }}</span>
    </div>
  </div>
  <div class="footer">
    Este recordatorio fue generado automáticamente por el sistema de Agenda de Pagos.
  </div>
</div>
</body>
</html>
