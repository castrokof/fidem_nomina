@extends("theme.$theme.layout")

@section('titulo')
    Consentimientos Informados
@endsection

@section("styles")
<link href="{{asset("assets/$theme/plugins/datatables-bs4/css/dataTables.bootstrap4.css")}}" rel="stylesheet" type="text/css"/>
@endsection

@section('scripts')
<script src="{{asset("assets/$theme/plugins/datatables/jquery.dataTables.js")}}"></script>
<script src="{{asset("assets/$theme/plugins/datatables-bs4/js/dataTables.bootstrap4.js")}}"></script>
<script>
$(document).ready(function() {

    let dtInstance = null;

    // ── Inicializar DataTable ────────────────────────────────────────────────
    function initDT() {
        if (dtInstance) { dtInstance.destroy(); dtInstance = null; }
        dtInstance = $('#tablaConsentimientos').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json',
                emptyTable: '<i class="fas fa-filter mr-1"></i> Use los filtros para buscar consentimientos.'
            },
            order: [[5, 'desc']],
            pageLength: 25,
            columnDefs: [
                {
                    // Columna Fecha: se pasa el ISO string para ordenar, se formatea para mostrar
                    targets: 5,
                    render: function(data, type) {
                        if (type === 'sort' || type === 'type') return data;
                        if (!data) return '-';
                        const d = new Date(data.replace(' ', 'T'));
                        return d.toLocaleDateString('es-CO', { day:'2-digit', month:'2-digit', year:'numeric' })
                             + ' ' + d.toLocaleTimeString('es-CO', { hour:'2-digit', minute:'2-digit', hour12:false });
                    }
                },
                { targets: [6, 7, 8], orderable: false } // Estado, Creado por, Acciones
            ]
        });
    }
    initDT();

    // ── Renderizar filas desde JSON ──────────────────────────────────────────
    function renderTabla(data) {
        dtInstance.clear();

        if (data.length) {
            data.forEach(function(c) {
                let badge = '';
                if      (c.estado === 'pendiente')  badge = '<span class="badge badge-warning"><i class="fas fa-clock"></i> Pendiente</span>';
                else if (c.estado === 'en_proceso') badge = '<span class="badge badge-info"><i class="fas fa-spinner"></i> En proceso</span>';
                else if (c.estado === 'firmado')    badge = '<span class="badge badge-success"><i class="fas fa-check"></i> Firmado</span>';
                else if (c.estado === 'anulado')    badge = '<span class="badge badge-danger"><i class="fas fa-times"></i> Anulado</span>';
                else                                badge = '<span class="badge badge-secondary">' + c.estado + '</span>';

                const pac  = (c.paciente  || '').replace(/"/g, '&quot;');
                const plnt = (c.plantilla || '').replace(/"/g, '&quot;');

                let acc = `<a href="${c.url_show}" class="btn btn-info btn-sm" title="Ver Detalle"><i class="fas fa-eye"></i></a> `;
                if (c.estado === 'firmado')
                    acc += `<a href="${c.url_pdf}" class="btn btn-danger btn-sm" title="Descargar PDF" target="_blank"><i class="fas fa-file-pdf"></i></a> `;
                if (c.estado === 'pendiente' || c.estado === 'en_proceso')
                    acc += `<button type="button" class="btn btn-warning btn-sm" title="Copiar enlace de firma"
                                onclick="copiarEnlaceFirma('${c.url_firma}')"><i class="fas fa-link"></i></button> `;
                if (c.estado === 'firmado')
                    acc += `<button type="button" class="btn btn-danger btn-sm btn-anular-firmado" title="Anular (requiere contraseña)"
                                data-url="${c.url_anular}" data-paciente="${pac}" data-plantilla="${plnt}">
                                <i class="fas fa-ban"></i> <i class="fas fa-lock fa-xs"></i></button>`;
                else if (c.estado !== 'anulado')
                    acc += `<button type="button" class="btn btn-secondary btn-sm btn-anular" title="Anular consentimiento"
                                data-url="${c.url_anular}" data-paciente="${pac}" data-plantilla="${plnt}">
                                <i class="fas fa-ban"></i></button>`;

                dtInstance.row.add([
                    c.id,
                    c.paciente,
                    c.documento,
                    c.plantilla,
                    c.profesional,
                    c.fecha_sort,   // ISO → ordenado por columnDefs.render, mostrado formateado
                    badge,
                    '<small class="text-muted"><i class="fas fa-user fa-xs"></i> ' + (c.creado_por || '—') + '</small>',
                    acc
                ]);
            });
        }

        dtInstance.draw();
    }

    // ── Buscar vía AJAX ──────────────────────────────────────────────────────
    $('#formFiltros').submit(function(e) {
        e.preventDefault();

        const $btn = $('#btnBuscar');
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Buscando...');
        $('#contadorResultados').text('');

        $.ajax({
            url:  '{{ route("consentimientos.index") }}',
            type: 'GET',
            data: $(this).serialize(),
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(res) {
                renderTabla(res.consentimientos || []);
                const n = res.total || 0;
                $('#contadorResultados').html(
                    `<span class="text-muted small ml-2"><i class="fas fa-list mr-1"></i>${n} resultado(s)</span>`
                );
            },
            error: function() {
                Swal.fire('Error', 'No se pudieron cargar los datos.', 'error');
            },
            complete: function() {
                $btn.prop('disabled', false).html('<i class="fas fa-search"></i> Buscar');
            }
        });
    });

    // ── Limpiar filtros ──────────────────────────────────────────────────────
    $('#btnLimpiar').click(function() {
        $('#formFiltros')[0].reset();
        $('#contadorResultados').text('');
        dtInstance.clear().draw();
    });

    // ── Anular consentimiento ────────────────────────────────────────────────
    $(document).on('click', '.btn-anular', function() {
        const btn  = $(this);
        const url  = btn.data('url');
        const pac  = btn.data('paciente');
        const plnt = btn.data('plantilla');

        Swal.fire({
            title: '¿Anular consentimiento?',
            html: `<p>Paciente: <strong>${pac}</strong></p><p>Procedimiento: <strong>${plnt}</strong></p>
                   <p class="text-danger mt-2">Esta acción no se puede revertir.</p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-ban"></i> Sí, anular',
            cancelButtonText: 'Cancelar'
        }).then(function(result) {
            if (!result.isConfirmed) return;

            $.ajax({
                url: url,
                method: 'POST',
                data: { _token: '{{ csrf_token() }}', _method: 'PATCH' },
                success: function(response) {
                    if (response.success) {
                        const fila = btn.closest('tr');
                        fila.find('td:nth-child(7)').html(
                            '<span class="badge badge-danger"><i class="fas fa-times"></i> Anulado</span>'
                        );
                        fila.find('.btn-anular, .btn-warning[title="Copiar enlace de firma"]').remove();
                        Swal.fire({ toast: true, icon: 'success', title: response.message,
                            position: 'top-end', timer: 3000, showConfirmButton: false });
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', xhr.responseJSON?.message || 'No se pudo anular.', 'error');
                }
            });
        });
    });

    // ── Anular consentimiento FIRMADO (requiere contraseña) ─────────────────
    $(document).on('click', '.btn-anular-firmado', function() {
        const btn  = $(this);
        const url  = btn.data('url');
        const pac  = btn.data('paciente');
        const plnt = btn.data('plantilla');

        Swal.fire({
            title: 'Anular consentimiento firmado',
            html: `<p>Paciente: <strong>${pac}</strong><br>Procedimiento: <strong>${plnt}</strong></p>
                   <p class="text-danger font-weight-bold">Este consentimiento ya fue FIRMADO.<br>Ingrese la contraseña para anularlo:</p>
                   <input id="swal-pwd" type="password" class="swal2-input" placeholder="Contraseña de autorización">`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-ban"></i> Anular de todas formas',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {
                const pwd = Swal.getPopup().querySelector('#swal-pwd').value;
                if (!pwd) { Swal.showValidationMessage('Ingrese la contraseña'); return false; }
                return pwd;
            }
        }).then(function(result) {
            if (!result.isConfirmed) return;

            $.ajax({
                url: url,
                method: 'POST',
                data: { _token: '{{ csrf_token() }}', _method: 'PATCH', password: result.value },
                success: function(response) {
                    if (response.success) {
                        const fila = btn.closest('tr');
                        fila.find('td:nth-child(7)').html(
                            '<span class="badge badge-danger"><i class="fas fa-times"></i> Anulado</span>'
                        );
                        fila.find('.btn-anular-firmado, .btn-warning[title="Copiar enlace de firma"]').remove();
                        Swal.fire({ toast: true, icon: 'success', title: response.message,
                            position: 'top-end', timer: 3000, showConfirmButton: false });
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', xhr.responseJSON?.message || 'No se pudo anular.', 'error');
                }
            });
        });
    });

    // ── Sincronizar agenda ───────────────────────────────────────────────────
    $('#btnSincronizarAgenda').click(function() {
        if (!confirm('¿Desea sincronizar la agenda desde la API?')) return;
        const btn = $(this), orig = btn.html();
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sincronizando...');
        $.ajax({
            url: '{{ route("agenda.sync.sincronizar") }}',
            method: 'POST',
            data: { _token: '{{ csrf_token() }}', dias_atras: 2, dias_adelante: 3 },
            success: function(r) {
                alert('✓ ' + r.message + '\n\nEl job se procesará automáticamente.');
            },
            error: function(xhr) {
                alert('Error: ' + (xhr.responseJSON?.message || 'No se pudo sincronizar.'));
            },
            complete: function() { btn.prop('disabled', false).html(orig); }
        });
    });

});

function copiarEnlaceFirma(url) {
    navigator.clipboard.writeText(url).then(function() {
        Swal.fire({ toast: true, icon: 'success', title: 'Enlace copiado',
            position: 'top-end', timer: 2000, showConfirmButton: false });
    }).catch(function() {
        const inp = document.createElement('input');
        inp.value = url;
        document.body.appendChild(inp);
        inp.select(); document.execCommand('copy');
        document.body.removeChild(inp);
        alert('Enlace copiado:\n' + url);
    });
}
</script>
@endsection

@section('contenido')
<div class="content-wrapper" style="min-height: 543px;">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><i class="fas fa-file-signature"></i> Consentimientos Informados</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
                        <li class="breadcrumb-item active">Consentimientos</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            {{-- Panel de filtros (colapsado por defecto) --}}
            <div class="card card-outline card-primary collapsed-card mb-3">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-filter"></i> Filtros de búsqueda</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display:none;">
                    <form id="formFiltros">
                        <div class="row align-items-end">
                            <div class="col-md-2 col-sm-6 mb-2">
                                <label class="small font-weight-bold">Fecha desde</label>
                                <input type="date" name="fecha_desde" class="form-control form-control-sm" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-2 col-sm-6 mb-2">
                                <label class="small font-weight-bold">Fecha hasta</label>
                                <input type="date" name="fecha_hasta" class="form-control form-control-sm" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-2 col-sm-6 mb-2">
                                <label class="small font-weight-bold">Documento paciente</label>
                                <input type="text" name="documento" class="form-control form-control-sm" placeholder="Ej: 1234567">
                            </div>
                            <div class="col-md-3 col-sm-6 mb-2">
                                <label class="small font-weight-bold">Médico</label>
                                <select name="medico" class="form-control form-control-sm">
                                    <option value="">Todos</option>
                                    @foreach($medicos as $m)
                                        <option value="{{ $m->id }}">{{ $m->apellidos }}, {{ $m->nombres }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 col-sm-6 mb-2">
                                <label class="small font-weight-bold">Estado</label>
                                <select name="estado" class="form-control form-control-sm">
                                    <option value="">Todos</option>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="en_proceso">En proceso</option>
                                    <option value="firmado">Firmado</option>
                                    <option value="anulado">Anulado</option>
                                </select>
                            </div>
                            <div class="col-md-1 col-sm-12 mb-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary btn-sm btn-block" id="btnBuscar">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-1">
                            <button type="button" id="btnLimpiar" class="btn btn-link btn-sm p-0 text-secondary">
                                <i class="fas fa-times-circle"></i> Limpiar filtros
                            </button>
                            <span id="contadorResultados"></span>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Listado de Consentimientos Informados</h3>
                    <div class="card-tools">
                        <button type="button" id="btnSincronizarAgenda" class="btn btn-success btn-sm mr-2">
                            <i class="fas fa-sync"></i> Sincronizar Agenda
                        </button>
                        <a href="{{route('consentimientos.create')}}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Nuevo Consentimiento
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tablaConsentimientos" class="table table-bordered table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Paciente</th>
                                    <th>Documento</th>
                                    <th>Procedimiento</th>
                                    <th>Profesional</th>
                                    <th>Fecha Procedimiento</th>
                                    <th>Estado</th>
                                    <th>Creado por</th>
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
@endsection
