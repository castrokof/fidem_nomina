@extends("theme.$theme.layout")

@section('titulo')
    Pacientes
@endsection

@section("styles")
<link href="{{asset("assets/$theme/plugins/datatables-bs4/css/dataTables.bootstrap4.css")}}" rel="stylesheet"/>
<style>
.timeline { position: relative; padding: 0; list-style: none; }
.timeline::before { content:''; position:absolute; top:0; bottom:0; left:18px; width:3px; background:#dee2e6; }
.timeline > div { position: relative; margin-bottom: 15px; padding-left: 50px; }
.timeline > div > .tl-icon {
    position: absolute; left: 0; width: 36px; height: 36px; border-radius: 50%;
    text-align: center; line-height: 36px; font-size: 14px; color: #fff; z-index: 1;
}
.timeline > .tl-label { margin-bottom: 10px; padding-left: 0; }
.timeline > .tl-label > span { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; color:#fff; }
.timeline-item { background:#f8f9fa; border:1px solid #dee2e6; border-radius:8px; padding:10px 14px; }
.timeline-item .tl-time { float:right; font-size:11px; color:#6c757d; }
.timeline-item .tl-title { font-weight:600; margin-bottom:3px; }
.timeline-item .tl-body { font-size:12px; color:#495057; }
</style>
@endsection

@section('scripts')
<script src="{{asset("assets/$theme/plugins/datatables/jquery.dataTables.js")}}"></script>
<script src="{{asset("assets/$theme/plugins/datatables-bs4/js/dataTables.bootstrap4.js")}}"></script>
<script>
$(document).ready(function() {

    let dtPacientes = null;

    function initDT() {
        if (dtPacientes) { dtPacientes.destroy(); dtPacientes = null; }
        dtPacientes = $('#tablaPacientes').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json',
                emptyTable: '<i class="fas fa-search mr-1"></i> Use los filtros para buscar pacientes.'
            },
            order: [[1, 'asc']],
            pageLength: 25
        });
    }
    initDT();

    // ── BUSCAR ───────────────────────────────────────────────────────────────
    $('#formFiltros').submit(function(e) {
        e.preventDefault();
        const $btn = $('#btnBuscar');
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Buscando...');
        $('#contadorPac').text('');

        $.ajax({
            url: '{{ route("pacientes.ajax.buscar") }}',
            type: 'GET',
            data: $(this).serialize(),
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(res) { renderTabla(res.pacientes || []); $('#contadorPac').html(`<span class="text-muted small ml-2">${res.total} resultado(s)</span>`); },
            error: function() { Swal.fire('Error', 'No se pudieron cargar los datos.', 'error'); },
            complete: function() { $btn.prop('disabled', false).html('<i class="fas fa-search"></i> Buscar'); }
        });
    });

    $('#btnLimpiar').click(function() {
        $('#formFiltros')[0].reset();
        $('#contadorPac').text('');
        if (dtPacientes) { dtPacientes.destroy(); dtPacientes = null; }
        $('#tablaPacientes tbody').empty();
        initDT();
    });

    // ── RENDER TABLA ─────────────────────────────────────────────────────────
    function renderTabla(data) {
        if (dtPacientes) { dtPacientes.destroy(); dtPacientes = null; }
        const tbody = $('#tablaPacientes tbody');
        tbody.empty();
        if (!data.length) { initDT(); return; }

        tbody.html(data.map(p => `
            <tr>
                <td>${p.id}</td>
                <td>${p.apellidos}</td>
                <td>${p.nombres}</td>
                <td>${p.documento}</td>
                <td>${p.historia_clinica}</td>
                <td>${p.telefono || '-'}</td>
                <td class="text-center">
                    <span class="badge badge-info">${p.total_citas}</span>
                </td>
                <td class="text-center">
                    <span class="badge badge-${p.total_consentimientos > 0 ? 'primary' : 'secondary'}">${p.total_consentimientos}</span>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-info btn-detalle"
                        data-id="${p.id}" title="Ver detalle">
                        <i class="fas fa-eye"></i>
                    </button>
                    <a href="${p.url_edit}" class="btn btn-sm btn-warning" title="Editar">
                        <i class="fas fa-edit"></i>
                    </a>
                </td>
            </tr>`).join(''));

        initDT();
    }

    // ── ABRIR MODAL DETALLE ──────────────────────────────────────────────────
    $(document).on('click', '.btn-detalle', function() {
        const id = $(this).data('id');
        $('#modalDetallePaciente').modal('show');
        $('#detalleContenido').html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');

        $.ajax({
            url: '{{ route("pacientes.ajax.detalle", ":id") }}'.replace(':id', id),
            type: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(res) { renderDetalle(res); },
            error: function() { $('#detalleContenido').html('<div class="alert alert-danger">Error cargando datos.</div>'); }
        });
    });

    // ── RENDER DETALLE (tabs + timeline) ─────────────────────────────────────
    function renderDetalle(res) {
        const p   = res.paciente;
        const st  = res.stats;

        const ESTADOS_CITA = {
            0: ['secondary','Asignada'], 1: ['success','Atendido'],
            2: ['warning','Incumplido'], 3: ['danger','Cancelada'],
            4: ['danger','Cancelada-Prestador']
        };
        function badgeCita(e) {
            const [c,l] = ESTADOS_CITA[parseInt(e)] || ['light','?'];
            return `<span class="badge badge-${c}">${l}</span>`;
        }
        function badgeCI(e) {
            const map = { pendiente:['warning','Pendiente'], en_proceso:['info','En proceso'],
                          firmado:['success','Firmado'], anulado:['danger','Anulado'] };
            const [c,l] = map[e] || ['secondary', e];
            return `<span class="badge badge-${c}">${l}</span>`;
        }

        // ── Tab: Citas ──
        let htmlCitas = `<div class="table-responsive"><table class="table table-sm table-bordered table-hover">
            <thead class="thead-light"><tr><th>Fecha</th><th>CUPS</th><th>Centro</th><th>Profesional</th><th>EPS</th><th>Estado</th></tr></thead><tbody>`;
        if (res.agendas.length === 0) {
            htmlCitas += '<tr><td colspan="6" class="text-center text-muted">Sin citas registradas</td></tr>';
        } else {
            res.agendas.forEach(a => {
                const cancelada = [2,3,4].includes(parseInt(a.estado));
                htmlCitas += `<tr class="${cancelada ? 'table-danger' : ''}">
                    <td>${a.fecha}</td><td>${a.cups}</td><td>${a.centroprod}</td>
                    <td>${a.profesional}</td><td>${a.empresafac || '-'}</td>
                    <td>${badgeCita(a.estado)}</td></tr>`;
            });
        }
        htmlCitas += '</tbody></table></div>';

        // ── Tab: Consentimientos ──
        let htmlCI = `<div class="table-responsive"><table class="table table-sm table-bordered table-hover">
            <thead class="thead-light"><tr><th>ID</th><th>Procedimiento</th><th>Profesional</th><th>Fecha</th><th>Estado</th><th></th></tr></thead><tbody>`;
        if (res.consentimientos.length === 0) {
            htmlCI += '<tr><td colspan="6" class="text-center text-muted">Sin consentimientos registrados</td></tr>';
        } else {
            res.consentimientos.forEach(c => {
                htmlCI += `<tr>
                    <td>${c.id}</td><td>${c.plantilla}</td><td>${c.profesional}</td>
                    <td>${c.fecha}</td><td>${badgeCI(c.estado)}</td>
                    <td><a href="${c.url_show}" class="btn btn-xs btn-info btn-sm" target="_blank"><i class="fas fa-eye"></i></a></td></tr>`;
            });
        }
        htmlCI += '</tbody></table></div>';

        // ── Tab: Timeline ──
        let htmlTL = '<div class="timeline mt-2">';
        let lastDate = '';
        res.timeline.forEach(item => {
            const d = item.fecha ? item.fecha.substring(0,10) : '';
            if (d !== lastDate) {
                lastDate = d;
                htmlTL += `<div class="tl-label"><span class="bg-primary">${d}</span></div>`;
            }
            if (item.tipo === 'cita') {
                const cancelada = [2,3,4].includes(parseInt(item.estado));
                htmlTL += `
                <div>
                    <div class="tl-icon" style="background:${cancelada?'#dc3545':parseInt(item.estado)===1?'#28a745':'#17a2b8'}">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="timeline-item">
                        <span class="tl-time">${item.fecha}</span>
                        <div class="tl-title"><i class="fas fa-stethoscope mr-1"></i>Cita — ${item.cups || '-'} ${badgeCita(item.estado)}</div>
                        <div class="tl-body">
                            Centro: ${item.centroprod} &bull; ${item.profesional}<br>
                            ${item.empresafac ? 'EPS: ' + item.empresafac : ''}
                            ${item.observaciones ? '<br><em class="text-muted">' + item.observaciones.substring(0,80) + (item.observaciones.length>80?'…':'') + '</em>' : ''}
                        </div>
                    </div>
                </div>`;
            } else {
                const ciColors = { pendiente:'#ffc107', en_proceso:'#17a2b8', firmado:'#28a745', anulado:'#dc3545' };
                htmlTL += `
                <div>
                    <div class="tl-icon" style="background:${ciColors[item.estado]||'#6c757d'}">
                        <i class="fas fa-file-signature"></i>
                    </div>
                    <div class="timeline-item">
                        <span class="tl-time">${item.fecha}</span>
                        <div class="tl-title"><i class="fas fa-file-medical mr-1"></i>Consentimiento ${badgeCI(item.estado)}</div>
                        <div class="tl-body">
                            ${item.plantilla}<br>
                            <small>Dr/a. ${item.profesional}</small>
                            <a href="${item.url_show}" class="btn btn-xs btn-outline-info btn-sm ml-2" target="_blank"><i class="fas fa-eye"></i></a>
                        </div>
                    </div>
                </div>`;
            }
        });
        if (res.timeline.length === 0) htmlTL += '<p class="text-muted text-center py-3">Sin actividad registrada</p>';
        htmlTL += '</div>';

        $('#detalleContenido').html(`
            <div class="row mb-3">
                <div class="col-md-7">
                    <h5 class="mb-1">${p.nombre_completo}</h5>
                    <span class="badge badge-secondary mr-1">${p.documento}</span>
                    <span class="badge badge-light">HC: ${p.historia_clinica}</span>
                    <div class="text-muted small mt-1">
                        ${p.telefono !== 'N/A' ? '<i class="fas fa-phone mr-1"></i>' + p.telefono + ' &nbsp;' : ''}
                        ${p.email !== 'N/A' ? '<i class="fas fa-envelope mr-1"></i>' + p.email : ''}
                        ${p.edad !== 'N/A' ? '&nbsp; <i class="fas fa-birthday-cake mr-1"></i>' + p.edad + ' años' : ''}
                    </div>
                </div>
                <div class="col-md-5 text-right">
                    <span class="badge badge-info badge-pill p-2 mr-1"><i class="fas fa-calendar"></i> ${st.total_citas} citas</span>
                    <span class="badge badge-primary badge-pill p-2 mr-1"><i class="fas fa-file-signature"></i> ${st.total_consentimientos} CI</span>
                    <span class="badge badge-success badge-pill p-2 mr-1"><i class="fas fa-check"></i> ${st.consentimientos_firmados} firmados</span>
                    ${st.consentimientos_pendientes > 0 ? `<span class="badge badge-warning badge-pill p-2"><i class="fas fa-clock"></i> ${st.consentimientos_pendientes} pendientes</span>` : ''}
                    <br><a href="${p.url_edit}" class="btn btn-warning btn-sm mt-2"><i class="fas fa-edit"></i> Editar</a>
                </div>
            </div>
            <ul class="nav nav-tabs" id="tabsDetalle">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tabCitas">
                    <i class="fas fa-calendar"></i> Citas <span class="badge badge-info">${st.total_citas}</span></a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tabCI">
                    <i class="fas fa-file-signature"></i> Consentimientos <span class="badge badge-primary">${st.total_consentimientos}</span></a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tabTimeline">
                    <i class="fas fa-history"></i> Timeline</a></li>
            </ul>
            <div class="tab-content pt-3">
                <div class="tab-pane active" id="tabCitas">${htmlCitas}</div>
                <div class="tab-pane"        id="tabCI">${htmlCI}</div>
                <div class="tab-pane"        id="tabTimeline">${htmlTL}</div>
            </div>
        `);
    }

});
</script>
@endsection

@section('contenido')
<div class="content-wrapper" style="min-height: 543px;">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><i class="fas fa-users"></i> Pacientes</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
                        <li class="breadcrumb-item active">Pacientes</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            {{-- Filtros --}}
            <div class="card card-outline card-primary collapsed-card mb-3">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-search"></i> Buscar paciente</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
                    </div>
                </div>
                <div class="card-body" style="display:none;">
                    <form id="formFiltros">
                        <div class="row align-items-end">
                            <div class="col-md-3 col-sm-6 mb-2">
                                <label class="small font-weight-bold">Nombre / Apellido</label>
                                <input type="text" name="nombre" class="form-control form-control-sm" placeholder="Buscar por nombre...">
                            </div>
                            <div class="col-md-3 col-sm-6 mb-2">
                                <label class="small font-weight-bold">Documento</label>
                                <input type="text" name="documento" class="form-control form-control-sm" placeholder="Número de documento...">
                            </div>
                            <div class="col-md-3 col-sm-6 mb-2">
                                <label class="small font-weight-bold">Historia Clínica</label>
                                <input type="text" name="historia" class="form-control form-control-sm" placeholder="Nro. historia...">
                            </div>
                            <div class="col-md-2 col-sm-6 mb-2">
                                <button type="submit" class="btn btn-primary btn-sm btn-block" id="btnBuscar">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                            <div class="col-md-1 col-sm-6 mb-2">
                                <button type="button" id="btnLimpiar" class="btn btn-secondary btn-sm btn-block">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <span id="contadorPac"></span>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Listado de Pacientes</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tablaPacientes" class="table table-bordered table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Apellidos</th>
                                    <th>Nombres</th>
                                    <th>Documento</th>
                                    <th>Historia Clínica</th>
                                    <th>Teléfono</th>
                                    <th>Citas</th>
                                    <th>Consentimientos</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

{{-- Modal detalle paciente --}}
<div class="modal fade" id="modalDetallePaciente" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="fas fa-user-circle"></i> Detalle del Paciente</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="detalleContenido" style="min-height:300px;">
                <div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x"></i></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection
