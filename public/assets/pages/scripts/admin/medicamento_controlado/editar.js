$(document).ready(function() {

    // Envío del formulario con AJAX
    $('#form-medicamento').on('submit', function(e) {
        e.preventDefault();

        // Crear FormData
        var formData = new FormData(this);

        // Deshabilitar botón de submit
        var btnActualizar = $('#btn-actualizar');
        var btnTextoOriginal = btnActualizar.html();
        btnActualizar.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Actualizando...');

        // Enviar datos por AJAX
        $.ajax({
            url: actualizarMedicamentoUrl,
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
                    title: '¡Medicamento Actualizado!',
                    html: '<p>El medicamento ha sido actualizado exitosamente.</p>',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#0bad52',
                    timer: 2000
                }).then(function() {
                    // Redirigir al listado
                    window.location.href = response.redirect || '/admin/medicamento-controlado';
                });

                // Re-habilitar botón
                btnActualizar.prop('disabled', false).html(btnTextoOriginal);
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
                    errors = 'Ha ocurrido un error al actualizar el medicamento';
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: errors,
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#f5576c'
                });

                // Re-habilitar botón
                btnActualizar.prop('disabled', false).html(btnTextoOriginal);
            }
        });
    });

    // Inicializar tooltips
    $('[data-toggle="tooltip"]').tooltip();

});
