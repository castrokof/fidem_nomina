@extends("theme.$theme.layout")

@section('titulo')
    Registrar Firma del Profesional
@endsection

@section("styles")
<style>
    .signature-pad {
        border: 2px solid #007bff;
        border-radius: 10px;
        background-color: white;
        cursor: crosshair;
        display: block;
        margin: 0 auto;
    }
    .firma-preview {
        max-width: 400px;
        border: 2px solid #28a745;
        border-radius: 10px;
        padding: 10px;
        background-color: #f8f9fa;
        margin: 0 auto;
        display: block;
    }
    .btn-clear {
        background-color: #dc3545;
        color: white;
    }
    .btn-clear:hover {
        background-color: #c82333;
        color: white;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    $(document).ready(function() {
        // ========== IMAGEN DE FIRMA DIGITAL ==========
        // Vista previa de imagen
        $('#firmaImagen').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#previewImg').attr('src', e.target.result);
                    $('#imagenPreview').show();
                };
                reader.readAsDataURL(file);

                // Actualizar label del custom-file
                const fileName = file.name;
                $(this).next('.custom-file-label').html(fileName);
            }
        });

        // Cargar imagen de firma
        $('#formImagenFirma').submit(function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const btnCargar = $('#btnCargarImagen');

            btnCargar.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Cargando...');

            $.ajax({
                url: '{{ route("profesionales.cargar-imagen-firma", $profesional->id) }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        alert('Imagen de firma cargada exitosamente');
                        location.reload();
                    }
                },
                error: function(xhr) {
                    alert('Error al cargar la imagen: ' + (xhr.responseJSON?.message || 'Error desconocido'));
                    btnCargar.prop('disabled', false).html('<i class="fas fa-upload"></i> Cargar Imagen de Firma');
                }
            });
        });

        // Eliminar imagen de firma
        $('#btnEliminarImagen').click(function() {
            if (!confirm('¿Está seguro de eliminar la imagen de firma digital?')) {
                return;
            }

            const btn = $(this);
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Eliminando...');

            $.ajax({
                url: '{{ route("profesionales.eliminar-imagen-firma", $profesional->id) }}',
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        alert('Imagen de firma eliminada exitosamente');
                        location.reload();
                    }
                },
                error: function(xhr) {
                    alert('Error al eliminar la imagen: ' + (xhr.responseJSON?.message || 'Error desconocido'));
                    btn.prop('disabled', false).html('<i class="fas fa-trash"></i> Eliminar Imagen Actual');
                }
            });
        });

        // ========== FIRMA A MANO ALZADA ==========
        const canvas = document.getElementById('signaturePad');
        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)',
            penColor: 'rgb(0, 0, 0)'
        });

        // Ajustar canvas para pantallas pequeñas
        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            const container = canvas.parentElement;
            const width = Math.min(600, container.offsetWidth - 40);

            canvas.width = width;
            canvas.height = 200;
            canvas.style.width = width + 'px';
            canvas.style.height = '200px';
        }

        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();

        // Limpiar firma
        $('#btnLimpiar').click(function() {
            signaturePad.clear();
        });

        // Validar y guardar
        $('#formFirma').submit(function(e) {
            if (signaturePad.isEmpty()) {
                e.preventDefault();
                alert('Por favor, dibuje su firma antes de guardar.');
                return false;
            }

            // Convertir firma a base64
            const firmaBase64 = signaturePad.toDataURL();
            $('#firmaInput').val(firmaBase64);
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
                    <h1 class="m-0 text-dark"><i class="fas fa-signature"></i> Registrar Firma del Profesional</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{route('profesionales.index')}}">Profesionales</a></li>
                        <li class="breadcrumb-item active">Firma</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Información del Profesional -->
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title"><i class="fas fa-user-md"></i> Información del Profesional</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nombre Completo:</strong> {{$profesional->nombres}} {{$profesional->apellidos}}</p>
                            <p><strong>Documento:</strong> {{$profesional->tipo_documento}}-{{$profesional->numero_documento}}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Especialidad:</strong> {{$profesional->especialidad->nombre ?? 'N/A'}}</p>
                            <p><strong>Registro Médico:</strong> {{$profesional->registro_medico}}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Firma Actual (si existe) -->
            @if($profesional->firma_base64 || $profesional->firma_imagen_path)
                <div class="card">
                    <div class="card-header bg-success">
                        <h3 class="card-title"><i class="fas fa-check-circle"></i> Firma Actual Registrada</h3>
                    </div>
                    <div class="card-body text-center">
                        @if($profesional->firma_imagen_path && file_exists(public_path($profesional->firma_imagen_path)))
                            <div class="mb-3">
                                <span class="badge badge-primary">Firma Digital (Imagen)</span>
                                <img src="{{ asset($profesional->firma_imagen_path) }}" alt="Firma Digital" class="firma-preview">
                            </div>
                        @endif
                        @if($profesional->firma_base64)
                            <div class="mb-3">
                                <span class="badge badge-info">Firma a Mano Alzada</span>
                                <img src="{{$profesional->firma_base64}}" alt="Firma Actual" class="firma-preview">
                            </div>
                        @endif
                        <p class="mt-3 text-muted">
                            <i class="fas fa-info-circle"></i> Esta firma se estampará automáticamente en todos los consentimientos informados
                        </p>
                        @if($profesional->firma_imagen_path)
                            <p class="text-muted"><small>La firma digital tiene prioridad sobre la firma a mano alzada</small></p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Opción 1: Cargar Imagen de Firma Digital -->
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">
                        <i class="fas fa-upload"></i> Opción 1: Cargar Imagen de Firma Digital
                    </h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> <strong>Recomendado:</strong> Cargue una imagen escaneada o digital de su firma (PNG, JPG, GIF). Máximo 2MB.
                    </div>

                    <form id="formImagenFirma" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="firmaImagen">Seleccione la imagen de su firma:</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="firmaImagen" name="firma_imagen" accept="image/*">
                                <label class="custom-file-label" for="firmaImagen">Elegir archivo...</label>
                            </div>
                            <small class="form-text text-muted">Formatos: JPG, PNG, GIF. Tamaño máximo: 2MB</small>
                        </div>

                        <div id="imagenPreview" class="text-center mb-3" style="display:none;">
                            <p class="font-weight-bold">Vista Previa:</p>
                            <img id="previewImg" src="" alt="Preview" style="max-width: 400px; border: 2px solid #007bff; border-radius: 10px; padding: 10px;">
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary" id="btnCargarImagen">
                                <i class="fas fa-upload"></i> Cargar Imagen de Firma
                            </button>
                            @if($profesional->firma_imagen_path)
                                <button type="button" class="btn btn-danger" id="btnEliminarImagen">
                                    <i class="fas fa-trash"></i> Eliminar Imagen Actual
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Opción 2: Dibujar Firma -->
            <div class="card">
                <div class="card-header bg-secondary">
                    <h3 class="card-title">
                        <i class="fas fa-pen"></i> Opción 2: Dibujar Firma a Mano Alzada
                    </h3>
                </div>
                <form id="formFirma" action="{{route('profesionales.guardar-firma', $profesional->id)}}" method="POST">
                    @csrf
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle"></i> {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

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
                            <i class="fas fa-info-circle"></i> <strong>Instrucciones:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Dibuje su firma en el recuadro blanco usando el mouse o pantalla táctil</li>
                                <li>La firma debe ser clara y legible</li>
                                <li>Use el botón "Limpiar" si desea volver a dibujar</li>
                                <li>Esta firma se usará en todos los consentimientos informados que firme</li>
                            </ul>
                        </div>

                        <div class="text-center mb-3">
                            <label class="font-weight-bold">Dibuje su firma aquí:</label>
                            <div class="d-flex justify-content-center">
                                <canvas id="signaturePad" class="signature-pad" width="600" height="200"></canvas>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="button" id="btnLimpiar" class="btn btn-clear">
                                <i class="fas fa-eraser"></i> Limpiar Firma
                            </button>
                        </div>

                        <input type="hidden" name="firma_base64" id="firmaInput">
                    </div>

                    <div class="card-footer text-center">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save"></i>
                            @if($profesional->firma_base64)
                                Actualizar Firma
                            @else
                                Guardar Firma
                            @endif
                        </button>
                        <a href="{{route('profesionales.index')}}" class="btn btn-default btn-lg">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
