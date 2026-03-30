@extends("theme.$theme.layout")

@section('titulo')
    Especialidades Médicas
@endsection

@section("styles")
<link href="{{asset("assets/$theme/plugins/datatables-bs4/css/dataTables.bootstrap4.css")}}" rel="stylesheet" type="text/css"/>
@endsection

@section('scripts')
<script src="{{asset("assets/$theme/plugins/datatables/jquery.dataTables.js")}}"></script>
<script src="{{asset("assets/$theme/plugins/datatables-bs4/js/dataTables.bootstrap4.js")}}"></script>
<script>
    $(document).ready(function() {
        $('#tablaEspecialidades').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
            },
            "order": [[1, "asc"]]
        });
    });

    function confirmarEliminacion(id) {
        if (confirm('¿Está seguro de eliminar esta especialidad?')) {
            document.getElementById('form-delete-' + id).submit();
        }
    }
</script>
@endsection

@section('contenido')
<div class="content-wrapper" style="min-height: 543px;">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><i class="fas fa-stethoscope"></i> Especialidades Médicas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
                        <li class="breadcrumb-item active">Especialidades</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Listado de Especialidades</h3>
                    <div class="card-tools">
                        <a href="{{route('especialidades.create')}}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Nueva Especialidad
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

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table id="tablaEspecialidades" class="table table-bordered table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Profesionales</th>
                                    <th>Plantillas CI</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($especialidades as $especialidad)
                                <tr>
                                    <td>{{ $especialidad->id }}</td>
                                    <td><strong>{{ $especialidad->nombre }}</strong></td>
                                    <td>{{ $especialidad->descripcion ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-primary">{{ $especialidad->profesionales_count ?? 0 }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-info">{{ $especialidad->plantillas_count ?? 0 }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($especialidad->activo)
                                            <span class="badge badge-success">Activa</span>
                                        @else
                                            <span class="badge badge-danger">Inactiva</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{route('especialidades.edit', $especialidad->id)}}" class="btn btn-info btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form id="form-delete-{{$especialidad->id}}" action="{{route('especialidades.destroy', $especialidad->id)}}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmarEliminacion({{$especialidad->id}})" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
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
@endsection
