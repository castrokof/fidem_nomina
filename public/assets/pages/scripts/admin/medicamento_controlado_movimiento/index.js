$(document).ready(function() {

    // Inicializar DataTable
    $('#tabla-movimientos').DataTable({
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
        },
        order: [[0, 'desc']],
        pageLength: 25
    });

});
