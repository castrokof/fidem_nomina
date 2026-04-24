@extends("theme.$theme.layout")

@section('titulo') Agenda de Pagos @endsection

@section('styles')
<style>
/* ── Reset / base ─────────────────────────────────── */
:root {
    --ios-bg:      #f2f2f7;
    --ios-card:    #ffffff;
    --ios-border:  rgba(60,60,67,.18);
    --ios-radius:  14px;
    --ios-shadow:  0 2px 12px rgba(0,0,0,.08);
    --ios-blue:    #007aff;
    --ios-green:   #34c759;
    --ios-red:     #ff3b30;
    --ios-orange:  #ff9500;
    --ios-gray:    #8e8e93;
    --ios-gray2:   #aeaeb2;
    --ios-label:   #3a3a3c;
}

body { background: var(--ios-bg) !important; }

/* ── Tarjeta principal ──────────────────────────── */
.ios-card {
    background: var(--ios-card);
    border-radius: var(--ios-radius);
    box-shadow: var(--ios-shadow);
    border: none;
    overflow: hidden;
}

/* ── Toolbar ────────────────────────────────────── */
.toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    padding: 16px 20px;
    background: var(--ios-card);
    border-radius: var(--ios-radius);
    box-shadow: var(--ios-shadow);
    margin-bottom: 16px;
}
.year-nav { display:flex; align-items:center; gap:10px; }
.year-btn {
    width:32px; height:32px; border-radius:50%;
    border:none; background:var(--ios-bg);
    display:flex; align-items:center; justify-content:center;
    cursor:pointer; color:var(--ios-blue); font-size:14px;
    transition: background .15s;
}
.year-btn:hover { background:#e5e5ea; }
.year-label { font-size:20px; font-weight:700; color:var(--ios-label); min-width:52px; text-align:center; }

/* ── Botones iOS ────────────────────────────────── */
.btn-ios {
    border:none; border-radius:10px; font-size:13px;
    font-weight:600; padding:8px 16px; cursor:pointer;
    display:inline-flex; align-items:center; gap:6px;
    transition: opacity .15s, transform .1s;
}
.btn-ios:active { transform:scale(.96); }
.btn-ios-primary  { background:var(--ios-blue);   color:#fff; }
.btn-ios-danger   { background:var(--ios-red);    color:#fff; }
.btn-ios-success  { background:var(--ios-green);  color:#fff; }
.btn-ios-ghost    {
    background:rgba(0,122,255,.1); color:var(--ios-blue);
}
.btn-ios-sm { padding:5px 12px; font-size:12px; border-radius:8px; }

/* ── Leyenda ────────────────────────────────────── */
.leyenda {
    display:flex; flex-wrap:wrap; gap:8px;
    padding:12px 20px;
    background: var(--ios-card);
    border-radius: var(--ios-radius);
    box-shadow: var(--ios-shadow);
    margin-bottom: 16px;
    font-size:12px;
}
.ley-item { display:flex; align-items:center; gap:5px; }
.ley-dot { width:10px; height:10px; border-radius:50%; flex-shrink:0; }

/* ── Tabla calendario ───────────────────────────── */
.tabla-wrap { overflow-x:auto; border-radius: var(--ios-radius); box-shadow: var(--ios-shadow); }
#tablaCalendario { width:100%; border-collapse:separate; border-spacing:0; font-size:12px; background:#fff; }
#tablaCalendario thead th {
    background:#f2f2f7; color:var(--ios-gray);
    font-weight:600; font-size:11px; text-transform:uppercase; letter-spacing:.5px;
    padding:10px 6px; text-align:center; border-bottom:1px solid var(--ios-border);
    white-space:nowrap; position:sticky; top:0; z-index:2;
}
#tablaCalendario thead th.th-factura { text-align:left; padding-left:16px; min-width:180px; position:sticky; left:0; z-index:3; }
#tablaCalendario thead th.mes-actual { background:var(--ios-blue) !important; color:#fff !important; }

#tablaCalendario tbody tr { border-bottom:1px solid var(--ios-border); }
#tablaCalendario tbody tr:last-child { border-bottom:none; }
#tablaCalendario tbody tr:hover td { background:rgba(0,122,255,.03); }

.td-factura {
    position:sticky; left:0; z-index:1; background:#fff;
    padding:10px 12px 10px 16px; min-width:180px; border-right:1px solid var(--ios-border);
}
.td-factura .f-nombre { font-weight:700; font-size:13px; color:var(--ios-label); }
.td-factura .f-meta   { font-size:11px; color:var(--ios-gray); margin-top:2px; line-height:1.4; }
.td-factura .f-ref    { font-size:11px; color:var(--ios-blue); }
.td-factura .f-desc   { font-size:11px; color:var(--ios-gray2); margin-top:2px; font-style:italic; }

.td-mes { padding:4px 3px; text-align:center; width:60px; }

/* ── Celda de mes ───────────────────────────────── */
.celda {
    display:flex; flex-direction:column; align-items:center; justify-content:center;
    border-radius:10px; padding:6px 2px; min-height:48px;
    cursor:pointer; transition: transform .12s, box-shadow .12s;
    border:1.5px solid transparent;
}
.celda:hover  { transform:scale(1.06); box-shadow:0 4px 12px rgba(0,0,0,.12); }
.celda .c-dia { font-size:17px; font-weight:700; line-height:1; }
.celda .c-ico { font-size:10px; margin-top:2px; }

.c-pagado   { background:#e8faf0; border-color:#34c759; color:#1a7a35; }
.c-vencido  { background:#fff0ef; border-color:#ff3b30; color:#c0392b; }
.c-proximo  { background:#fff8e6; border-color:#ff9500; color:#8a5000; }
.c-pendiente{ background:#f2f2f7; border-color:#d1d1d6; color:var(--ios-gray); }
.c-futuro   { background:#fafafa; border-color:#e5e5ea; color:#c7c7cc; cursor:default; }

/* ── Fila categoría ─────────────────────────────── */
.tr-cat td {
    background:#f2f2f7; padding:6px 16px; font-size:10px;
    font-weight:700; text-transform:uppercase; letter-spacing:.8px; color:var(--ios-gray);
}

/* ── Acciones fila ──────────────────────────────── */
.td-acciones { padding:4px 10px; white-space:nowrap; text-align:center; }
.btn-fila {
    width:28px; height:28px; border-radius:8px; border:none;
    display:inline-flex; align-items:center; justify-content:center;
    font-size:12px; cursor:pointer; transition: opacity .15s;
}
.btn-fila:hover { opacity:.8; }
.btn-edit   { background:rgba(255,149,0,.15); color:#ff9500; }
.btn-delete { background:rgba(255,59,48,.12); color:#ff3b30; }

/* ── Notificaciones ─────────────────────────────── */
.notif-btn {
    position:relative; width:36px; height:36px; border-radius:50%;
    border:none; background:rgba(255,149,0,.12);
    display:flex; align-items:center; justify-content:center;
    color:#ff9500; cursor:pointer; font-size:16px;
}
.notif-badge {
    position:absolute; top:-2px; right:-2px;
    background:var(--ios-red); color:#fff; border-radius:50%;
    width:16px; height:16px; font-size:9px; font-weight:700;
    display:flex; align-items:center; justify-content:center; display:none;
}
#panelNotif {
    position:absolute; right:0; top:44px; z-index:1050;
    width:320px; max-height:400px; overflow-y:auto;
    background:#fff; border-radius:14px;
    box-shadow:0 8px 32px rgba(0,0,0,.18); display:none;
}
.notif-item { padding:10px 14px; border-bottom:1px solid #f2f2f7; font-size:12px; cursor:pointer; }
.notif-item:hover { background:#f9f9f9; }
.notif-item.vencido { border-left:3px solid var(--ios-red); }
.notif-item.proximo { border-left:3px solid var(--ios-orange); }

/* ── Modal iOS ──────────────────────────────────── */
.modal-content {
    border:none !important;
    border-radius:18px !important;
    box-shadow:0 20px 60px rgba(0,0,0,.25) !important;
    overflow:hidden;
}
.modal-header {
    background: linear-gradient(135deg, #007aff 0%, #5856d6 100%);
    border:none !important; padding:18px 22px;
}
.modal-header .modal-title { color:#fff; font-weight:700; font-size:17px; }
.modal-header .close { color:rgba(255,255,255,.8) !important; opacity:1 !important; font-size:22px; }

.ios-form-section {
    background:#f2f2f7; border-radius:12px; padding:4px 0; margin-bottom:14px;
}
.ios-field {
    display:flex; align-items:center; gap:10px;
    padding:11px 14px; background:#fff;
    border-bottom:1px solid rgba(60,60,67,.1);
}
.ios-field:first-child { border-radius:12px 12px 0 0; }
.ios-field:last-child  { border-radius:0 0 12px 12px; border-bottom:none; }
.ios-field:only-child  { border-radius:12px; border-bottom:none; }
.ios-field-icon { width:30px; height:30px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:14px; flex-shrink:0; }
.ios-field-body { flex:1; min-width:0; }
.ios-field-label { font-size:11px; color:var(--ios-gray); font-weight:600; margin-bottom:2px; }
.ios-field-body input,
.ios-field-body select,
.ios-field-body textarea {
    width:100%; border:none; outline:none; font-size:14px;
    color:var(--ios-label); background:transparent; padding:0;
    font-family:inherit;
}
.ios-field-body select { cursor:pointer; }
.ios-field-body textarea { resize:none; }

.ios-modal-footer {
    display:flex; gap:10px; justify-content:flex-end;
    padding:14px 22px 20px;
    border-top:1px solid rgba(60,60,67,.1);
}

/* Categoría inline ─── */
#nuevaCatRow {
    display:none; background:#fff;
    border-radius:0 0 12px 12px;
    border-top:1px dashed rgba(0,122,255,.3);
    padding:8px 14px; align-items:center; gap:8px;
}
#nuevaCatRow input { flex:1; border:1px solid #d1d1d6; border-radius:8px; padding:5px 10px; font-size:13px; }
</style>
@endsection

@section('scripts')
<script>
$(function() {
    var CSRF   = '{{ csrf_token() }}';
    var anio   = {{ $anio }};
    var mesHoy = {{ $hoy->month }};

    /* ── Notificaciones ──────────────────────────── */
    function cargarNotificaciones() {
        $.get('{{ route("pagos.notificaciones") }}', function(res) {
            var $b = $('#notifBadge');
            $b.text(res.total).toggle(res.total > 0);
            var $p = $('#panelNotif').empty();
            if (!res.items.length) {
                $p.append('<div class="p-3 text-center text-muted small">Sin notificaciones</div>');
                return;
            }
            $p.append('<div class="d-flex justify-content-between align-items-center px-3 py-2" style="border-bottom:1px solid #f2f2f7"><strong style="font-size:13px">Notificaciones</strong><a href="#" id="leerTodas" style="font-size:12px;color:#007aff">Marcar leídas</a></div>');
            $.each(res.items, function(i, n) {
                $p.append('<div class="notif-item ' + n.tipo + '" data-id="' + n.id + '"><div style="font-weight:600">' + n.titulo + '</div><div style="color:#8e8e93">' + n.mensaje + '</div><div style="font-size:10px;color:#aeaeb2;margin-top:3px">' + n.fecha + '</div></div>');
            });
        });
    }
    cargarNotificaciones();

    $('#btnNotif').click(function(e) { e.stopPropagation(); $('#panelNotif').toggle(); });
    $(document).click(function(e) { if (!$(e.target).closest('#btnNotif,#panelNotif').length) $('#panelNotif').hide(); });
    $(document).on('click','#leerTodas',function(e) {
        e.preventDefault();
        $.post('{{ route("pagos.notificaciones.leer","all") }}', {_token:CSRF}, function() { cargarNotificaciones(); $('#panelNotif').hide(); });
    });
    $(document).on('click','.notif-item',function() {
        var id = $(this).data('id');
        $.post('{{ route("pagos.notificaciones.leer","__id__") }}'.replace('__id__',id), {_token:CSRF}, cargarNotificaciones);
        $(this).fadeOut(150, function() { $(this).remove(); });
    });

    /* ── Año ─────────────────────────────────────── */
    $('#btnPrev').click(function() { location.href = '{{ route("pagos.index") }}?anio=' + (anio-1); });
    $('#btnNext').click(function() { location.href = '{{ route("pagos.index") }}?anio=' + (anio+1); });

    /* ── Modal factura ───────────────────────────── */
    function abrirModal(titulo) {
        $('#formFactura')[0].reset();
        $('#facturaId').val('');
        $('#modalFacturaLabel').text(titulo);
        $('#nuevaCatRow').hide();
        $('#modalFactura').modal({ backdrop:'static', keyboard:false });
        $('#modalFactura').modal('show');
    }

    $('#btnNuevaFactura').click(function() { abrirModal('Nueva Factura'); });

    $(document).on('click','.btn-editar-factura',function() {
        var f = $(this).data();
        abrirModal('Editar Factura');
        $('#facturaId').val(f.id);
        $('#fnombre').val(f.nombre);
        $('#fcategoria').val(f.categoria);
        $('#fdescripcion').val(f.descripcion);
        $('#freferencia').val(f.referencia);
        $('#fsede').val(f.sede);
        $('#fdia').val(f.dia);
        $('#fmonto').val(f.monto);
        $('#fcorreo').val(f.correo);
        $('#faviso').val(f.aviso);
    });

    /* ── Nueva categoría inline ──────────────────── */
    $('#btnNuevaCat').click(function() { $('#nuevaCatRow').toggle(); $('#inputNuevaCat').val('').focus(); });
    $('#btnGuardarCat').click(function() {
        var nom = $('#inputNuevaCat').val().trim();
        if (!nom) return;
        $.ajax({
            url: '{{ route("pagos.categorias.store") }}',
            method: 'POST',
            data: { _token: CSRF, nombre: nom },
            success: function(r) {
                $('#fcategoria').append('<option value="' + r.nombre + '">' + r.nombre + '</option>');
                $('#fcategoria').val(r.nombre);
                $('#nuevaCatRow').hide();
            },
            error: function(xhr) {
                var msg = xhr.responseJSON && xhr.responseJSON.errors && xhr.responseJSON.errors.nombre
                    ? xhr.responseJSON.errors.nombre[0] : 'Error al guardar';
                alert(msg);
            }
        });
    });
    $('#inputNuevaCat').keydown(function(e) { if (e.key === 'Enter') { e.preventDefault(); $('#btnGuardarCat').click(); } });

    /* ── Guardar factura ─────────────────────────── */
    $('#formFactura').submit(function(e) {
        e.preventDefault();
        var id  = $('#facturaId').val();
        var url = id
            ? '{{ route("pagos.facturas.update","__id__") }}'.replace('__id__', id)
            : '{{ route("pagos.facturas.store") }}';
        $.ajax({
            url: url, method: id ? 'PUT' : 'POST',
            data: $(this).serialize() + '&_token=' + CSRF,
            success: function(r) { if (r.success) { $('#modalFactura').modal('hide'); location.reload(); } },
            error: function(xhr) {
                var errs = xhr.responseJSON && xhr.responseJSON.errors ? xhr.responseJSON.errors : {};
                var msg  = Object.values(errs).length ? Object.values(errs).flat().join('\n') : 'Error al guardar.';
                Swal.fire({ title:'Error', text:msg, icon:'error', confirmButtonColor:'#007aff' });
            }
        });
    });

    /* ── Eliminar factura ────────────────────────── */
    $(document).on('click','.btn-eliminar-factura',function() {
        var id = $(this).data('id'), nom = $(this).data('nombre');
        Swal.fire({
            title: '¿Desactivar factura?',
            html: '<strong>' + nom + '</strong> dejará de aparecer en el calendario.',
            icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#ff3b30', cancelButtonColor: '#8e8e93',
            confirmButtonText: 'Desactivar', cancelButtonText: 'Cancelar'
        }).then(function(r) {
            if (!r.isConfirmed) return;
            $.ajax({
                url: '{{ route("pagos.facturas.destroy","__id__") }}'.replace('__id__', id),
                method: 'DELETE', data: { _token: CSRF },
                success: function(res) { if (res.success) location.reload(); }
            });
        });
    });

    /* ── Celdas de mes ───────────────────────────── */
    $(document).on('click','.celda',function() {
        var estado  = $(this).data('estado');
        var regId   = $(this).data('registro-id');
        var factura = $(this).data('factura');
        var mesNom  = $(this).data('mes-nom');
        var anioV   = $(this).data('anio');

        if (estado === 'futuro') return;

        if (estado === 'pagado') {
            Swal.fire({
                title: 'Revertir pago',
                html: '<strong>' + factura + '</strong> — ' + mesNom + ' ' + anioV,
                icon: 'question', showCancelButton: true,
                confirmButtonText: 'Revertir a pendiente', cancelButtonText: 'Cancelar',
                confirmButtonColor: '#ff9500'
            }).then(function(r) {
                if (!r.isConfirmed) return;
                $.ajax({
                    url: '{{ route("pagos.registros.revertir","__id__") }}'.replace('__id__', regId),
                    method: 'POST', data: { _token: CSRF },
                    success: function(res) { if (res.success) location.reload(); }
                });
            });
            return;
        }

        Swal.fire({
            title: 'Registrar pago',
            html: '<p style="margin-bottom:12px"><strong>' + factura + '</strong> — ' + mesNom + ' ' + anioV + '</p>'
                + '<div style="text-align:left">'
                + '<label style="font-size:12px;color:#8e8e93;font-weight:600">FECHA DE PAGO</label>'
                + '<input id="sw-fecha" type="date" class="swal2-input" value="{{ $hoy->format("Y-m-d") }}">'
                + '<label style="font-size:12px;color:#8e8e93;font-weight:600;margin-top:8px">MONTO PAGADO</label>'
                + '<input id="sw-monto" type="number" step="0.01" class="swal2-input" placeholder="0.00">'
                + '<label style="font-size:12px;color:#8e8e93;font-weight:600;margin-top:8px">NOTAS</label>'
                + '<textarea id="sw-notas" class="swal2-textarea" placeholder="N° recibo, comprobante..."></textarea>'
                + '</div>',
            showCancelButton: true,
            confirmButtonColor: '#34c759', cancelButtonColor: '#8e8e93',
            confirmButtonText: '✓ Registrar pago', cancelButtonText: 'Cancelar',
            preConfirm: function() {
                return {
                    fecha_pago:   document.getElementById('sw-fecha').value,
                    monto_pagado: document.getElementById('sw-monto').value,
                    notas:        document.getElementById('sw-notas').value
                };
            }
        }).then(function(r) {
            if (!r.isConfirmed) return;
            $.ajax({
                url: '{{ route("pagos.registros.pagar","__id__") }}'.replace('__id__', regId),
                method: 'POST', data: $.extend({ _token: CSRF }, r.value),
                success: function(res) { if (res.success) location.reload(); }
            });
        });
    });
});
</script>
@endsection

@section('contenido')
<div class="content-wrapper" style="min-height:543px; background:var(--ios-bg)">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0" style="color:var(--ios-label);font-weight:700">
            <i class="fas fa-calendar-check" style="color:var(--ios-blue)"></i> Agenda de Pagos
          </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right" style="background:transparent">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:var(--ios-blue)">Inicio</a></li>
            <li class="breadcrumb-item active">Agenda de Pagos</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      {{-- Toolbar --}}
      <div class="toolbar">
        <div class="year-nav">
          <button class="year-btn" id="btnPrev"><i class="fas fa-chevron-left"></i></button>
          <span class="year-label">{{ $anio }}</span>
          <button class="year-btn" id="btnNext"><i class="fas fa-chevron-right"></i></button>
          @if($anio != $hoy->year)
            <a href="{{ route('pagos.index') }}" style="font-size:12px;color:var(--ios-blue);margin-left:4px">Hoy</a>
          @endif
        </div>
        <div class="d-flex align-items-center" style="gap:10px">
          <div class="position-relative">
            <button class="notif-btn" id="btnNotif">
              <i class="fas fa-bell"></i>
              <span class="notif-badge" id="notifBadge">0</span>
            </button>
            <div id="panelNotif"></div>
          </div>
          <button class="btn-ios btn-ios-primary" id="btnNuevaFactura">
            <i class="fas fa-plus"></i> Nueva Factura
          </button>
        </div>
      </div>

      {{-- Leyenda --}}
      <div class="leyenda">
        <div class="ley-item"><div class="ley-dot" style="background:#34c759"></div> Pagado</div>
        <div class="ley-item"><div class="ley-dot" style="background:#ff3b30"></div> Vencido</div>
        <div class="ley-item"><div class="ley-dot" style="background:#ff9500"></div> Próximo</div>
        <div class="ley-item"><div class="ley-dot" style="background:#d1d1d6"></div> Pendiente</div>
        <div class="ley-item" style="margin-left:auto;color:var(--ios-gray)">
          <i class="fas fa-hand-pointer fa-xs"></i> Toca una celda para registrar el pago
        </div>
      </div>

      @if($facturas->isEmpty())
        <div style="background:#fff;border-radius:14px;padding:40px;text-align:center;box-shadow:0 2px 12px rgba(0,0,0,.08)">
          <i class="fas fa-calendar-plus fa-3x" style="color:#d1d1d6;margin-bottom:12px"></i>
          <p style="color:var(--ios-gray);font-size:15px;margin:0">
            No hay facturas activas.<br>
            <a href="#" id="btnNuevaFactura2" style="color:var(--ios-blue)">Agrega la primera</a>
          </p>
        </div>
      @else

      {{-- Tabla --}}
      <div class="tabla-wrap">
        <table id="tablaCalendario">
          <thead>
            <tr>
              <th class="th-factura">Factura</th>
              @foreach($meses as $i => $mes)
                @php $nm = $i + 1; @endphp
                <th class="{{ $nm === $hoy->month && $anio === $hoy->year ? 'mes-actual' : '' }}">
                  {{ $mes }}
                </th>
              @endforeach
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            @php $porCat = $facturas->groupBy('categoria'); @endphp

            @foreach($porCat as $cat => $grupo)
              @if($cat)
              <tr class="tr-cat">
                <td colspan="{{ count($meses) + 2 }}">
                  <i class="fas fa-tag fa-xs"></i> {{ $cat }}
                </td>
              </tr>
              @endif

              @foreach($grupo as $factura)
              <tr>
                <td class="td-factura">
                  <div class="f-nombre">{{ $factura->nombre }}</div>
                  @if($factura->sede)
                    <div class="f-meta"><i class="fas fa-map-marker-alt fa-xs"></i> {{ $factura->sede }}</div>
                  @endif
                  @if($factura->referencia)
                    <div class="f-ref"><i class="fas fa-hashtag fa-xs"></i> {{ $factura->referencia }}</div>
                  @endif
                  <div class="f-meta">
                    Día {{ $factura->dia_vencimiento }}
                    @if($factura->monto_estimado > 0)
                      · ${{ number_format($factura->monto_estimado, 0, ',', '.') }}
                    @endif
                  </div>
                  @if($factura->descripcion)
                    <div class="f-desc" title="{{ e($factura->descripcion) }}">
                      {{ Str::limit($factura->descripcion, 45) }}
                    </div>
                  @endif
                </td>

                @foreach($meses as $i => $mesNom)
                  @php
                    $nm  = $i + 1;
                    $reg = isset($registros[$factura->id][$nm]) ? $registros[$factura->id][$nm] : null;
                    $esFuturo = ($anio > $hoy->year) || ($anio === $hoy->year && $nm > $hoy->month);
                    $ec = $reg ? $reg->estadoCalc() : 'pendiente';
                    if ($esFuturo && $ec !== 'pagado') $ec = 'futuro';

                    if ($ec === 'pagado')       { $ico = '<i class="fas fa-check c-ico"></i>'; }
                    elseif ($ec === 'vencido')  { $ico = '<i class="fas fa-exclamation c-ico"></i>'; }
                    elseif ($ec === 'proximo')  { $ico = '<i class="fas fa-bell c-ico"></i>'; }
                    else                        { $ico = ''; }

                    if ($ec === 'pagado')       { $tip = 'Pagado' . ($reg && $reg->fecha_pago ? ' el '.$reg->fecha_pago->format('d/m/Y') : ''); }
                    elseif ($ec === 'vencido')  { $tip = 'VENCIDO'; }
                    elseif ($ec === 'proximo')  { $tip = 'Próximo a vencer'; }
                    elseif ($ec === 'futuro')   { $tip = ''; }
                    else                        { $tip = 'Pendiente'; }
                  @endphp
                  <td class="td-mes">
                    @if($reg)
                    <div class="celda c-{{ $ec }}"
                         data-registro-id="{{ $reg->id }}"
                         data-estado="{{ $ec }}"
                         data-factura="{{ e($factura->nombre) }}"
                         data-mes-nom="{{ $mesNom }}"
                         data-anio="{{ $anio }}"
                         title="{{ $tip }}">
                      <span class="c-dia">{{ $factura->dia_vencimiento }}</span>
                      {!! $ico !!}
                    </div>
                    @else
                    <div class="celda c-futuro"><span class="c-dia">—</span></div>
                    @endif
                  </td>
                @endforeach

                <td class="td-acciones">
                  <button class="btn-fila btn-edit btn-editar-factura"
                          data-id="{{ $factura->id }}"
                          data-nombre="{{ e($factura->nombre) }}"
                          data-categoria="{{ e($factura->categoria) }}"
                          data-descripcion="{{ e($factura->descripcion) }}"
                          data-referencia="{{ e($factura->referencia) }}"
                          data-sede="{{ e($factura->sede) }}"
                          data-dia="{{ $factura->dia_vencimiento }}"
                          data-monto="{{ $factura->monto_estimado }}"
                          data-correo="{{ e($factura->correo_notificacion) }}"
                          data-aviso="{{ $factura->dias_aviso }}"
                          title="Editar">
                    <i class="fas fa-pen"></i>
                  </button>
                  <button class="btn-fila btn-delete btn-eliminar-factura"
                          data-id="{{ $factura->id }}"
                          data-nombre="{{ e($factura->nombre) }}"
                          title="Desactivar">
                    <i class="fas fa-trash"></i>
                  </button>
                </td>
              </tr>
              @endforeach
            @endforeach
          </tbody>
        </table>
      </div>
      @endif

    </div>
  </section>
</div>

{{-- Modal Factura --}}
<div class="modal fade" id="modalFactura" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalFacturaLabel">Nueva Factura</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <form id="formFactura">
        <input type="hidden" id="facturaId">
        <div class="modal-body" style="padding:20px 22px">

          {{-- Sección: Identificación --}}
          <div style="font-size:11px;color:var(--ios-gray);font-weight:700;text-transform:uppercase;letter-spacing:.6px;margin-bottom:6px">Identificación</div>
          <div class="ios-form-section">
            <div class="ios-field">
              <div class="ios-field-icon" style="background:rgba(0,122,255,.12);color:var(--ios-blue)"><i class="fas fa-file-invoice"></i></div>
              <div class="ios-field-body">
                <div class="ios-field-label">Nombre de la factura *</div>
                <input id="fnombre" name="nombre" type="text" placeholder="Ej: Movistar, Tigo, Luz…" required>
              </div>
            </div>
            <div class="ios-field">
              <div class="ios-field-icon" style="background:rgba(88,86,214,.12);color:#5856d6"><i class="fas fa-tag"></i></div>
              <div class="ios-field-body">
                <div class="ios-field-label">Categoría</div>
                <div style="display:flex;align-items:center;gap:8px">
                  <select id="fcategoria" name="categoria" style="flex:1">
                    <option value="">Sin categoría</option>
                    @foreach($categorias as $cat)
                      <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                  </select>
                  <button type="button" id="btnNuevaCat" style="border:none;background:rgba(0,122,255,.1);color:var(--ios-blue);border-radius:6px;padding:3px 8px;font-size:12px;cursor:pointer" title="Agregar categoría">
                    <i class="fas fa-plus"></i>
                  </button>
                </div>
              </div>
            </div>
            <div id="nuevaCatRow" style="display:none;padding:8px 14px;border-top:1px dashed rgba(0,122,255,.3);align-items:center;gap:8px;background:#fff">
              <input id="inputNuevaCat" type="text" placeholder="Nueva categoría…" style="flex:1;border:1px solid #d1d1d6;border-radius:8px;padding:5px 10px;font-size:13px">
              <button type="button" id="btnGuardarCat" class="btn-ios btn-ios-primary btn-ios-sm">Agregar</button>
            </div>
            <div class="ios-field">
              <div class="ios-field-icon" style="background:rgba(52,199,89,.12);color:var(--ios-green)"><i class="fas fa-map-marker-alt"></i></div>
              <div class="ios-field-body">
                <div class="ios-field-label">Sede / Ubicación</div>
                <input id="fsede" name="sede" type="text" placeholder="Ej: Sede Norte, Oficina Central…">
              </div>
            </div>
          </div>

          {{-- Sección: Referencia y descripción --}}
          <div style="font-size:11px;color:var(--ios-gray);font-weight:700;text-transform:uppercase;letter-spacing:.6px;margin-bottom:6px">Referencia</div>
          <div class="ios-form-section">
            <div class="ios-field">
              <div class="ios-field-icon" style="background:rgba(255,149,0,.12);color:var(--ios-orange)"><i class="fas fa-hashtag"></i></div>
              <div class="ios-field-body">
                <div class="ios-field-label">N° Línea / Contrato / Referencia</div>
                <input id="freferencia" name="referencia" type="text" placeholder="Ej: 573001234567">
              </div>
            </div>
            <div class="ios-field">
              <div class="ios-field-icon" style="background:rgba(142,142,147,.12);color:var(--ios-gray)"><i class="fas fa-align-left"></i></div>
              <div class="ios-field-body">
                <div class="ios-field-label">Descripción</div>
                <textarea id="fdescripcion" name="descripcion" rows="2" placeholder="Descripción u observaciones…"></textarea>
              </div>
            </div>
          </div>

          {{-- Sección: Pago --}}
          <div style="font-size:11px;color:var(--ios-gray);font-weight:700;text-transform:uppercase;letter-spacing:.6px;margin-bottom:6px">Datos de Pago</div>
          <div class="ios-form-section">
            <div class="ios-field">
              <div class="ios-field-icon" style="background:rgba(255,59,48,.12);color:var(--ios-red)"><i class="fas fa-calendar-day"></i></div>
              <div class="ios-field-body">
                <div class="ios-field-label">Día de vencimiento (1–31) *</div>
                <input id="fdia" name="dia_vencimiento" type="number" min="1" max="31" placeholder="Ej: 5">
              </div>
            </div>
            <div class="ios-field">
              <div class="ios-field-icon" style="background:rgba(52,199,89,.12);color:var(--ios-green)"><i class="fas fa-dollar-sign"></i></div>
              <div class="ios-field-body">
                <div class="ios-field-label">Monto estimado</div>
                <input id="fmonto" name="monto_estimado" type="number" step="0.01" min="0" placeholder="0.00">
              </div>
            </div>
          </div>

          {{-- Sección: Recordatorio --}}
          <div style="font-size:11px;color:var(--ios-gray);font-weight:700;text-transform:uppercase;letter-spacing:.6px;margin-bottom:6px">Recordatorio</div>
          <div class="ios-form-section">
            <div class="ios-field">
              <div class="ios-field-icon" style="background:rgba(255,149,0,.12);color:var(--ios-orange)"><i class="fas fa-envelope"></i></div>
              <div class="ios-field-body">
                <div class="ios-field-label">Correos (separados por coma)</div>
                <input id="fcorreo" name="correo_notificacion" type="text" placeholder="a@mail.com, b@mail.com">
              </div>
            </div>
            <div class="ios-field">
              <div class="ios-field-icon" style="background:rgba(88,86,214,.12);color:#5856d6"><i class="fas fa-bell"></i></div>
              <div class="ios-field-body">
                <div class="ios-field-label">Avisar con (días de anticipación)</div>
                <input id="faviso" name="dias_aviso" type="number" min="1" max="30" value="3">
              </div>
            </div>
          </div>

        </div>
        <div class="ios-modal-footer">
          <button type="button" class="btn-ios btn-ios-ghost" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn-ios btn-ios-primary"><i class="fas fa-save"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
