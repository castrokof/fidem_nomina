@extends("theme.$theme.layout")
@section('titulo')
Medicamentos Controlados
@endsection

@section('styles')
<link href="{{asset('assets/css/custom/medicamentos-glassmorphism.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/$theme/plugins/datatables-bs4/css/dataTables.bootstrap4.css")}}" rel="stylesheet" type="text/css"/>
<style>
    .content-wrapper {
        background: linear-gradient(-45deg, #4facfe, #00f2fe, #5ba3d0, #2193b0, #6dd5ed);
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
    <div class="row animate-in">
        <!-- Estadísticas -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="stats-card">
                <div class="icon">
                    <i class="fas fa-pills"></i>
                </div>
                <div class="value" id="total-medicamentos">{{ count($medicamentos) }}</div>
                <div class="label">Total Medicamentos</div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="stats-card">
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="value" id="medicamentos-activos">{{ $medicamentos->where('activo', 1)->count() }}</div>
                <div class="label">Medicamentos Activos</div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="stats-card">
                <div class="icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="value" id="total-stock">{{ $medicamentos->sum('saldo_actual') }}</div>
                <div class="label">Stock Total</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            @include('includes.form-error')
            @include('includes.form-mensaje')

            <div class="glass-card animate-in">
                <div class="glass-card-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h3><i class="fas fa-pills mr-2"></i> Medicamentos Controlados</h3>
                        <div class="mt-2 mt-md-0">
                            <div class="btn-group-ios">
                                <button type="button" class="btn-ios" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);" data-toggle="modal" data-target="#modal-crear-medicamento">
                                    <i class="fas fa-plus-circle"></i>
                                    <span>Nuevo</span>
                                </button>
                                <button type="button" class="btn-ios" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);" data-toggle="modal" data-target="#modal-entrada">
                                    <i class="fas fa-arrow-down"></i>
                                    <span>Entrada</span>
                                </button>
                                <button type="button" class="btn-ios" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);" data-toggle="modal" data-target="#modal-salida">
                                    <i class="fas fa-arrow-up"></i>
                                    <span>Salida</span>
                                </button>
                                <a href="{{route('medicamento_controlado_movimiento')}}" class="btn-ios" style="background: linear-gradient(135deg, #5ba3d0 0%, #2193b0 100%);">
                                    <i class="fas fa-list"></i>
                                    <span>Movimientos</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="glass-card-body">
                    <div class="table-responsive">
                        <table class="table glass-table table-hover" id="tabla-medicamentos">
                            <thead>
                                <tr>
                                    <th width="8%">
                                        <i class="fas fa-hashtag"></i> ID
                                    </th>
                                    <th width="32%">
                                        <i class="fas fa-capsules"></i> Nombre
                                    </th>
                                    <th width="25%">
                                        <i class="fas fa-info-circle"></i> Descripción
                                    </th>
                                    <th width="12%" class="text-center">
                                        <i class="fas fa-warehouse"></i> Saldo Actual
                                    </th>
                                    <th width="10%" class="text-center">
                                        <i class="fas fa-toggle-on"></i> Estado
                                    </th>
                                    <th width="13%" class="text-center">
                                        <i class="fas fa-cogs"></i> Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($medicamentos as $medicamento)
                                <tr data-id="{{$medicamento->id}}">
                                    <td><strong>{{$medicamento->id}}</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-prescription-bottle text-primary mr-2"></i>
                                            <strong>{{$medicamento->nombre}}</strong>
                                        </div>
                                    </td>
                                    <td>{{$medicamento->descripcion ?? 'N/A'}}</td>
                                    <td class="text-center">
                                        @if($medicamento->saldo_actual > 50)
                                            <span class="glass-badge glass-badge-success">
                                                <i class="fas fa-box-open"></i> {{$medicamento->saldo_actual}}
                                            </span>
                                        @elseif($medicamento->saldo_actual > 20)
                                            <span class="glass-badge glass-badge-warning">
                                                <i class="fas fa-box-open"></i> {{$medicamento->saldo_actual}}
                                            </span>
                                        @else
                                            <span class="glass-badge glass-badge-danger">
                                                <i class="fas fa-exclamation-triangle"></i> {{$medicamento->saldo_actual}}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($medicamento->activo)
                                            <span class="glass-badge glass-badge-success">
                                                <i class="fas fa-check-circle"></i> Activo
                                            </span>
                                        @else
                                            <span class="glass-badge glass-badge-secondary">
                                                <i class="fas fa-times-circle"></i> Inactivo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group-ios">
                                            <button type="button"
                                                    class="btn-ios btn-ios-warning btn-editar"
                                                    data-id="{{$medicamento->id}}"
                                                    data-nombre="{{$medicamento->nombre}}"
                                                    data-descripcion="{{$medicamento->descripcion}}"
                                                    data-activo="{{$medicamento->activo}}"
                                                    title="Editar"
                                                    data-toggle="tooltip">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button"
                                                    class="btn-ios btn-ios-danger btn-eliminar"
                                                    data-id="{{$medicamento->id}}"
                                                    title="Eliminar"
                                                    data-toggle="tooltip">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.medicamento_controlado.modal.modalCrear')
