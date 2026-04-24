@extends("theme.$theme.layout")

@section('titulo')
    Agenda de Pagos
@endsection

@section('styles')
<style>
/* ── Tabla calendario ──────────────────────────────────── */
#tablaCalendario { font-size: 13px; }
#tablaCalendario th { text-align: center; white-space: nowrap; }
#tablaCalendario td { vertical-align: middle; }

.col-factura { min-width: 160px; }
.col-mes     { width: 72px; text-align: center; }

/* Celdas de mes */
.celda-mes {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 2px;
    min-height: 46px;
    border-radius: 6px;
    padding: 4px 2px;
    cursor: pointer;
    transition: opacity .15s;
}
.celda-mes:hover { opacity: .85; }
.celda-mes .dia  { font-size: 16px; font-weight: 700; line-height: 1; }
.celda-mes .ico  { font-size: 11px; }

/* Estados */
.estado-pendiente { background: #f8f9fa; color: #6c757d; border: 1px dashed #ced4da; }
.estado-proximo   { background: #fff3cd; color: #856404; border: 1px solid #ffc107; }
.estado-vencido   { background: #f8d7da; color: #842029; border: 1px solid #dc3545; }
.estado-pagado    { background: #d1e7dd; color: #0a3622; border: 1px solid #198754; }
.estado-futuro    { background: #f8f9fa; color: #dee2e6; border: 1px dashed #dee2e6; cursor: default; }

/* Mes actual: cabecera resaltada */
.mes-actual th.mes-col { background: #0d6efd !important; color: #fff !important; }

/* Notif badge */
.notif-badge {
    position: absolute; top: -4px; right: -4px;
    background: #dc3545; color: #fff;
    border-radius: 50%; width: 18px; height: 18px;
    font-size: 10px; display: flex; align-items: center; justify-content: center;
    font-weight: 700;
}
.btn-notif { position: relative; }

/* Panel notificaciones */
#panelNotif {
    position: absolute; right: 0; top: 42px; z-index: 1050;
    width: 340px; max-height: 420px; overflow-y: auto;
    background: #fff; border: 1px solid #dee2e6;
    border-radius: 8px; box-shadow: 0 4px 16px rgba(0,0,0,.15);
    display: none;
}
#panelNotif .notif-item {
    padding: 10px 14px; border-bottom: 1px solid #f0f0f0;
    font-size: 12px; cursor: pointer;
}
#panelNotif .notif-item:hover { background: #f8f9fa; }
#panelNotif .notif-item.vencido { border-left: 3px solid #dc3545; }
#panelNotif .notif-item.proximo { border-left: 3px solid #fd7e14; }
</style>
@endsection

@section('scripts')
<script>
$(function() {

    const CSRF  = '{{ csrf_token() }}';
    const anio  = {{ $anio }};
    const mesHoy = {{ $hoy->month }};

    // ── Notificaciones ───────────────────────────────────────────────────────
    function cargarNotificaciones() {
        $.get('{{ route("pagos.notificaciones") }}', function(res) {
            const $badge = $('#notifBadge');
            const $panel = $('#panelNotif');
            $badge.text(res.total).toggle(res.total > 0);

            $panel.empty();
            if (!res.items.length) {
                $panel.append('<div class="p-3 text-center text-muted small">Sin notificaciones pendientes</div>');
            } else {
                $panel.append(`<div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                    <strong class="small">Notificaciones (${res.total})</strong>
                    <a href="#" id="leerTodas" class="small text-primary">Marcar todas leídas</a>
                </div>`);
                res.items.forEach(function(n) {
                    $panel.append(`
                        <div class="notif-item ${n.tipo}" data-id="${n.id}">
                            <div class="font-weight-bold">${n.titulo}</div>
                            <div class="text-muted">${n.mensaje}</div>
                            <div class="text-muted mt-1" style="font-size:10px">${n.fecha}</div>
                        </div>`);
                });
            }
        });
    }

    cargarNotificaciones();

    $(document).on('click', '#btnNotif', function(e) {
        e.stopPropagation();
        $('#panelNotif').toggle();
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('#btnNotif, #panelNotif').length) $('#panelNotif').hide();
    });

    $(document).on('click', '#leerTodas', function(e) {
        e.preventDefault();
        $.post('{{ route("pagos.notificaciones.leer", "all") }}', { _token: CSRF }, function() {
            cargarNotificaciones();
            $('#panelNotif').hide();
        });
    });

    $(document).on('click', '#panelNotif .notif-item', function() {
        const id = $(this).data('id');
        $.post('{{ route("pagos.notificaciones.leer", "__id__") }}'.replace('__id__', id), { _token: CSRF }, cargarNotificaciones);
        $(this).fadeOut(200, function() { $(this).remove(); });
    });

    // ── Modal Nueva / Editar factura ─────────────────────────────────────────
    $('#btnNuevaFactura').click(function() {
        $('#formFactura')[0].reset();
        $('#facturaId').val('');
        $('#modalFacturaLabel').text('Nueva Factura');
        $('#modalFactura').modal('show');
    });

    $(document).on('click', '.btn-editar-factura', function() {
        const f = $(this).data();
        $('#facturaId').val(f.id);
        $('#fnombre').val(f.nombre);
        $('#fcategoria').val(f.categoria);
        $('#fdescripcion').val(f.descripcion);
        $('#fdia').val(f.dia);
        $('#fmonto').val(f.monto);
        $('#fcorreo').val(f.correo);
        $('#faviso').val(f.aviso);
        $('#modalFacturaLabel').text('Editar Factura');
        $('#modalFactura').modal('show');
    });

    $('#formFactura').submit(function(e) {
        e.preventDefault();
        const id  = $('#facturaId').val();
        const url = id
            ? '{{ route("pagos.facturas.update", "__id__") }}'.replace('__id__', id)
            : '{{ route("pagos.facturas.store") }}';
        const method = id ? 'PUT' : 'POST';

        $.ajax({ url, method, data: $(this).serialize() + '&_token=' + CSRF,
            success: function(r) {
                if (r.success) { $('#modalFactura').modal('hide'); location.reload(); }
            },
            error: function(xhr) {
                const errs = xhr.responseJSON?.errors;
                let msg = errs ? Object.values(errs).flat().join('\n') : 'Error al guardar.';
                Swal.fire('Error', msg, 'error');
            }
        });
    });

    // ── Eliminar factura ─────────────────────────────────────────────────────
    $(document).on('click', '.btn-eliminar-factura', function() {
        const id   = $(this).data('id');
        const nom  = $(this).data('nombre');
        Swal.fire({
            title: '¿Desactivar factura?',
            html: `La factura <strong>${nom}</strong> no aparecerá más en el calendario.<br>Los registros históricos se conservan.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Sí, desactivar',
            cancelButtonText: 'Cancelar'
        }).then(function(r) {
            if (!r.isConfirmed) return;
            $.ajax({
                url: '{{ route("pagos.facturas.destroy", "__id__") }}'.replace('__id__', id),
                method: 'DELETE',
                data: { _token: CSRF },
                success: function(res) {
                    if (res.success) location.reload();
                }
            });
        });
    });

    // ── Marcar pago desde celda ──────────────────────────────────────────────
    $(document).on('click', '.celda-mes', function() {
        const estado     = $(this).data('estado');
        const registroId = $(this).data('registro-id');
        const factura    = $(this).data('factura');
        const mesNom     = $(this).data('mes-nom');
        const anioVal    = $(this).data('anio');

        if (estado === 'pagado') {
            // Revertir
            Swal.fire({
                title: 'Revertir pago',
                html: `¿Marcar <strong>${factura} – ${mesNom} ${anioVal}</strong> como <em>pendiente</em> de nuevo?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, revertir',
                cancelButtonText: 'Cancelar'
            }).then(function(r) {
                if (!r.isConfirmed) return;
                $.ajax({
                    url: '{{ route("pagos.registros.revertir", "__id__") }}'.replace('__id__', registroId),
                    method: 'POST',
                    data: { _token: CSRF },
                    success: function(res) { if (res.success) location.reload(); }
                });
            });
            return;
        }

        // Marcar como pagado
        Swal.fire({
            title: `Registrar pago`,
            html: `<p class="mb-2"><strong>${factura}</strong> — ${mesNom} ${anioVal}</p>
                   <div class="form-group text-left">
                     <label class="small">Fecha de pago</label>
                     <input id="swal-fecha" type="date" class="swal2-input" value="{{ $hoy->format('Y-m-d') }}">
                   </div>
                   <div class="form-group text-left">
                     <label class="small">Monto pagado</label>
                     <input id="swal-monto" type="number" step="0.01" class="swal2-input" placeholder="0.00">
                   </div>
                   <div class="form-group text-left">
                     <label class="small">Notas (opcional)</label>
                     <textarea id="swal-notas" class="swal2-textarea" placeholder="Número de recibo, observaciones..."></textarea>
                   </div>`,
            showCancelButton: true,
            confirmButtonColor: '#198754',
            confirmButtonText: '<i class="fas fa-check"></i> Registrar pago',
            cancelButtonText: 'Cancelar',
            preConfirm: () => ({
                fecha_pago:   document.getElementById('swal-fecha').value,
                monto_pagado: document.getElementById('swal-monto').value,
                notas:        document.getElementById('swal-notas').value,
            })
        }).then(function(r) {
            if (!r.isConfirmed) return;
            $.ajax({
                url: '{{ route("pagos.registros.pagar", "__id__") }}'.replace('__id__', registroId),
                method: 'POST',
                data: { _token: CSRF, ...r.value },
                success: function(res) { if (res.success) location.reload(); }
            });
        });
    });

    // ── Cambiar año ──────────────────────────────────────────────────────────
    $('#btnAnioAnterior').click(function() {
        window.location = '{{ route("pagos.index") }}?anio=' + (anio - 1);
    });
    $('#btnAnioSiguiente').click(function() {
        window.location = '{{ route("pagos.index") }}?anio=' + (anio + 1);
    });

});
</script>
@endsection

@section('contenido')
<div class="content-wrapper" style="min-height:543px">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark"><i class="fas fa-calendar-check"></i> Agenda de Pagos</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Agenda de Pagos</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      {{-- Barra de herramientas --}}
      <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap" style="gap:8px">
        {{-- Selector de año --}}
        <div class="d-flex align-items-center" style="gap:8px">
          <button id="btnAnioAnterior" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-chevron-left"></i>
          </button>
          <span class="h5 mb-0 font-weight-bold">{{ $anio }}</span>
          <button id="btnAnioSiguiente" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-chevron-right"></i>
          </button>
          @if($anio != $hoy->year)
            <a href="{{ route('pagos.index') }}" class="btn btn-link btn-sm text-muted">Volver a {{ $hoy->year }}</a>
          @endif
        </div>

        {{-- Acciones --}}
        <div class="d-flex align-items-center" style="gap:8px">
          {{-- Notificaciones --}}
          <div class="position-relative">
            <button id="btnNotif" class="btn btn-outline-warning btn-sm btn-notif">
              <i class="fas fa-bell"></i>
              <span id="notifBadge" class="notif-badge" style="display:none">0</span>
            </button>
            <div id="panelNotif"></div>
          </div>

          <button id="btnNuevaFactura" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Nueva Factura
          </button>
        </div>
      </div>

      {{-- Leyenda --}}
      <div class="mb-3 d-flex flex-wrap" style="gap:10px; font-size:12px">
        <span><span class="badge" style="background:#d1e7dd;color:#0a3622;border:1px solid #198754">✓ Pagado</span></span>
        <span><span class="badge" style="background:#f8d7da;color:#842029;border:1px solid #dc3545">⚠ Vencido</span></span>
        <span><span class="badge" style="background:#fff3cd;color:#856404;border:1px solid #ffc107">🔔 Próximo</span></span>
        <span><span class="badge" style="background:#f8f9fa;color:#6c757d;border:1px dashed #ced4da">Pendiente</span></span>
        <span class="text-muted">— Haz clic en una celda para registrar o revertir el pago</span>
      </div>

      @if($facturas->isEmpty())
        <div class="alert alert-info">
          <i class="fas fa-info-circle mr-1"></i>
          No hay facturas activas. Haz clic en <strong>Nueva Factura</strong> para agregar la primera.
        </div>
      @else
      {{-- Tabla calendario --}}
      <div class="card">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table id="tablaCalendario" class="table table-bordered table-hover mb-0">
              <thead class="thead-dark">
                <tr>
                  <th class="col-factura">Factura</th>
                  @foreach($meses as $i => $mes)
                    @php $numMes = $i + 1; @endphp
                    <th class="col-mes mes-col {{ $numMes === $hoy->month && $anio === $hoy->year ? 'bg-primary text-white' : '' }}">
                      {{ $mes }}
                    </th>
                  @endforeach
                  <th style="width:90px">Acciones</th>
                </tr>
              </thead>
              <tbody>
                @php
                  // Agrupar por categoría
                  $porCategoria = $facturas->groupBy('categoria');
                @endphp

                @foreach($porCategoria as $cat => $grupoFacturas)
                  @if($cat)
                  <tr class="table-secondary">
                    <td colspan="{{ count($meses) + 2 }}" class="py-1 px-3">
                      <small class="font-weight-bold text-uppercase text-muted">
                        <i class="fas fa-tag mr-1"></i>{{ $cat }}
                      </small>
                    </td>
                  </tr>
                  @endif

                  @foreach($grupoFacturas as $factura)
                  <tr>
                    {{-- Nombre de factura --}}
                    <td class="col-factura">
                      <div class="font-weight-bold">{{ $factura->nombre }}</div>
                      @if($factura->monto_estimado > 0)
                        <small class="text-muted">
                          $ {{ number_format($factura->monto_estimado, 0, ',', '.') }}
                          · día {{ $factura->dia_vencimiento }}
                        </small>
                      @else
                        <small class="text-muted">Día {{ $factura->dia_vencimiento }}</small>
                      @endif
                    </td>

                    {{-- Celda por mes --}}
                    @foreach($meses as $i => $mesNom)
                      @php
                        $numMes  = $i + 1;
                        $reg     = $registros[$factura->id][$numMes] ?? null;
                        $esFuturo = ($anio > $hoy->year) || ($anio === $hoy->year && $numMes > $hoy->month);
                        $estadoCalc = $reg ? $reg->estadoCalc() : 'pendiente';
                        if ($esFuturo && $estadoCalc !== 'pagado') $estadoCalc = 'futuro';

                        $claseEstado = "estado-{$estadoCalc}";
                        $icono = match($estadoCalc) {
                            'pagado'    => '<i class="fas fa-check ico"></i>',
                            'vencido'   => '<i class="fas fa-exclamation-triangle ico"></i>',
                            'proximo'   => '<i class="fas fa-bell ico"></i>',
                            'futuro'    => '',
                            default     => '',
                        };
                        $titulo = match($estadoCalc) {
                            'pagado'  => 'Pagado' . ($reg->fecha_pago ? ' el ' . $reg->fecha_pago->format('d/m/Y') : ''),
                            'vencido' => 'VENCIDO — sin registrar',
                            'proximo' => 'Próximo a vencer',
                            'futuro'  => 'Mes futuro',
                            default   => 'Pendiente',
                        };
                      @endphp
                      <td class="col-mes p-1">
                        @if($reg)
                        <div class="celda-mes {{ $claseEstado }}"
                             data-registro-id="{{ $reg->id }}"
                             data-estado="{{ $estadoCalc }}"
                             data-factura="{{ e($factura->nombre) }}"
                             data-mes-nom="{{ $mesNom }}"
                             data-anio="{{ $anio }}"
                             title="{{ $titulo }}">
                          <span class="dia">{{ $factura->dia_vencimiento }}</span>
                          {!! $icono !!}
                        </div>
                        @else
                        <div class="celda-mes estado-futuro" title="Sin registro">
                          <span class="dia text-muted" style="font-size:12px">—</span>
                        </div>
                        @endif
                      </td>
                    @endforeach

                    {{-- Acciones de la factura --}}
                    <td class="text-center" style="white-space:nowrap">
                      <button class="btn btn-warning btn-xs btn-editar-factura"
                              title="Editar"
                              data-id="{{ $factura->id }}"
                              data-nombre="{{ e($factura->nombre) }}"
                              data-categoria="{{ e($factura->categoria) }}"
                              data-descripcion="{{ e($factura->descripcion) }}"
                              data-dia="{{ $factura->dia_vencimiento }}"
                              data-monto="{{ $factura->monto_estimado }}"
                              data-correo="{{ e($factura->correo_notificacion) }}"
                              data-aviso="{{ $factura->dias_aviso }}">
                        <i class="fas fa-edit"></i>
                      </button>
                      <button class="btn btn-danger btn-xs btn-eliminar-factura"
                              title="Desactivar"
                              data-id="{{ $factura->id }}"
                              data-nombre="{{ e($factura->nombre) }}">
                        <i class="fas fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                  @endforeach
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      @endif

    </div>
  </section>
</div>

{{-- ── Modal: Nueva / Editar Factura ──────────────────────────────────────── --}}
<div class="modal fade" id="modalFactura" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalFacturaLabel">Nueva Factura</h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <form id="formFactura">
        @csrf
        <input type="hidden" id="facturaId">
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group col-md-8">
              <label class="small font-weight-bold">Nombre de la factura <span class="text-danger">*</span></label>
              <input id="fnombre" name="nombre" type="text" class="form-control form-control-sm"
                     placeholder="Ej: Movistar, Tigo, Luz, Internet…" required>
            </div>
            <div class="form-group col-md-4">
              <label class="small font-weight-bold">Categoría</label>
              <input id="fcategoria" name="categoria" type="text" class="form-control form-control-sm"
                     placeholder="Ej: Servicios, Telefonía…" list="listaCategorias">
              <datalist id="listaCategorias">
                @foreach($facturas->pluck('categoria')->filter()->unique() as $cat)
                  <option value="{{ $cat }}">
                @endforeach
              </datalist>
            </div>
          </div>
          <div class="form-group">
            <label class="small font-weight-bold">Descripción</label>
            <textarea id="fdescripcion" name="descripcion" class="form-control form-control-sm" rows="2"
                      placeholder="Descripción opcional…"></textarea>
          </div>
          <div class="form-row">
            <div class="form-group col-md-4">
              <label class="small font-weight-bold">Día de vencimiento <span class="text-danger">*</span></label>
              <input id="fdia" name="dia_vencimiento" type="number" min="1" max="31"
                     class="form-control form-control-sm" placeholder="1–31" required>
              <small class="text-muted">Día del mes en que vence</small>
            </div>
            <div class="form-group col-md-4">
              <label class="small font-weight-bold">Monto estimado</label>
              <div class="input-group input-group-sm">
                <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                <input id="fmonto" name="monto_estimado" type="number" step="0.01" min="0"
                       class="form-control" placeholder="0.00">
              </div>
            </div>
            <div class="form-group col-md-4">
              <label class="small font-weight-bold">Avisar con (días)</label>
              <input id="faviso" name="dias_aviso" type="number" min="1" max="30"
                     class="form-control form-control-sm" value="3">
              <small class="text-muted">Días antes del vencimiento</small>
            </div>
          </div>
          <div class="form-group">
            <label class="small font-weight-bold">Correo para recordatorios</label>
            <input id="fcorreo" name="correo_notificacion" type="email"
                   class="form-control form-control-sm"
                   placeholder="correo@ejemplo.com (opcional)">
            <small class="text-muted">Si se configura, recibirá emails automáticos de recordatorio.</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary btn-sm">
            <i class="fas fa-save"></i> Guardar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
