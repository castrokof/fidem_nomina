@extends("theme.$theme.layout")

@section('titulo')
    Nueva Validación de Derechos
@endsection

@section("styles")
<style>
/* ── Zona de carga de imagen ── */
#zona-imagen {
    border: 2px dashed #adb5bd;
    border-radius: 10px;
    padding: 40px 20px;
    text-align: center;
    cursor: pointer;
    transition: border-color .2s, background .2s;
    background: #f8f9fa;
    position: relative;
    min-height: 160px;
}
#zona-imagen.dragover {
    border-color: #007bff;
    background: #e8f0fe;
}
#zona-imagen.tiene-imagen {
    border-style: solid;
    border-color: #28a745;
    padding: 8px;
    background: #fff;
}
#zona-imagen .placeholder-text { color: #6c757d; }
#zona-imagen .placeholder-text i { font-size: 2.5rem; display: block; margin-bottom: 8px; color: #adb5bd; }
#preview-imagen {
    max-width: 100%;
    max-height: 400px;
    border-radius: 6px;
    display: none;
}
#btn-limpiar-imagen {
    position: absolute;
    top: 8px; right: 8px;
    display: none;
    z-index: 10;
}
#zona-imagen.tiene-imagen #btn-limpiar-imagen { display: inline-block; }
#zona-imagen.tiene-imagen .placeholder-text    { display: none; }
#zona-imagen.tiene-imagen #preview-imagen      { display: block; margin: 0 auto; }

/* ── Toast de pegado ── */
#toast-pegar {
    position: fixed; bottom: 20px; right: 20px;
    background: #343a40; color: #fff;
    padding: 10px 18px; border-radius: 8px;
    font-size: 13px; z-index: 9999;
    display: none; opacity: 0;
    transition: opacity .3s;
}
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {

    const zona        = document.getElementById('zona-imagen');
    const preview     = document.getElementById('preview-imagen');
    const inputB64    = document.getElementById('imagen_base64');
    const btnLimpiar  = document.getElementById('btn-limpiar-imagen');
    const toast       = document.getElementById('toast-pegar');

    // ── Helpers ──────────────────────────────────────────────────────────────
    function mostrarImagen(dataUrl) {
        preview.src = dataUrl;
        inputB64.value = dataUrl;
        zona.classList.add('tiene-imagen');
        preview.style.display = 'block';
    }

    function limpiarImagen() {
        preview.src = '';
        inputB64.value = '';
        zona.classList.remove('tiene-imagen');
        preview.style.display = 'none';
    }

    function mostrarToast(msg) {
        toast.textContent = msg;
        toast.style.display = 'block';
        setTimeout(function() { toast.style.opacity = '1'; }, 10);
        setTimeout(function() {
            toast.style.opacity = '0';
            setTimeout(function() { toast.style.display = 'none'; }, 300);
        }, 2500);
    }

    function procesarArchivo(file) {
        if (!file || !file.type.startsWith('image/')) {
            alert('El archivo debe ser una imagen (PNG, JPEG, etc.).');
            return;
        }
        const reader = new FileReader();
        reader.onload = function(e) { mostrarImagen(e.target.result); };
        reader.readAsDataURL(file);
    }

    // ── Botón limpiar ────────────────────────────────────────────────────────
    btnLimpiar.addEventListener('click', function(e) {
        e.stopPropagation();
        limpiarImagen();
    });

    // ── Click en zona → abrir selector de archivo ────────────────────────────
    zona.addEventListener('click', function(e) {
        if (e.target === btnLimpiar || btnLimpiar.contains(e.target)) return;
        if (zona.classList.contains('tiene-imagen')) return;
        document.getElementById('archivo-input').click();
    });

    document.getElementById('archivo-input').addEventListener('change', function() {
        if (this.files && this.files[0]) procesarArchivo(this.files[0]);
        this.value = '';
    });

    // ── Drag & Drop ──────────────────────────────────────────────────────────
    zona.addEventListener('dragover', function(e) {
        e.preventDefault();
        zona.classList.add('dragover');
    });
    zona.addEventListener('dragleave', function() {
        zona.classList.remove('dragover');
    });
    zona.addEventListener('drop', function(e) {
        e.preventDefault();
        zona.classList.remove('dragover');
        const file = e.dataTransfer.files[0];
        procesarArchivo(file);
    });

    // ── Pegar desde portapapeles (Ctrl+V / Cmd+V) ────────────────────────────
    document.addEventListener('paste', function(e) {
        const items = e.clipboardData ? e.clipboardData.items : [];
        for (let i = 0; i < items.length; i++) {
            if (items[i].type.startsWith('image/')) {
                const blob = items[i].getAsFile();
                procesarArchivo(blob);
                mostrarToast('✅ Pantallazo pegado desde el portapapeles');
                return;
            }
        }
    });

    // ── Búsqueda en agenda (AJAX) ─────────────────────────────────────────────
    let timer = null;

    function buscarEnAgenda() {
        const q     = $('#buscar_agenda').val().trim();
        const fecha = $('#buscar_fecha').val();
        if (q.length < 3 && !fecha) { $('#resultados-agenda').hide(); return; }

        clearTimeout(timer);
        timer = setTimeout(function() {
            $.ajax({
                url:  '{{ route("validaciones.ajax.agenda") }}',
                data: { q: q, fecha: fecha },
                success: function(items) {
                    const ul = $('#lista-agenda').empty();
                    if (!items.length) {
                        ul.append('<li class="list-group-item text-muted small">Sin resultados.</li>');
                    } else {
                        items.forEach(function(item) {
                            ul.append(
                                $('<li class="list-group-item list-group-item-action py-2 small" style="cursor:pointer">')
                                    .text(item.label)
                                    .on('click', function() { llenarDatos(item); })
                            );
                        });
                    }
                    $('#resultados-agenda').show();
                }
            });
        }, 300);
    }

    $('#buscar_agenda').on('input', buscarEnAgenda);
    $('#buscar_fecha').on('change', buscarEnAgenda);

    function llenarDatos(item) {
        $('#agenda_ci_id').val(item.id);
        $('#paciente_nombre').val(item.paciente_nombre);
        $('#paciente_cedula').val(item.paciente_cedula);
        $('#fecha_atencion').val(item.fecha);
        $('#numero_factura').val(item.numero_factura);
        $('#atencion_factura').val(item.atencion_factura);
        $('#contrato').val(item.contrato);
        $('#empresafac').val(item.empresafac);
        $('#cups_codigo').val(item.cups_codigo);
        $('#cups_descripcion').val(item.cups_descripcion);
        $('#resultados-agenda').hide();
        $('#buscar_agenda').val(item.paciente_nombre + ' — ' + item.paciente_cedula);
        // Resaltar sección de datos
        $('#card-datos-cita').addClass('border-success');
        setTimeout(function() { $('#card-datos-cita').removeClass('border-success'); }, 1500);
    }

    // Cerrar lista al hacer click fuera
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#bloque-busqueda').length) {
            $('#resultados-agenda').hide();
        }
    });

    // ── Validación antes de enviar ────────────────────────────────────────────
    $('#form-validacion').on('submit', function(e) {
        if (!inputB64.value) {
            e.preventDefault();
            zona.style.borderColor = '#dc3545';
            alert('Debe adjuntar el pantallazo de validación de derechos.');
            zona.scrollIntoView({ behavior: 'smooth' });
        }
    });
});
</script>
@endsection

