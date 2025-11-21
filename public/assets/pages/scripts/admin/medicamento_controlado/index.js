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

});
