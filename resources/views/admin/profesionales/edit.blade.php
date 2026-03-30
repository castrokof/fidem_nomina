@extends("theme.$theme.layout")

@section('titulo')
    Editar Profesional
@endsection

@section("styles")
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.0.0/dist/select2-bootstrap4.min.css" rel="stylesheet" />
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
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
                    <h1 class="m-0 text-dark"><i class="fas fa-user-md"></i> Editar Profesional</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{route('profesionales.index')}}">Profesionales</a></li>
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
                    <h3 class="card-title">Editar Datos del Profesional</h3>
                    <div class="card-tools">
                        <a href="{{route('profesionales.firma', $profesional->id)}}" class="btn btn-warning btn-sm">
                            <i class="fas fa-signature"></i> Gestionar Firma
                        </a>
                    </div>
                </div>
                <form action="{{route('profesionales.update', $profesional->id)}}" method="POST">
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

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombres">Nombres <span class="text-danger">*</span></label>
                                    <input type="text" name="nombres" id="nombres" class="form-control" value="{{old('nombres', $profesional->nombres)}}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="apellidos">Apellidos <span class="text-danger">*</span></label>
                                    <input type="text" name="apellidos" id="apellidos" class="form-control" value="{{old('apellidos', $profesional->apellidos)}}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tipo_documento">Tipo de Documento <span class="text-danger">*</span></label>
                                    <select name="tipo_documento" id="tipo_documento" class="form-control" required>
                                        <option value="">Seleccione...</option>
                                        <option value="CC" {{old('tipo_documento', $profesional->tipo_documento) == 'CC' ? 'selected' : ''}}>Cédula de Ciudadanía</option>
                                        <option value="CE" {{old('tipo_documento', $profesional->tipo_documento) == 'CE' ? 'selected' : ''}}>Cédula de Extranjería</option>
                                        <option value="PA" {{old('tipo_documento', $profesional->tipo_documento) == 'PA' ? 'selected' : ''}}>Pasaporte</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="numero_documento">Número de Documento <span class="text-danger">*</span></label>
                                    <input type="text" name="numero_documento" id="numero_documento" class="form-control" value="{{old('numero_documento', $profesional->numero_documento)}}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="registro_medico">Registro Médico <span class="text-danger">*</span></label>
                                    <input type="text" name="registro_medico" id="registro_medico" class="form-control" value="{{old('registro_medico', $profesional->registro_medico)}}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="especialidad_id">Especialidad <span class="text-danger">*</span></label>
                                    <select name="especialidad_id" id="especialidad_id" class="form-control select2" required>
                                        <option value="">Seleccione una especialidad...</option>
                                        @foreach($especialidades as $especialidad)
                                            <option value="{{$especialidad->id}}" {{old('especialidad_id', $profesional->especialidad_id) == $especialidad->id ? 'selected' : ''}}>
                                                {{$especialidad->nombre}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="codigo_usuario">Código de Usuario (Facturación)</label>
                                    <input type="text" name="codigo_usuario" id="codigo_usuario" class="form-control" value="{{old('codigo_usuario', $profesional->codigo_usuario)}}" placeholder="Debe coincidir con CODIGO_USUARIO de fac_m_citas">
                                    <small class="form-text text-muted">Este código se usa para sincronizar con el sistema de citas</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="form-control" value="{{old('email', $profesional->email)}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="telefono">Teléfono</label>
                                    <input type="text" name="telefono" id="telefono" class="form-control" value="{{old('telefono', $profesional->telefono)}}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="usuario_id">Usuario del Sistema (Opcional)</label>
                                    <select name="usuario_id" id="usuario_id" class="form-control select2">
                                        <option value="">Sin usuario de sistema...</option>
                                        @foreach($usuarios as $usuario)
                                            <option value="{{$usuario->id}}" {{old('usuario_id', $profesional->usuario_id) == $usuario->id ? 'selected' : ''}}>
                                                {{$usuario->usuario}} - {{$usuario->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Seleccione solo si este profesional tiene acceso al sistema</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input type="checkbox" name="activo" id="activo" class="form-check-input" value="1" {{old('activo', $profesional->activo) ? 'checked' : ''}}>
                                    <label class="form-check-label" for="activo">
                                        Profesional Activo
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Actualizar Profesional
                        </button>
                        <a href="{{route('profesionales.index')}}" class="btn btn-default">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
