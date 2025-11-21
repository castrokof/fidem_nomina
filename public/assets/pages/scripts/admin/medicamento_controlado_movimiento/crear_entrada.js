$(document).ready(function() {

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
            url: guardarMovimientoUrl,
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
}
