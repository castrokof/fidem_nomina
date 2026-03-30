@extends("theme.$theme.layout")

@section('titulo')
    Editar Paciente
@endsection

@section('contenido')
<div class="content-wrapper" style="min-height: 543px;">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><i class="fas fa-user-edit"></i> Editar Paciente</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{route('pacientes.index')}}">Pacientes</a></li>
                        <li class="breadcrumb-item active">Editar</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Editar Datos del Paciente</h3>
                </div>
                <form action="{{route('pacientes.update', $paciente->id)}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> <strong>Nota:</strong> Los campos básicos (nombres, apellidos, documento) se sincronizan automáticamente desde el sistema de citas. Puede completar los campos adicionales aquí.
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombres">Nombres</label>
                                    <input type="text" name="nombres" id="nombres" class="form-control" value="{{old('nombres', $paciente->nombres)}}" readonly>
                                    <small class="form-text text-muted">Este campo se actualiza automáticamente desde citas</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="apellidos">Apellidos</label>
                                    <input type="text" name="apellidos" id="apellidos" class="form-control" value="{{old('apellidos', $paciente->apellidos)}}" readonly>
                                    <small class="form-text text-muted">Este campo se actualiza automáticamente desde citas</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tipo_documento">Tipo de Documento</label>
                                    <input type="text" class="form-control" value="{{$paciente->tipo_documento}}" readonly>
                                    <small class="form-text text-muted">Este campo se actualiza automáticamente desde citas</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="numero_documento">Número de Documento</label>
                                    <input type="text" class="form-control" value="{{$paciente->numero_documento}}" readonly>
                                    <small class="form-text text-muted">Este campo se actualiza automáticamente desde citas</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="historia_clinica">Historia Clínica</label>
                                    <input type="text" class="form-control" value="{{$paciente->historia_clinica}}" readonly>
                                    <small class="form-text text-muted">Este campo se actualiza automáticamente desde citas</small>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5>Información Adicional (Editable)</h5>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control" value="{{old('fecha_nacimiento', $paciente->fecha_nacimiento)}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edad">Edad</label>
                                    <input type="number" name="edad" id="edad" class="form-control" value="{{old('edad', $paciente->edad)}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="genero">Género</label>
                                    <select name="genero" id="genero" class="form-control">
                                        <option value="">Seleccione...</option>
                                        <option value="Masculino" {{old('genero', $paciente->genero) == 'Masculino' ? 'selected' : ''}}>Masculino</option>
                                        <option value="Femenino" {{old('genero', $paciente->genero) == 'Femenino' ? 'selected' : ''}}>Femenino</option>
                                        <option value="Otro" {{old('genero', $paciente->genero) == 'Otro' ? 'selected' : ''}}>Otro</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="telefono">Teléfono</label>
                                    <input type="text" name="telefono" id="telefono" class="form-control" value="{{old('telefono', $paciente->telefono)}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="form-control" value="{{old('email', $paciente->email)}}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Actualizar Paciente
                        </button>
                        <a href="{{route('pacientes.index')}}" class="btn btn-default">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
