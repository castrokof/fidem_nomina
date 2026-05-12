@extends("theme.$theme.layout")

@section('titulo')
    Validación de Derechos
@endsection

@section("styles")
<link href="{{asset("assets/$theme/plugins/datatables-bs4/css/dataTables.bootstrap4.css")}}" rel="stylesheet" type="text/css"/>
<style>
.badge-validado  { background: #28a745; color: #fff; }
.badge-pendiente { background: #ffc107; color: #212529; }
.img-thumb { width: 64px; height: 44px; object-fit: cover; border-radius: 5px; cursor: pointer; border: 1px solid #dee2e6; }
.modal-imagen img { max-width: 100%; border-radius: 6px; }
#tablaAgenda td { vertical-align: middle; }
</style>
@endsection

@section('scripts')
<script src="{{asset("assets/$theme/plugins/datatables/jquery.dataTables.js")}}"></script>
<script src="{{asset("assets/$theme/plugins/datatables-bs4/js/dataTables.bootstrap4.js")}}"></script>
<script>
$(document).ready(function () {

    var dt = null;

    // ── DataTable ─────────────────────────────────────────────────────────────
    function initDT() {
        if (dt) { dt.destroy(); dt = null; }
        dt = $('#tablaAgenda').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json' },
            order: [[0, 'asc']],
            pageLength: 50,
            columnDefs: [
                { targets: '_all', defaultContent: '-' },
                { targets: [7], orderable: false },
            ]
        });
    }
    initDT();

    // ── Renderizar filas ──────────────────────────────────────────────────────
    function renderTabla(data) {
        dt.clear();
        if (data && data.length) {
            data.forEach(function (r) {

                var estadoBadge = '';
                if (r.validado) {
                    var estadoText = r.estado_afiliacion || 'Validado';
                    var cls = (r.estado_afiliacion === 'ACTIVO') ? 'success'
                            : (r.estado_afiliacion === 'SUSPENDIDO' || r.estado_afiliacion === 'INACTIVO' || r.estado_afiliacion === 'RETIRADO') ? 'danger'
                            : 'success';
                    estadoBadge = '<span class="badge badge-' + cls + '"><i class="fas fa-check mr-1"></i>' + estadoText + '</span>';
                } else {
                    estadoBadge = '<span class="badge badge-warning"><i class="fas fa-clock mr-1"></i>Pendiente</span>';
                }

                var acciones = '';
                if (r.validado) {
                    acciones  = '<img src="' + r.imagen_url + '" class="img-thumb mr-1" data-url="' + r.imagen_url + '" title="Ver pantallazo">';
                    acciones += '<button class="btn btn-sm btn-outline-danger btn-eliminar ml-1" '
                              + 'data-url="' + r.eliminar_url + '" title="Eliminar validación">'
                              + '<i class="fas fa-trash"></i></button>';
                } else {
                    acciones = '<a href="' + r.crear_url + '" class="btn btn-sm btn-primary">'
                             + '<i class="fas fa-upload mr-1"></i>Validar</a>';
                }

                dt.row.add([
                    r.hora,
                    r.paciente_nombre,
                    r.paciente_cedula,
                    r.empresafac,
                    r.cups_descripcion,
                    r.numero_factura,
                    estadoBadge,
                    acciones,
                ]);
            });
        }
        dt.draw();
    }

    // ── Buscar ────────────────────────────────────────────────────────────────
    function buscar() {
        var fecha = ($('#f_fecha').val() || '').trim();
        var q     = ($('#f_q').val()     || '').trim();

        $.ajax({
            url:  '{{ route("validaciones.ajax.agenda") }}',
            data: { fecha: fecha, q: q },
            success: function (resp) { renderTabla(resp); },
            error:   function () { alert('Error al consultar la agenda.'); }
        });
    }

    $('#btn-buscar').on('click', buscar);
    $('#f_q').on('keydown', function (e) { if (e.key === 'Enter') buscar(); });

    // Cargar automáticamente al abrir
    buscar();

    // ── Ver imagen en modal ───────────────────────────────────────────────────
    $(document).on('click', '.img-thumb', function () {
        $('#modalImagen .modal-imagen').html('<img src="' + $(this).data('url') + '" alt="Pantallazo">');
        $('#modalImagen').modal('show');
    });

    // ── Eliminar validación ───────────────────────────────────────────────────
    $(document).on('click', '.btn-eliminar', function () {
        var url = $(this).data('url');
        var row = $(this).closest('tr');
        if (!confirm('¿Eliminar esta validación? El pantallazo se perderá.')) return;
        $.ajax({
            url:  url,
            type: 'POST',
            data: { _method: 'DELETE', _token: '{{ csrf_token() }}' },
            success: function () { buscar(); },
            error:   function () { alert('Error al eliminar.'); }
        });
    });

    // ── Flash de éxito ────────────────────────────────────────────────────────
    @if(session('success'))
        var $toast = $('<div>')
            .text('{{ session("success") }}')
            .css({ position:'fixed', bottom:'20px', right:'20px', background:'#28a745',
                   color:'#fff', padding:'10px 18px', borderRadius:'8px',
                   fontSize:'13px', zIndex:9999 })
            .appendTo('body');
        setTimeout(function() { $toast.fadeOut(400, function() { $toast.remove(); }); }, 3000);
    @endif
});
</script>
@endsection

@section('content')
<div class="container-fluid">

    {{-- Encabezado --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h4 class="mb-0">
                <i class="fas fa-shield-alt text-primary mr-2"></i>Validación de Derechos
            </h4>
            <small class="text-muted">
                Consulte la agenda del día, busque el paciente y adjunte el pantallazo de validación.
            </small>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body py-2">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="small mb-1">Fecha de la cita</label>
                    <input type="date" id="f_fecha" class="form-control form-control-sm"
                        value="{{ request('fecha', date('Y-m-d')) }}">
                </div>
                <div class="col-md-5">
                    <label class="small mb-1">Buscar paciente (nombre o cédula)</label>
                    <input type="text" id="f_q" class="form-control form-control-sm"
                        placeholder="Escriba nombre o número de documento…">
                </div>
                <div class="col-md-2">
                    <button id="btn-buscar" class="btn btn-primary btn-sm btn-block">
                        <i class="fas fa-search mr-1"></i> Buscar
                    </button>
                </div>
                <div class="col-md-2 text-right">
                    <span class="badge badge-success px-2 py-1">
                        <i class="fas fa-check mr-1"></i> Validado
                    </span>
                    <span class="badge badge-warning px-2 py-1 ml-1">
                        <i class="fas fa-clock mr-1"></i> Pendiente
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="tablaAgenda" class="table table-sm table-hover mb-0" style="width:100%">
                    <thead class="thead-light">
                        <tr>
                            <th style="width:60px">Hora</th>
                            <th>Paciente</th>
                            <th>Tipo / Documento</th>
                            <th>Empresa / EPS</th>
                            <th>Procedimiento</th>
                            <th>Factura</th>
                            <th style="width:130px">Estado validación</th>
                            <th style="width:140px"></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal imagen --}}
<div class="modal fade" id="modalImagen" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-image mr-1"></i> Pantallazo de validación
                </h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body modal-imagen text-center"></div>
        </div>
    </div>
</div>
@endsection