@include('admin.medicamento_controlado.modal.modalEditar')
@include('admin.medicamento_controlado.modal.modalEntrada')
@include('admin.medicamento_controlado.modal.modalSalida')

@endsection
@section('scriptsPlugins')
    <script src="{{ asset("assets/$theme/plugins/datatables/jquery.dataTables.js") }}" type="text/javascript"></script>
    <script src="{{ asset("assets/$theme/plugins/datatables-bs4/js/dataTables.bootstrap4.js") }}" type="text/javascript">
    </script>
    <script src="{{ asset('assets/js/jquery-select2/select2.min.js') }}" type="text/javascript"></script>

    <script>
        $(document).ready(function() {


 // Inicializar DataTable
    $('#tabla-medicamentos').DataTable({
        responsive: true,
        language: idioma_espanol,
        order: [[1, 'asc']]
    });
    // Eliminar medicamento
    $('.btn-eliminar').on('click', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = 'medicamento-controlado/' + id + '/eliminar';

        Swal.fire({
            title: '¿Está seguro?',
            text: "¿Desea eliminar este medicamento?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Eliminado!',
                                response.mensaje,
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                response.mensaje,
                                'error'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'Error!',
                            'Ha ocurrido un error al eliminar',
                            'error'
                        );
                    }
                });
            }
        });
    });

    // =====================================================
    // MODAL CREAR MEDICAMENTO
    // =====================================================
    $('#form-crear-medicamento').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: 'medicamento-controlado/guardar',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#modal-crear-medicamento').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: response.mensaje,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.mensaje
                    });
                }
            },
            error: function(xhr) {
                var mensaje = 'Ha ocurrido un error al guardar';
                if (xhr.responseJSON && xhr.responseJSON.mensaje) {
                    mensaje = xhr.responseJSON.mensaje;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: mensaje
                });
            }
        });
    });

    // =====================================================
    // MODAL ENTRADA
    // =====================================================

    // Actualizar saldo cuando se selecciona medicamento
    $('#entrada_medicamento_id').on('change', function() {
        var saldo = $(this).find(':selected').data('saldo') || 0;
        $('#entrada-saldo-actual').text(saldo);
        calcularNuevoSaldoEntrada();
    });

    // Calcular nuevo saldo al cambiar cantidad
    $('#entrada_cantidad').on('input', function() {
        calcularNuevoSaldoEntrada();
    });

    function calcularNuevoSaldoEntrada() {
        var saldoActual = parseInt($('#entrada-saldo-actual').text()) || 0;
        var entrada = parseInt($('#entrada_cantidad').val()) || 0;
        var nuevoSaldo = saldoActual + entrada;
        $('#entrada-nuevo-saldo').text(nuevoSaldo);
    }

    // Submit formulario entrada
    $('#form-entrada').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: 'medicamento-controlado-movimiento/guardar',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#modal-entrada').modal('hide');
                    limpiarFormEntrada();
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: response.mensaje,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.mensaje
                    });
                }
            },
            error: function(xhr) {
                var mensaje = 'Ha ocurrido un error al guardar';
                if (xhr.responseJSON && xhr.responseJSON.mensaje) {
                    mensaje = xhr.responseJSON.mensaje;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: mensaje
                });
            }
        });
    });

    // =====================================================
    // MODAL SALIDA
    // =====================================================

    // Actualizar saldo cuando se selecciona medicamento
    $('#salida_medicamento_id').on('change', function() {
        var saldo = $(this).find(':selected').data('saldo') || 0;
        $('#salida-saldo-actual').text(saldo);
        calcularNuevoSaldoSalida();
    });

    // Calcular nuevo saldo al cambiar cantidad
    $('#salida_cantidad').on('input', function() {
        calcularNuevoSaldoSalida();
    });

    function calcularNuevoSaldoSalida() {
        var saldoActual = parseInt($('#salida-saldo-actual').text()) || 0;
        var salida = parseInt($('#salida_cantidad').val()) || 0;
        var nuevoSaldo = saldoActual - salida;

        // Validar que no sea negativo
        if (nuevoSaldo < 0) {
            $('#salida-nuevo-saldo')
                .text('ERROR')
                .css({
                    'background': 'linear-gradient(135deg, #ffcdd2 0%, #ef9a9a 100%)',
                    'color': '#c62828',
                    'border-color': '#ef5350'
                });
        } else {
            $('#salida-nuevo-saldo')
                .text(nuevoSaldo)
                .css({
                    'background': 'linear-gradient(135deg, #fff9c4 0%, #fff59d 100%)',
                    'color': '#f57f17',
                    'border-color': '#ffeb3b'
                });
        }
    }

    // Preview de imagen para salida
    $('#salida_foto_formula').on('change', function() {
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#salida-preview-imagen').attr('src', e.target.result);
                $('#salida-preview-container').show();
            };
            reader.readAsDataURL(file);
        }
    });

    // Eliminar foto
    $('#btn-eliminar-foto-salida').on('click', function() {
        $('#salida_foto_formula').val('');
        $('#salida-preview-container').hide();
        $('#salida-preview-imagen').attr('src', '');
    });

    // Submit formulario salida
    $('#form-salida').on('submit', function(e) {
        e.preventDefault();

        // Validar que el nuevo saldo no sea negativo
        var saldoActual = parseInt($('#salida-saldo-actual').text()) || 0;
        var salida = parseInt($('#salida_cantidad').val()) || 0;

        if (salida > saldoActual) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'La cantidad a retirar no puede ser mayor al saldo disponible'
            });
            return;
        }

        var formData = new FormData(this);

        $.ajax({
            url: 'medicamento-controlado-movimiento/guardar',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#modal-salida').modal('hide');
                    limpiarFormSalida();
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: response.mensaje,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.mensaje
                    });
                }
            },
            error: function(xhr) {
                var mensaje = 'Ha ocurrido un error al guardar';
                if (xhr.responseJSON && xhr.responseJSON.mensaje) {
                    mensaje = xhr.responseJSON.mensaje;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: mensaje
                });
            }
        });
    });

    // =====================================================
    // MODAL EDITAR
    // =====================================================

    // Abrir modal y cargar datos
    $(document).on('click', '.btn-editar', function() {
        var id = $(this).data('id');
        var nombre = $(this).data('nombre');
        var descripcion = $(this).data('descripcion');
        var activo = $(this).data('activo');

        $('#editar_id').val(id);
        $('#editar_nombre').val(nombre);
        $('#editar_descripcion').val(descripcion);
        $('#editar_activo').prop('checked', activo == 1);
        $('#editar-estado-texto').text(activo == 1 ? 'Activo' : 'Inactivo');

        $('#modal-editar-medicamento').modal('show');
    });

    // Submit formulario editar
    $('#form-editar-medicamento').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: 'medicamento-controlado/actualizar',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#modal-editar-medicamento').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: response.mensaje,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.mensaje
                    });
                }
            },
            error: function(xhr) {
                var mensaje = 'Ha ocurrido un error al actualizar';
                if (xhr.responseJSON && xhr.responseJSON.mensaje) {
                    mensaje = xhr.responseJSON.mensaje;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: mensaje
                });
            }
        });
    });

});

// =====================================================
// FUNCIONES DE LIMPIEZA
// =====================================================

function limpiarFormCrear() {
    $('#form-crear-medicamento')[0].reset();
    $('#crear_activo').prop('checked', true);
    $('#crear-estado-texto').text('Activo');
}

function limpiarFormEntrada() {
    $('#form-entrada')[0].reset();
    $('#entrada-saldo-actual').text('0');
    $('#entrada-nuevo-saldo').text('0');
    $('#entrada_fecha').val(new Date().toISOString().split('T')[0]);
}

function limpiarFormSalida() {
    $('#form-salida')[0].reset();
    $('#salida-saldo-actual').text('0');
    $('#salida-nuevo-saldo')
        .text('0')
        .css({
            'background': 'linear-gradient(135deg, #fff9c4 0%, #fff59d 100%)',
            'color': '#f57f17',
            'border-color': '#ffeb3b'
        });
    $('#salida-preview-container').hide();
    $('#salida-preview-imagen').attr('src', '');
    $('#salida_fecha').val(new Date().toISOString().split('T')[0]);
}

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