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
        $('#tablaConsentimientos').DataTable({
            "language": { "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json" },
            "order": [[5, "desc"]],
            "pageLength": 25
        });

        // ========== ANULAR CONSENTIMIENTO ==========
        $(document).on('click', '.btn-anular', function() {
            const btn  = $(this);
            const url  = btn.data('url');
            const pac  = btn.data('paciente');
            const plnt = btn.data('plantilla');

            Swal.fire({
                title: '¿Anular consentimiento?',
                html: `<p>Paciente: <strong>${pac}</strong></p><p>Procedimiento: <strong>${plnt}</strong></p><p class="text-danger mt-2">Esta acción no se puede revertir.</p>`,
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
                            // Actualizar badge de estado en la fila
                            const fila = btn.closest('tr');
                            fila.find('td:nth-child(7)').html('<span class="badge badge-danger"><i class="fas fa-times"></i> Anulado</span>');
                            // Ocultar botones que ya no aplican (link y anular)
                            fila.find('.btn-anular, .btn-warning[title="Copiar enlace de firma"]').remove();

                            Swal.fire({ toast: true, icon: 'success', title: response.message,
                                position: 'top-end', timer: 3000, showConfirmButton: false });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseJSON?.message || 'No se pudo anular el consentimiento.', 'error');
                    }
                });
            });
        });

        // Función para sincronizar agenda
        $('#btnSincronizarAgenda').click(function() {
            if (!confirm('¿Desea sincronizar la agenda de consentimientos informados desde la API?')) return;

            const btn = $(this);
            const originalHtml = btn.html();
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sincronizando...');

            $.ajax({
                url: '{{ route("agenda.sync.sincronizar") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    dias_atras: 2,
                    dias_adelante: 3
                },
                success: function(response) {
                    alert('✓ ' + response.message + '\n\nEl job se ha agregado a la cola y se procesará automáticamente.');
                    btn.prop('disabled', false).html(originalHtml);
                },
                error: function(xhr) {
                    alert('Error: ' + (xhr.responseJSON?.message || 'No se pudo sincronizar. Verifica que el sistema de colas esté configurado.'));
                    btn.prop('disabled', false).html(originalHtml);
                }
            });
        });
    });
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

            {{-- Panel de filtros --}}
            <div class="card card-outline card-primary mb-3">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-filter"></i> Filtros</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('consentimientos.index') }}" id="formFiltros">
                        <div class="row align-items-end">
                            <div class="col-md-2 col-sm-6 mb-2">
                                <label class="small font-weight-bold">Fecha desde</label>
                                <input type="date" name="fecha_desde" class="form-control form-control-sm"
                                       value="{{ request('fecha_desde') }}">
                            </div>
                            <div class="col-md-2 col-sm-6 mb-2">
                                <label class="small font-weight-bold">Fecha hasta</label>
                                <input type="date" name="fecha_hasta" class="form-control form-control-sm"
                                       value="{{ request('fecha_hasta') }}">
                            </div>
                            <div class="col-md-2 col-sm-6 mb-2">
                                <label class="small font-weight-bold">Documento paciente</label>
                                <input type="text" name="documento" class="form-control form-control-sm"
                                       placeholder="Ej: 1234567"
                                       value="{{ request('documento') }}">
                            </div>
                            <div class="col-md-3 col-sm-6 mb-2">
                                <label class="small font-weight-bold">Médico</label>
                                <select name="medico" class="form-control form-control-sm">
                                    <option value="">Todos</option>
                                    @foreach($medicos as $m)
                                        <option value="{{ $m->id }}" {{ request('medico') == $m->id ? 'selected' : '' }}>
                                            {{ $m->apellidos }}, {{ $m->nombres }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 col-sm-6 mb-2">
                                <label class="small font-weight-bold">Estado</label>
                                <select name="estado" class="form-control form-control-sm">
                                    <option value="">Todos</option>
                                    <option value="pendiente"  {{ request('estado') == 'pendiente'  ? 'selected' : '' }}>Pendiente</option>
                                    <option value="en_proceso" {{ request('estado') == 'en_proceso' ? 'selected' : '' }}>En proceso</option>
                                    <option value="firmado"    {{ request('estado') == 'firmado'    ? 'selected' : '' }}>Firmado</option>
                                    <option value="anulado"    {{ request('estado') == 'anulado'    ? 'selected' : '' }}>Anulado</option>
                                </select>
                            </div>
                            <div class="col-md-1 col-sm-12 mb-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary btn-sm btn-block">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                        @if(request()->hasAny(['fecha_desde','fecha_hasta','documento','medico','estado']))
                            <div class="mt-1">
                                <a href="{{ route('consentimientos.index') }}" class="btn btn-link btn-sm p-0 text-secondary">
                                    <i class="fas fa-times-circle"></i> Limpiar filtros
                                </a>
                                <span class="text-muted small ml-2">{{ $consentimientos->count() }} resultado(s)</span>
                            </div>
                        @endif
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
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

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
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($consentimientos as $consentimiento)
                                <tr>
                                    <td>{{ $consentimiento->id }}</td>
                                    <td>{{ $consentimiento->paciente->nombres }} {{ $consentimiento->paciente->apellidos }}</td>
                                    <td>{{ $consentimiento->paciente->tipo_documento }}-{{ $consentimiento->paciente->numero_documento }}</td>
                                    <td>{{ $consentimiento->plantilla->nombre }}</td>
                                    <td>{{ $consentimiento->profesional->nombres }} {{ $consentimiento->profesional->apellidos }}</td>
                                    <td>{{ \Carbon\Carbon::parse($consentimiento->fecha_procedimiento)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($consentimiento->estado == 'pendiente')
                                            <span class="badge badge-warning">
                                                <i class="fas fa-clock"></i> Pendiente
                                            </span>
                                        @elseif($consentimiento->estado == 'firmado')
                                            <span class="badge badge-success">
                                                <i class="fas fa-check"></i> Firmado
                                            </span>
                                        @elseif($consentimiento->estado == 'anulado')
                                            <span class="badge badge-danger">
                                                <i class="fas fa-times"></i> Anulado
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{route('consentimientos.show', $consentimiento->id)}}" class="btn btn-info btn-sm" title="Ver Detalle">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($consentimiento->estado == 'firmado')
                                            <a href="{{route('consentimientos.pdf', $consentimiento->id)}}" class="btn btn-danger btn-sm" title="Descargar PDF" target="_blank">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        @endif
                                        @if($consentimiento->estado == 'pendiente')
                                            <button type="button" class="btn btn-warning btn-sm" title="Copiar enlace de firma" onclick="copiarEnlaceFirma('{{ route('consentimientos.firmar', $consentimiento->token_firma) }}')">
                                                <i class="fas fa-link"></i>
                                            </button>
                                        @endif
                                        @if($consentimiento->estado != 'anulado')
                                            <button type="button"
                                                class="btn btn-secondary btn-sm btn-anular"
                                                title="Anular consentimiento"
                                                data-id="{{ $consentimiento->id }}"
                                                data-url="{{ route('consentimientos.anular', $consentimiento->id) }}"
                                                data-paciente="{{ $consentimiento->paciente->nombres }} {{ $consentimiento->paciente->apellidos }}"
                                                data-plantilla="{{ $consentimiento->plantilla->nombre }}">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    function copiarEnlaceFirma(url) {
        const input = document.createElement('input');
        input.value = url;
        document.body.appendChild(input);
        input.select();
        document.execCommand('copy');
        document.body.removeChild(input);

        alert('Enlace copiado al portapapeles:\n' + url);
    }
</script>
@endsection
