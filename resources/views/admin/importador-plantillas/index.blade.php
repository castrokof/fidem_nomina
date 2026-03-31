@extends("theme.$theme.layout")

@section('titulo')
    Importar Plantillas de Consentimientos
@endsection

@section("styles")
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.0.0/dist/select2-bootstrap4.min.css" rel="stylesheet" />
<style>
    .preview-section {
        max-height: 300px;
        overflow-y: auto;
        border: 1px solid #dee2e6;
        padding: 15px;
        background-color: #f8f9fa;
        border-radius: 5px;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%',
            placeholder: 'Seleccione las especialidades...'
        });

        // Previsualizar contenido
        $('#contenido_texto').on('input', function() {
            var texto = $(this).val();
            if (texto.trim() !== '') {
                $('#preview').html(texto.replace(/\n/g, '<br>'));
                $('#previewSection').show();
            } else {
                $('#previewSection').hide();
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
                    <h1 class="m-0 text-dark"><i class="fas fa-file-import"></i> Importar Plantillas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
                        <li class="breadcrumb-item active">Importar Plantillas</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="alert alert-info">
                <h5><i class="fas fa-info-circle"></i> Instrucciones</h5>
                <ol class="mb-0">
                    <li>Copie el contenido del documento Word</li>
                    <li>Pegue el contenido en el campo de texto</li>
                    <li>Ingrese el nombre de la plantilla</li>
                    <li>Seleccione las especialidades aplicables</li>
                    <li>Haga clic en "Importar Plantilla"</li>
                </ol>
            </div>

            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title"><i class="fas fa-upload"></i> Importar Nueva Plantilla</h3>
                </div>
                <form action="{{route('importador-plantillas.store')}}" method="POST">
                    @csrf
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle"></i> {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="nombre">Nombre de la Plantilla <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="nombre" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="especialidades">Especialidades (separadas por comas)</label>
                            <input type="text" name="especialidades" id="especialidades" class="form-control" placeholder="Ej: Cardiología, Neurología">
                            <small class="form-text text-muted">Ingrese las especialidades separadas por comas. Déjelo vacío si es de uso general.</small>
                        </div>

                        <div class="form-group">
                            <label for="cups_codigo">Código CUPS</label>
                            <input type="text" name="cups_codigo" id="cups_codigo" class="form-control" placeholder="Ej: 890201">
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" name="uso_general" id="uso_general" class="form-check-input" value="1">
                            <label class="form-check-label" for="uso_general">
                                Uso general (aplica para todas las especialidades)
                            </label>
                        </div>

                        <div class="form-group">
                            <label for="contenido_texto">Contenido <span class="text-danger">*</span></label>
                            <textarea name="contenido_texto" id="contenido_texto" class="form-control" rows="15" required></textarea>
                        </div>

                        <div id="previewSection" style="display: none;">
                            <label><i class="fas fa-eye"></i> Previsualización</label>
                            <div class="preview-section" id="preview"></div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar para Importación
                        </button>
                    </div>
                </form>
            </div>

            @if($importaciones->count() > 0)
            <div class="card mt-4">
                <div class="card-header bg-secondary">
                    <h3 class="card-title"><i class="fas fa-list"></i> Importaciones Guardadas</h3>
                    <div class="card-tools">
                        <form action="{{route('importador-plantillas.procesar-todas')}}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fas fa-cogs"></i> Procesar Todas
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Especialidades</th>
                                    <th>CUPS</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($importaciones as $importacion)
                                <tr>
                                    <td>{{ $importacion->id }}</td>
                                    <td>{{ $importacion->nombre }}</td>
                                    <td>
                                        @if($importacion->uso_general)
                                            <span class="badge badge-info">Uso General</span>
                                        @else
                                            {{ $importacion->especialidades ?? 'N/A' }}
                                        @endif
                                    </td>
                                    <td>{{ $importacion->cups_codigo ?? 'N/A' }}</td>
                                    <td>
                                        @if($importacion->estado == 'pendiente')
                                            <span class="badge badge-warning">Pendiente</span>
                                        @elseif($importacion->estado == 'procesada')
                                            <span class="badge badge-success">Procesada</span>
                                        @else
                                            <span class="badge badge-danger">Error</span>
                                        @endif
                                    </td>
                                    <td>{{ $importacion->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($importacion->estado == 'pendiente')
                                            <form action="{{route('importador-plantillas.procesar', $importacion->id)}}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm" title="Procesar">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{route('importador-plantillas.destroy', $importacion->id)}}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Eliminar" onclick="return confirm('¿Está seguro?')">
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
            @endif
        </div>
    </section>
</div>
@endsection
