@extends("theme.$theme.layout")

@section('titulo') Pacientes Boxalud @endsection

@section('styles')
<link href="{{ asset("assets/$theme/plugins/datatables-bs4/css/dataTables.bootstrap4.css") }}" rel="stylesheet">
<style>
/* ── Stats cards ──────────────────────────────────────────────────── */
.stat-card { border:none; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,.08); transition:transform .2s; }
.stat-card:hover { transform:translateY(-3px); }
.stat-icon { font-size:2.4rem; opacity:.85; }

/* ── Filter panel ─────────────────────────────────────────────────── */
.filter-card { border:1px solid #dee2e6; border-radius:10px; background:#f8f9fa; }
.filter-card .form-control, .filter-card .form-select { font-size:.85rem; }

/* ── Badge helpers ────────────────────────────────────────────────── */
.badge-vigente    { background:#28a745; color:#fff; }
.badge-no-vigente { background:#dc3545; color:#fff; }
.badge-pagos-ok   { background:#17a2b8; color:#fff; }
.badge-pagos-pend { background:#ffc107; color:#212529; }

/* ── Timeline ─────────────────────────────────────────────────────── */
.timeline { position:relative; padding:0; list-style:none; }
.timeline::before {
    content:''; position:absolute; top:0; left:22px; height:100%;
    width:3px; background:linear-gradient(to bottom,#007bff,#6f42c1);
    border-radius:3px;
}
.timeline-item { position:relative; padding:0 0 24px 56px; }
.timeline-dot {
    position:absolute; left:12px; top:4px;
    width:22px; height:22px; border-radius:50%;
    border:3px solid #fff; box-shadow:0 0 0 3px currentColor;
    display:flex; align-items:center; justify-content:center;
    font-size:.65rem; font-weight:700; color:#fff;
}
.timeline-dot.vigente    { background:#28a745; box-shadow:0 0 0 3px #28a745; }
.timeline-dot.no-vigente { background:#dc3545; box-shadow:0 0 0 3px #dc3545; }
.timeline-dot.sin-dato   { background:#6c757d; box-shadow:0 0 0 3px #6c757d; }
.timeline-card {
    background:#fff; border:1px solid #e9ecef; border-radius:10px;
    padding:12px 16px; box-shadow:0 1px 6px rgba(0,0,0,.06);
}
.timeline-card:hover { box-shadow:0 3px 14px rgba(0,0,0,.1); }
.timeline-date { font-size:.75rem; color:#6c757d; font-weight:600; }
.timeline-title { font-size:.95rem; font-weight:700; color:#343a40; margin:2px 0 6px; }
.timeline-chips span { font-size:.72rem; padding:2px 8px; border-radius:20px; margin-right:4px; }

/* ── Photo viewer ─────────────────────────────────────────────────── */
.foto-container { position:relative; background:#f8f9fa; border-radius:10px; overflow:hidden; text-align:center; min-height:200px; }
.foto-container img { max-width:100%; border-radius:8px; cursor:zoom-in; transition:transform .2s; }
.foto-container img:hover { transform:scale(1.02); }
.no-foto { padding:40px 20px; color:#adb5bd; }

/* ── Detail info grid ─────────────────────────────────────────────── */
.info-grid dt { font-size:.75rem; text-transform:uppercase; letter-spacing:.04em; color:#6c757d; margin-bottom:1px; }
.info-grid dd { font-size:.9rem; font-weight:500; color:#212529; margin-bottom:10px; }

/* ── Lightbox overlay ────────────────────────────────────────────── */
#lightbox-overlay {
    display:none; position:fixed; inset:0; background:rgba(0,0,0,.88);
    z-index:9999; align-items:center; justify-content:center; cursor:zoom-out;
}
#lightbox-overlay img { max-width:92vw; max-height:90vh; border-radius:8px; box-shadow:0 8px 40px rgba(0,0,0,.6); }
#lightbox-overlay.active { display:flex; }
</style>
@endsection

@section('contenido')
<div class="container-fluid">

    {{-- ── Page header ──────────────────────────────────────────────────────── --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h4 class="mb-0"><i class="fas fa-hospital-user text-primary mr-2"></i>Pacientes Boxalud</h4>
            <small class="text-muted">Consultas y trazabilidad de afiliados</small>
        </div>
        <button class="btn btn-outline-secondary btn-sm" id="btn-toggle-filtros">
            <i class="fas fa-filter mr-1"></i> Filtros
        </button>
    </div>

    {{-- ── Stats ──────────────────────────────────────────────────────────── --}}
    <div class="row mb-3">
        <div class="col-6 col-md-3 mb-2">
            <div class="card stat-card bg-gradient-primary text-white p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase" style="font-size:.7rem;letter-spacing:.06em;opacity:.85">Total registros</div>
                        <div class="h3 mb-0 font-weight-bold" id="stat-total">{{ $stats['total'] }}</div>
                    </div>
                    <i class="fas fa-database stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-2">
            <div class="card stat-card bg-gradient-info text-white p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase" style="font-size:.7rem;letter-spacing:.06em;opacity:.85">Consultas hoy</div>
                        <div class="h3 mb-0 font-weight-bold" id="stat-hoy">{{ $stats['hoy'] }}</div>
                    </div>
                    <i class="fas fa-calendar-day stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-2">
            <div class="card stat-card bg-gradient-success text-white p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase" style="font-size:.7rem;letter-spacing:.06em;opacity:.85">Vigentes</div>
                        <div class="h3 mb-0 font-weight-bold" id="stat-vigentes">{{ $stats['vigentes'] }}</div>
                    </div>
                    <i class="fas fa-check-circle stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-2">
            <div class="card stat-card bg-gradient-warning text-white p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase" style="font-size:.7rem;letter-spacing:.06em;opacity:.85">Con foto</div>
                        <div class="h3 mb-0 font-weight-bold" id="stat-foto">{{ $stats['con_foto'] }}</div>
                    </div>
                    <i class="fas fa-camera stat-icon"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Filtros ─────────────────────────────────────────────────────────── --}}
    <div id="panel-filtros" class="card filter-card mb-3 p-3" style="display:none;">
        <div class="row g-2">
            <div class="col-md-2 col-6">
                <label class="small font-weight-bold mb-1">N° Documento</label>
                <input type="text" id="f-documento" class="form-control form-control-sm" placeholder="Ej: 12345678">
            </div>
            <div class="col-md-2 col-6">
                <label class="small font-weight-bold mb-1">Nombre / Apellido</label>
                <input type="text" id="f-nombre" class="form-control form-control-sm" placeholder="Buscar...">
            </div>
            <div class="col-md-2 col-6">
                <label class="small font-weight-bold mb-1">Fecha desde</label>
                <input type="date" id="f-fecha-desde" class="form-control form-control-sm">
            </div>
            <div class="col-md-2 col-6">
                <label class="small font-weight-bold mb-1">Fecha hasta</label>
                <input type="date" id="f-fecha-hasta" class="form-control form-control-sm">
            </div>
            <div class="col-md-2 col-6">
                <label class="small font-weight-bold mb-1">Vigencia</label>
                <select id="f-vigencia" class="form-control form-control-sm">
                    <option value="">Todas</option>
                    <option value="Vigente">Vigente</option>
                    <option value="No Vigente">No Vigente</option>
                </select>
            </div>
            <div class="col-md-2 col-6">
                <label class="small font-weight-bold mb-1">Plan</label>
                <select id="f-plan" class="form-control form-control-sm">
                    <option value="">Todos</option>
                    @foreach($planes as $plan)
                        <option value="{{ $plan }}">{{ $plan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 col-8">
                <label class="small font-weight-bold mb-1">IPS</label>
                <input type="text" id="f-ips" class="form-control form-control-sm" placeholder="Nombre IPS...">
            </div>
            <div class="col-md-3 col-4 d-flex align-items-end">
                <button id="btn-buscar" class="btn btn-primary btn-sm mr-2">
                    <i class="fas fa-search mr-1"></i>Buscar
                </button>
                <button id="btn-limpiar" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-times mr-1"></i>Limpiar
                </button>
            </div>
        </div>
    </div>

    {{-- ── Tabla ──────────────────────────────────────────────────────────── --}}
    <div class="card shadow-sm">
        <div class="card-body p-2">
            <div class="table-responsive">
                <table id="tabla-boxalud" class="table table-hover table-sm text-nowrap" style="width:100%">
                    <thead class="thead-light">
                        <tr>
                            <th>Acciones</th>
                            <th>Documento</th>
                            <th>Nombre completo</th>
                            <th>Tipo afiliado</th>
                            <th>Plan</th>
                            <th>Vigencia</th>
                            <th>Estado pagos</th>
                            <th>IPS</th>
                            <th>Municipio</th>
                            <th>Fecha consulta</th>
                            <th>Consultado por</th>
                            <th>Foto</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- ══ MODAL DETALLE / TIMELINE ═══════════════════════════════════════════ --}}
<div class="modal fade" id="modal-detalle" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white py-2">
                <h5 class="modal-title mb-0">
                    <i class="fas fa-id-card mr-2"></i>
                    <span id="modal-paciente-nombre">—</span>
                    <small class="ml-2 opacity-75" id="modal-paciente-doc"></small>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">

                {{-- Tabs --}}
                <ul class="nav nav-tabs nav-fill border-0 bg-light px-3 pt-2" id="tabs-detalle">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#tab-personal">
                            <i class="fas fa-user mr-1"></i>Datos personales
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab-atencion">
                            <i class="fas fa-map-marker-alt mr-1"></i>Atención
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab-ips">
                            <i class="fas fa-hospital mr-1"></i>IPS / Plan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab-timeline" id="tab-link-timeline">
                            <i class="fas fa-history mr-1"></i>Timeline
                            <span class="badge badge-secondary ml-1" id="badge-timeline">—</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab-foto" id="tab-link-foto">
                            <i class="fas fa-camera mr-1"></i>Foto
                        </a>
                    </li>
                </ul>

                <div class="tab-content p-3" id="tabs-content">

                    {{-- Tab: Datos personales --}}
                    <div class="tab-pane fade show active" id="tab-personal">
                        <div class="row">
                            <div class="col-md-8">
                                <dl class="row info-grid mb-0" id="info-personal"></dl>
                            </div>
                            <div class="col-md-4">
                                <div class="foto-container" id="mini-foto">
                                    <div class="no-foto">
                                        <i class="fas fa-image fa-3x d-block mb-2"></i>
                                        <span>Sin foto</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tab: Atención --}}
                    <div class="tab-pane fade" id="tab-atencion">
                        <dl class="row info-grid mb-0" id="info-atencion"></dl>
                    </div>

                    {{-- Tab: IPS / Plan --}}
                    <div class="tab-pane fade" id="tab-ips">
                        <dl class="row info-grid mb-0" id="info-ips"></dl>
                    </div>

                    {{-- Tab: Timeline --}}
                    <div class="tab-pane fade" id="tab-timeline">
                        <div id="timeline-loading" class="text-center py-5" style="display:none;">
                            <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                            <p class="mt-2 text-muted">Cargando historial...</p>
                        </div>
                        <ul class="timeline mt-2" id="timeline-content"></ul>
                        <div id="timeline-empty" class="text-center py-5 text-muted" style="display:none;">
                            <i class="fas fa-inbox fa-3x d-block mb-2"></i>
                            <span>Sin registros históricos</span>
                        </div>
                    </div>

                    {{-- Tab: Foto --}}
                    <div class="tab-pane fade" id="tab-foto">
                        <div class="foto-container" id="foto-grande">
                            <div class="no-foto">
                                <i class="fas fa-image fa-3x d-block mb-2"></i>
                                <span>Sin foto disponible</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- Lightbox --}}
<div id="lightbox-overlay">
    <img id="lightbox-img" src="" alt="Screenshot">
</div>
@endsection


@section('scriptsPlugins')
<script src="{{ asset("assets/$theme/plugins/datatables/jquery.dataTables.js") }}"></script>
<script src="{{ asset("assets/$theme/plugins/datatables-bs4/js/dataTables.bootstrap4.js") }}"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
@endsection

@section('scripts')
<script>
$(function () {

    // ── Helpers ──────────────────────────────────────────────────────────────

    function badgeVigencia(val) {
        if (!val || val === '-') return '<span class="badge badge-secondary">-</span>';
        var ok = val.toLowerCase().indexOf('vigente') !== -1 && val.toLowerCase().indexOf('no') === -1;
        return ok
            ? '<span class="badge badge-vigente px-2 py-1">' + val + '</span>'
            : '<span class="badge badge-no-vigente px-2 py-1">' + val + '</span>';
    }

    function badgePagos(val) {
        if (!val || val === '-') return '<span class="badge badge-secondary">-</span>';
        var ok = val.toLowerCase().indexOf('al día') !== -1 || val.toLowerCase().indexOf('ok') !== -1;
        return ok
            ? '<span class="badge badge-pagos-ok px-2 py-1">' + val + '</span>'
            : '<span class="badge badge-pagos-pend px-2 py-1">' + val + '</span>';
    }

    function infoRow(label, value) {
        return '<dt class="col-sm-4">' + label + '</dt><dd class="col-sm-8">' + (value || '-') + '</dd>';
    }

    function fotoHtml(url, small) {
        var sz = small ? 'max-height:160px;' : 'max-height:420px;';
        return '<img src="' + url + '" style="' + sz + '" class="img-thumbnail foto-zoom" data-src="' + url + '" alt="Screenshot">';
    }

    // ── DataTable ─────────────────────────────────────────────────────────────

    var dt = $('#tabla-boxalud').DataTable({
        language:    idioma_espanol,
        processing:  true,
        serverSide:  true,
        lengthMenu:  [[25, 50, 100, -1], [25, 50, 100, 'Todos']],
        aaSorting:   [[9, 'desc']],
        dom: '<"row"<"col-md-4"l><"col-md-5"f><"col-md-3"B>>rt<"row"<"col-md-8"i><"col-md-4"p>>',
        buttons: [
            { extend:'excelHtml5', className:'btn btn-outline-success btn-sm', titleAttr:'Exportar Excel' },
            { extend:'csvHtml5',   className:'btn btn-outline-warning btn-sm', titleAttr:'Exportar CSV'   },
            { extend:'pdfHtml5',   className:'btn btn-outline-danger btn-sm',  titleAttr:'Exportar PDF'   },
        ],
        ajax: {
            url: '{{ route("boxalud.index") }}',
            type: 'GET',
            data: function (d) {
                d.documento   = $('#f-documento').val();
                d.nombre      = $('#f-nombre').val();
                d.vigencia    = $('#f-vigencia').val();
                d.plan        = $('#f-plan').val();
                d.ips         = $('#f-ips').val();
                d.fecha_desde = $('#f-fecha-desde').val();
                d.fecha_hasta = $('#f-fecha-hasta').val();
            }
        },
        columns: [
            { data: null, orderable: false, render: function(d, t, r) {
                return '<div class="btn-group btn-group-sm">' +
                    '<button class="btn btn-primary btn-detalle" data-id="' + r.id + '" data-doc="' + r.numero_documento + '" title="Ver detalle"><i class="fas fa-eye"></i></button>' +
                    '<button class="btn btn-secondary btn-timeline" data-id="' + r.id + '" data-doc="' + r.numero_documento + '" data-nombre="' + r.nombre_completo + '" title="Ver timeline"><i class="fas fa-history"></i></button>' +
                    '</div>';
            }},
            { data: 'numero_documento', render: function(d, t, r) {
                return '<span class="font-weight-bold">' + r.tipo_documento + ' ' + d + '</span>';
            }},
            { data: 'nombre_completo' },
            { data: 'tipo_afiliado' },
            { data: 'plan' },
            { data: 'vigencia', render: function(d) { return badgeVigencia(d); } },
            { data: 'estado_pagos', render: function(d) { return badgePagos(d); } },
            { data: 'ips_nombre_oferta', render: function(d) {
                if (!d || d==='-') return '-';
                return '<span title="' + d + '">' + (d.length > 30 ? d.substring(0,28)+'…' : d) + '</span>';
            }},
            { data: 'municipio_atencion' },
            { data: 'fecha_consulta' },
            { data: 'consultado_por', orderable: false, render: function(d) {
                return d && d !== '-'
                    ? '<span class="badge badge-light border"><i class="fas fa-user mr-1"></i>' + d + '</span>'
                    : '<span class="text-muted">-</span>';
            }},
            { data: 'tiene_foto', orderable: false, render: function(d, t, r) {
                return d
                    ? '<button class="btn btn-sm btn-warning btn-foto" data-url="' + r.screenshot_url + '" title="Ver foto"><i class="fas fa-camera"></i></button>'
                    : '<span class="text-muted"><i class="fas fa-times-circle"></i></span>';
            }},
        ],
        drawCallback: function() {
            $('[title]').tooltip({ trigger:'hover', placement:'top' });
        }
    });

    // ── Filtros ───────────────────────────────────────────────────────────────

    $('#btn-toggle-filtros').on('click', function () {
        $('#panel-filtros').slideToggle(200);
    });

    $('#btn-buscar').on('click', function () { dt.ajax.reload(); });

    $('#f-documento, #f-nombre, #f-ips').on('keypress', function (e) {
        if (e.which === 13) dt.ajax.reload();
    });

    $('#btn-limpiar').on('click', function () {
        $('#f-documento,#f-nombre,#f-ips,#f-fecha-desde,#f-fecha-hasta').val('');
        $('#f-vigencia,#f-plan').val('');
        dt.ajax.reload();
    });

    // ── Abrir detalle ─────────────────────────────────────────────────────────

    function abrirDetalle(id, doc, loadTimeline) {
        $.get('{{ url("admin/validaciones-boxalud") }}/' + id + '/detalle', function (res) {
            var d = res.data;

            $('#modal-paciente-nombre').text(d.nombre_completo);
            $('#modal-paciente-doc').text(d.tipo_documento + ' ' + d.numero_documento);

            // Tab personal
            var usuarioLabel = d.consultado_por !== '-'
                ? '<span class="badge badge-info"><i class="fas fa-user mr-1"></i>' + d.consultado_por + '</span>' +
                  (d.consultado_por_usuario && d.consultado_por_usuario !== '-' ? ' <small class="text-muted">(@' + d.consultado_por_usuario + ')</small>' : '')
                : '-';
            $('#info-personal').html(
                infoRow('Documento',    d.tipo_documento + ' ' + d.numero_documento) +
                infoRow('Nacimiento',   d.fecha_nacimiento) +
                infoRow('Sexo biológico', d.sexo_biologico) +
                infoRow('Sexo identif.', d.sexo_identificacion) +
                infoRow('Tipo afiliado', d.tipo_afiliado) +
                infoRow('Rango salarial', d.rango_salarial) +
                infoRow('Nacionalidad',  d.nacionalidad) +
                infoRow('País nacim.',   d.pais_nacimiento) +
                infoRow('Dpto. nacim.',  d.departamento_nacimiento) +
                infoRow('Mpio. nacim.',  d.municipio_nacimiento) +
                infoRow('Fecha consulta', d.fecha_consulta) +
                infoRow('Consultado por', usuarioLabel)
            );

            // Mini foto
            if (d.tiene_foto) {
                $('#mini-foto').html(fotoHtml(d.screenshot_url, true));
            } else {
                $('#mini-foto').html('<div class="no-foto"><i class="fas fa-image fa-2x d-block mb-1"></i><small>Sin foto</small></div>');
            }

            // Tab atención
            $('#info-atencion').html(
                infoRow('Dpto. atención',  d.departamento_atencion) +
                infoRow('Mpio. atención',  d.municipio_atencion) +
                infoRow('Localidad',       d.localidad) +
                infoRow('Barrio',          d.barrio) +
                infoRow('Dirección',       d.direccion) +
                infoRow('Teléfono',        d.telefono) +
                infoRow('Celular',         d.celular) +
                infoRow('Correo',          d.correo_electronico) +
                infoRow('Inicio atención', d.fecha_inicio_atencion) +
                infoRow('Fin atención',    d.fecha_fin_atencion)
            );

            // Tab IPS / Plan
            $('#info-ips').html(
                infoRow('Plan',        d.plan) +
                infoRow('Vigencia',    badgeVigencia(d.vigencia)) +
                infoRow('Estado pagos', badgePagos(d.estado_pagos)) +
                infoRow('Estado docs', d.estado_documentos) +
                infoRow('IPS nombre',  d.ips_nombre_oferta) +
                infoRow('IPS código',  d.ips_codigo) +
                infoRow('IPS sede',    d.ips_sede) +
                infoRow('IPS servicio',d.ips_servicio)
            );

            // Tab foto grande
            if (d.tiene_foto) {
                $('#foto-grande').html(fotoHtml(d.screenshot_url, false));
            } else {
                $('#foto-grande').html('<div class="no-foto"><i class="fas fa-image fa-3x d-block mb-2"></i><span>Sin foto disponible</span></div>');
            }

            // Activar tab correcto
            if (loadTimeline) {
                cargarTimeline(doc, d.nombre_completo);
                $('#tabs-detalle a[href="#tab-timeline"]').tab('show');
            } else {
                $('#tabs-detalle a[href="#tab-personal"]').tab('show');
            }

            $('#modal-detalle').modal('show');
        });
    }

    // ── Timeline ──────────────────────────────────────────────────────────────

    var timelineDoc = null;

    function cargarTimeline(doc, nombre) {
        if (timelineDoc === doc) return;
        timelineDoc = doc;

        $('#badge-timeline').text('…');
        $('#timeline-loading').show();
        $('#timeline-content').empty();
        $('#timeline-empty').hide();

        $.get('{{ url("admin/validaciones-boxalud/historial") }}/' + doc, function (res) {
            $('#timeline-loading').hide();
            $('#badge-timeline').text(res.data.length);

            if (!res.data.length) {
                $('#timeline-empty').show();
                return;
            }

            var prev = {};
            $.each(res.data, function (i, r) {
                var dotClass = 'sin-dato';
                if (r.vigencia && r.vigencia !== '-') {
                    dotClass = (r.vigencia.toLowerCase().indexOf('no') === -1 && r.vigencia.toLowerCase().indexOf('vigente') !== -1) ? 'vigente' : 'no-vigente';
                }

                // Detectar cambios respecto al registro anterior
                var cambios = [];
                if (i > 0) {
                    if (prev.vigencia    !== r.vigencia)    cambios.push('<span class="badge badge-light border">Vigencia</span>');
                    if (prev.plan        !== r.plan)        cambios.push('<span class="badge badge-light border">Plan</span>');
                    if (prev.estado_pagos !== r.estado_pagos) cambios.push('<span class="badge badge-light border">Pagos</span>');
                    if (prev.tipo_afiliado !== r.tipo_afiliado) cambios.push('<span class="badge badge-light border">Tipo afiliado</span>');
                    if (prev.ips_nombre_oferta !== r.ips_nombre_oferta) cambios.push('<span class="badge badge-light border">IPS</span>');
                }
                prev = r;

                var cambiosHtml = cambios.length
                    ? '<div class="mt-1 text-warning" style="font-size:.72rem;"><i class="fas fa-exchange-alt mr-1"></i>Cambios: ' + cambios.join(' ') + '</div>'
                    : (i === 0 ? '<div class="mt-1 text-muted" style="font-size:.72rem;"><i class="fas fa-flag mr-1"></i>Registro inicial</div>' : '');

                var fotoBtn = r.tiene_foto
                    ? '<button class="btn btn-xs btn-warning btn-foto ml-1" data-url="' + r.screenshot_url + '" style="font-size:.7rem;padding:1px 6px;"><i class="fas fa-camera mr-1"></i>Foto</button>'
                    : '';

                var consultadoPorHtml = (r.consultado_por && r.consultado_por !== '-')
                    ? '<span class="ml-2 text-muted" style="font-size:.72rem;"><i class="fas fa-user mr-1"></i>' + r.consultado_por + '</span>'
                    : '';

                $('#timeline-content').append(
                    '<li class="timeline-item">' +
                        '<div class="timeline-dot ' + dotClass + '">' + (i + 1) + '</div>' +
                        '<div class="timeline-card">' +
                            '<div class="timeline-date"><i class="fas fa-clock mr-1"></i>' + r.fecha_consulta + fotoBtn + consultadoPorHtml + '</div>' +
                            '<div class="timeline-title">' + badgeVigencia(r.vigencia) + ' &nbsp;' + badgePagos(r.estado_pagos) + '</div>' +
                            '<div class="timeline-chips">' +
                                (r.plan && r.plan !== '-' ? '<span class="badge badge-info">' + r.plan + '</span>' : '') +
                                (r.tipo_afiliado && r.tipo_afiliado !== '-' ? '<span class="badge badge-secondary">' + r.tipo_afiliado + '</span>' : '') +
                                (r.ips_nombre_oferta && r.ips_nombre_oferta !== '-' ? '<span class="badge badge-light border">' + r.ips_nombre_oferta + '</span>' : '') +
                                (r.municipio_atencion && r.municipio_atencion !== '-' ? '<span class="badge badge-light border"><i class="fas fa-map-marker-alt mr-1"></i>' + r.municipio_atencion + '</span>' : '') +
                            '</div>' +
                            cambiosHtml +
                        '</div>' +
                    '</li>'
                );
            });
        });
    }

    // ── Click handlers ────────────────────────────────────────────────────────

    $(document).on('click', '.btn-detalle', function () {
        timelineDoc = null;
        abrirDetalle($(this).data('id'), $(this).data('doc'), false);
    });

    $(document).on('click', '.btn-timeline', function () {
        timelineDoc = null;
        abrirDetalle($(this).data('id'), $(this).data('doc'), true);
    });

    $(document).on('click', '.btn-foto', function (e) {
        e.stopPropagation();
        abrirFoto($(this).data('url'));
    });

    // Cargar timeline al hacer clic en el tab si aún no está cargado
    $('#tabs-detalle a[href="#tab-timeline"]').on('shown.bs.tab', function () {
        var doc = $('#modal-paciente-doc').text().replace(/^[A-Z]+\s/, '').trim();
        if (doc) cargarTimeline(doc, $('#modal-paciente-nombre').text());
    });

    // Reset timeline al cerrar modal
    $('#modal-detalle').on('hidden.bs.modal', function () { timelineDoc = null; });

    // ── Lightbox ─────────────────────────────────────────────────────────────

    function abrirFoto(url) {
        $('#lightbox-img').attr('src', url);
        $('#lightbox-overlay').addClass('active');
    }

    $(document).on('click', '.foto-zoom', function () { abrirFoto($(this).data('src')); });
    $('#lightbox-overlay').on('click', function () { $(this).removeClass('active'); $('#lightbox-img').attr('src',''); });

});

// ── i18n DataTables ───────────────────────────────────────────────────────────
var idioma_espanol = {
    "sProcessing":   "Procesando...",
    "sLengthMenu":   "Mostrar _MENU_ registros",
    "sZeroRecords":  "No se encontraron resultados",
    "sEmptyTable":   "Ningún dato disponible",
    "sInfo":         "Registros del _START_ al _END_ de _TOTAL_",
    "sInfoEmpty":    "Registros del 0 al 0 de 0",
    "sInfoFiltered": "(filtrado de _MAX_ registros)",
    "sSearch":       "Buscar:",
    "oPaginate": { "sFirst":"Primero","sLast":"Último","sNext":"Siguiente","sPrevious":"Anterior" },
    "oAria": { "sSortAscending":": Ascendente","sSortDescending":": Descendente" },
    "buttons": { "copy":"Copiar","colvis":"Visibilidad" }
};
</script>
@endsection
