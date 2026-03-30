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
        $('#contenido').on('input', function() {
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
                <form action="{{route('admin.importar-plantillas.store')}}" method="POST">
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
                            <label for="especialidades_id">Especialidades <span class="text-danger">*</span></label>
                            <select name="especialidades_id[]" id="especialidades_id" class="form-control select2" multiple required>
                                @foreach($especialidades as $especialidad)
                                    <option value="{{$especialidad->id}}">{{$especialidad->nombre}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="contenido">Contenido <span class="text-danger">*</span></label>
                            <textarea name="contenido" id="contenido" class="form-control" rows="15" required></textarea>
                        </div>

                        <div id="previewSection" style="display: none;">
                            <label><i class="fas fa-eye"></i> Previsualización</label>
                            <div class="preview-section" id="preview"></div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Importar Plantilla
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
