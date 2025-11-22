$(document).ready(function() {

    // Configuración de idioma español
    var idioma_espanol = {
        "sProcessing": "Procesando...",
        "sLengthMenu": "Mostrar _MENU_ registros",
        "sZeroRecords": "No se encontraron resultados",
        "sEmptyTable": "Ningún dato disponible en esta tabla",
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
        }
    };

    // Inicializar DataTable con server-side processing
    var tabla = $('#tabla-medicamentos').DataTable({
        language: idioma_espanol,
        processing: true,
        serverSide: true,
        responsive: true,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
        pageLength: 25,
        order: [[1, 'asc']],
        ajax: {
            url: "/admin/medicamento-controlado",
            type: 'GET'
        },
        columns: [
            { data: 'id', name: 'id', width: '8%' },
            { data: 'nombre', name: 'nombre', width: '32%' },
            { data: 'descripcion', name: 'descripcion', width: '25%' },
            { data: 'saldo_actual', name: 'saldo_actual', width: '12%', className: 'text-center', orderable: true },
            { data: 'activo', name: 'activo', width: '10%', className: 'text-center', orderable: true },
            { data: 'action', name: 'action', width: '13%', className: 'text-center', orderable: false, searchable: false }
        ],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12 col-md-6"B>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        buttons: [
            {
                extend: 'copyHtml5',
                text: '<i class="fas fa-copy"></i> Copiar',
                titleAttr: 'Copiar',
                className: 'btn btn-secondary btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                }
            },
            {
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> Excel',
                titleAttr: 'Excel',
                className: 'btn btn-success btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                }
            },
            {
                extend: 'csvHtml5',
                text: '<i class="fas fa-file-csv"></i> CSV',
                titleAttr: 'CSV',
                className: 'btn btn-info btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                titleAttr: 'PDF',
                className: 'btn btn-danger btn-sm',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                },
                customize: function(doc) {
                    doc.defaultStyle.fontSize = 8;
                    doc.styles.tableHeader.fontSize = 9;
                }
            }
        ]
    });

    // Eliminar medicamento (usando delegación de eventos para elementos dinámicos)
    $(document).on('click', '.btn-eliminar', function(e) {
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
                                tabla.ajax.reload(null, false); // Recargar tabla sin resetear paginación
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
                    limpiarFormCrear();
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: response.mensaje,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        tabla.ajax.reload(null, false); // Recargar tabla sin resetear paginación
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
                        tabla.ajax.reload(null, false); // Recargar tabla sin resetear paginación
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
                        tabla.ajax.reload(null, false); // Recargar tabla sin resetear paginación
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
                        tabla.ajax.reload(null, false); // Recargar tabla sin resetear paginación
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
