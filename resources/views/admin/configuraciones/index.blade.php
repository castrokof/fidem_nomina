@extends("theme.$theme.layout")

@section('titulo')
    Configuraciones del Sistema
@endsection

@section("styles")
<style>
    .logo-preview {
        max-width: 300px;
        max-height: 200px;
        border: 2px solid #28a745;
        border-radius: 10px;
        padding: 15px;
        background-color: #f8f9fa;
        display: block;
        margin: 0 auto;
        object-fit: contain;
    }
    .config-card {
        margin-bottom: 20px;
    }
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Vista previa de logo
        $('#logoFidem').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#logoPreviewImg').attr('src', e.target.result);
                    $('#logoPreview').show();
                };
                reader.readAsDataURL(file);

                // Actualizar label
                const fileName = file.name;
                $(this).next('.custom-file-label').html(fileName);
            }
        });

        // Cargar logo
        $('#formLogo').submit(function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const btnCargar = $('#btnCargarLogo');

            btnCargar.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Cargando...');

            $.ajax({
                url: '{{ route("configuraciones.cargar-logo") }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        alert('Logo cargado exitosamente');
                        location.reload();
                    }
                },
                error: function(xhr) {
                    alert('Error al cargar el logo: ' + (xhr.responseJSON?.message || 'Error desconocido'));
                    btnCargar.prop('disabled', false).html('<i class="fas fa-upload"></i> Cargar Logo');
                }
            });
        });

        // Eliminar logo
        $('#btnEliminarLogo').click(function() {
            if (!confirm('¿Está seguro de eliminar el logo de FIDEM?')) {
                return;
            }

            const btn = $(this);
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Eliminando...');

            $.ajax({
                url: '{{ route("configuraciones.eliminar-logo") }}',
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        alert('Logo eliminado exitosamente');
                        location.reload();
                    }
                },
                error: function(xhr) {
                    alert('Error al eliminar el logo: ' + (xhr.responseJSON?.message || 'Error desconocido'));
                    btn.prop('disabled', false).html('<i class="fas fa-trash"></i> Eliminar Logo');
                }
            });
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
                    <h1 class="m-0 text-dark"><i class="fas fa-cog"></i> Configuraciones del Sistema</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
                        <li class="breadcrumb-item active">Configuraciones</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Logo de FIDEM -->
            <div class="card config-card">
                <div class="card-header bg-primary">
                    <h3 class="card-title"><i class="fas fa-image"></i> Logo de FIDEM para Consentimientos Informados</h3>
                </div>
                <div class="card-body">
                    @if($logoFidem && $logoFidem->valor && file_exists(public_path($logoFidem->valor)))
                        <div class="mb-4">
                            <h5 class="text-center"><i class="fas fa-check-circle text-success"></i> Logo Actual</h5>
                            <img src="{{ asset($logoFidem->valor) }}" alt="Logo FIDEM" class="logo-preview">
                            <p class="text-center mt-3 text-muted">
                                <i class="fas fa-info-circle"></i> Este logo se mostrará en el encabezado de todos los PDFs de consentimientos informados
                            </p>
                            <div class="text-center mt-3">
                                <button type="button" id="btnEliminarLogo" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Eliminar Logo
                                </button>
                            </div>
                        </div>
                        <hr>
                        <h5 class="text-center mb-3">Actualizar Logo</h5>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> <strong>No hay logo configurado.</strong> Cargue una imagen del logo de FIDEM que se mostrará en los consentimientos informados.
                        </div>
                    @endif

                    <form id="formLogo" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="logoFidem">Seleccione la imagen del logo:</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="logoFidem" name="logo_fidem" accept="image/*" required>
                                <label class="custom-file-label" for="logoFidem">Elegir archivo...</label>
                            </div>
                            <small class="form-text text-muted">
                                Formatos: JPG, PNG, GIF. Tamaño máximo: 2MB. Se recomienda usar un logo en formato PNG con fondo transparente.
                            </small>
                        </div>

                        <div id="logoPreview" class="text-center mb-3" style="display:none;">
                            <p class="font-weight-bold">Vista Previa:</p>
                            <img id="logoPreviewImg" src="" alt="Preview" class="logo-preview">
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary" id="btnCargarLogo">
                                <i class="fas fa-upload"></i> {{ $logoFidem && $logoFidem->valor ? 'Actualizar Logo' : 'Cargar Logo' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Otras configuraciones futuras -->
            <div class="card config-card">
                <div class="card-header bg-secondary">
                    <h3 class="card-title"><i class="fas fa-list"></i> Otras Configuraciones</h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        <i class="fas fa-info-circle"></i> Más configuraciones estarán disponibles próximamente.
                    </p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
