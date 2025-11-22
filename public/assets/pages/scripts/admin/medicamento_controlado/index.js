$(document).ready(function() {

    // Inicializar DataTable
    $('#tabla-medicamentos').DataTable({
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
        },
        order: [[1, 'asc']]
    });

    // Eliminar medicamento
    $('.btn-eliminar').on('click', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = '/admin/medicamento-controlado/' + id + '/eliminar';

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
            url: '/admin/medicamento-controlado/guardar',
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
            url: '/admin/medicamento-controlado-movimiento/guardar',
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
            $('#salida-nuevo-saldo').text('ERROR').css('background', 'rgba(220, 53, 69, 0.5)');
        } else {
            $('#salida-nuevo-saldo').text(nuevoSaldo).css('background', 'rgba(255, 193, 7, 0.3)');
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
            url: '/admin/medicamento-controlado-movimiento/guardar',
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
            url: '/admin/medicamento-controlado/actualizar',
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
    $('#salida-nuevo-saldo').text('0').css('background', 'rgba(255, 193, 7, 0.3)');
    $('#salida-preview-container').hide();
    $('#salida-preview-imagen').attr('src', '');
    $('#salida_fecha').val(new Date().toISOString().split('T')[0]);
}
