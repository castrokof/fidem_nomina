@extends("theme.$theme.layout")

@section('titulo')
    {{ $agenda ? 'Validar derechos — ' . $agenda->paciente_nombre : 'Nueva Validación de Derechos' }}
@endsection

@section("styles")
<style>
/* ── Zona de carga ── */
#zona-imagen {
    border: 2px dashed #adb5bd; border-radius: 10px;
    padding: 40px 20px; text-align: center; cursor: pointer;
    transition: border-color .2s, background .2s;
    background: #f8f9fa; position: relative; min-height: 160px;
}
#zona-imagen.dragover   { border-color: #007bff; background: #e8f0fe; }
#zona-imagen.tiene-imagen { border-style: solid; border-color: #28a745; padding: 8px; background: #fff; }
#zona-imagen .placeholder-text { color: #6c757d; }
#zona-imagen .placeholder-text i { font-size: 2.5rem; display: block; margin-bottom: 8px; color: #adb5bd; }
#preview-imagen { max-width: 100%; max-height: 380px; border-radius: 6px; display: none; }
#btn-limpiar-imagen { position: absolute; top: 8px; right: 8px; display: none; z-index: 10; }
#zona-imagen.tiene-imagen #btn-limpiar-imagen { display: inline-block; }
#zona-imagen.tiene-imagen .placeholder-text   { display: none; }
#zona-imagen.tiene-imagen #preview-imagen     { display: block; margin: 0 auto; }

