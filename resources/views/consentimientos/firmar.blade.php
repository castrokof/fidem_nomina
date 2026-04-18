<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Firmar Consentimiento Informado - Clínica Fidem</title>

    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 20px;
        }
        .signature-pad {
            border: 2px solid #007bff;
            border-radius: 10px;
            background-color: white;
            cursor: crosshair;
            touch-action: none;
            user-select: none;
            -webkit-user-select: none;
        }
        .btn-clear {
            background-color: #dc3545;
            color: white;
        }
        .btn-clear:hover {
            background-color: #c82333;
            color: white;
        }
        .section-title {
            background-color: #f8f9fa;
            padding: 10px 15px;
            border-left: 4px solid #007bff;
            margin: 20px 0 15px 0;
            font-weight: bold;
        }
        .info-field {
            margin-bottom: 10px;
            padding: 8px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="logo-container">
                    <h2 class="text-white font-weight-bold">
                        <i class="fas fa-hospital"></i> Clínica Fidem
                    </h2>
                    <p class="text-white">Cali, Colombia</p>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">
                            <i class="fas fa-file-signature"></i> Consentimiento Informado
                        </h4>
                    </div>
                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                            </div>
                        @endif

                        @if(isset($consentimiento))
                            <!-- Información del paciente -->
                            <div class="section-title">
                                <i class="fas fa-user"></i> Información del Paciente
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-field">
                                        <strong>Nombre:</strong> {{ $consentimiento->paciente->nombres }} {{ $consentimiento->paciente->apellidos }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-field">
                                        <strong>Identificación:</strong> {{ $consentimiento->paciente->tipo_documento }}-{{ $consentimiento->paciente->numero_documento }}
                                    </div>
                                </div>
                            </div>

                            <!-- Información del procedimiento -->
                            <div class="section-title">
                                <i class="fas fa-notes-medical"></i> Información del Procedimiento
                            </div>
                            <div class="info-field">
                                <strong>Procedimiento:</strong> {{ $consentimiento->plantilla->nombre }}
                            </div>
                            <div class="info-field">
                                <strong>Profesional:</strong> {{ $consentimiento->profesional->nombres }} {{ $consentimiento->profesional->apellidos }}
                            </div>
                            <div class="info-field">
                                <strong>Fecha del procedimiento:</strong> {{ \Carbon\Carbon::parse($consentimiento->fecha_procedimiento)->format('d/m/Y H:i') }}
                            </div>

                           {{-- Contenido del Consentimiento --}}
<div class="info-section">
    <h5><i class="fas fa-file-alt"></i> Contenido del Consentimiento</h5>
    
    @php
        // Preparar variables para reemplazo
        $variables = [
            'cups_descripcion'      => $consentimiento->cups_descripcion ?? $consentimiento->plantilla->nombre ?? '',
            'cups_codigo'           => $consentimiento->cups_codigo ?? '',
            'paciente_nombre'       => $consentimiento->paciente->nombres . ' ' . $consentimiento->paciente->apellidos,
            'paciente_cedula'       => $consentimiento->paciente->numero_documento,
            'paciente_tipo_doc'     => $consentimiento->paciente->tipo_documento,
            'paciente_edad'         => $consentimiento->paciente->edad ?? 'N/A',
            'paciente_genero'       => $consentimiento->paciente->genero ?? 'N/A',
            'profesional_nombre'    => $consentimiento->profesional->nombres . ' ' . $consentimiento->profesional->apellidos,
            'registro_medico'       => $consentimiento->profesional->registro_medico ?? 'N/A',
            'especialidad'          => $consentimiento->profesional->especialidad->nombre ?? 'N/A',
            'fecha_procedimiento'   => \Carbon\Carbon::parse($consentimiento->fecha_procedimiento)->format('d/m/Y H:i'),
            'fecha_actual'          => \Carbon\Carbon::now()->format('d/m/Y'),
            'clinica_nombre'        => 'Clínica Fidem',
            'clinica_direccion'     => 'Manizales, Colombia',
        ];
        
        // Renderizar contenido
        $contenidoRenderizado = $consentimiento->plantilla->renderizar($variables);
    @endphp
    
    <div style="max-height: 400px; overflow-y: auto; padding: 15px; background-color: white; border: 1px solid #dee2e6;">
        {!! $contenidoRenderizado !!}
    </div>
</div>

                            <!-- Formulario de firma -->
                            <form id="formFirma" method="POST" action="{{ route('consentimientos.guardar-firma', $token) }}">
                                @csrf

                                <!-- Sección de voluntad de información -->
                                <div class="section-title mt-4">
                                    <i class="fas fa-question-circle"></i> Voluntad de Información
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">*¿DESEO SER INFORMADO sobre mi enfermedad y la intervención que me van a realizar?</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="desea_ser_informado" id="desea_si" value="1" required checked>
                                        <label class="form-check-label" for="desea_si">
                                            Sí
                                        </label>
                                        DESEO QUE LA INFORMACIÓN de mi enfermedad y la intervención que me van a realizar le sea proporcionada a mi familiar / tutor / representante legal:

                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="desea_ser_informado" id="desea_no" value="0">
                                        <label class="form-check-label" for="desea_no">
                                            No
                                        </label>
                                         “MANIFIESTO MI DESEO DE NO SER INFORMADO Y PRESTO MI CONSENTIMIENTO" para que se lleve a cabo el procedimiento descrito en este documento

                                    </div>
                                </div>

                                <!-- Firma del Paciente -->
                                <div class="section-title">
                                    <i class="fas fa-signature"></i> Firma del Paciente
                                </div>
                                <div id="declaracionPaciente">
                                    <p id="textoDeclaracionSi"><strong>DECLARO</strong> que he comprendido adecuadamente la información que contiene este documento, que firmo el consentimiento para la realización del procedimiento que se describe en el mismo, que he recibido copia del mismo y que conozco que el consentimiento puede ser revocado por escrito en cualquier momento.</p>
                                    <p id="textoDeclaracionNo" style="display:none;"><strong>MANIFIESTO MI DESEO DE NO SER INFORMADO Y PRESTO MI CONSENTIMIENTO</strong> para que se lleve a cabo el procedimiento descrito en este documento.</p>
                                </div>

                                <!-- Campos obligatorios de edad y género -->
                                <div class="section-title mt-3">
                                    <i class="fas fa-user-check"></i> Confirmación de Datos del Paciente
                                </div>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Por favor, confirme su edad y género antes de firmar.
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Edad <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="paciente_edad" id="pacienteEdad"
                                                   min="0" max="150" required
                                                   placeholder="Ingrese su edad">
                                            <small class="form-text text-muted">Ingrese su edad en años</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Género <span class="text-danger">*</span></label>
                                            <select class="form-control" name="paciente_genero" id="pacienteGenero" required>
                                                <option value="">Seleccione...</option>
                                                <option value="Masculino">Masculino</option>
                                                <option value="Femenino">Femenino</option>
                                                <option value="Otro">Otro</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Selector: Firma o Foto -->
                                <div class="mb-3">
                                    <div class="btn-group w-100" role="group">
                                        <button type="button" id="btnModoPacienteFirma" class="btn btn-primary"
                                                onclick="setModo('paciente','firma')">
                                            <i class="fas fa-pen-nib"></i> Puedo firmar
                                        </button>
                                        <button type="button" id="btnModoPacienteFoto" class="btn btn-outline-secondary"
                                                onclick="setModo('paciente','foto')">
                                            <i class="fas fa-camera"></i> No sabe / no puede firmar
                                        </button>
                                    </div>
                                </div>

                                <!-- Panel de firma -->
                                <div id="panelFirmaPaciente" class="form-group">
                                    <label class="font-weight-bold">Firme en el recuadro a continuación:</label>
                                    <div class="text-center">
                                        <canvas id="signaturePadPaciente" class="signature-pad" width="600" height="200"></canvas>
                                    </div>
                                    <button type="button" class="btn btn-clear btn-sm mt-2" onclick="clearSignature('signaturePadPaciente')">
                                        <i class="fas fa-eraser"></i> Limpiar Firma
                                    </button>
                                </div>

                                <!-- Panel de cámara -->
                                <div id="panelFotoPaciente" class="form-group" style="display:none;">
                                    <label class="font-weight-bold">Tome una foto del paciente:</label>
                                    <div class="text-center">
                                        <video id="videoPaciente" autoplay playsinline
                                               style="width:100%;max-width:400px;border-radius:8px;border:2px solid #007bff;display:none;"></video>
                                        <canvas id="canvasFotoPaciente" style="display:none;"></canvas>
                                        <img id="previewFotoPaciente" style="display:none;max-width:400px;width:100%;border-radius:8px;border:2px solid #28a745;" alt="Foto capturada">
                                    </div>
                                    <div class="text-center mt-2">
                                        <button type="button" class="btn btn-info btn-sm" id="btnAbrirCamaraPaciente" onclick="abrirCamara('paciente')">
                                            <i class="fas fa-camera"></i> Abrir cámara
                                        </button>
                                        <button type="button" class="btn btn-success btn-sm" id="btnCapturarPaciente" onclick="capturarFoto('paciente')" style="display:none;">
                                            <i class="fas fa-circle"></i> Capturar foto
                                        </button>
                                        <button type="button" class="btn btn-warning btn-sm" id="btnRepetirPaciente" onclick="repetirFoto('paciente')" style="display:none;">
                                            <i class="fas fa-redo"></i> Repetir
                                        </button>
                                    </div>
                                    <div id="timestampPaciente" class="text-center mt-1 text-muted small" style="display:none;"></div>
                                </div>

                                <input type="hidden" name="firma_base64"   id="firmaPacienteInput">
                                <input type="hidden" name="foto_base64"    id="fotoPacienteInput">
                                <input type="hidden" name="no_sabe_firmar" id="noSabeFirePaciente" value="0">

                                <!-- Sección de Acudiente/Familiar/Tutor -->
                                @php
                                    $requiereAcudienteObligatorio = $consentimiento->plantilla->requiere_acudiente_obligatorio ?? false;
                                @endphp
                                <div class="section-title">
                                    <i class="fas fa-user-friends"></i> Firma del Familiar/Tutor/Representante
                                    @if($requiereAcudienteObligatorio)
                                        <span class="badge badge-danger">OBLIGATORIO</span>
                                    @else
                                        <span class="badge badge-info">Opcional</span>
                                    @endif
                                </div>

                                @if($requiereAcudienteObligatorio)
                                    <div class="alert alert-warning mb-3">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <strong>Atención:</strong> Este consentimiento requiere obligatoriamente la firma de un familiar, tutor o representante legal.
                                    </div>
                                    <input type="hidden" id="requiereAcudiente" name="requiere_acudiente" value="1">
                                @else
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="requiereAcudiente" name="requiere_acudiente" value="1">
                                        <label class="form-check-label" for="requiereAcudiente">
                                            Requiere firma de familiar/tutor/representante legal
                                        </label>
                                    </div>
                                @endif

                                <div id="seccionAcudiente" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Nombres del Acudiente <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="acudiente_nombres" id="acudienteNombres">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Apellidos del Acudiente <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="acudiente_apellidos" id="acudienteApellidos">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Tipo de Documento <span class="text-danger">*</span></label>
                                                <select class="form-control" name="acudiente_tipo_documento" id="acudienteTipoDoc">
                                                    <option value="">Seleccione...</option>
                                                    <option value="CC">Cédula de Ciudadanía</option>
                                                    <option value="CE">Cédula de Extranjería</option>
                                                    <option value="TI">Tarjeta de Identidad</option>
                                                    <option value="PA">Pasaporte</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Número de Documento <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="acudiente_numero_documento" id="acudienteNumDoc">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Parentesco o Relación <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="acudiente_parentesco" id="acudienteParentesco" placeholder="Ej: Padre, Madre, Tutor Legal, etc.">
                                    </div>

                                    <!-- Selector: Firma o Foto acudiente -->
                                    <div class="mb-3">
                                        <div class="btn-group w-100" role="group">
                                            <button type="button" id="btnModoAcudienteFirma" class="btn btn-primary"
                                                    onclick="setModo('acudiente','firma')">
                                                <i class="fas fa-pen-nib"></i> Puede firmar
                                            </button>
                                            <button type="button" id="btnModoAcudienteFoto" class="btn btn-outline-secondary"
                                                    onclick="setModo('acudiente','foto')">
                                                <i class="fas fa-camera"></i> No sabe / no puede firmar
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Panel firma acudiente -->
                                    <div id="panelFirmaAcudiente" class="form-group">
                                        <label class="font-weight-bold">Firma del Acudiente:</label>
                                        <div class="text-center">
                                            <canvas id="signaturePadAcudiente" class="signature-pad" width="600" height="200"></canvas>
                                        </div>
                                        <button type="button" class="btn btn-clear btn-sm mt-2" onclick="clearSignature('signaturePadAcudiente')">
                                            <i class="fas fa-eraser"></i> Limpiar Firma
                                        </button>
                                    </div>

                                    <!-- Panel cámara acudiente -->
                                    <div id="panelFotoAcudiente" class="form-group" style="display:none;">
                                        <label class="font-weight-bold">Tome una foto del acudiente:</label>
                                        <div class="text-center">
                                            <video id="videoAcudiente" autoplay playsinline
                                                   style="width:100%;max-width:400px;border-radius:8px;border:2px solid #007bff;display:none;"></video>
                                            <canvas id="canvasFotoAcudiente" style="display:none;"></canvas>
                                            <img id="previewFotoAcudiente" style="display:none;max-width:400px;width:100%;border-radius:8px;border:2px solid #28a745;" alt="Foto capturada">
                                        </div>
                                        <div class="text-center mt-2">
                                            <button type="button" class="btn btn-info btn-sm" id="btnAbrirCamaraAcudiente" onclick="abrirCamara('acudiente')">
                                                <i class="fas fa-camera"></i> Abrir cámara
                                            </button>
                                            <button type="button" class="btn btn-success btn-sm" id="btnCapturarAcudiente" onclick="capturarFoto('acudiente')" style="display:none;">
                                                <i class="fas fa-circle"></i> Capturar foto
                                            </button>
                                            <button type="button" class="btn btn-warning btn-sm" id="btnRepetirAcudiente" onclick="repetirFoto('acudiente')" style="display:none;">
                                                <i class="fas fa-redo"></i> Repetir
                                            </button>
                                        </div>
                                        <div id="timestampAcudiente" class="text-center mt-1 text-muted small" style="display:none;"></div>
                                    </div>

                                    <input type="hidden" name="firma_base64"   id="firmaAcudienteInput">
                                    <input type="hidden" name="foto_base64"    id="fotoAcudienteInput">
                                    <input type="hidden" name="no_sabe_firmar" id="noSabeFireAcudiente" value="0">
                                </div>

                                <!-- Botón de envío -->
                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg" id="btnEnviar">
                                        <i class="fas fa-check-circle"></i> Confirmar y Enviar Firmas
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> No se encontró el consentimiento informado o el enlace ha expirado.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="text-center mt-3">
                    <p class="text-white">
                        <small>&copy; {{ date('Y') }} Clínica Fidem - Todos los derechos reservados</small>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <!-- Signature Pad -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

    <script>
        // Inicializar signature pads
        const canvasPaciente = document.getElementById('signaturePadPaciente');
        const signaturePadPaciente = new SignaturePad(canvasPaciente, {
            backgroundColor: 'rgb(255, 255, 255)',
            penColor: 'rgb(0, 0, 0)'
        });

        const canvasAcudiente = document.getElementById('signaturePadAcudiente');
        const signaturePadAcudiente = new SignaturePad(canvasAcudiente, {
            backgroundColor: 'rgb(255, 255, 255)',
            penColor: 'rgb(0, 0, 0)'
        });

        // Ajustar canvas para pantallas pequeñas.
        // En móvil el evento resize se dispara al hacer scroll (la barra del navegador
        // se oculta/muestra cambiando el alto del viewport). Para evitar limpiar el canvas
        // innecesariamente, solo redimensionamos cuando el ANCHO cambia realmente.
        let lastCanvasWidth = 0;

        function resizeCanvas() {
            const parent = canvasPaciente.parentElement;
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            const newWidth = Math.min(600, parent.offsetWidth - 20);

            // Si el ancho no cambió (ej: scroll en móvil), no hacer nada
            if (newWidth === lastCanvasWidth) return;
            lastCanvasWidth = newWidth;

            // Guardar firmas antes de redimensionar (canvas.width = x limpia el canvas)
            const dataPaciente  = signaturePadPaciente.isEmpty()  ? null : signaturePadPaciente.toData();
            const dataAcudiente = signaturePadAcudiente.isEmpty() ? null : signaturePadAcudiente.toData();

            document.querySelectorAll('.signature-pad').forEach((canvas) => {
                canvas.width  = newWidth * ratio;
                canvas.height = 200 * ratio;
                canvas.style.width  = newWidth + 'px';
                canvas.style.height = '200px';
                canvas.getContext('2d').scale(ratio, ratio);
            });

            // Restaurar firmas después de redimensionar
            if (dataPaciente)  signaturePadPaciente.fromData(dataPaciente);
            if (dataAcudiente) signaturePadAcudiente.fromData(dataAcudiente);
        }

        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();

        // Función para limpiar firma
        function clearSignature(canvasId) {
            if (canvasId === 'signaturePadPaciente') {
                signaturePadPaciente.clear();
            } else if (canvasId === 'signaturePadAcudiente') {
                signaturePadAcudiente.clear();
            }
        }

        // Mostrar/ocultar sección de acudiente
        function acudienteActivo() {
            const el = document.getElementById('requiereAcudiente');
            if (!el) return false;
            return el.type === 'hidden' ? el.value === '1' : el.checked;
        }

        function toggleSeccionAcudiente() {
            if (acudienteActivo()) {
                $('#seccionAcudiente').slideDown();
                $('#acudienteNombres, #acudienteApellidos, #acudienteTipoDoc, #acudienteNumDoc, #acudienteParentesco')
                    .prop('required', true);
            } else {
                $('#seccionAcudiente').slideUp();
                $('#acudienteNombres, #acudienteApellidos, #acudienteTipoDoc, #acudienteNumDoc, #acudienteParentesco')
                    .prop('required', false);
                signaturePadAcudiente.clear();
            }
        }

        $('#requiereAcudiente').change(toggleSeccionAcudiente);

        // Cambiar texto de declaración según la opción seleccionada
        function toggleDeclaracionPaciente() {
            const deseaSerInformado = $('input[name="desea_ser_informado"]:checked').val() === '1';
            if (deseaSerInformado) {
                $('#textoDeclaracionSi').show();
                $('#textoDeclaracionNo').hide();
            } else {
                $('#textoDeclaracionSi').hide();
                $('#textoDeclaracionNo').show();
            }
        }

        $('input[name="desea_ser_informado"]').change(toggleDeclaracionPaciente);

        // Ejecutar al cargar la página por si viene obligatorio
        $(document).ready(function() {
            toggleSeccionAcudiente();
            toggleDeclaracionPaciente();
        });

        // ─── Cámara ────────────────────────────────────────────────────────────
        const streams = {};  // guarda el MediaStream activo por persona

        function setModo(persona, modo) {
            const esFoto = modo === 'foto';
            $(`#panelFirma${cap(persona)}`).toggle(!esFoto);
            $(`#panelFoto${cap(persona)}`).toggle(esFoto);
            $(`#btnModo${cap(persona)}Firma`).toggleClass('active btn-outline-primary btn-primary', !esFoto)
                                              .toggleClass('active btn-outline-primary btn-primary', !esFoto);
            $(`#noSabeFirem${cap(persona)},#noSabeFirePaciente,#noSabeFireAcudiente`).filter(`#noSabeFirem${cap(persona)}`);
            document.getElementById(`noSabeFirem${cap(persona)}`) || null;

            // Actualizar hidden según persona
            if (persona === 'paciente') {
                document.getElementById('noSabeFirePaciente').value = esFoto ? '1' : '0';
            } else {
                document.getElementById('noSabeFireAcudiente').value = esFoto ? '1' : '0';
            }

            // Resaltar botones
            document.getElementById(`btnModo${cap(persona)}Firma`).className =
                'btn ' + (esFoto ? 'btn-outline-primary' : 'btn-primary');
            document.getElementById(`btnModo${cap(persona)}Foto`).className =
                'btn ' + (esFoto ? 'btn-secondary' : 'btn-outline-secondary');

            // Si sale del modo foto, detener cámara
            if (!esFoto && streams[persona]) {
                streams[persona].getTracks().forEach(t => t.stop());
                delete streams[persona];
                document.getElementById(`video${cap(persona)}`).style.display = 'none';
                document.getElementById(`btnCapturar${cap(persona)}`).style.display = 'none';
                document.getElementById(`btnRepetir${cap(persona)}`).style.display = 'none';
                document.getElementById(`btnAbrirCamara${cap(persona)}`).style.display = '';
                document.getElementById(`previewFoto${cap(persona)}`).style.display = 'none';
                document.getElementById(`timestamp${cap(persona)}`).style.display = 'none';
            }
        }

        function cap(s) { return s.charAt(0).toUpperCase() + s.slice(1); }

        async function abrirCamara(persona) {
            const video = document.getElementById(`video${cap(persona)}`);
            const btnAbrir    = document.getElementById(`btnAbrirCamara${cap(persona)}`);
            const btnCapturar = document.getElementById(`btnCapturar${cap(persona)}`);

            // Preferir cámara frontal, luego trasera, luego cualquiera
            const intentos = [
                { video: { facingMode: 'user' } },
                { video: { facingMode: 'environment' } },
                { video: true }
            ];

            for (const constraints of intentos) {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia(constraints);
                    streams[persona] = stream;
                    video.srcObject = stream;
                    video.style.display = 'block';
                    btnAbrir.style.display = 'none';
                    btnCapturar.style.display = '';
                    return;
                } catch (e) { /* intentar siguiente */ }
            }
            alert('No se pudo acceder a ninguna cámara. Verifique los permisos del navegador.');
        }

        function capturarFoto(persona) {
            const video   = document.getElementById(`video${cap(persona)}`);
            const canvas  = document.getElementById(`canvasFoto${cap(persona)}`);
            const preview = document.getElementById(`previewFoto${cap(persona)}`);
            const btnCapturar = document.getElementById(`btnCapturar${cap(persona)}`);
            const btnRepetir  = document.getElementById(`btnRepetir${cap(persona)}`);
            const tsDiv   = document.getElementById(`timestamp${cap(persona)}`);

            canvas.width  = video.videoWidth  || 640;
            canvas.height = video.videoHeight || 480;
            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

            const dataUrl = canvas.toDataURL('image/jpeg', 0.85);

            // Guardar en el hidden input correspondiente
            document.getElementById(persona === 'paciente' ? 'fotoPacienteInput' : 'fotoAcudienteInput').value = dataUrl;

            // Mostrar preview y timestamp
            preview.src = dataUrl;
            preview.style.display = 'block';
            video.style.display = 'none';
            btnCapturar.style.display = 'none';
            btnRepetir.style.display = '';

            const ahora = new Date();
            tsDiv.textContent = 'Foto tomada: ' + ahora.toLocaleString('es-CO');
            tsDiv.style.display = 'block';

            // Detener cámara para liberar recurso
            if (streams[persona]) {
                streams[persona].getTracks().forEach(t => t.stop());
                delete streams[persona];
            }
        }

        function repetirFoto(persona) {
            const preview = document.getElementById(`previewFoto${cap(persona)}`);
            const btnRepetir  = document.getElementById(`btnRepetir${cap(persona)}`);
            const btnAbrir    = document.getElementById(`btnAbrirCamara${cap(persona)}`);
            const tsDiv = document.getElementById(`timestamp${cap(persona)}`);

            preview.style.display = 'none';
            btnRepetir.style.display = 'none';
            btnAbrir.style.display = '';
            tsDiv.style.display = 'none';

            document.getElementById(persona === 'paciente' ? 'fotoPacienteInput' : 'fotoAcudienteInput').value = '';
        }

        // ─── Validar y enviar ───────────────────────────────────────────────────
        $('#formFirma').submit(function(e) {
            e.preventDefault();

            const edadPaciente   = $('#pacienteEdad').val();
            const generoPaciente = $('#pacienteGenero').val();
            const noSabeFirmaPaciente = document.getElementById('noSabeFirePaciente').value === '1';

            if (!edadPaciente || edadPaciente < 0 || edadPaciente > 150) {
                alert('Por favor, ingrese una edad válida (entre 0 y 150 años).');
                $('#pacienteEdad').focus();
                return false;
            }
            if (!generoPaciente) {
                alert('Por favor, seleccione el género.');
                $('#pacienteGenero').focus();
                return false;
            }

            // Validar firma o foto del paciente
            if (noSabeFirmaPaciente) {
                if (!$('#fotoPacienteInput').val()) {
                    alert('Por favor, tome una foto del paciente antes de continuar.');
                    return false;
                }
            } else {
                if (signaturePadPaciente.isEmpty()) {
                    alert('Por favor, firme en el recuadro del paciente antes de continuar.');
                    return false;
                }
            }

            if (acudienteActivo()) {
                const noSabeFirmaAcudiente = document.getElementById('noSabeFireAcudiente').value === '1';

                if (noSabeFirmaAcudiente) {
                    if (!$('#fotoAcudienteInput').val()) {
                        alert('Por favor, tome una foto del acudiente antes de continuar.');
                        return false;
                    }
                } else if (signaturePadAcudiente.isEmpty()) {
                    alert('Por favor, firme en el recuadro del acudiente antes de continuar.');
                    return false;
                }

                if (!$('#acudienteNombres').val().trim() || !$('#acudienteApellidos').val().trim()
                    || !$('#acudienteTipoDoc').val() || !$('#acudienteNumDoc').val().trim()
                    || !$('#acudienteParentesco').val().trim()) {
                    alert('Por favor, complete todos los campos del acudiente.');
                    return false;
                }
            }

            $('#btnEnviar').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Enviando...');

            const deseaSerInformado = $('input[name="desea_ser_informado"]:checked').val() === '1' ? 1 : 0;
            const nombrePaciente    = '{{ $consentimiento->paciente_nombre }}';
            const cedulaPaciente    = '{{ $consentimiento->paciente_cedula }}';

            const firmaDataPaciente = noSabeFirmaPaciente ? null : signaturePadPaciente.toDataURL();
            const fotoDataPaciente  = noSabeFirmaPaciente ? $('#fotoPacienteInput').val() : null;

            enviarFirma('paciente', firmaDataPaciente, fotoDataPaciente, noSabeFirmaPaciente,
                        nombrePaciente, cedulaPaciente, null, edadPaciente, generoPaciente, deseaSerInformado)
                .then(response => {
                    if (!response.success) throw new Error(response.message);

                    if (acudienteActivo()) {
                        const noSabeFirmaAcudiente = document.getElementById('noSabeFireAcudiente').value === '1';
                        const nombreAcudiente = $('#acudienteNombres').val() + ' ' + $('#acudienteApellidos').val();
                        const cedulaAcudiente = $('#acudienteNumDoc').val();
                        const parentesco      = $('#acudienteParentesco').val();
                        const firmaAcudiente  = noSabeFirmaAcudiente ? null : signaturePadAcudiente.toDataURL();
                        const fotoAcudiente   = noSabeFirmaAcudiente ? $('#fotoAcudienteInput').val() : null;

                        return enviarFirma('acudiente', firmaAcudiente, fotoAcudiente, noSabeFirmaAcudiente,
                                           nombreAcudiente, cedulaAcudiente, parentesco);
                    }
                    return response;
                })
                .then(response => {
                    if (response.success) {
                        window.location.href = '{{ route("consentimientos.firmar", $token) }}';
                    }
                })
                .catch(error => {
                    alert('Error: ' + error.message);
                    $('#btnEnviar').prop('disabled', false).html('<i class="fas fa-check-circle"></i> Confirmar y Enviar Firmas');
                });
        });

        // Función para enviar firma o foto via AJAX
        function enviarFirma(tipoFirmante, firmaBase64, fotoBase64, noSabeFirmar,
                             nombreFirmante, cedulaFirmante, relacionFirmante,
                             edadFirmante, generoFirmante, deseaSerInformado) {
            return $.ajax({
                url: '{{ route("consentimientos.guardar-firma", $token) }}',
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    tipo_firmante:       tipoFirmante,
                    firma_base64:        firmaBase64,
                    foto_base64:         fotoBase64,
                    no_sabe_firmar:      noSabeFirmar ? 1 : 0,
                    firmante_nombre:     nombreFirmante,
                    firmante_cedula:     cedulaFirmante,
                    firmante_relacion:   relacionFirmante,
                    firmante_edad:       edadFirmante,
                    firmante_genero:     generoFirmante,
                    desea_ser_informado: deseaSerInformado
                }
            });
        }
    </script>
</body>
</html>
