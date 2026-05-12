@extends("theme.$theme.layout")

@section('titulo')
    Validación de Derechos
@endsection

@section("styles")
<link href="{{asset("assets/$theme/plugins/datatables-bs4/css/dataTables.bootstrap4.css")}}" rel="stylesheet" type="text/css"/>
<style>
    .badge-validado { background-color: #28a745; color: #fff; }
    #filtros-card .form-control { font-size: 13px; }
    .img-thumb { width: 60px; height: 40px; object-fit: cover; border-radius: 4px; cursor: pointer; border: 1px solid #dee2e6; }
    .modal-imagen img { max-width: 100%; border-radius: 6px; }
</style>
@endsection

@section('scripts')
<script src="{{asset("assets/$theme/plugins/datatables/jquery.dataTables.js")}}"></script>
<script src="{{asset("assets/$theme/plugins/datatables-bs4/js/dataTables.bootstrap4.js")}}"></script>
<script>
$(document).ready(function() {

    let dtInstance = null;

    function initDT() {
        if (dtInstance) { dtInstance.destroy(); dtInstance = null; }
        dtInstance = $('#tablaValidaciones').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json',
                emptyTable: '<i class="fas fa-filter mr-1"></i> Use los filtros para buscar registros.' },
            order: [[6, 'desc']],
            pageLength: 25,
            columnDefs: [
                { targets: '_all', defaultContent: '' },
                {
                    targets: 6,
                    render: function(data, type) {
                        if (type === 'sort' || type === 'type') return data;
                        if (!data) return '-';
                        const d = new Date(data * 1000);
                        return d.toLocaleDateString('es-CO', { day:'2-digit', month:'2-digit', year:'numeric' })
                             + ' ' + d.toLocaleTimeString('es-CO', { hour:'2-digit', minute:'2-digit', hour12:false });
                    }
                },
                { targets: [0, 8], orderable: false }
            ]
        });
    }
    initDT();

    function renderTabla(data) {
        dtInstance.clear();
        if (data && data.length) {
            data.forEach(function(r) {
                dtInstance.row.add([
                    '<img src="' + r.imagen_url + '" class="img-thumb" data-url="' + r.imagen_url + '" title="Ver pantallazo">',
                    r.paciente_nombre,
                    r.paciente_cedula,
                    r.numero_factura,
                    r.empresafac,
                    r.fecha_atencion,
                    r.created_at_sort,
                    r.created_by_nombre,
                    '<button class="btn btn-sm btn-outline-danger btn-eliminar" data-url="' + r.eliminar_url + '" data-id="' + r.id + '" title="Eliminar">' +
                        '<i class="fas fa-trash"></i></button>'
                ]);
            });
        }
        dtInstance.draw();
    }

    // ── Filtros ──────────────────────────────────────────────────────────────
    function buscar() {
        const params = {
            cedula:      $('#f_cedula').val().trim(),
            factura:     $('#f_factura').val().trim(),
            empresa:     $('#f_empresa').val().trim(),
            fecha_desde: $('#f_fecha_desde').val(),
            fecha_hasta: $('#f_fecha_hasta').val(),
        };
        $.ajax({
            url: '{{ route("validaciones.index") }}',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            data: params,
            success: function(resp) { renderTabla(resp); },
            error:   function() { alert('Error al cargar los registros.'); }
        });
    }

    $('#btn-buscar').on('click', buscar);
    $('#f_cedula, #f_factura, #f_empresa').on('keydown', function(e) {
        if (e.key === 'Enter') buscar();
    });

    // Buscar al cargar con fecha de hoy
    $('#f_fecha_desde, #f_fecha_hasta').val(new Date().toISOString().split('T')[0]);
    buscar();

    // ── Ver imagen en modal ──────────────────────────────────────────────────
    $(document).on('click', '.img-thumb', function() {
        $('#modalImagen .modal-imagen').html('<img src="' + $(this).data('url') + '" alt="Pantallazo">');
        $('#modalImagen').modal('show');
    });

    // ── Eliminar ─────────────────────────────────────────────────────────────
    $(document).on('click', '.btn-eliminar', function() {
        const url = $(this).data('url');
        const row = $(this).closest('tr');
        if (!confirm('¿Eliminar este registro? Esta acción no se puede deshacer.')) return;
        $.ajax({
            url:  url,
            type: 'POST',
            data: { _method: 'DELETE', _token: '{{ csrf_token() }}' },
            success: function() { dtInstance.row(row).remove().draw(); },
            error:   function() { alert('Error al eliminar.'); }
        });
    });
});
</script>
@endsection

@section('content')
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    {{-- Encabezado --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h4 class="mb-0"><i class="fas fa-shield-alt text-primary mr-2"></i>Validación de Derechos</h4>
            <small class="text-muted">Trazabilidad de pantallazos de validación de derechos EPS/aseguradora.</small>
        </div>
        <a href="{{ route('validaciones.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> Nuevo registro
        </a>
    </div>

    {{-- Filtros --}}
    <div class="card shadow-sm mb-3" id="filtros-card">
        <div class="card-body py-2">
            <div class="row align-items-end">
                <div class="col-md-2">
                    <label class="small mb-1">Cédula paciente</label>
                    <input type="text" id="f_cedula" class="form-control form-control-sm" placeholder="Número doc.">
                </div>
                <div class="col-md-2">
                    <label class="small mb-1">N° Factura</label>
                    <input type="text" id="f_factura" class="form-control form-control-sm" placeholder="Factura">
                </div>
                <div class="col-md-2">
                    <label class="small mb-1">Empresa / EPS</label>
                    <input type="text" id="f_empresa" class="form-control form-control-sm" placeholder="EPS, aseguradora…">
                </div>
                <div class="col-md-2">
                    <label class="small mb-1">Desde</label>
                    <input type="date" id="f_fecha_desde" class="form-control form-control-sm">
                </div>
                <div class="col-md-2">
                    <label class="small mb-1">Hasta</label>
                    <input type="date" id="f_fecha_hasta" class="form-control form-control-sm">
                </div>
                <div class="col-md-2">
                    <button id="btn-buscar" class="btn btn-primary btn-sm btn-block">
                        <i class="fas fa-search mr-1"></i> Buscar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="tablaValidaciones" class="table table-sm table-hover mb-0" style="width:100%">
                    <thead class="thead-light">
                        <tr>
                            <th>Imagen</th>
                            <th>Paciente</th>
                            <th>Cédula</th>
                            <th>Factura</th>
                            <th>Empresa / EPS</th>
                            <th>Fecha atención</th>
                            <th>Guardado</th>
                            <th>Registrado por</th>
                            <th></th>
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
                <h5 class="modal-title"><i class="fas fa-image mr-1"></i> Pantallazo de validación</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body modal-imagen text-center"></div>
        </div>
    </div>
</div>
@endsection
