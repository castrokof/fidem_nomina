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
                    <p class="text-white">Manizales, Colombia</p>
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
                                    <label class="font-weight-bold">¿Desea ser informado sobre su enfermedad?</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="desea_ser_informado" id="desea_si" value="1" required checked>
                                        <label class="form-check-label" for="desea_si">
                                            Sí, deseo ser informado directamente
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="desea_ser_informado" id="desea_familiar" value="0">
                                        <label class="form-check-label" for="desea_familiar">
                                            Deseo que la información sea proporcionada a mi familiar/tutor/representante
                                        </label>
                                    </div>
                                </div>

                                <!-- Firma del Paciente -->
                                <div class="section-title">
                                    <i class="fas fa-signature"></i> Firma del Paciente
                                </div>
                                <p><strong>DECLARO</strong> que he comprendido adecuadamente la información que contiene este documento, que firmo el consentimiento para la realización del procedimiento que se describe en el mismo, que he recibido copia del mismo y que conozco que el consentimiento puede ser revocado por escrito en cualquier momento.</p>

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

                                <div class="form-group">
                                    <label class="font-weight-bold">Firme en el recuadro a continuación:</label>
                                    <div class="text-center">
                                        <canvas id="signaturePadPaciente" class="signature-pad" width="600" height="200"></canvas>
                                    </div>
                                    <button type="button" class="btn btn-clear btn-sm mt-2" onclick="clearSignature('signaturePadPaciente')">
                                        <i class="fas fa-eraser"></i> Limpiar Firma
                                    </button>
                                    <input type="hidden" name="firma_base64" id="firmaPacienteInput">
                                </div>

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

                                    <div class="form-group">
                                        <label class="font-weight-bold">Firma del Acudiente:</label>
                                        <div class="text-center">
                                            <canvas id="signaturePadAcudiente" class="signature-pad" width="600" height="200"></canvas>
                                        </div>
                                        <button type="button" class="btn btn-clear btn-sm mt-2" onclick="clearSignature('signaturePadAcudiente')">
                                            <i class="fas fa-eraser"></i> Limpiar Firma
                                        </button>
                                        <input type="hidden" name="firma_base64" id="firmaAcudienteInput">
                                    </div>
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

        // Ajustar canvas para pantallas pequeñas
        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            const containers = document.querySelectorAll('.signature-pad');

            containers.forEach((canvas) => {
                const parent = canvas.parentElement;
                const width = Math.min(600, parent.offsetWidth - 20);

                canvas.width = width;
                canvas.height = 200;
                canvas.style.width = width + 'px';
                canvas.style.height = '200px';
            });
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
        function toggleSeccionAcudiente() {
            const requiereAcudiente = $('#requiereAcudiente').is(':checked') || $('#requiereAcudiente').val() === '1';
            if (requiereAcudiente) {
                $('#seccionAcudiente').slideDown();
                $('#acudienteNombres, #acudienteApellidos, #acudienteTipoDoc, #acudienteNumDoc, #acudienteParentesco').prop('required', true);
            } else {
                $('#seccionAcudiente').slideUp();
                $('#acudienteNombres, #acudienteApellidos, #acudienteTipoDoc, #acudienteNumDoc, #acudienteParentesco').prop('required', false);
                signaturePadAcudiente.clear();
            }
        }

        $('#requiereAcudiente').change(toggleSeccionAcudiente);

        // Ejecutar al cargar la página por si viene obligatorio
        $(document).ready(function() {
            toggleSeccionAcudiente();
        });

        // Validar y enviar formulario
        $('#formFirma').submit(function(e) {
            e.preventDefault();

            // Validar edad y género del paciente
            const edadPaciente = $('#pacienteEdad').val();
            const generoPaciente = $('#pacienteGenero').val();

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

            // Validar firma del paciente
            if (signaturePadPaciente.isEmpty()) {
                alert('Por favor, firme en el recuadro del paciente antes de continuar.');
                return false;
            }

            // Si requiere acudiente, validar su firma
            const requiereAcudiente = $('#requiereAcudiente').is(':checked');
            if (requiereAcudiente) {
                if (signaturePadAcudiente.isEmpty()) {
                    alert('Por favor, firme en el recuadro del acudiente antes de continuar.');
                    return false;
                }
                // Validar campos del acudiente
                if (!$('#acudienteNombres').val() || !$('#acudienteApellidos').val() ||
                    !$('#acudienteTipoDoc').val() || !$('#acudienteNumDoc').val() ||
                    !$('#acudienteParentesco').val()) {
                    alert('Por favor, complete todos los campos del acudiente.');
                    return false;
                }
            }

            // Deshabilitar botón para evitar doble envío
            $('#btnEnviar').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Enviando...');

            // Enviar firma del paciente primero
            const nombrePaciente = '{{ $consentimiento->paciente_nombre }}';
            const cedulaPaciente = '{{ $consentimiento->paciente_cedula }}';

            enviarFirma('paciente', signaturePadPaciente.toDataURL(), nombrePaciente, cedulaPaciente, null, edadPaciente, generoPaciente)
                .then(response => {
                    if (!response.success) {
                        throw new Error(response.message);
                    }

                    // Si requiere acudiente, enviar su firma también
                    if (requiereAcudiente) {
                        const nombreAcudiente = $('#acudienteNombres').val() + ' ' + $('#acudienteApellidos').val();
                        const cedulaAcudiente = $('#acudienteNumDoc').val();
                        const parentesco = $('#acudienteParentesco').val();

                        return enviarFirma('acudiente', signaturePadAcudiente.toDataURL(), nombreAcudiente, cedulaAcudiente, parentesco);
                    }
                    return response;
                })
                .then(response => {
                    if (response.success) {
                        alert('Firmas registradas exitosamente');
                        window.location.href = '{{ route("consentimientos.show", $consentimiento->id) }}';
                    }
                })
                .catch(error => {
                    alert('Error: ' + error.message);
                    $('#btnEnviar').prop('disabled', false).html('<i class="fas fa-check-circle"></i> Confirmar y Enviar Firmas');
                });
        });

        // Función para enviar firma via AJAX
        function enviarFirma(tipoFirmante, firmaBase64, nombreFirmante, cedulaFirmante, relacionFirmante, edadFirmante, generoFirmante) {
            return $.ajax({
                url: '{{ route("consentimientos.guardar-firma", $token) }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    tipo_firmante: tipoFirmante,
                    firma_base64: firmaBase64,
                    firmante_nombre: nombreFirmante,
                    firmante_cedula: cedulaFirmante,
                    firmante_relacion: relacionFirmante,
                    firmante_edad: edadFirmante,
                    firmante_genero: generoFirmante
                }
            });
        }
    </script>
</body>
</html>
