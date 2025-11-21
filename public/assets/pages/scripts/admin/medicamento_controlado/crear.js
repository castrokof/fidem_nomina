$(document).ready(function() {

    // Envío del formulario con AJAX
    $('#form-medicamento').on('submit', function(e) {
        e.preventDefault();

        // Crear FormData
        var formData = new FormData(this);

        // Deshabilitar botón de submit
        var btnGuardar = $('#btn-guardar');
        var btnTextoOriginal = btnGuardar.html();
        btnGuardar.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Guardando...');

        // Enviar datos por AJAX
        $.ajax({
            url: guardarMedicamentoUrl,
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
                    title: '¡Medicamento Creado!',
                    html: '<p>El medicamento <strong>' + response.nombre + '</strong> ha sido creado exitosamente.</p>',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#0bad52',
                    timer: 3000
                }).then(function() {
                    // Limpiar formulario
                    $('#form-medicamento')[0].reset();
                    $('#estado-texto').text('Activo');
                    $('#activo').prop('checked', true);
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
                    errors = 'Ha ocurrido un error al crear el medicamento';
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

    // Inicializar tooltips
    $('[data-toggle="tooltip"]').tooltip();

});
