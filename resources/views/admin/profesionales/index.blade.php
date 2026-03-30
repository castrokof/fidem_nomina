@extends("theme.$theme.layout")

@section('titulo')
    Profesionales
@endsection

@section("styles")
<link href="{{asset("assets/$theme/plugins/datatables-bs4/css/dataTables.bootstrap4.css")}}" rel="stylesheet" type="text/css"/>
@endsection

@section('scripts')
<script src="{{asset("assets/$theme/plugins/datatables/jquery.dataTables.js")}}"></script>
<script src="{{asset("assets/$theme/plugins/datatables-bs4/js/dataTables.bootstrap4.js")}}"></script>
<script>
    $(document).ready(function() {
        $('#tablaProfesionales').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
            }
        });
    });

    function confirmarEliminacion(id) {
        if (confirm('¿Está seguro de eliminar este profesional?')) {
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
                    <h1 class="m-0 text-dark"><i class="fas fa-user-md"></i> Profesionales</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
                        <li class="breadcrumb-item active">Profesionales</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Listado de Profesionales</h3>
                    <div class="card-tools">
                        <a href="{{route('profesionales.create')}}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Nuevo Profesional
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
                        <table id="tablaProfesionales" class="table table-bordered table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombres</th>
                                    <th>Apellidos</th>
                                    <th>Documento</th>
                                    <th>Especialidad</th>
                                    <th>Registro Médico</th>
                                    <th>Tiene Firma</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($profesionales as $profesional)
                                <tr>
                                    <td>{{ $profesional->id }}</td>
                                    <td>{{ $profesional->nombres }}</td>
                                    <td>{{ $profesional->apellidos }}</td>
                                    <td>{{ $profesional->tipo_documento }}-{{ $profesional->numero_documento }}</td>
                                    <td>
                                        @if($profesional->especialidad)
                                            <span class="badge badge-info">{{ $profesional->especialidad->nombre }}</span>
                                        @else
                                            <span class="badge badge-secondary">Sin especialidad</span>
                                        @endif
                                    </td>
                                    <td>{{ $profesional->registro_medico ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        @if($profesional->firma_base64)
                                            <span class="badge badge-success">
                                                <i class="fas fa-check"></i> Sí
                                            </span>
                                        @else
                                            <span class="badge badge-warning">
                                                <i class="fas fa-times"></i> No
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($profesional->activo)
                                            <span class="badge badge-success">Activo</span>
                                        @else
                                            <span class="badge badge-danger">Inactivo</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{route('profesionales.firma', $profesional->id)}}" class="btn btn-warning btn-sm" title="Registrar/Ver Firma">
                                            <i class="fas fa-signature"></i>
                                        </a>
                                        <a href="{{route('profesionales.edit', $profesional->id)}}" class="btn btn-info btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form id="form-delete-{{$profesional->id}}" action="{{route('profesionales.destroy', $profesional->id)}}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmarEliminacion({{$profesional->id}})" title="Eliminar">
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
