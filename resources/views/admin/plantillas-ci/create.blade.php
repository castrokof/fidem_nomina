@extends("theme.$theme.layout")

@section('titulo')
    Crear Plantilla de Consentimiento Informado
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
                    <h1 class="m-0 text-dark"><i class="fas fa-file-contract"></i> Crear Plantilla CI</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{route('plantillas-ci.index')}}">Plantillas CI</a></li>
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
                    <h3 class="card-title">Datos de la Nueva Plantilla</h3>
                </div>
                <form action="{{route('plantillas-ci.store')}}" method="POST">
                    @csrf
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
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="nombre">Nombre de la Plantilla <span class="text-danger">*</span></label>
                                    <input type="text" name="nombre" id="nombre" class="form-control" value="{{old('nombre')}}" required placeholder="Ej: Consentimiento Informado para Cirugía">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cups_codigo">Código CUPS</label>
                                    <input type="text" name="cups_codigo" id="cups_codigo" class="form-control" value="{{old('cups_codigo')}}" placeholder="Ej: 890201">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="descripcion">Descripción</label>
                                    <textarea name="descripcion" id="descripcion" class="form-control" rows="2" placeholder="Descripción breve de la plantilla">{{old('descripcion')}}</textarea>
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
                                                {{in_array($especialidad->id, old('especialidades', [])) ? 'selected' : ''}}>
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
                                    <label for="contenido_html">Contenido de la Plantilla <span class="text-danger">*</span></label>
                                    <textarea name="contenido_html" id="contenido_html" class="form-control" rows="15" required>{{old('contenido_html')}}</textarea>
                                    <small class="form-text text-muted">
                                        <strong>Variables disponibles:</strong> Consulte la tabla de ayuda abajo para ver todas las variables disponibles.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" name="activo" id="activo" class="form-check-input" value="1" {{old('activo', true) ? 'checked' : ''}}>
                                    <label class="form-check-label" for="activo">
                                        Plantilla Activa
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" name="uso_general" id="uso_general" class="form-check-input" value="1" {{old('uso_general') ? 'checked' : ''}}>
                                    <label class="form-check-label" for="uso_general">
                                        Uso General (aplica para todas las especialidades)
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" name="requiere_acudiente_obligatorio" id="requiere_acudiente_obligatorio" class="form-check-input" value="1" {{old('requiere_acudiente_obligatorio') ? 'checked' : ''}}>
                                    <label class="form-check-label" for="requiere_acudiente_obligatorio">
                                        Requiere Firma de Acudiente Obligatorio
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Crear Plantilla
                        </button>
                        <a href="{{route('plantillas-ci.index')}}" class="btn btn-default">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-info-circle"></i> Variables Disponibles</h3>
                </div>
                <div class="card-body">
                    <p><strong>Variables disponibles para usar en el contenido de la plantilla:</strong></p>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Variable</th>
                                    <th>Descripción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($variablesDisponibles as $variable => $descripcion)
                                <tr>
                                    <td><code>{{ $variable }}</code></td>
                                    <td>{{ $descripcion }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                     <p class="mb-0"><strong>Ejemplo:</strong> "Yo, {nombre_paciente}, identificado con {documento_paciente}, declaro que..."</p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
