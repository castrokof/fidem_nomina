$(document).ready(function() {

    // Mostrar saldo actual cuando se selecciona un medicamento
    $('#medicamento_controlado_id').on('change', function() {
        var saldo = $(this).find(':selected').data('saldo');
        $('#saldo-actual').text(saldo || 0);
        calcularNuevoSaldo();
    });

    // Calcular nuevo saldo al cambiar cantidad de salida
    $('#salida').on('input', function() {
        calcularNuevoSaldo();
    });

    // Función para calcular el nuevo saldo
    function calcularNuevoSaldo() {
        var saldoActual = parseInt($('#saldo-actual').text()) || 0;
        var salida = parseInt($('#salida').val()) || 0;
        var nuevoSaldo = saldoActual - salida;

        $('#nuevo-saldo').text(nuevoSaldo);

        // Cambiar color del badge según el resultado
        if (nuevoSaldo < 0) {
            $('#nuevo-saldo').removeClass('badge-warning badge-success').addClass('badge-danger');
        } else if (nuevoSaldo === 0) {
            $('#nuevo-saldo').removeClass('badge-danger badge-success').addClass('badge-warning');
        } else {
            $('#nuevo-saldo').removeClass('badge-danger badge-warning').addClass('badge-success');
        }
    }

    // Validar antes de enviar el formulario
    $('#form-salida').on('submit', function(e) {
        var saldoActual = parseInt($('#saldo-actual').text()) || 0;
        var salida = parseInt($('#salida').val()) || 0;

        if (salida > saldoActual) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'La cantidad de salida (' + salida + ') no puede ser mayor al saldo actual (' + saldoActual + ')',
                confirmButtonText: 'Entendido'
            });
            return false;
        }
    });

    // Preview de la foto del formulario
    $('#foto_formula').on('change', function(e) {
        var file = e.target.files[0];

        if (file) {
            // Validar tamaño (5MB)
            if (file.size > 5 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'Archivo muy grande',
                    text: 'La imagen no puede superar 5MB',
                    confirmButtonText: 'Entendido'
                });
                $(this).val('');
                return;
            }

            // Validar tipo de archivo
            var validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(file.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Formato inválido',
                    text: 'Solo se permiten imágenes JPG, JPEG o PNG',
                    confirmButtonText: 'Entendido'
                });
                $(this).val('');
                return;
            }

            // Mostrar preview
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#preview-imagen').attr('src', e.target.result);
                $('#preview-container').show();
            };
            reader.readAsDataURL(file);

            // Actualizar label del input file
            var fileName = file.name;
            $('.custom-file-label').text(fileName);
        }
    });

    // Eliminar foto seleccionada
    $('#btn-eliminar-foto').on('click', function() {
        $('#foto_formula').val('');
        $('.custom-file-label').text('Seleccionar foto o tomar foto...');
        $('#preview-container').hide();
        $('#preview-imagen').attr('src', '');
    });

    // Al resetear el formulario
    $('#btn-limpiar').on('click', function() {
        setTimeout(function() {
            $('#saldo-actual').text(0);
            $('#nuevo-saldo').text(0).removeClass('badge-danger badge-success').addClass('badge-warning');
            $('.custom-file-label').text('Seleccionar foto o tomar foto...');
            $('#preview-container').hide();
            $('#preview-imagen').attr('src', '');
        }, 10);
    });

    // Actualizar label del custom file input cuando cambia
    $('.custom-file-input').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });

});
