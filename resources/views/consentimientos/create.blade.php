@extends("theme.$theme.layout")

@section('titulo')
    Crear Consentimiento(s) Informado(s)
@endsection

@section("styles")
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.0.0/dist/select2-bootstrap4.min.css" rel="stylesheet" />
<style>
    .loading { opacity: 0.6; pointer-events: none; position: relative; }
    .loading::after {
        content: ""; position: absolute; top: 50%; left: 50%;
        width: 30px; height: 30px; margin: -15px 0 0 -15px;
        border: 3px solid #f3f3f3; border-top: 3px solid #007bff;
        border-radius: 50%; animation: spin 1s linear infinite;
    }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    .observaciones-cell { cursor: pointer; max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .observaciones-cell:hover { text-decoration: underline; }
    .modal-observaciones { max-height: 400px; overflow-y: auto; white-space: pre-wrap; }
    .seccion-oculta { display: none; }

    /* Observaciones completas con word-wrap */
.observaciones-completas {
    background-color: #02155a;
    padding: 10px;
    border: 1px solid #dee2e6;
    border-radius: 5px;
    font-size: 0.9rem;
    line-height: 1.4;
    white-space: pre-wrap;      /* Respeta saltos de línea */
    word-wrap: break-word;      /* Rompe palabras largas */
    word-break: break-word;     /* Compatibilidad adicional */
    max-height: 200px;          /* Limita altura inicial */
    overflow-y: auto;           /* Scroll si es muy largo */
    transition: max-height 0.3s ease;
}

.observaciones-completas:hover {
    border-color: #007bff;
    background-color: #092b4e;
}

/* Para el modal, sin límite de altura */
.modal-observaciones {
    max-height: 500px;
    overflow-y: auto;
    white-space: pre-wrap;
    word-wrap: break-word;
    font-size: 0.95rem;
    line-height: 1.5;
}
</style>
@endsection

@section('contenido')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><i class="fas fa-file-signature"></i> Crear Consentimiento(s)</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{route('consentimientos.index')}}">Consentimientos</a></li>
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
                    <h3 class="card-title">Datos del Nuevo Consentimiento</h3>
                </div>
                <form action="{{route('consentimientos.store')}}" method="POST">
                    @csrf

                    @if(isset($agenda))
                        <input type="hidden" name="agenda_ci_id" value="{{$agenda->id}}">
                    @endif

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

                        @if(isset($agenda))
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> <strong>Creando consentimiento desde cita agendada</strong>
                                <br>Paciente: {{$agenda->paciente_nombre}} | Fecha: {{$agenda->fecha->format('d/m/Y H:i')}}
                            </div>
                        @endif

                        <div class="row">
                            <!-- Profesional -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="profesional_id">Profesional <span class="text-danger">*</span></label>
                                    <select name="profesional_id" id="profesional_id" class="form-control select2" required>
                                        <option value="">Seleccione un profesional...</option>
                                        @foreach($profesionales as $profesional)
                                            <option value="{{$profesional->id}}" {{ old('profesional_id', isset($agenda) ? $agenda->profesional_id : '') == $profesional->id ? 'selected' : '' }}>
                                                {{$profesional->nombres}} {{$profesional->apellidos}} - {{$profesional->especialidad->nombre ?? 'Sin especialidad'}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Paciente -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="paciente_id">Paciente <span class="text-danger">*</span></label>
                                    <select name="paciente_id" id="paciente_id" class="form-control select2" required>
                                        <option value="">Seleccione un paciente...</option>
                                        @foreach($pacientes as $paciente)
                                            <option value="{{$paciente->id}}" {{ old('paciente_id', isset($agenda) ? $agenda->paciente_id : '') == $paciente->id ? 'selected' : '' }}>
                                                {{$paciente->nombres}} {{$paciente->apellidos}} - {{$paciente->tipo_documento}}-{{$paciente->numero_documento}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mr-3 mb-2">
                            <label class="mr-2 small">Especialista:</label>
                            <select name="codigo_usuario" id="codigo_usuario" class="form-control form-control-sm select2" style="min-width:200px;">
                                <option value="">Seleccione...</option>
                                @foreach($especialistas as $esp)
                                    <option value="{{ $esp->codigo_usuario }}" data-especialidad="{{ $esp->especialidad_id ?? '' }}">
                                        {{ $esp->nombres }} {{ $esp->apellidos }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mr-3 mb-2">
                            <label class="mr-2 small">Centro:</label>
                            <select name="centroprod" id="centroprod" class="form-control form-control-sm select2" style="min-width:100px;">
                                <option value="">Todos</option>
                                @foreach($centrosProd as $centro)
                                    <option value="{{ $centro }}">{{ $centro }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" id="btnFiltrar" class="btn btn-primary btn-sm mb-2">
                            <i class="fas fa-search"></i> Buscar Pacientes
                        </button>
                    </form>

                    {{-- Sección: Lista de pacientes (AJAX) --}}
                    <div id="seccionPacientes" class="seccion-oculta">
                        <hr>
                        <h5 class="mb-3"><i class="fas fa-users"></i> Pacientes Encontrados</h5>
                        <div class="list-group" id="listaPacientes"></div>
                    </div>

                    {{-- Sección: Datos del paciente seleccionado + plantillas --}}
                    <div id="seccionCrear" class="seccion-oculta">
                        <hr>
                        <div id="datosPaciente"></div>
                        
                        <form id="formCrearConsentimientos">
                            @csrf
                            <input type="hidden" name="paciente_id" id="inputPacienteId">
                            <input type="hidden" name="agenda_ci_id" id="inputAgendaId">
                            <input type="hidden" name="fecha_procedimiento" id="inputFecha">
                            <input type="hidden" name="cups_codigo" id="inputCups">
                            <input type="hidden" name="observaciones" id="inputObservaciones">
                            <input type="hidden" name="profesional_id" id="inputProfesionalId" value="{{ $profesional->id ?? '' }}">
                            
                            <h5 class="mb-3"><i class="fas fa-clipboard-list"></i> Seleccione Consentimientos</h5>
                            <div id="listaPlantillas" class="mb-3"></div>
                            
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success" id="btnCrear" disabled>
                                    <i class="fas fa-save"></i> Crear <span id="contadorPlantillas">0</span> Consentimiento(s)
                                </button>
                                <button type="button" class="btn btn-secondary ml-2" id="btnNuevo">
                                    <i class="fas fa-plus"></i> Nuevo Paciente
                                </button>
                                <span class="text-muted small ml-2" id="mensajeEstado"></span>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>

{{-- Modal Observaciones --}}
<div class="modal fade" id="modalObservaciones" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="fas fa-sticky-note"></i> Observaciones</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-observaciones" id="contenidoObservaciones"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button class="btn btn-primary" onclick="copiarObservaciones()"><i class="fas fa-copy"></i> Copiar</button>
            </div>
        </div>
    </div>
</div>
         {{-- Modal para ver detalles completos de citas del paciente --}}
        <div class="modal fade" id="modalDetallesPaciente" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="fas fa-list"></i> Citas del Paciente</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div id="contenidoDetallesPaciente"></div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button class="btn btn-success" onclick="confirmarSeleccionDesdeModal()">
                            <i class="fas fa-check"></i> Seleccionar este Paciente
                        </button>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('.select2').select2({ theme: 'bootstrap4', width: '100%' });
    
    // ========== FILTRAR PACIENTES ==========
    $('#btnFiltrar').click(function() {
        const fecha = $('#fecha').val();
        const codigoUsuario = $('#codigo_usuario').val();
        
        if (!fecha || !codigoUsuario) {
            Swal.fire('Atención', 'Seleccione fecha y especialista', 'warning');
            return;
        }
        
        $('#seccionPacientes').addClass('loading');
        
        $.ajax({
            url: '{{ route("consentimientos.ajax.pacientes") }}',
            type: 'GET',
            data: { fecha, codigo_usuario: codigoUsuario, centroprod: $('#centroprod').val() },
            success: function(response) {
                $('#seccionPacientes').removeClass('loading');
                
                // Dentro de success de ajaxPacientesPorFiltros
if (!response.success || response.pacientes.length === 0) {
    $('#listaPacientes').html('<div class="alert alert-info">No hay pacientes para estos filtros</div>');
} else {
    // Reemplazar la generación del HTML de la tabla de pacientes
let html = '<div class="table-responsive"><table class="table table-sm table-bordered">';
html += `<thead class="thead-light"><tr>
    <th>Hora</th>
    <th>Paciente</th>
    <th>Documento</th>
    <th>Centro</th>
    <th>CUPS</th>
    <th>Citas</th>
    <th>Acción</th>
</tr></thead><tbody>`;

        response.pacientes.forEach(function(pac) {
            // Mostrar PRIMERA cita ordenada (ya viene ordenada por hora asc)
            const primeraCita = pac.citas[0];
            html += `
                <tr>
                    <td class="text-center">
                        <strong class="text-primary">${primeraCita.hora_completa}</strong><br>
                        <small class="text-muted">${new Date(primeraCita.fecha).toLocaleDateString('es-CO')}</small>
                    </td>
                    <td><strong>${pac.nombre_completo}</strong></td>
                    <td>
                        <span class="badge badge-secondary">${pac.documento.split('-')[0]}</span><br>
                        <small>${pac.documento.split('-')[1]}</small>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-info">${primeraCita.centroprod || '-'}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-primary">${primeraCita.cups_codigo || '-'}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-pill badge-success">${pac.citas_count}</span>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-info" 
                            onclick="verDetallesPaciente(${pac.id}, ${JSON.stringify(pac.citas).replace(/"/g, '&quot;')})"
            data-toggle="modal" data-target="#modalDetallesPaciente">
            <i class="fas fa-eye"></i> Ver
        </button>
        <button type="button" class="btn btn-sm btn-success mt-1"
            onclick="seleccionarPaciente(${pac.id})">
            <i class="fas fa-check"></i> Seleccionar
        </button>
        </td>
        </tr>
        `;
        });
        html += '</tbody></table></div>';
        $('#listaPacientes').html(html);
        }
                $('#seccionPacientes').removeClass('seccion-oculta');
                $('#seccionCrear').addClass('seccion-oculta');
            },
            error: function() {
                $('#seccionPacientes').removeClass('loading');
                Swal.fire('Error', 'No se pudieron cargar los pacientes', 'error');
            }
        });
    });
    
    // ========== SELECCIONAR PACIENTE ==========
    window.seleccionarPaciente = function(pacienteId) {
        const fecha = $('#fecha').val();
        const codigoUsuario = $('#codigo_usuario').val();
        
        $('#seccionCrear').addClass('loading').removeClass('seccion-oculta');
        
        $.ajax({
            url: `{{ route("consentimientos.ajax.datos", ["paciente_id" => ":id"]) }}`.replace(':id', pacienteId),
            type: 'GET',
            data: { fecha, codigo_usuario: codigoUsuario },
            success: function(response) {
                $('#seccionCrear').removeClass('loading');
                
                if (!response.success) {
                    Swal.fire('Error', response.message, 'error');
                    return;
                }
                
               // Dentro de success de ajaxDatosPaciente
$('#datosPaciente').html(`
    <div class="alert alert-info">
        <div class="row">
            <div class="col-md-6">
                <strong>👤 Paciente:</strong><br>
                ${response.paciente.nombre}<br>
                <small class="text-muted">
                    ${response.paciente.documento}-${response.paciente.cedula}
                    ${response.paciente.telefono ? ` • 📞 ${response.paciente.telefono}` : ''}
                </small>
            </div>
            <div class="col-md-6">
                <strong>📅 Cita:</strong><br>
                <!-- ✅ HORA COMPLETA DESTACADA -->
                <span class="badge badge-primary badge-lg mr-2">
                    <i class="fas fa-clock"></i> ${response.cita.hora_completa}
                </span>
                <small class="text-muted d-block">
                    ${response.cita.fecha ? new Date(response.cita.fecha).toLocaleDateString('es-CO') : 'N/A'}
                </small>
            </div>
        </div>
        <hr class="my-2">
        <div class="row">
            <div class="col-md-3">
                <strong>🏥 Centro:</strong><br>
                <span class="badge badge-info">${response.cita.centroprod || '-'}</span>
            </div>
            <div class="col-md-3">
                <strong>💊 CUPS:</strong><br>
                <span class="badge badge-primary">${response.cita.cups_codigo || '-'}</span>
            </div>
            <div class="col-md-3">
                <strong>📄 Contrato:</strong><br>
                <small>${response.cita.contrato || '-'}</small>
            </div>
            <div class="col-md-3">
                <strong>🏢 EPS:</strong><br>
                <small>${response.cita.empresafac || '-'}</small>
            </div>
        </div>
        ${response.cita.historia ? `
        <div class="mt-2">
            <strong>📋 Historia Clínica:</strong>
            <small class="d-block">${response.cita.historia}</small>
        </div>
        ` : ''}
        ${response.cita.observaciones ? `
        <div class="mt-2">
                <strong>📝 Observaciones:</strong><br>
                <div class="observaciones-completas" 
                    onclick="mostrarObservacionesModal('${(response.cita.observaciones || '').replace(/'/g, "\\'")}')"
                    style="cursor: pointer;" 
                    title="Click para ampliar si es muy largo">
                    ${response.cita.observaciones || 'Sin observaciones'}
                </div>
            </div>
        ` : ''}
    </div>
`);
                
                // Llenar inputs ocultos
                $('#inputPacienteId').val(response.paciente.id);
                $('#inputAgendaId').val(response.cita.agenda_id);
                $('#inputProfesionalId').val(response.profesional.id);
                $('#inputFecha').val(response.cita.fecha);
                $('#inputCups').val(response.cita.cups_codigo);
                $('#inputObservaciones').val(response.cita.observaciones);

                // Debug: verificar en consola
    console.log('Profesional ID:', response.profesional.id);
    console.log('Input value:', $('#inputProfesionalId').val());
                
                
                // Renderizar plantillas como checkboxes
                renderPlantillas(response.plantillas);
                
                // Ocultar lista de pacientes
                $('#seccionPacientes').addClass('seccion-oculta');
            },
            error: function() {
                $('#seccionCrear').removeClass('loading');
                Swal.fire('Error', 'No se pudieron cargar los datos del paciente', 'error');
            }
        });
    };
    
    // ========== RENDERIZAR PLANTILLAS COMO CHECKBOXES ==========
    function renderPlantillas(plantillas) {
        if (!plantillas || plantillas.length === 0) {
            $('#listaPlantillas').html('<div class="alert alert-warning">No hay plantillas disponibles para esta especialidad</div>');
            $('#btnCrear').prop('disabled', true);
            return;
        }
        
        let html = '<div class="row">';
        plantillas.forEach(function(p) {
            html += `
                <div class="col-md-6 mb-2">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input plantilla-checkbox" 
                            id="plantilla_${p.id}" name="plantillas[]" value="${p.id}">
                        <label class="custom-control-label" for="plantilla_${p.id}">${p.nombre}</label>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        
        $('#listaPlantillas').html(html);
        
        // Event listeners para checkboxes
        $('.plantilla-checkbox').change(function() {
            actualizarContador();
        });
        
        actualizarContador();
    }
    
    // ========== ACTUALIZAR CONTADOR ==========
    function actualizarContador() {
        const count = $('.plantilla-checkbox:checked').length;
        $('#contadorPlantillas').text(count);
        $('#btnCrear').prop('disabled', count === 0);
        $('#mensajeEstado').text(count > 0 ? `✓ ${count} seleccionado(s)` : '');
    }
    
    // ========== GUARDAR CONSENTIMIENTOS (AJAX) ==========
    $('#formCrearConsentimientos').submit(function(e) {
        e.preventDefault();
        
        if ($('.plantilla-checkbox:checked').length === 0) {
            Swal.fire('Atención', 'Seleccione al menos una plantilla', 'warning');
            return;
        }
        
        $('#btnCrear').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
        
        $.ajax({
            url: '{{ route("consentimientos.store") }}',
            type: 'POST',
            data: $(this).serialize(),
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(function() {
                        window.location.href = response.redirect;
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                    $('#btnCrear').prop('disabled', false).html('<i class="fas fa-save"></i> Crear Consentimiento(s)');
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors || ['Error desconocido'];
                Swal.fire('Error', errors.join('<br>'), 'error');
                $('#btnCrear').prop('disabled', false).html('<i class="fas fa-save"></i> Crear Consentimiento(s)');
            }
        });
    });
    
    // ========== BOTÓN NUEVO PACIENTE ==========
    $('#btnNuevo').click(function() {
        $('#seccionCrear').addClass('seccion-oculta');
        $('#seccionPacientes').removeClass('seccion-oculta');
        $('#listaPlantillas').html('');
        actualizarContador();
    });
    
    // ========== MODAL OBSERVACIONES ==========
    window.mostrarObservacionesModal = function(texto) {
        $('#contenidoObservaciones').text(texto);
        $('#modalObservaciones').modal('show');
    };
    
    window.copiarObservaciones = function() {
        const texto = $('#contenidoObservaciones').text();
        navigator.clipboard.writeText(texto).then(() => {
            Swal.fire({ toast: true, icon: 'success', title: 'Copiado', position: 'top-end', timer: 1500, showConfirmButton: false });
        });
    };


    // Variable global para almacenar paciente seleccionado desde modal
let pacienteSeleccionadoDesdeModal = null;

window.verDetallesPaciente = function(pacienteId, citas) {
    pacienteSeleccionadoDesdeModal = pacienteId;
    
    let html = `<h6 class="mb-3">📋 ${citas.length} cita(s) - Ordenadas por hora</h6>`;
    html += '<div class="table-responsive"><table class="table table-sm table-striped">';
    html += `<thead><tr>
        <th>Hora Completa</th>
        <th>Centro</th>
        <th>CUPS</th>
        <th>Observaciones</th>
        <th>Contrato</th>
        <th>EPS</th>
        <th>Estado</th>
    </tr></thead><tbody>`;
    
    // ✅ Las citas ya vienen ordenadas por fecha asc desde el controller
    citas.forEach(function(cita) {
        let estadoIcon = '🕐';
        let estadoClass = 'badge badge-warning';
        if (cita.llegada_confirmada) {
            estadoIcon = '✅';
            estadoClass = 'badge badge-success';
        } else if (cita.atendido == '1') {
            estadoIcon = '👤';
            estadoClass = 'badge badge-info';
        }
        
        html += `
            <tr>
                <td class="text-center">
                    <strong class="text-primary">${cita.hora_completa}</strong><br>
                    <small class="text-muted">${new Date(cita.fecha).toLocaleDateString('es-CO')}</small>
                </td>
                <td><span class="badge badge-info">${cita.centroprod || '-'}</span></td>
                <td><span class="badge badge-primary">${cita.cups_codigo || '-'}</span></td>
                <td>
                    <span class="observaciones-cell" 
                        onclick="mostrarObservacionesModal('${(cita.observaciones || '').replace(/'/g, "\\'")}')"
                        style="cursor:pointer;max-width:150px;" 
                        title="Click para ver completo">
                        ${(cita.observaciones || 'Sin observaciones').substring(0, 30)}...
                    </span>
                </td>
                <td><small>${cita.contrato ? cita.contrato.substring(0,10) : '-'}</small></td>
                <td><small>${cita.empresafac || '-'}</small></td>
                <td class="text-center"><span class="${estadoClass}">${estadoIcon}</span></td>
            </tr>
        `;
    });
    
    html += '</tbody></table></div>';
    $('#contenidoDetallesPaciente').html(html);
};
// Confirmar selección desde el modal
window.confirmarSeleccionDesdeModal = function() {
    if (pacienteSeleccionadoDesdeModal) {
        $('#modalDetallesPaciente').modal('hide');
        seleccionarPaciente(pacienteSeleccionadoDesdeModal);
        pacienteSeleccionadoDesdeModal = null;
    }
};
   
});


</script>
@endsection