@section('content')
<div class="container-fluid">

    <div class="d-flex align-items-center mb-3">
        <a href="{{ route('validaciones.index') }}" class="btn btn-sm btn-outline-secondary mr-2">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h4 class="mb-0"><i class="fas fa-shield-alt text-primary mr-2"></i>Nueva Validación de Derechos</h4>
            <small class="text-muted">Adjunte el pantallazo de validación y vincúlelo a una cita de la agenda.</small>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form id="form-validacion" action="{{ route('validaciones.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Input oculto y selector de archivo --}}
        <input type="hidden" id="imagen_base64" name="imagen_base64">
        <input type="file" id="archivo-input" accept="image/*" style="display:none">

        <div class="row">

            {{-- Columna izquierda: imagen + observaciones --}}
            <div class="col-lg-6 mb-3">

                <div class="card shadow-sm">
                    <div class="card-header py-2 font-weight-bold">
                        <i class="fas fa-image mr-1 text-primary"></i> Pantallazo de validación
                        <span class="text-danger">*</span>
                    </div>
                    <div class="card-body">
                        {{-- Zona de carga --}}
                        <div id="zona-imagen">
                            <button type="button" id="btn-limpiar-imagen" class="btn btn-sm btn-danger rounded-circle"
                                title="Quitar imagen" style="width:28px;height:28px;padding:0;line-height:1;">
                                <i class="fas fa-times" style="font-size:11px"></i>
                            </button>
                            <div class="placeholder-text">
                                <i class="fas fa-clipboard-check"></i>
                                <strong>Pegue el pantallazo aquí</strong><br>
                                <span class="small">Ctrl+V · arrastre una imagen · o haga clic para seleccionar archivo</span>
                            </div>
                            <img id="preview-imagen" src="" alt="Pantallazo">
                        </div>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Copie el pantallazo al portapapeles y presione <kbd>Ctrl+V</kbd> en cualquier parte de la página,
                            o arrastre el archivo directamente sobre el recuadro.
                        </small>
                    </div>
                </div>

                <div class="card shadow-sm mt-3">
                    <div class="card-header py-2 font-weight-bold">
                        <i class="fas fa-comment-alt mr-1 text-secondary"></i> Observaciones
                    </div>
                    <div class="card-body">
                        <textarea name="observaciones" id="observaciones" class="form-control" rows="3"
                            placeholder="Observaciones adicionales sobre la validación…">{{ old('observaciones') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Columna derecha: búsqueda en agenda + datos --}}
            <div class="col-lg-6 mb-3">

                {{-- Búsqueda en agenda --}}
                <div class="card shadow-sm mb-3">
                    <div class="card-header py-2 font-weight-bold">
                        <i class="fas fa-search mr-1 text-info"></i> Vincular a cita de la agenda
                    </div>
                    <div class="card-body">
                        <div id="bloque-busqueda" class="position-relative">
                            <div class="row">
                                <div class="col-8">
                                    <label class="small mb-1">Buscar por nombre o cédula</label>
                                    <input type="text" id="buscar_agenda" class="form-control form-control-sm"
                                        placeholder="Nombre o cédula del paciente…">
                                </div>
                                <div class="col-4">
                                    <label class="small mb-1">Fecha de la cita</label>
                                    <input type="date" id="buscar_fecha" class="form-control form-control-sm"
                                        value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                            <div id="resultados-agenda" class="position-absolute w-100" style="z-index:200; display:none; top:100%; left:0">
                                <ul id="lista-agenda" class="list-group shadow-sm" style="max-height:220px;overflow-y:auto;border-radius:0 0 6px 6px;"></ul>
                            </div>
                        </div>
                        <small class="text-muted mt-1 d-block">
                            <i class="fas fa-info-circle mr-1"></i>
                            Seleccione la cita correspondiente para auto-llenar los datos. Si no aparece, puede ingresarlos manualmente.
                        </small>
                        <input type="hidden" id="agenda_ci_id" name="agenda_ci_id">
                    </div>
                </div>

                {{-- Datos de la cita --}}
                <div class="card shadow-sm" id="card-datos-cita" style="transition: border-color .5s">
                    <div class="card-header py-2 font-weight-bold">
                        <i class="fas fa-user-injured mr-1 text-warning"></i> Datos del paciente / cita
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8 mb-2">
                                <label class="small mb-1">Nombre del paciente</label>
                                <input type="text" id="paciente_nombre" name="paciente_nombre"
                                    class="form-control form-control-sm"
                                    value="{{ old('paciente_nombre') }}" placeholder="Nombre completo">
                            </div>
                            <div class="col-4 mb-2">
                                <label class="small mb-1">Cédula</label>
                                <input type="text" id="paciente_cedula" name="paciente_cedula"
                                    class="form-control form-control-sm"
                                    value="{{ old('paciente_cedula') }}" placeholder="Documento">
                            </div>
                            <div class="col-4 mb-2">
                                <label class="small mb-1">Fecha atención</label>
                                <input type="date" id="fecha_atencion" name="fecha_atencion"
                                    class="form-control form-control-sm"
                                    value="{{ old('fecha_atencion', date('Y-m-d')) }}">
                            </div>
                            <div class="col-4 mb-2">
                                <label class="small mb-1">N° Factura</label>
                                <input type="text" id="numero_factura" name="numero_factura"
                                    class="form-control form-control-sm"
                                    value="{{ old('numero_factura') }}" placeholder="Factura">
                            </div>
                            <div class="col-4 mb-2">
                                <label class="small mb-1">Atención factura</label>
                                <input type="text" id="atencion_factura" name="atencion_factura"
                                    class="form-control form-control-sm"
                                    value="{{ old('atencion_factura') }}" placeholder="Atención">
                            </div>
                            <div class="col-6 mb-2">
                                <label class="small mb-1">Empresa / EPS</label>
                                <input type="text" id="empresafac" name="empresafac"
                                    class="form-control form-control-sm"
                                    value="{{ old('empresafac') }}" placeholder="EPS o aseguradora">
                            </div>
                            <div class="col-6 mb-2">
                                <label class="small mb-1">Contrato</label>
                                <input type="text" id="contrato" name="contrato"
                                    class="form-control form-control-sm"
                                    value="{{ old('contrato') }}" placeholder="Contrato">
                            </div>
                            <div class="col-3 mb-2">
                                <label class="small mb-1">CUPS</label>
                                <input type="text" id="cups_codigo" name="cups_codigo"
                                    class="form-control form-control-sm"
                                    value="{{ old('cups_codigo') }}" placeholder="Código">
                            </div>
                            <div class="col-9 mb-2">
                                <label class="small mb-1">Descripción procedimiento</label>
                                <input type="text" id="cups_descripcion" name="cups_descripcion"
                                    class="form-control form-control-sm"
                                    value="{{ old('cups_descripcion') }}" placeholder="Descripción">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Botones --}}
                <div class="d-flex justify-content-end mt-3">
                    <a href="{{ route('validaciones.index') }}" class="btn btn-outline-secondary mr-2">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Guardar validación
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="toast-pegar"></div>
@endsection
