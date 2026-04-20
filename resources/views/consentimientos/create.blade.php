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
    /* Modal pantalla completa (Bootstrap 4) */
    #modalCrearConsentimiento .modal-dialog {
        max-width: 100%;
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
    }
    #modalCrearConsentimiento .modal-content {
        height: 100%;
        border-radius: 0;
        display: flex;
        flex-direction: column;
    }
    #modalCrearConsentimiento .modal-body {
        flex: 1 1 auto;
        overflow-y: auto;
        min-height: 0;
    }
    #modalCrearConsentimiento .modal-footer {
        flex-shrink: 0;
    }
    /* Modal de links: footer siempre visible */
    #modalConsentimientosCreados .modal-content {
        display: flex;
        flex-direction: column;
        max-height: 90vh;
    }
    #modalConsentimientosCreados .modal-body {
        flex: 1 1 auto;
        overflow-y: auto;
        min-height: 0;
    }
    #modalConsentimientosCreados .modal-footer {
        flex-shrink: 0;
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
                <div class="card-header"><h3 class="card-title"><i class="fas fa-filter"></i> Filtrar</h3></div>
                <div class="card-body">
                    
                    {{-- Filtros iniciales --}}
                    <form id="formFiltros" class="form-inline mb-4 flex-wrap">
                        @csrf
                        <div class="form-group mr-3 mb-2">
                            <label class="mr-2 small">Fecha:</label>
                            <input type="date" name="fecha" id="fecha" class="form-control form-control-sm" value="{{ date('Y-m-d') }}" required>
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

                </div>
            </div>
        </div>
    </section>
</div>

