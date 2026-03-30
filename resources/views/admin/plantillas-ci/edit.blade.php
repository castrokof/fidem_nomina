@extends("theme.$theme.layout")

@section('titulo')
    Editar Plantilla de Consentimiento Informado
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
            width: '100%',
            placeholder: 'Seleccione especialidades...'
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
                    <h1 class="m-0 text-dark"><i class="fas fa-file-contract"></i> Editar Plantilla CI</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{route('plantillas-ci.index')}}">Plantillas CI</a></li>
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
                    <h3 class="card-title">Editar Datos de la Plantilla</h3>
                </div>
                <form action="{{route('plantillas-ci.update', $plantilla->id)}}" method="POST">
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
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="nombre">Nombre de la Plantilla <span class="text-danger">*</span></label>
                                    <input type="text" name="nombre" id="nombre" class="form-control" value="{{old('nombre', $plantilla->nombre)}}" required placeholder="Ej: Consentimiento Informado para Cirugía">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="especialidades">Especialidades <span class="text-danger">*</span></label>
                                    <select name="especialidades[]" id="especialidades" class="form-control select2" multiple required>
                                        @foreach($especialidades as $especialidad)
                                            <option value="{{$especialidad->id}}"
                                                {{in_array($especialidad->id, old('especialidades', $plantilla->especialidades->pluck('id')->toArray())) ? 'selected' : ''}}>
                                                {{$especialidad->nombre}}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Seleccione una o más especialidades para las cuales aplica esta plantilla</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="contenido">Contenido de la Plantilla <span class="text-danger">*</span></label>
                                    <textarea name="contenido" id="contenido" class="form-control" rows="15" required>{{old('contenido', $plantilla->contenido)}}</textarea>
                                    <small class="form-text text-muted">
                                        <strong>Variables disponibles:</strong>
                                        {nombre_paciente}, {documento_paciente}, {edad_paciente}, {nombre_profesional},
                                        {registro_medico}, {especialidad}, {fecha_actual}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input type="checkbox" name="activo" id="activo" class="form-check-input" value="1" {{old('activo', $plantilla->activo) ? 'checked' : ''}}>
                                    <label class="form-check-label" for="activo">
                                        Plantilla Activa
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Actualizar Plantilla
                        </button>
                        <a href="{{route('plantillas-ci.index')}}" class="btn btn-default">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-info-circle"></i> Ayuda</h3>
                </div>
                <div class="card-body">
                    <p><strong>Variables disponibles para usar en el contenido:</strong></p>
                    <ul>
                        <li><code>{nombre_paciente}</code> - Nombre completo del paciente</li>
                        <li><code>{documento_paciente}</code> - Documento de identidad del paciente</li>
                        <li><code>{edad_paciente}</code> - Edad del paciente</li>
                        <li><code>{nombre_profesional}</code> - Nombre completo del profesional</li>
                        <li><code>{registro_medico}</code> - Registro médico del profesional</li>
                        <li><code>{especialidad}</code> - Especialidad del profesional</li>
                        <li><code>{fecha_actual}</code> - Fecha actual</li>
                    </ul>
                    <p class="mb-0"><strong>Ejemplo:</strong> "Yo, {nombre_paciente}, identificado con {documento_paciente}, declaro que..."</p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
