@extends("theme.$theme.layout")

@section('titulo')
    Detalle del Paciente
@endsection

@section('contenido')
<div class="content-wrapper" style="min-height: 543px;">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><i class="fas fa-user"></i> Detalle del Paciente</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{route('pacientes.index')}}">Pacientes</a></li>
                        <li class="breadcrumb-item active">Detalle</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Información Personal -->
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title"><i class="fas fa-user"></i> Información Personal</h3>
                    <div class="card-tools">
                        <a href="{{route('pacientes.edit', $paciente->id)}}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="{{route('pacientes.index')}}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nombres:</strong> {{$paciente->nombres}}</p>
                            <p><strong>Apellidos:</strong> {{$paciente->apellidos}}</p>
                            <p><strong>Tipo de Documento:</strong> {{$paciente->tipo_documento}}</p>
                            <p><strong>Número de Documento:</strong> {{$paciente->numero_documento}}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Historia Clínica:</strong> {{$paciente->historia_clinica ?? 'N/A'}}</p>
                            <p><strong>Fecha de Nacimiento:</strong> {{$paciente->fecha_nacimiento ? \Carbon\Carbon::parse($paciente->fecha_nacimiento)->format('d/m/Y') : 'N/A'}}</p>
                            <p><strong>Edad:</strong> {{$paciente->edad ?? 'N/A'}}</p>
                            <p><strong>Género:</strong> {{$paciente->genero ?? 'N/A'}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Teléfono:</strong> {{$paciente->telefono ?? 'N/A'}}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Email:</strong> {{$paciente->email ?? 'N/A'}}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Consentimientos del Paciente -->
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title"><i class="fas fa-file-signature"></i> Consentimientos Informados</h3>
                </div>
                <div class="card-body">
                    @if($paciente->consentimientos->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Procedimiento</th>
                                        <th>Profesional</th>
                                        <th>Fecha Procedimiento</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($paciente->consentimientos as $consentimiento)
                                    <tr>
                                        <td>{{$consentimiento->id}}</td>
                                        <td>{{$consentimiento->plantilla->nombre}}</td>
                                        <td>{{$consentimiento->profesional->nombres}} {{$consentimiento->profesional->apellidos}}</td>
                                        <td>{{\Carbon\Carbon::parse($consentimiento->fecha_procedimiento)->format('d/m/Y H:i')}}</td>
                                        <td>
                                            @if($consentimiento->estado == 'pendiente')
                                                <span class="badge badge-warning">Pendiente</span>
                                            @elseif($consentimiento->estado == 'firmado')
                                                <span class="badge badge-success">Firmado</span>
                                            @elseif($consentimiento->estado == 'anulado')
                                                <span class="badge badge-danger">Anulado</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{route('consentimientos.show', $consentimiento->id)}}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> Ver
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Este paciente no tiene consentimientos informados registrados.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