{{-- Modal: Crear consentimiento --}}
<div class="modal fade" id="modalCrearConsentimiento" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-file-signature"></i> Crear Consentimiento</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="datosPaciente"></div>

                <form id="formCrearConsentimientos">
                    @csrf
                    <input type="hidden" name="paciente_id"         id="inputPacienteId">
                    <input type="hidden" name="agenda_ci_id"        id="inputAgendaId">
                    <input type="hidden" name="fecha_procedimiento" id="inputFecha">
                    <input type="hidden" name="cups_codigo"         id="inputCups">
                    <input type="hidden" name="observaciones"       id="inputObservaciones">
                    <input type="hidden" name="profesional_id"      id="inputProfesionalId">

                    <h6 class="mb-2 mt-3"><i class="fas fa-clipboard-list"></i> Seleccione Consentimientos</h6>
                    <div class="input-group input-group-sm mb-3" id="wrapperBuscadorPlantillas" style="display:none!important;">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" id="buscadorPlantillas" class="form-control"
                               placeholder="Buscar consentimiento..." autocomplete="off">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" onclick="$('#buscadorPlantillas').val('').trigger('input')">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div id="listaPlantillas" class="mb-3"></div>

                    <span class="text-muted small" id="mensajeEstado"></span>

                    <div class="d-flex justify-content-end mt-3 pt-3 border-top">
                        <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-success" id="btnCrear" disabled>
                            <i class="fas fa-save"></i> Crear <span id="contadorPlantillas">0</span> Consentimiento(s)
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
{{-- Modal: Consentimientos creados con links de firma --}}
<div class="modal fade" id="modalConsentimientosCreados" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-check-circle"></i> Consentimientos creados</h5>
            </div>
            <div class="modal-body">
                <div class="alert alert-success mb-3">
                    <strong id="modalPacienteNombre"></strong> — Consentimientos creados. Comparta cada enlace con el paciente para que firme.
                </div>
                <div id="modalListaConsentimientos"></div>

                <div class="d-flex justify-content-end mt-3 pt-3 border-top">
                    <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cerrar
                    </button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="resetFormCrear()">
                        <i class="fas fa-plus"></i> Nuevo consentimiento
                    </button>
                </div>
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
let html = '<div class="table-responsive"><table class="table table-sm table-bordered" id="tablaPacientes">';
html += `<thead class="thead-light"><tr>
    <th class="th-sort" data-col="hora" style="cursor:pointer;white-space:nowrap;">Hora <i class="fas fa-sort text-muted sort-ico"></i></th>
    <th class="th-sort" data-col="nombre" style="cursor:pointer;white-space:nowrap;">Paciente <i class="fas fa-sort text-muted sort-ico"></i></th>
    <th class="th-sort" data-col="documento" style="cursor:pointer;white-space:nowrap;">Documento <i class="fas fa-sort text-muted sort-ico"></i></th>
    <th class="th-sort" data-col="centro" style="cursor:pointer;white-space:nowrap;">Centro <i class="fas fa-sort text-muted sort-ico"></i></th>
    <th class="th-sort" data-col="cups" style="cursor:pointer;white-space:nowrap;">CUPS <i class="fas fa-sort text-muted sort-ico"></i></th>
    <th class="th-sort" data-col="citas" style="cursor:pointer;white-space:nowrap;">Citas <i class="fas fa-sort text-muted sort-ico"></i></th>
    <th>Consentimientos</th>
    <th>Acción</th>
</tr></thead><tbody id="tbodyPacientes">`;

        response.pacientes.forEach(function(pac) {
            // Mostrar PRIMERA cita ordenada (ya viene ordenada por hora asc)
            const primeraCita = pac.citas[0];

            // ✅ Generar badge de estado de consentimientos
            let badgeConsentimiento = '';
            if (!primeraCita.tiene_consentimientos) {
                badgeConsentimiento = '<span class="badge badge-light" title="Sin consentimientos"><i class="fas fa-times-circle"></i> Sin CI</span>';
            } else {
                const total = primeraCita.total_consentimientos;
                const firmados = primeraCita.consentimientos_firmados;
                const enProceso = primeraCita.consentimientos_en_proceso;
                const pendientes = primeraCita.consentimientos_pendientes;

                if (primeraCita.estado_consentimientos === 'todos_firmados') {
                    badgeConsentimiento = `<span class="badge badge-success" title="${firmados} consentimiento(s) firmado(s)">
                        <i class="fas fa-check-circle"></i> ${total} Firmado${total > 1 ? 's' : ''}
                    </span>`;
                } else if (primeraCita.estado_consentimientos === 'en_proceso') {
                    badgeConsentimiento = `<span class="badge badge-warning" title="${enProceso} en proceso, ${pendientes} pendiente(s), ${firmados} firmado(s)">
                        <i class="fas fa-clock"></i> ${total} En proceso
                    </span>`;
                } else {
                    badgeConsentimiento = `<span class="badge badge-danger" title="${pendientes} pendiente(s) de firma">
                        <i class="fas fa-exclamation-circle"></i> ${total} Pendiente${total > 1 ? 's' : ''}
                    </span>`;
                }
            }

            html += `
                <tr data-hora="${primeraCita.fecha || ''}"
                    data-nombre="${pac.nombre_completo.toLowerCase()}"
                    data-documento="${pac.documento}"
                    data-centro="${primeraCita.centroprod || ''}"
                    data-cups="${primeraCita.cups_codigo || ''}"
                    data-citas="${pac.citas_count}">
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
                    <td class="text-center">
                        ${badgeConsentimiento}
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
    
    // ========== SELECCIONAR PACIENTE → abre modal ==========
    window.seleccionarPaciente = function(pacienteId) {
        const fecha = $('#fecha').val();
        const codigoUsuario = $('#codigo_usuario').val();

        // Limpiar modal antes de abrir
        $('#datosPaciente').html('<div class="text-center py-3"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');
        $('#listaPlantillas').html('');
        $('#btnCrear').prop('disabled', true).html('<i class="fas fa-save"></i> Crear <span id="contadorPlantillas">0</span> Consentimiento(s)');
        $('#modalCrearConsentimiento').modal('show');

        $.ajax({
            url: `{{ route("consentimientos.ajax.datos", ["paciente_id" => ":id"]) }}`.replace(':id', pacienteId),
            type: 'GET',
            data: { fecha, codigo_usuario: codigoUsuario },
            success: function(response) {
                if (!response.success) {
                    $('#modalCrearConsentimiento').modal('hide');
                    Swal.fire('Error', response.message, 'error');
                    return;
                }

                $('#datosPaciente').html(`
                    <div class="alert alert-info mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <strong><i class="fas fa-user"></i> Paciente:</strong><br>
                                ${response.paciente.nombre}<br>
                                <small class="text-muted">
                                    ${response.paciente.documento}-${response.paciente.cedula}
                                    ${response.paciente.telefono ? ` &bull; <i class="fas fa-phone"></i> ${response.paciente.telefono}` : ''}
                                </small>
                            </div>
                            <div class="col-md-6">
                                <strong><i class="fas fa-calendar"></i> Cita:</strong><br>
                                <span class="badge badge-primary mr-1"><i class="fas fa-clock"></i> ${response.cita.hora_completa}</span>
                                <small class="text-muted d-block">${response.cita.fecha ? new Date(response.cita.fecha).toLocaleDateString('es-CO') : 'N/A'}</small>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-6 col-md-3"><strong>Centro:</strong><br><span class="badge badge-info">${response.cita.centroprod || '-'}</span></div>
                            <div class="col-6 col-md-3"><strong>CUPS:</strong><br><span class="badge badge-primary">${response.cita.cups_codigo || '-'}</span></div>
                            <div class="col-6 col-md-3"><strong>Contrato:</strong><br><small>${response.cita.contrato || '-'}</small></div>
                            <div class="col-6 col-md-3"><strong>EPS:</strong><br><small>${response.cita.empresafac || '-'}</small></div>
                        </div>
                        ${(response.cita.documento_factura || response.cita.numero_factura) ? `
                        <div class="row mt-1">
                            <div class="col-12"><strong>Factura:</strong>
                                <span class="badge badge-secondary ml-1">${response.cita.documento_factura || ''}${response.cita.numero_factura ? '-' + response.cita.numero_factura : ''}</span>
                            </div>
                        </div>` : ''}
                        ${response.cita.observaciones ? `
                        <div class="mt-2">
                            <strong>Observaciones:</strong><br>
                            <div class="observaciones-completas"
                                onclick="mostrarObservacionesModal('${(response.cita.observaciones || '').replace(/'/g, "\\'")}')"
                                style="cursor:pointer;" title="Click para ampliar">
                                ${response.cita.observaciones}
                            </div>
                        </div>` : ''}
                    </div>
                `);

                $('#inputPacienteId').val(response.paciente.id);
                $('#inputAgendaId').val(response.cita.agenda_id);
                $('#inputProfesionalId').val(response.profesional.id);
                $('#inputFecha').val(response.cita.fecha);
                $('#inputCups').val(response.cita.cups_codigo);
                $('#inputObservaciones').val(response.cita.observaciones);

                renderPlantillas(response.plantillas);
            },
            error: function() {
                $('#modalCrearConsentimiento').modal('hide');
                Swal.fire('Error', 'No se pudieron cargar los datos del paciente', 'error');
            }
        });
    };
    
    // ========== RENDERIZAR PLANTILLAS COMO CHECKBOXES ==========
    function renderPlantillas(plantillas) {
        // Limpiar buscador
        $('#buscadorPlantillas').val('');

        if (!plantillas || plantillas.length === 0) {
            $('#listaPlantillas').html('<div class="alert alert-warning">No hay plantillas disponibles para esta especialidad</div>');
            $('#wrapperBuscadorPlantillas').hide();
            $('#btnCrear').prop('disabled', true);
            return;
        }

        // Mostrar buscador solo si hay más de 5 plantillas
        if (plantillas.length > 5) {
            $('#wrapperBuscadorPlantillas').css('display', 'flex');
        } else {
            $('#wrapperBuscadorPlantillas').hide();
        }

        // Ordenar alfabéticamente
        plantillas.sort((a, b) => a.nombre.localeCompare(b.nombre, 'es'));

        let html = '<div class="row" id="rowPlantillas">';
        plantillas.forEach(function(p) {
            html += `
                <div class="col-md-6 mb-2 plantilla-item" data-nombre="${p.nombre.toLowerCase()}">
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

        $('.plantilla-checkbox').change(function() { actualizarContador(); });
        actualizarContador();
    }

    // Filtrar plantillas al escribir en el buscador
    $(document).on('input', '#buscadorPlantillas', function() {
        const q = $(this).val().toLowerCase().trim();
        $('.plantilla-item').each(function() {
            $(this).toggle(q === '' || $(this).data('nombre').includes(q));
        });
    });
    
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
                $('#btnCrear').prop('disabled', false).html('<i class="fas fa-save"></i> Crear <span id="contadorPlantillas">0</span> Consentimiento(s)');
                if (response.success) {
                    let html = '<div class="list-group">';
                    response.consentimientos.forEach(function(ci) {
                        html += `
                            <div class="list-group-item mb-2">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong><i class="fas fa-file-medical text-primary"></i> ${ci.plantilla}</strong>
                                        <small class="d-block text-muted">Expira: ${ci.expira_at}</small>
                                    </div>
                                    <a href="${ci.link_firma}" target="_blank" class="btn btn-sm btn-outline-primary ml-2">
                                        <i class="fas fa-external-link-alt"></i> Abrir
                                    </a>
                                </div>
                                <div class="input-group input-group-sm mt-2">
                                    <input type="text" class="form-control" value="${ci.link_firma}" readonly id="link_${ci.id}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" onclick="copiarLink('link_${ci.id}')">
                                            <i class="fas fa-copy"></i> Copiar
                                        </button>
                                    </div>
                                </div>
                            </div>`;
                    });
                    html += '</div>';

                    $('#modalPacienteNombre').text(response.paciente_nombre);
                    $('#modalListaConsentimientos').html(html);

                    // Cerrar modal de creación y abrir el de links
                    $('#modalCrearConsentimiento').modal('hide');
                    $('#modalCrearConsentimiento').one('hidden.bs.modal', function() {
                        $('#modalConsentimientosCreados').modal('show');
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors || ['Error desconocido'];
                Swal.fire('Error', errors.join('<br>'), 'error');
                $('#btnCrear').prop('disabled', false).html('<i class="fas fa-save"></i> Crear <span id="contadorPlantillas">0</span> Consentimiento(s)');
            }
        });
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
        <th>Factura</th>
        <th>Consentimientos</th>
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

        // ✅ Badge de consentimientos para el modal
        let badgeConsentimiento = '';
        if (!cita.tiene_consentimientos) {
            badgeConsentimiento = '<span class="badge badge-light"><i class="fas fa-times-circle"></i> Sin CI</span>';
        } else {
            const total = cita.total_consentimientos;
            const firmados = cita.consentimientos_firmados;
            const enProceso = cita.consentimientos_en_proceso;
            const pendientes = cita.consentimientos_pendientes;

            if (cita.estado_consentimientos === 'todos_firmados') {
                badgeConsentimiento = `<span class="badge badge-success" title="${firmados} firmado(s)">
                    <i class="fas fa-check-circle"></i> ${total} Firmado${total > 1 ? 's' : ''}
                </span>`;
            } else if (cita.estado_consentimientos === 'en_proceso') {
                badgeConsentimiento = `<span class="badge badge-warning" title="${enProceso} en proceso">
                    <i class="fas fa-clock"></i> ${total} En proceso
                </span>`;
            } else {
                badgeConsentimiento = `<span class="badge badge-danger" title="${pendientes} pendiente(s)">
                    <i class="fas fa-exclamation-circle"></i> ${total} Pendiente${total > 1 ? 's' : ''}
                </span>`;
            }
        }

        html += `
            <tr>
                <td class="text-center">
                    <strong class="text-primary">${cita.hora_completa}</strong><br>
                    <small class="text-muted">${new Date(cita.fecha).toLocaleDateString('es-CO')}</small>
                </td>
                <td><span class="badge badge-info">${cita.centroprod || '-'}</span></td>
                <td><span class="badge badge-primary">${cita.cups_codigo || '-'}</span></td>
                <td><small>${cita.documento_factura || ''}${cita.numero_factura ? '-' + cita.numero_factura : (cita.documento_factura ? '' : '-')}</small></td>
                <td class="text-center">${badgeConsentimiento}</td>
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
        const id = pacienteSeleccionadoDesdeModal;
        pacienteSeleccionadoDesdeModal = null;
        $('#modalDetallesPaciente').modal('hide');
        $('#modalDetallesPaciente').one('hidden.bs.modal', function() {
            seleccionarPaciente(id);
        });
    }
};

// Copiar link individual al portapapeles
window.copiarLink = function(inputId) {
    const input = document.getElementById(inputId);
    input.select();
    navigator.clipboard.writeText(input.value).then(function() {
        Swal.fire({ toast: true, icon: 'success', title: 'Enlace copiado', position: 'top-end', timer: 1500, showConfirmButton: false });
    });
};

// ========== ORDENAR TABLA PACIENTES ==========
let sortCol = 'hora', sortDir = 'asc';

$(document).on('click', '.th-sort', function() {
    const col = $(this).data('col');
    sortDir = (sortCol === col && sortDir === 'asc') ? 'desc' : 'asc';
    sortCol = col;

    // Íconos
    $('.th-sort .sort-ico').removeClass('fa-sort-up fa-sort-down text-primary').addClass('fa-sort text-muted');
    $(this).find('.sort-ico')
        .removeClass('fa-sort text-muted')
        .addClass((sortDir === 'asc' ? 'fa-sort-up' : 'fa-sort-down') + ' text-primary');

    const tbody = document.getElementById('tbodyPacientes');
    if (!tbody) return;

    Array.from(tbody.querySelectorAll('tr'))
        .sort((a, b) => {
            let va = (a.dataset[col] || '').trim();
            let vb = (b.dataset[col] || '').trim();
            if (col === 'citas') {
                return sortDir === 'asc' ? (parseInt(va)||0) - (parseInt(vb)||0)
                                         : (parseInt(vb)||0) - (parseInt(va)||0);
            }
            const cmp = va.localeCompare(vb, 'es', { numeric: true, sensitivity: 'base' });
            return sortDir === 'asc' ? cmp : -cmp;
        })
        .forEach(row => tbody.appendChild(row));
});

// Al cerrar el modal de links, queda en la lista de pacientes con filtros intactos
window.resetFormCrear = function() {
    $('#listaPlantillas').html('');
    actualizarContador();
};

});


</script>
@endsection