/* ── OCR ── */
#ocr-panel { display:none; border-radius:0 0 8px 8px; border:1px solid #dee2e6; border-top:none; background:#fff; padding:10px 14px; font-size:13px; }
#ocr-progress-bar { transition: width .3s; }
.ocr-campo-ok { animation: flashGreen .8s ease; }
@keyframes flashGreen { 0% { background-color:#d4edda; } 100% { background-color:transparent; } }
.badge-ocr { font-size:10px; font-weight:600; vertical-align:middle; background:#17a2b8; color:#fff; border-radius:4px; padding:1px 5px; margin-left:4px; }

/* ── Toast ── */
#toast-pegar { position:fixed; bottom:20px; right:20px; background:#343a40; color:#fff; padding:10px 18px; border-radius:8px; font-size:13px; z-index:9999; display:none; opacity:0; transition:opacity .3s; }

/* ── Cita vinculada ── */
#card-cita-vinculada { background: #f0fff4; border: 1px solid #c3e6cb; border-radius: 8px; }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>
<script>
$(document).ready(function () {

    var zona       = document.getElementById('zona-imagen');
    var preview    = document.getElementById('preview-imagen');
    var inputB64   = document.getElementById('imagen_base64');
    var btnLimpiar = document.getElementById('btn-limpiar-imagen');
    var toast      = document.getElementById('toast-pegar');

    // ── OCR ──────────────────────────────────────────────────────────────────
    function mostrarPanelOCR(estado) {
        var panel  = document.getElementById('ocr-panel');
        var spin   = document.getElementById('ocr-spinner');
        var ok     = document.getElementById('ocr-ok-icon');
        var err    = document.getElementById('ocr-err-icon');
        panel.style.display = estado === 'hidden' ? 'none' : 'block';
        spin.style.display  = estado === 'loading' ? 'inline-block' : 'none';
        ok.style.display    = estado === 'ok'      ? 'inline-block' : 'none';
        err.style.display   = estado === 'error'   ? 'inline-block' : 'none';
        if (estado === 'loading') {
            document.getElementById('ocr-barra').style.width = '0%';
            document.getElementById('ocr-barra').className = 'progress-bar progress-bar-striped progress-bar-animated bg-info';
            document.getElementById('ocr-texto').textContent = 'Iniciando OCR…';
        }
    }

    function setProgreso(pct, msg) {
        document.getElementById('ocr-barra').style.width = pct + '%';
        document.getElementById('ocr-texto').textContent = msg;
    }

    async function ejecutarOCR(dataUrl) {
        mostrarPanelOCR('loading');
        try {
            var resultado = await Tesseract.recognize(dataUrl, 'spa', {
                logger: function (m) {
                    if (m.status === 'loading tesseract core')         setProgreso(5,  'Cargando motor OCR…');
                    if (m.status === 'initializing tesseract')         setProgreso(15, 'Inicializando…');
                    if (m.status === 'loading language traineddata')   setProgreso(30, 'Descargando modelo de español…');
                    if (m.status === 'initializing api')               setProgreso(50, 'Preparando análisis…');
                    if (m.status === 'recognizing text')               setProgreso(50 + Math.round(m.progress * 45), 'Reconociendo texto… ' + Math.round(m.progress * 100) + '%');
                }
            });
            setProgreso(100, 'Analizando resultado…');
            var campos = parsearTextoOCR(resultado.data.text);
            aplicarCamposOCR(campos);
            var n = Object.keys(campos).length;
            document.getElementById('ocr-barra').className = 'progress-bar bg-success';
            document.getElementById('ocr-texto').textContent = n > 0
                ? 'OCR completado — ' + n + ' campo(s) detectado(s). Revise antes de guardar.'
                : 'OCR completado — no se detectaron campos. Complete manualmente.';
            mostrarPanelOCR('ok');
        } catch (e) {
            document.getElementById('ocr-texto').textContent = 'Error en OCR. Complete los campos manualmente.';
            mostrarPanelOCR('error');
        }
    }

    function parsearTextoOCR(texto) {
        var res   = {};
        var upper = texto.toUpperCase();

        var estados = ['ACTIVO','SUSPENDIDO','INACTIVO','RETIRADO','PENDIENTE'];
        for (var i = 0; i < estados.length; i++) {
            if (upper.indexOf(estados[i]) !== -1) { res.estado_afiliacion = estados[i]; break; }
        }

        var mTipo = upper.match(/TIPO\s*(?:DE\s*)?DOC(?:UMENTO)?\s*[:\-]?\s*(CC|TI|CE|RC|PA|NIT|AS|MS)/);
        if (mTipo) { res.tipo_doc = mTipo[1]; }
        else if (/C[EÉ]DULA\s*DE\s*CIUDADAN/.test(upper))   { res.tipo_doc = 'CC'; }
        else if (/TARJETA\s*DE\s*IDENTIDAD/.test(upper))    { res.tipo_doc = 'TI'; }
        else if (/C[EÉ]DULA\s*(?:DE\s*)?EXTRANJER/.test(upper)) { res.tipo_doc = 'CE'; }
        else if (/REGISTRO\s*CIVIL/.test(upper))            { res.tipo_doc = 'RC'; }
        else if (/PASAPORTE/.test(upper))                   { res.tipo_doc = 'PA'; }

        var patronesDoc = [
            /(?:N[°oúO]?\.?\s*(?:DE\s*)?DOCUMENTO|C[EÉ]DULA|NO\.?\s*IDENTIFICACION|NO\.?\s*ID)\s*[:\-]?\s*(\d{5,12})/i,
            /\bCC\s*[:\-#]?\s*(\d{6,12})\b/i,
            /\bTI\s*[:\-#]?\s*(\d{6,12})\b/i,
        ];
        for (var j = 0; j < patronesDoc.length; j++) {
            var mDoc = texto.match(patronesDoc[j]);
            if (mDoc) { res.cedula = mDoc[1]; break; }
        }

        var patronesNombre = [
            /(?:NOMBRE\s*(?:DEL?\s*)?(?:PACIENTE|AFILIADO|USUARIO)?|AFILIADO\s*:|PACIENTE\s*:)\s*[:\-]?\s*([A-ZÁÉÍÓÚÑÜ][A-ZÁÉÍÓÚÑÜ ,\.\-]{4,70})/i,
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

    function aplicarCamposOCR(campos) {
        function setOCR(id, valor) {
            if (!valor) return;
            var el = document.getElementById(id);
            if (!el || el.value.trim() !== '') return;
            el.value = valor;
            el.parentElement.classList.add('ocr-campo-ok');
            var label = el.previousElementSibling;
            if (label && !label.querySelector('.badge-ocr')) {
                label.innerHTML += '<span class="badge-ocr">OCR</span>';
            }
        }
        if (campos.nombre)            setOCR('paciente_nombre', campos.nombre);
        if (campos.tipo_doc)          setOCR('paciente_tipo_doc', campos.tipo_doc);
        if (campos.cedula)            setOCR('paciente_cedula', campos.cedula);
        if (campos.estado_afiliacion) setOCR('estado_afiliacion', campos.estado_afiliacion);
    }

    // ── Imagen ───────────────────────────────────────────────────────────────
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
        document.querySelectorAll('.badge-ocr').forEach(function (b) { b.remove(); });
    }

    function procesarArchivo(file) {
        if (!file || !file.type.startsWith('image/')) { alert('El archivo debe ser una imagen.'); return; }
        var reader = new FileReader();
        reader.onload = function (e) { mostrarImagen(e.target.result); };
        reader.readAsDataURL(file);
    }

    function mostrarToast(msg) {
        toast.textContent = msg;
        toast.style.display = 'block';
        setTimeout(function () { toast.style.opacity = '1'; }, 10);
        setTimeout(function () {
            toast.style.opacity = '0';
            setTimeout(function () { toast.style.display = 'none'; }, 300);
        }, 2500);
    }

    btnLimpiar.addEventListener('click', function (e) { e.stopPropagation(); limpiarImagen(); });
    zona.addEventListener('click', function (e) {
        if (e.target === btnLimpiar || btnLimpiar.contains(e.target)) return;
        if (zona.classList.contains('tiene-imagen')) return;
        document.getElementById('archivo-input').click();
    });
    document.getElementById('archivo-input').addEventListener('change', function () {
        if (this.files && this.files[0]) procesarArchivo(this.files[0]);
        this.value = '';
    });
    zona.addEventListener('dragover',  function (e) { e.preventDefault(); zona.classList.add('dragover'); });
    zona.addEventListener('dragleave', function ()  { zona.classList.remove('dragover'); });
    zona.addEventListener('drop', function (e) {
        e.preventDefault(); zona.classList.remove('dragover');
        procesarArchivo(e.dataTransfer.files[0]);
    });
    document.addEventListener('paste', function (e) {
        var items = e.clipboardData ? e.clipboardData.items : [];
        for (var i = 0; i < items.length; i++) {
            if (items[i].type.startsWith('image/')) {
                procesarArchivo(items[i].getAsFile());
                mostrarToast('✅ Pantallazo pegado — ejecutando OCR…');
                return;
            }
        }
    });

    // ── Validación formulario ────────────────────────────────────────────────
    $('#form-validacion').on('submit', function (e) {
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

    {{-- Encabezado --}}
    <div class="d-flex align-items-center mb-3">
        <a href="{{ route('validaciones.index', ['fecha' => $agenda ? \Carbon\Carbon::parse($agenda->fecha)->format('Y-m-d') : date('Y-m-d')]) }}"
           class="btn btn-sm btn-outline-secondary mr-2">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h4 class="mb-0">
                <i class="fas fa-shield-alt text-primary mr-2"></i>
                {{ $agenda ? 'Validación de derechos' : 'Nueva Validación de Derechos' }}
            </h4>
            <small class="text-muted">
                Adjunte el pantallazo — el OCR completará los campos automáticamente.
            </small>
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

        {{-- Fecha de retorno para redirigir al índice correcto --}}
        @if($agenda)
            <input type="hidden" name="fecha_agenda"
                value="{{ \Carbon\Carbon::parse($agenda->fecha)->format('Y-m-d') }}">
        @endif

        <div class="row">

            {{-- ── Columna izquierda: imagen ── --}}
            <div class="col-lg-6 mb-3">

                {{-- Cita vinculada (si viene desde el índice) --}}
                @if($agenda)
                <div id="card-cita-vinculada" class="p-3 mb-3">
                    <div class="d-flex align-items-center mb-1">
                        <i class="fas fa-link text-success mr-2"></i>
                        <strong>Cita vinculada desde la agenda</strong>
                    </div>
                    <div class="row small">
                        <div class="col-6">
                            <span class="text-muted">Paciente:</span>
                            <strong>{{ $agenda->paciente_nombre }}</strong>
                        </div>
                        <div class="col-6">
                            <span class="text-muted">Documento:</span>
                            {{ $agenda->paciente_tipo_doc }} {{ $agenda->paciente_cedula }}
                        </div>
                        <div class="col-6 mt-1">
                            <span class="text-muted">Empresa:</span>
                            {{ $agenda->empresafac ?? '-' }}
                        </div>
                        <div class="col-6 mt-1">
                            <span class="text-muted">Hora:</span>
                            {{ \Carbon\Carbon::parse($agenda->fecha)->format('d/m/Y H:i') }}
                        </div>
                        @if($agenda->cups_descripcion)
                        <div class="col-12 mt-1">
                            <span class="text-muted">Procedimiento:</span>
                            {{ $agenda->cups_descripcion }}
                        </div>
                        @endif
                    </div>
                </div>
                @endif

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
                                title="Quitar imagen" style="width:28px;height:28px;padding:0;line-height:1;">
                                <i class="fas fa-times" style="font-size:11px"></i>
                            </button>
                            <div class="placeholder-text">
                                <i class="fas fa-clipboard-check"></i>
                                <strong>Pegue el pantallazo aquí</strong><br>
                                <span class="small">
                                    <kbd>Ctrl+V</kbd> &nbsp;·&nbsp; arrastre una imagen &nbsp;·&nbsp; o haga clic
                                </span>
                            </div>
                            <img id="preview-imagen" src="" alt="Pantallazo">
                        </div>
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
                                Los campos marcados <span class="badge-ocr">OCR</span> fueron completados automáticamente — revíselos.
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
                            placeholder="Observaciones adicionales…">{{ old('observaciones') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- ── Columna derecha: datos del paciente / cita ── --}}
            <div class="col-lg-6 mb-3">

                <input type="hidden" id="agenda_ci_id" name="agenda_ci_id"
                    value="{{ $agenda ? $agenda->id : old('agenda_ci_id') }}">

                <div class="card shadow-sm" id="card-datos-cita">
                    <div class="card-header py-2 font-weight-bold">
                        <i class="fas fa-user-injured mr-1 text-warning"></i> Datos del paciente / validación
                        <small class="text-muted font-weight-normal ml-2">
                            (pre-llenados desde la agenda · edite si es necesario)
                        </small>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 mb-2">
                                <label class="small mb-1">Nombre del paciente</label>
                                <input type="text" id="paciente_nombre" name="paciente_nombre"
                                    class="form-control form-control-sm"
                                    value="{{ old('paciente_nombre', $agenda->paciente_nombre ?? '') }}"
                                    placeholder="Nombre completo">
                            </div>
                            <div class="col-2 mb-2">
                                <label class="small mb-1">Tipo doc.</label>
                                <select id="paciente_tipo_doc" name="paciente_tipo_doc"
                                    class="form-control form-control-sm">
                                    @foreach(['CC'=>'CC','TI'=>'TI','CE'=>'CE','RC'=>'RC','PA'=>'PA','MS'=>'MS','AS'=>'AS'] as $v=>$l)
                                        <option value="{{ $v }}"
                                            {{ old('paciente_tipo_doc', $agenda->paciente_tipo_doc ?? 'CC') === $v ? 'selected' : '' }}>
                                            {{ $l }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4 mb-2">
                                <label class="small mb-1">Documento</label>
                                <input type="text" id="paciente_cedula" name="paciente_cedula"
                                    class="form-control form-control-sm"
                                    value="{{ old('paciente_cedula', $agenda->paciente_cedula ?? '') }}"
                                    placeholder="Número de documento">
                            </div>
                            <div class="col-4 mb-2">
                                <label class="small mb-1">Estado de afiliación</label>
                                <input type="text" id="estado_afiliacion" name="estado_afiliacion"
                                    class="form-control form-control-sm"
                                    value="{{ old('estado_afiliacion') }}"
                                    placeholder="ACTIVO, SUSPENDIDO…"
                                    list="estados-list">
                                <datalist id="estados-list">
                                    <option value="ACTIVO">
                                    <option value="SUSPENDIDO">
                                    <option value="INACTIVO">
                                    <option value="RETIRADO">
                                    <option value="PENDIENTE">
                                    <option value="NO APLICA">
                                </datalist>
                            </div>
                            <div class="col-4 mb-2">
                                <label class="small mb-1">Fecha atención</label>
                                <input type="date" id="fecha_atencion" name="fecha_atencion"
                                    class="form-control form-control-sm"
                                    value="{{ old('fecha_atencion', $agenda ? \Carbon\Carbon::parse($agenda->fecha)->format('Y-m-d') : date('Y-m-d')) }}">
                            </div>
                            <div class="col-4 mb-2">
                                <label class="small mb-1">N° Factura</label>
                                <input type="text" id="numero_factura" name="numero_factura"
                                    class="form-control form-control-sm"
                                    value="{{ old('numero_factura', $agenda->numero_factura ?? '') }}"
                                    placeholder="Factura">
                            </div>
                            <div class="col-4 mb-2">
                                <label class="small mb-1">Atención factura</label>
                                <input type="text" id="atencion_factura" name="atencion_factura"
                                    class="form-control form-control-sm"
                                    value="{{ old('atencion_factura', $agenda->atencion_factura ?? '') }}"
                                    placeholder="Atención">
                            </div>
                            <div class="col-6 mb-2">
                                <label class="small mb-1">Empresa / EPS</label>
                                <input type="text" id="empresafac" name="empresafac"
                                    class="form-control form-control-sm"
                                    value="{{ old('empresafac', $agenda->empresafac ?? '') }}"
                                    placeholder="EPS o aseguradora">
                            </div>
                            <div class="col-6 mb-2">
                                <label class="small mb-1">Contrato</label>
                                <input type="text" id="contrato" name="contrato"
                                    class="form-control form-control-sm"
                                    value="{{ old('contrato', $agenda->contrato ?? '') }}"
                                    placeholder="Contrato">
                            </div>
                            <div class="col-3 mb-2">
                                <label class="small mb-1">CUPS</label>
                                <input type="text" id="cups_codigo" name="cups_codigo"
                                    class="form-control form-control-sm"
                                    value="{{ old('cups_codigo', $agenda->cups_codigo ?? '') }}"
                                    placeholder="Código">
                            </div>
                            <div class="col-9 mb-2">
                                <label class="small mb-1">Descripción procedimiento</label>
                                <input type="text" id="cups_descripcion" name="cups_descripcion"
                                    class="form-control form-control-sm"
                                    value="{{ old('cups_descripcion', $agenda->cups_descripcion ?? '') }}"
                                    placeholder="Descripción">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <a href="{{ route('validaciones.index', ['fecha' => $agenda ? \Carbon\Carbon::parse($agenda->fecha)->format('Y-m-d') : date('Y-m-d')]) }}"
                        class="btn btn-outline-secondary mr-2">
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
