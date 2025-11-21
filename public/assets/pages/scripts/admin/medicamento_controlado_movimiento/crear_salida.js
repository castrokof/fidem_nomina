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
