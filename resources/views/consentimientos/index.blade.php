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
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
            },
            "order": [[0, "desc"]]
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
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Listado de Consentimientos Informados</h3>
                    <div class="card-tools">
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
                                    <th>Fecha Cita</th>
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
                                    <td>{{ \Carbon\Carbon::parse($consentimiento->fecha_cita)->format('d/m/Y H:i') }}</td>
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
