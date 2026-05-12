@extends("theme.$theme.layout")

@section('titulo')
    Nueva Validación de Derechos
@endsection

@section("styles")
<style>
/* ── Zona de carga ── */
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
#zona-imagen.dragover  { border-color: #007bff; background: #e8f0fe; }
#zona-imagen.tiene-imagen { border-style: solid; border-color: #28a745; padding: 8px; background: #fff; }
#zona-imagen .placeholder-text { color: #6c757d; }
#zona-imagen .placeholder-text i { font-size: 2.5rem; display: block; margin-bottom: 8px; color: #adb5bd; }
#preview-imagen { max-width: 100%; max-height: 380px; border-radius: 6px; display: none; }
#btn-limpiar-imagen { position: absolute; top: 8px; right: 8px; display: none; z-index: 10; }
#zona-imagen.tiene-imagen #btn-limpiar-imagen { display: inline-block; }
#zona-imagen.tiene-imagen .placeholder-text   { display: none; }
#zona-imagen.tiene-imagen #preview-imagen     { display: block; margin: 0 auto; }

/* ── OCR Status ── */
#ocr-panel {
    display: none;
    border-radius: 0 0 8px 8px;
    border: 1px solid #dee2e6;
    border-top: none;
    background: #fff;
    padding: 10px 14px;
    font-size: 13px;
}
#ocr-progress-bar { transition: width .3s; }
.ocr-campo-ok  { animation: flashGreen .8s ease; }
@keyframes flashGreen {
    0%   { background-color: #d4edda; }
    100% { background-color: transparent; }
}
.badge-ocr {
    font-size: 10px; font-weight: 600; vertical-align: middle;
    background: #17a2b8; color: #fff; border-radius: 4px;
    padding: 1px 5px; margin-left: 4px;
}

/* ── Toast ── */
#toast-pegar {
    position: fixed; bottom: 20px; right: 20px;
    background: #343a40; color: #fff;
    padding: 10px 18px; border-radius: 8px;
    font-size: 13px; z-index: 9999;
    display: none; opacity: 0; transition: opacity .3s;
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>
<script>
$(document).ready(function() {

    const zona       = document.getElementById('zona-imagen');
    const preview    = document.getElementById('preview-imagen');
    const inputB64   = document.getElementById('imagen_base64');
    const btnLimpiar = document.getElementById('btn-limpiar-imagen');
    const toast      = document.getElementById('toast-pegar');

    // ── OCR ──────────────────────────────────────────────────────────────────
    function mostrarPanelOCR(estado) {
        // estado: 'loading' | 'ok' | 'error' | 'hidden'
        const panel = document.getElementById('ocr-panel');
        const barra = document.getElementById('ocr-barra');
        const texto = document.getElementById('ocr-texto');
        const spin  = document.getElementById('ocr-spinner');
        const ok    = document.getElementById('ocr-ok-icon');
        const err   = document.getElementById('ocr-err-icon');

        panel.style.display = estado === 'hidden' ? 'none' : 'block';
        spin.style.display  = estado === 'loading' ? 'inline-block' : 'none';
        ok.style.display    = estado === 'ok'      ? 'inline-block' : 'none';
        err.style.display   = estado === 'error'   ? 'inline-block' : 'none';
        if (estado === 'loading') {
            barra.style.width = '0%';
            barra.className = 'progress-bar progress-bar-striped progress-bar-animated bg-info';
            texto.textContent = 'Iniciando OCR…';
        }
    }

    function setProgreso(pct, msg) {
        const barra = document.getElementById('ocr-barra');
        const texto = document.getElementById('ocr-texto');
        barra.style.width = pct + '%';
        texto.textContent = msg;
    }

    async function ejecutarOCR(dataUrl) {
        mostrarPanelOCR('loading');
        try {
            const resultado = await Tesseract.recognize(dataUrl, 'spa', {
                logger: function(m) {
                    if (m.status === 'loading tesseract core') setProgreso(5,  'Cargando motor OCR…');
                    if (m.status === 'initializing tesseract')  setProgreso(15, 'Inicializando…');
                    if (m.status === 'loading language traineddata') setProgreso(30, 'Descargando modelo de idioma…');
                    if (m.status === 'initializing api')        setProgreso(50, 'Preparando análisis…');
                    if (m.status === 'recognizing text')        setProgreso(50 + Math.round(m.progress * 45), 'Reconociendo texto… ' + Math.round(m.progress * 100) + '%');
                }
            });

            setProgreso(100, 'Analizando resultado…');
            const campos = parsearTextoOCR(resultado.data.text);
            aplicarCamposOCR(campos);

            const n = Object.keys(campos).length;
            document.getElementById('ocr-barra').className = 'progress-bar bg-success';
            document.getElementById('ocr-texto').textContent = n > 0
                ? 'OCR completado — ' + n + ' campo(s) detectado(s). Revise y corrija si es necesario.'
                : 'OCR completado — no se detectaron campos con etiquetas claras. Complete manualmente.';
            mostrarPanelOCR('ok');

        } catch (e) {
            console.error('OCR error:', e);
            document.getElementById('ocr-texto').textContent = 'Error en OCR. Complete los campos manualmente.';
            mostrarPanelOCR('error');
        }
    }

    // ── Parser de texto OCR ───────────────────────────────────────────────────
    function parsearTextoOCR(texto) {
        const res   = {};
        const upper = texto.toUpperCase();

        // Estado de afiliación — busca la palabra exacta
        const estados = ['ACTIVO', 'SUSPENDIDO', 'INACTIVO', 'RETIRADO', 'PENDIENTE'];
        for (var i = 0; i < estados.length; i++) {
            if (upper.indexOf(estados[i]) !== -1) {
                res.estado_afiliacion = estados[i];
                break;
            }
        }

        // Tipo de documento — etiqueta explícita o nombre completo
        var mTipo = upper.match(/TIPO\s*(?:DE\s*)?DOC(?:UMENTO)?\s*[:\-]?\s*(CC|TI|CE|RC|PA|NIT|AS|MS)/);
        if (mTipo) {
            res.tipo_doc = mTipo[1];
        } else if (/C[EÉ]DULA\s*DE\s*CIUDADAN/.test(upper))  { res.tipo_doc = 'CC'; }
        else if (/TARJETA\s*DE\s*IDENTIDAD/.test(upper))     { res.tipo_doc = 'TI'; }
        else if (/C[EÉ]DULA\s*(?:DE\s*)?EXTRANJER/.test(upper)) { res.tipo_doc = 'CE'; }
        else if (/REGISTRO\s*CIVIL/.test(upper))             { res.tipo_doc = 'RC'; }
        else if (/PASAPORTE/.test(upper))                    { res.tipo_doc = 'PA'; }

        // Número de documento — etiqueta + dígitos
        var patronesDoc = [
            /(?:N[°oúO]?\.?\s*(?:DE\s*)?DOCUMENTO|C[EÉ]DULA|DOCUMENTO|NO\.?\s*IDENTIFICACION|NO\.?\s*ID)\s*[:\-]?\s*(\d{5,12})/i,
            /\bCC\s*[:\-#]?\s*(\d{6,12})\b/i,
            /\bTI\s*[:\-#]?\s*(\d{6,12})\b/i,
        ];
        for (var j = 0; j < patronesDoc.length; j++) {
            var mDoc = texto.match(patronesDoc[j]);
            if (mDoc) { res.cedula = mDoc[1]; break; }
        }

        // Nombre del paciente — línea que sigue a una etiqueta conocida
        var patronesNombre = [
            /(?:NOMBRE\s*(?:DEL?\s*)?(?:PACIENTE|AFILIADO|USUARIO)?|AFILIADO\s*:|PACIENTE\s*:)\s*[:\-]?\s*([A-ZÁÉÍÓÚÑÜ][A-ZÁÉÍÓÚÑÜ ,\.\-]{4,70})/i,
            /(?:NOMBRE\s*COMPLETO)\s*[:\-]?\s*([A-ZÁÉÍÓÚÑÜ][A-ZÁÉÍÓÚÑÜ ,\.\-]{4,70})/i,
        ];
        for (var k = 0; k < patronesNombre.length; k++) {
            var mNom = texto.match(patronesNombre[k]);
            if (mNom) {
                var nombre = mNom[1].split('\n')[0].trim().replace(/\s{2,}/g, ' ');
                if (nombre.length > 4) { res.nombre = nombre; break; }
            }
        }

        return res;
    }

    // ── Aplicar campos detectados (solo si el campo está vacío) ───────────────
    function aplicarCamposOCR(campos) {
        function setOCR(id, valor, esSelect) {
            if (!valor) return;
            var el = document.getElementById(id);
            if (!el || el.value.trim() !== '') return; // no pisar lo que ya se llenó
            el.value = valor;
            el.parentElement.classList.add('ocr-campo-ok');
            // Agregar badge OCR
            var label = el.previousElementSibling;
            if (label && !label.querySelector('.badge-ocr')) {
                label.innerHTML += '<span class="badge-ocr">OCR</span>';
            }
        }
        if (campos.nombre)          setOCR('paciente_nombre', campos.nombre);
        if (campos.tipo_doc)        setOCR('paciente_tipo_doc', campos.tipo_doc, true);
        if (campos.cedula)          setOCR('paciente_cedula', campos.cedula);
        if (campos.estado_afiliacion) setOCR('estado_afiliacion', campos.estado_afiliacion);
    }

    // ── Mostrar imagen + disparar OCR ─────────────────────────────────────────
    function mostrarImagen(dataUrl) {
        preview.src = dataUrl;
        inputB64.value = dataUrl;
        zona.classList.add('tiene-imagen');
        preview.style.display = 'block';
        ejecutarOCR(dataUrl);
    }

    function limpiarImagen() {
        preview.src = '';
        inputB64.value = '';
        zona.classList.remove('tiene-imagen');
        preview.style.display = 'none';
        mostrarPanelOCR('hidden');
        // Quitar badges OCR
        document.querySelectorAll('.badge-ocr').forEach(function(b) { b.remove(); });
    }

    function procesarArchivo(file) {
        if (!file || !file.type.startsWith('image/')) {
            alert('El archivo debe ser una imagen (PNG, JPEG, etc.).');
            return;
        }
        var reader = new FileReader();
        reader.onload = function(e) { mostrarImagen(e.target.result); };
        reader.readAsDataURL(file);
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

    // ── Eventos de la zona de imagen ─────────────────────────────────────────
    btnLimpiar.addEventListener('click', function(e) { e.stopPropagation(); limpiarImagen(); });

    zona.addEventListener('click', function(e) {
        if (e.target === btnLimpiar || btnLimpiar.contains(e.target)) return;
        if (zona.classList.contains('tiene-imagen')) return;
        document.getElementById('archivo-input').click();
    });

    document.getElementById('archivo-input').addEventListener('change', function() {
        if (this.files && this.files[0]) procesarArchivo(this.files[0]);
        this.value = '';
    });

    zona.addEventListener('dragover',  function(e) { e.preventDefault(); zona.classList.add('dragover'); });
    zona.addEventListener('dragleave', function()  { zona.classList.remove('dragover'); });
    zona.addEventListener('drop', function(e) {
        e.preventDefault();
        zona.classList.remove('dragover');
        procesarArchivo(e.dataTransfer.files[0]);
    });

    document.addEventListener('paste', function(e) {
        var items = e.clipboardData ? e.clipboardData.items : [];
        for (var i = 0; i < items.length; i++) {
            if (items[i].type.startsWith('image/')) {
                procesarArchivo(items[i].getAsFile());
                mostrarToast('✅ Pantallazo pegado — ejecutando OCR…');
                return;
            }
        }
    });

    // ── Búsqueda en agenda ────────────────────────────────────────────────────
    var timer = null;

    function buscarEnAgenda() {
        var q     = $('#buscar_agenda').val().trim();
        var fecha = $('#buscar_fecha').val();
        if (q.length < 3 && !fecha) { $('#resultados-agenda').hide(); return; }
        clearTimeout(timer);
        timer = setTimeout(function() {
            $.ajax({
                url:  '{{ route("validaciones.ajax.agenda") }}',
                data: { q: q, fecha: fecha },
                success: function(items) {
                    var ul = $('#lista-agenda').empty();
                    if (!items.length) {
                        ul.append('<li class="list-group-item text-muted small">Sin resultados.</li>');
                    } else {
                        items.forEach(function(item) {
                            ul.append(
                                $('<li class="list-group-item list-group-item-action py-2 small" style="cursor:pointer">')
                                    .text(item.label)
                                    .on('click', function() { llenarDesdeAgenda(item); })
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

    function llenarDesdeAgenda(item) {
        $('#agenda_ci_id').val(item.id);
        $('#paciente_nombre').val(item.paciente_nombre);
        $('#paciente_tipo_doc').val(item.paciente_tipo_doc || 'CC');
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
        $('#card-datos-cita').addClass('border-success');
        setTimeout(function() { $('#card-datos-cita').removeClass('border-success'); }, 1500);
    }

    $(document).on('click', function(e) {
        if (!$(e.target).closest('#bloque-busqueda').length) $('#resultados-agenda').hide();
    });

    // ── Validación ────────────────────────────────────────────────────────────
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
            <small class="text-muted">Adjunte el pantallazo — el OCR intentará completar los campos automáticamente.</small>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form id="form-validacion" action="{{ route('validaciones.store') }}" method="POST">
        @csrf
        <input type="hidden" id="imagen_base64" name="imagen_base64">
        <input type="file"   id="archivo-input" accept="image/*" style="display:none">

        <div class="row">

            {{-- ── Columna izquierda: imagen ── --}}
            <div class="col-lg-6 mb-3">

                <div class="card shadow-sm">
                    <div class="card-header py-2 d-flex align-items-center justify-content-between">
                        <span class="font-weight-bold">
                            <i class="fas fa-image mr-1 text-primary"></i> Pantallazo de validación
                            <span class="text-danger">*</span>
                        </span>
                        <span class="badge badge-info" style="font-size:10px">
                            <i class="fas fa-magic mr-1"></i>OCR automático
                        </span>
                    </div>
                    <div class="card-body pb-0">
                        <div id="zona-imagen">
                            <button type="button" id="btn-limpiar-imagen"
                                class="btn btn-sm btn-danger rounded-circle"
                                title="Quitar imagen"
                                style="width:28px;height:28px;padding:0;line-height:1;">
                                <i class="fas fa-times" style="font-size:11px"></i>
                            </button>
                            <div class="placeholder-text">
                                <i class="fas fa-clipboard-check"></i>
                                <strong>Pegue el pantallazo aquí</strong><br>
                                <span class="small">
                                    <kbd>Ctrl+V</kbd> &nbsp;·&nbsp; arrastre una imagen &nbsp;·&nbsp; o haga clic para seleccionar archivo
                                </span>
                            </div>
                            <img id="preview-imagen" src="" alt="Pantallazo">
                        </div>

                        {{-- Panel de progreso OCR --}}
                        <div id="ocr-panel">
                            <div class="d-flex align-items-center mb-1">
                                <span class="spinner-border spinner-border-sm text-info mr-2" id="ocr-spinner" style="display:none"></span>
                                <i class="fas fa-check-circle text-success mr-2" id="ocr-ok-icon"  style="display:none"></i>
                                <i class="fas fa-exclamation-circle text-danger mr-2" id="ocr-err-icon" style="display:none"></i>
                                <span id="ocr-texto" class="small text-muted"></span>
                            </div>
                            <div class="progress" style="height:5px;">
                                <div id="ocr-barra" class="progress-bar" role="progressbar" style="width:0%"></div>
                            </div>
                            <small class="text-muted d-block mt-1">
                                <i class="fas fa-info-circle"></i>
                                Los campos marcados <span class="badge-ocr">OCR</span> fueron completados automáticamente — revíselos antes de guardar.
                            </small>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent py-2">
                        <small class="text-muted">
                            <i class="fas fa-keyboard mr-1"></i>
                            Copie el pantallazo y presione <kbd>Ctrl+V</kbd> en cualquier parte de la página.
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

            {{-- ── Columna derecha: agenda + datos ── --}}
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
                            <div id="resultados-agenda" class="position-absolute w-100"
                                style="z-index:200;display:none;top:100%;left:0">
                                <ul id="lista-agenda" class="list-group shadow-sm"
                                    style="max-height:220px;overflow-y:auto;border-radius:0 0 6px 6px;"></ul>
                            </div>
                        </div>
                        <small class="text-muted mt-1 d-block">
                            <i class="fas fa-info-circle mr-1"></i>
                            Seleccione la cita para auto-llenar los datos. Si no aparece, complétalos manualmente o déjelos que el OCR los detecte.
                        </small>
                        <input type="hidden" id="agenda_ci_id" name="agenda_ci_id">
                    </div>
                </div>

                {{-- Datos del paciente / cita --}}
                <div class="card shadow-sm" id="card-datos-cita" style="transition:border-color .5s">
                    <div class="card-header py-2 font-weight-bold">
                        <i class="fas fa-user-injured mr-1 text-warning"></i> Datos del paciente / cita
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{-- Nombre --}}
                            <div class="col-6 mb-2">
                                <label class="small mb-1">Nombre del paciente</label>
                                <input type="text" id="paciente_nombre" name="paciente_nombre"
                                    class="form-control form-control-sm"
                                    value="{{ old('paciente_nombre') }}" placeholder="Nombre completo">
                            </div>
                            {{-- Tipo doc --}}
                            <div class="col-2 mb-2">
                                <label class="small mb-1">Tipo doc.</label>
                                <select id="paciente_tipo_doc" name="paciente_tipo_doc"
                                    class="form-control form-control-sm">
                                    @foreach(['CC'=>'CC','TI'=>'TI','CE'=>'CE','RC'=>'RC','PA'=>'PA','MS'=>'MS','AS'=>'AS'] as $v=>$l)
                                        <option value="{{ $v }}" {{ old('paciente_tipo_doc','CC') === $v ? 'selected' : '' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- Documento --}}
                            <div class="col-4 mb-2">
                                <label class="small mb-1">Documento</label>
                                <input type="text" id="paciente_cedula" name="paciente_cedula"
                                    class="form-control form-control-sm"
                                    value="{{ old('paciente_cedula') }}" placeholder="Número de documento">
                            </div>
                            {{-- Estado afiliación --}}
                            <div class="col-4 mb-2">
                                <label class="small mb-1">Estado de afiliación</label>
                                <input type="text" id="estado_afiliacion" name="estado_afiliacion"
                                    class="form-control form-control-sm"
                                    value="{{ old('estado_afiliacion') }}"
                                    placeholder="ACTIVO, SUSPENDIDO…"
                                    list="estados-afiliacion-list">
                                <datalist id="estados-afiliacion-list">
                                    <option value="ACTIVO">
                                    <option value="SUSPENDIDO">
                                    <option value="INACTIVO">
                                    <option value="RETIRADO">
                                    <option value="PENDIENTE">
                                    <option value="NO APLICA">
                                </datalist>
                            </div>
                            {{-- Fecha atención --}}
                            <div class="col-4 mb-2">
                                <label class="small mb-1">Fecha atención</label>
                                <input type="date" id="fecha_atencion" name="fecha_atencion"
                                    class="form-control form-control-sm"
                                    value="{{ old('fecha_atencion', date('Y-m-d')) }}">
                            </div>
                            {{-- N° Factura --}}
                            <div class="col-4 mb-2">
                                <label class="small mb-1">N° Factura</label>
                                <input type="text" id="numero_factura" name="numero_factura"
                                    class="form-control form-control-sm"
                                    value="{{ old('numero_factura') }}" placeholder="Factura">
                            </div>
                            {{-- Atención factura --}}
                            <div class="col-4 mb-2">
                                <label class="small mb-1">Atención factura</label>
                                <input type="text" id="atencion_factura" name="atencion_factura"
                                    class="form-control form-control-sm"
                                    value="{{ old('atencion_factura') }}" placeholder="Atención">
                            </div>
                            {{-- Empresa / EPS --}}
                            <div class="col-6 mb-2">
                                <label class="small mb-1">Empresa / EPS</label>
                                <input type="text" id="empresafac" name="empresafac"
                                    class="form-control form-control-sm"
                                    value="{{ old('empresafac') }}" placeholder="EPS o aseguradora">
                            </div>
                            {{-- Contrato --}}
                            <div class="col-6 mb-2">
                                <label class="small mb-1">Contrato</label>
                                <input type="text" id="contrato" name="contrato"
                                    class="form-control form-control-sm"
                                    value="{{ old('contrato') }}" placeholder="Contrato">
                            </div>
                            {{-- CUPS --}}
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
