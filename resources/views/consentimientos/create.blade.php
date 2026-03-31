@extends("theme.$theme.layout")

@section('titulo')
    Crear Consentimiento Informado
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

        // Cargar plantillas según especialidad
        $('#especialidad_id').change(function() {
            var especialidadId = $(this).val();
            if (especialidadId) {
                $.ajax({
                    url: '/admin/plantillas-ci/por-especialidad/' + especialidadId,
                    type: 'GET',
                    success: function(data) {
                        $('#plantilla_ci_id').empty().append('<option value="">Seleccione una plantilla...</option>');
                        $.each(data, function(key, plantilla) {
                            $('#plantilla_ci_id').append('<option value="' + plantilla.id + '">' + plantilla.nombre + '</option>');
                        });
                    }
                });
            } else {
                $('#plantilla_ci_id').empty().append('<option value="">Seleccione primero una especialidad...</option>');
            }
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
                    <h1 class="m-0 text-dark"><i class="fas fa-file-signature"></i> Crear Consentimiento Informado</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{route('consentimientos.index')}}">Consentimientos</a></li>
                        <li class="breadcrumb-item active">Crear</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Datos del Nuevo Consentimiento</h3>
                </div>
                <form action="{{route('consentimientos.store')}}" method="POST">
                    @csrf

                    @if(isset($agenda))
                        <input type="hidden" name="agenda_ci_id" value="{{$agenda->id}}">
                    @endif

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

                        @if(isset($agenda))
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> <strong>Creando consentimiento desde cita agendada</strong>
                                <br>Paciente: {{$agenda->paciente_nombre}} | Fecha: {{$agenda->fecha->format('d/m/Y H:i')}}
                            </div>
                        @endif

                        <div class="row">
                            <!-- Profesional -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="profesional_id">Profesional <span class="text-danger">*</span></label>
                                    <select name="profesional_id" id="profesional_id" class="form-control select2" required>
                                        <option value="">Seleccione un profesional...</option>
                                        @foreach($profesionales as $profesional)
                                            <option value="{{$profesional->id}}" {{ old('profesional_id', isset($agenda) ? $agenda->profesional_id : '') == $profesional->id ? 'selected' : '' }}>
                                                {{$profesional->nombres}} {{$profesional->apellidos}} - {{$profesional->especialidad->nombre ?? 'Sin especialidad'}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Paciente -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="paciente_id">Paciente <span class="text-danger">*</span></label>
                                    <select name="paciente_id" id="paciente_id" class="form-control select2" required>
                                        <option value="">Seleccione un paciente...</option>
                                        @foreach($pacientes as $paciente)
                                            <option value="{{$paciente->id}}" {{ old('paciente_id', isset($agenda) ? $agenda->paciente_id : '') == $paciente->id ? 'selected' : '' }}>
                                                {{$paciente->nombres}} {{$paciente->apellidos}} - {{$paciente->tipo_documento}}-{{$paciente->numero_documento}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Especialidad -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="especialidad_id">Especialidad <span class="text-danger">*</span></label>
                                    <select name="especialidad_id" id="especialidad_id" class="form-control select2" required>
                                        <option value="">Seleccione una especialidad...</option>
                                        @foreach($especialidades as $especialidad)
                                            <option value="{{$especialidad->id}}" {{ old('especialidad_id') == $especialidad->id ? 'selected' : '' }}>
                                                {{$especialidad->nombre}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Plantilla -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="plantilla_ci_id">Plantilla de Consentimiento <span class="text-danger">*</span></label>
                                    <select name="plantilla_ci_id" id="plantilla_ci_id" class="form-control select2" required>
                                        <option value="">Seleccione primero una especialidad...</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Fecha del procedimiento -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_procedimiento">Fecha y Hora del Procedimiento <span class="text-danger">*</span></label>
                                    <input type="datetime-local" name="fecha_procedimiento" id="fecha_procedimiento" class="form-control" value="{{old('fecha_procedimiento', isset($agenda) ? $agenda->fecha->format('Y-m-d\TH:i') : '')}}" required>
                                </div>
                            </div>

                            <!-- Observaciones -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="observaciones">Observaciones</label>
                                    <textarea name="observaciones" id="observaciones" class="form-control" rows="2">{{old('observaciones')}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Crear Consentimiento
                        </button>
                        <a href="{{route('consentimientos.index')}}" class="btn btn-default">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
