$(document).ready(function() {

    // Mostrar saldo actual cuando se selecciona un medicamento
    $('#medicamento_controlado_id').on('change', function() {
        var saldo = $(this).find(':selected').data('saldo');
        $('#saldo-actual').text(saldo || 0);
        calcularNuevoSaldo();
    });

    // Calcular nuevo saldo al cambiar cantidad de entrada
    $('#entrada').on('input', function() {
        calcularNuevoSaldo();
    });

    // Función para calcular el nuevo saldo
    function calcularNuevoSaldo() {
        var saldoActual = parseInt($('#saldo-actual').text()) || 0;
        var entrada = parseInt($('#entrada').val()) || 0;
        var nuevoSaldo = saldoActual + entrada;
        $('#nuevo-saldo').text(nuevoSaldo);
    }

    // Al resetear el formulario
    $('#form-entrada')[0].addEventListener('reset', function() {
        setTimeout(function() {
            $('#saldo-actual').text(0);
            $('#nuevo-saldo').text(0);
        }, 10);
    });

});
