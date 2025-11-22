@extends("theme.$theme.layout")
@section('titulo')
Movimientos de Medicamentos Controlados
@endsection

@section('styles')
<link href="{{asset('assets/css/custom/medicamentos-glassmorphism.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/$theme/plugins/datatables-bs4/css/dataTables.bootstrap4.css")}}" rel="stylesheet" type="text/css"/>
<style>
    .content-wrapper {
        background: linear-gradient(-45deg, #7F7FD5, #86A8E7, #91EAE4, #11998e, #38ef7d);
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
    }
</style>
@endsection

@section('scripts')
<script src="{{asset("assets/$theme/plugins/datatables/jquery.dataTables.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/$theme/plugins/datatables-bs4/js/dataTables.bootstrap4.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/$theme/plugins/datatables-responsive/js/dataTables.responsive.min.js")}}" type="text/javascript"></script>

@endsection

@section('contenido')
<div class="medicamentos-wrapper">
    <div class="row">
        <div class="col-lg-12">
            @include('includes.form-error')
            @include('includes.form-mensaje')

            <!-- Filtros -->
            <div class="filter-glass animate-in mb-3">
                <div class="card-header" style="background: rgba(255, 255, 255, 0.1); border-radius: 15px 15px 0 0; padding: 15px 20px; border: none;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" style="color: white; font-weight: 600;">
                            <i class="fas fa-filter mr-2"></i>Filtros de Búsqueda
                        </h5>
                        <button type="button" class="btn btn-sm glass-btn glass-btn-info" data-toggle="collapse" data-target="#filtros-collapse">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                </div>
                <div class="collapse" id="filtros-collapse">
                    <div style="padding: 20px;">
                        <form action="{{route('medicamento_controlado_movimiento')}}" method="GET">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group-glass">
                                        <label><i class="fas fa-pills mr-2"></i>Medicamento</label>
                                        <select name="medicamento_id" class="form-control glass-select">
                                            <option value="">Todos los medicamentos</option>
                                            @foreach($medicamentos as $med)
                                                <option value="{{$med->id}}" {{$medicamento_id == $med->id ? 'selected' : ''}}>
                                                    {{$med->nombre}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group-glass">
                                        <label><i class="fas fa-calendar-alt mr-2"></i>Fecha Desde</label>
                                        <input type="date" name="fecha_desde" class="form-control glass-input" value="{{$fecha_desde}}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group-glass">
                                        <label><i class="fas fa-calendar-check mr-2"></i>Fecha Hasta</label>
                                        <input type="date" name="fecha_hasta" class="form-control glass-input" value="{{$fecha_hasta}}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group-glass">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn-ios btn-ios-info btn-block" style="width: 100%; justify-content: center;">
                                            <i class="fas fa-search"></i>
                                            <span>Filtrar</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Listado de movimientos -->
            <div class="glass-card animate-in">
                <div class="glass-card-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h3><i class="fas fa-exchange-alt mr-2"></i> Historial de Movimientos</h3>
                        <div class="mt-2 mt-md-0">
                            <a href="{{route('medicamento_controlado')}}" class="btn-ios btn-ios-info">
                                <i class="fas fa-arrow-left mr-1"></i>
                                <span>Volver a Medicamentos</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="glass-card-body">
                    <div class="table-responsive">
                        <table class="table glass-table table-hover table-sm" id="tabla-movimientos">
                            <thead>
                                <tr>
                                    <th width="8%"><i class="fas fa-calendar"></i> Fecha</th>
                                    <th width="18%"><i class="fas fa-capsules"></i> Medicamento</th>
                                    <th width="8%" class="text-center"><i class="fas fa-exchange-alt"></i> Tipo</th>
                                    <th width="15%"><i class="fas fa-truck"></i> Proveedor</th>
                                    <th width="15%"><i class="fas fa-user-injured"></i> Paciente</th>
                                    <th width="8%" class="text-center"><i class="fas fa-plus"></i> Entrada</th>
                                    <th width="8%" class="text-center"><i class="fas fa-minus"></i> Salida</th>
                                    <th width="8%" class="text-center"><i class="fas fa-warehouse"></i> Saldo</th>
                                    <th width="12%" class="text-center"><i class="fas fa-info-circle"></i> Detalles</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($movimientos as $mov)
                                <tr>
                                    <td><strong>{{date('d/m/Y', strtotime($mov->fecha))}}</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-prescription-bottle text-primary mr-2"></i>
                                            {{$mov->medicamentoControlado->nombre}}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($mov->tipo_movimiento == 'entrada')
                                            <span class="glass-badge glass-badge-success">
                                                <i class="fas fa-arrow-down"></i> ENTRADA
                                            </span>
                                        @else
                                            <span class="glass-badge glass-badge-danger">
                                                <i class="fas fa-arrow-up"></i> SALIDA
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{$mov->proveedor ?? '-'}}</td>
                                    <td>
                                        @if($mov->nombre_paciente)
                                            <strong>{{$mov->nombre_paciente}}</strong><br>
                                            <small class="text-muted"><i class="fas fa-id-card mr-1"></i>{{$mov->cedula_paciente}}</small>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($mov->entrada > 0)
                                            <span class="glass-badge glass-badge-success">+{{$mov->entrada}}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($mov->salida > 0)
                                            <span class="glass-badge glass-badge-danger">-{{$mov->salida}}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <strong style="font-size: 1.1rem;">{{$mov->saldo}}</strong>
                                    </td>
                                    <td class="text-center">
                                        @if($mov->foto_formula)
                                            <a href="{{asset('storage/' . $mov->foto_formula)}}" target="_blank" class="btn btn-info btn-sm" title="Ver foto fórmula" data-toggle="tooltip">
                                                <i class="fas fa-image"></i>
                                            </a>
                                        @endif
                                        @if($mov->numero_factura)
                                            <span class="badge badge-info" title="Factura: {{$mov->numero_factura}}" data-toggle="tooltip">
                                                <i class="fas fa-file-invoice"></i> {{$mov->numero_factura}}
                                            </span>
                                        @endif
                                        @if($mov->numero_formula_control)
                                            <span class="badge badge-warning" title="Fórmula Control: {{$mov->numero_formula_control}}" data-toggle="tooltip">
                                                <i class="fas fa-file-prescription"></i> {{$mov->numero_formula_control}}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center" style="padding: 40px;">
                                        <i class="fas fa-inbox" style="font-size: 3rem; color: rgba(0, 0, 0, 0.2);"></i>
                                        <p class="mt-3" style="color: rgba(0, 0, 0, 0.5);">No hay movimientos registrados</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scriptsPlugins')
    <script src="{{ asset("assets/$theme/plugins/datatables/jquery.dataTables.js") }}" type="text/javascript"></script>
    <script src="{{ asset("assets/$theme/plugins/datatables-bs4/js/dataTables.bootstrap4.js") }}" type="text/javascript">
    </script>
    <script src="{{ asset('assets/js/jquery-select2/select2.min.js') }}" type="text/javascript"></script>

    <script>
        $(document).ready(function() {

            // Inicializar DataTable
            $('#tabla-movimientos').DataTable({
                "language": idioma_espanol,
                "order": [[0, "desc"]],
                "responsive": true,
                "autoWidth": false,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
            });

             // Mostrar saldo actual cuando se selecciona un medicamento
    $('#medicamento_controlado_id').on('change', function() {
        var saldo = $(this).find(':selected').data('saldo');
        $('#saldo-actual').text(saldo || 0);
        calcularNuevoSaldo();

        // Animación del badge
        $('#saldo-actual').parent().addClass('pulse');
        setTimeout(function() {
            $('#saldo-actual').parent().removeClass('pulse');
        }, 600);
    });

    // Calcular nuevo saldo al cambiar cantidad de entrada
    $('#entrada').on('input', function() {
        calcularNuevoSaldo();
    });

    // Función para calcular el nuevo saldo con animación
    function calcularNuevoSaldo() {
        var saldoActual = parseInt($('#saldo-actual').text()) || 0;
        var entrada = parseInt($('#entrada').val()) || 0;
        var nuevoSaldo = saldoActual + entrada;

        $('#nuevo-saldo').text(nuevoSaldo);

        // Animación del badge
        $('#nuevo-saldo').addClass('pulse');
        setTimeout(function() {
            $('#nuevo-saldo').removeClass('pulse');
        }, 600);
    }

    // Envío del formulario con AJAX
    $('#form-entrada').on('submit', function(e) {
        e.preventDefault();

        // Crear FormData
        var formData = new FormData(this);

        // Deshabilitar botón de submit
        var btnGuardar = $('#btn-guardar');
        var btnTextoOriginal = btnGuardar.html();
        btnGuardar.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Guardando...');

        // Enviar datos por AJAX
        $.ajax({
            url: '/admin/medicamento-controlado-movimiento/guardar',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Mostrar mensaje de éxito
                Swal.fire({
                    icon: 'success',
                    title: '¡Entrada Registrada!',
                    html: '<p>El medicamento ha sido ingresado al inventario.</p><p><strong>Nuevo saldo: ' + response.saldo + '</strong></p>',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#0bad52',
                    timer: 3000
                }).then(function() {
                    // Limpiar formulario
                    $('#form-entrada')[0].reset();
                    $('#saldo-actual').text(0);
                    $('#nuevo-saldo').text(0);

                    // Actualizar saldo del medicamento en el select
                    if (response.medicamento_id) {
                        var option = $('#medicamento_controlado_id option[value="' + response.medicamento_id + '"]');
                        option.data('saldo', response.saldo);
                        option.text(response.medicamento_nombre + ' (Stock: ' + response.saldo + ')');
                    }
                });

                // Re-habilitar botón
                btnGuardar.prop('disabled', false).html(btnTextoOriginal);
            },
            error: function(xhr) {
                // Mostrar errores
                var errors = '';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    $.each(xhr.responseJSON.errors, function(key, value) {
                        errors += value[0] + '<br>';
                    });
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errors = xhr.responseJSON.message;
                } else {
                    errors = 'Ha ocurrido un error al registrar la entrada';
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: errors,
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#f5576c'
                });

                // Re-habilitar botón
                btnGuardar.prop('disabled', false).html(btnTextoOriginal);
            }
        });
    });

    // Al resetear el formulario
    $('#btn-limpiar').on('click', function() {
        setTimeout(function() {
            $('#saldo-actual').text(0);
            $('#nuevo-saldo').text(0);
        }, 10);
    });

    // Inicializar tooltips
    $('[data-toggle="tooltip"]').tooltip();


        // Mostrar saldo actual cuando se selecciona un medicamento
    $('#medicamento_controlado_id').on('change', function() {
        var saldo = $(this).find(':selected').data('saldo');
        $('#saldo-actual').text(saldo || 0);
        calcularNuevoSaldo();

        // Animación del badge
        $('#saldo-actual').parent().addClass('pulse');
        setTimeout(function() {
            $('#saldo-actual').parent().removeClass('pulse');
        }, 600);
    });

    // Calcular nuevo saldo al cambiar cantidad de salida
    $('#salida').on('input', function() {
        calcularNuevoSaldo();
    });

    // Función para calcular el nuevo saldo con animación
    function calcularNuevoSaldo() {
        var saldoActual = parseInt($('#saldo-actual').text()) || 0;
        var salida = parseInt($('#salida').val()) || 0;
        var nuevoSaldo = saldoActual - salida;

        $('#nuevo-saldo').text(nuevoSaldo);

        // Cambiar color del badge según el resultado
        var badge = $('#nuevo-saldo');
        badge.removeClass('glass-badge-warning glass-badge-success glass-badge-danger');

        if (nuevoSaldo < 0) {
            badge.addClass('glass-badge-danger');
        } else if (nuevoSaldo === 0) {
            badge.addClass('glass-badge-warning');
        } else {
            badge.addClass('glass-badge-success');
        }

        // Animación del badge
        badge.addClass('pulse');
        setTimeout(function() {
            badge.removeClass('pulse');
        }, 600);
    }

    // Envío del formulario con AJAX
    $('#form-salida').on('submit', function(e) {
        e.preventDefault();

        var saldoActual = parseInt($('#saldo-actual').text()) || 0;
        var salida = parseInt($('#salida').val()) || 0;

        // Validar stock
        if (salida > saldoActual) {
            Swal.fire({
                icon: 'error',
                title: 'Stock Insuficiente',
                text: 'La cantidad de salida (' + salida + ') no puede ser mayor al saldo actual (' + saldoActual + ')',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#f5576c'
            });
            return false;
        }

        // Crear FormData para enviar con archivos
        var formData = new FormData(this);

        // Deshabilitar botón de submit
        var btnGuardar = $('#btn-guardar');
        var btnTextoOriginal = btnGuardar.html();
        btnGuardar.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Guardando...');

        // Enviar datos por AJAX
        $.ajax({
            url: 'admin/medicamento-controlado-movimiento/guardar',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Mostrar mensaje de éxito
                Swal.fire({
                    icon: 'success',
                    title: '¡Salida Registrada!',
                    html: '<p>El medicamento ha sido entregado al paciente.</p><p><strong>Nuevo saldo: ' + response.saldo + '</strong></p>',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#0bad52',
                    timer: 3000
                }).then(function() {
                    // Limpiar formulario
                    $('#form-salida')[0].reset();
                    $('#saldo-actual').text(0);
                    $('#nuevo-saldo').text(0).removeClass('glass-badge-success glass-badge-danger').addClass('glass-badge-warning');
                    $('.custom-file-label').html('<i class="fas fa-camera mr-2"></i>Tomar foto o seleccionar...');
                    $('#preview-container').hide();
                    $('#preview-imagen').attr('src', '');

                    // Actualizar saldo del medicamento en el select
                    if (response.medicamento_id) {
                        var option = $('#medicamento_controlado_id option[value="' + response.medicamento_id + '"]');
                        option.data('saldo', response.saldo);
                        option.text(response.medicamento_nombre + ' (Stock: ' + response.saldo + ')');
                    }
                });

                // Re-habilitar botón
                btnGuardar.prop('disabled', false).html(btnTextoOriginal);
            },
            error: function(xhr) {
                // Mostrar errores
                var errors = '';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    $.each(xhr.responseJSON.errors, function(key, value) {
                        errors += value[0] + '<br>';
                    });
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errors = xhr.responseJSON.message;
                } else {
                    errors = 'Ha ocurrido un error al registrar la salida';
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: errors,
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#f5576c'
                });

                // Re-habilitar botón
                btnGuardar.prop('disabled', false).html(btnTextoOriginal);
            }
        });
    });

    // Preview de la foto del formulario con animación
    $('#foto_formula').on('change', function(e) {
        var file = e.target.files[0];

        if (file) {
            // Validar tamaño (5MB)
            if (file.size > 5 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'Archivo muy grande',
                    text: 'La imagen no puede superar 5MB',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#f5576c'
                });
                $(this).val('');
                $('.custom-file-label').html('<i class="fas fa-camera mr-2"></i>Tomar foto o seleccionar...');
                return;
            }

            // Validar tipo de archivo
            var validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(file.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Formato inválido',
                    text: 'Solo se permiten imágenes JPG, JPEG o PNG',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#f5576c'
                });
                $(this).val('');
                $('.custom-file-label').html('<i class="fas fa-camera mr-2"></i>Tomar foto o seleccionar...');
                return;
            }

            // Mostrar preview con animación
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#preview-imagen').attr('src', e.target.result);
                $('#preview-container').fadeIn(400);
            };
            reader.readAsDataURL(file);

            // Actualizar label del input file
            var fileName = file.name;
            $('.custom-file-label').html('<i class="fas fa-check-circle mr-2"></i>' + fileName);
        }
    });

    // Eliminar foto seleccionada con animación
    $('#btn-eliminar-foto').on('click', function() {
        $('#foto_formula').val('');
        $('.custom-file-label').html('<i class="fas fa-camera mr-2"></i>Tomar foto o seleccionar...');
        $('#preview-container').fadeOut(300);
        $('#preview-imagen').attr('src', '');
    });

    // Al resetear el formulario
    $('#btn-limpiar').on('click', function() {
        setTimeout(function() {
            $('#saldo-actual').text(0);
            $('#nuevo-saldo').text(0).removeClass('glass-badge-danger glass-badge-success').addClass('glass-badge-warning');
            $('.custom-file-label').html('<i class="fas fa-camera mr-2"></i>Tomar foto o seleccionar...');
            $('#preview-container').fadeOut(300);
            $('#preview-imagen').attr('src', '');
        }, 10);
    });

    // Inicializar tooltips
    $('[data-toggle="tooltip"]').tooltip();
   });

// Añadir CSS para animación pulse (si no existe)
if (!document.getElementById('pulse-animation-style')) {
    var style = document.createElement('style');
    style.id = 'pulse-animation-style';
    style.textContent = `
        .pulse {
            animation: pulse-animation 0.6s ease-in-out;
        }

        @keyframes pulse-animation {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }
    `;
    document.head.appendChild(style);

           

        });

        // Añadir CSS para animación pulse
var style = document.createElement('style');
style.textContent = `
    .pulse {
        animation: pulse-animation 0.6s ease-in-out;
    }

    @keyframes pulse-animation {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
    }
`;
document.head.appendChild(style);

        // Variable con array de idioma de datatable
        var idioma_espanol = {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla =(",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            },
            "buttons": {
                "copy": "Copiar",
                "colvis": "Visibilidad"
            }
        };
    </script>

    
@endsection