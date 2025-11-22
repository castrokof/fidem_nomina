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
    var tabla = $('#tabla-movimientos').DataTable({
        language: idioma_espanol,
        processing: true,
        serverSide: true,
        responsive: true,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
        pageLength: 25,
        order: [[0, 'desc']],
        ajax: {
            url: "/admin/medicamento-controlado-movimiento",
            type: 'GET',
            data: function(d) {
                // Pasar filtros al servidor
                d.medicamento_id = filtroMedicamento;
                d.fecha_desde = filtroFechaDesde;
                d.fecha_hasta = filtroFechaHasta;
            }
        },
        columns: [
            { data: 'fecha', name: 'fecha', width: '8%' },
            { data: 'medicamento', name: 'medicamentoControlado.nombre', width: '18%', orderable: false },
            { data: 'tipo_movimiento', name: 'tipo_movimiento', width: '8%', className: 'text-center' },
            { data: 'proveedor', name: 'proveedor', width: '15%' },
            { data: 'paciente', name: 'nombre_paciente', width: '15%', orderable: false },
            { data: 'entrada', name: 'entrada', width: '8%', className: 'text-center' },
            { data: 'salida', name: 'salida', width: '8%', className: 'text-center' },
            { data: 'saldo', name: 'saldo', width: '8%', className: 'text-center' },
            { data: 'detalles', name: 'detalles', width: '12%', className: 'text-center', orderable: false, searchable: false }
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
                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                }
            },
            {
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> Excel',
                titleAttr: 'Excel',
                className: 'btn btn-success btn-sm',
                title: 'Movimientos de Medicamentos Controlados',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                }
            },
            {
                extend: 'csvHtml5',
                text: '<i class="fas fa-file-csv"></i> CSV',
                titleAttr: 'CSV',
                className: 'btn btn-info btn-sm',
                title: 'Movimientos de Medicamentos Controlados',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                titleAttr: 'PDF',
                className: 'btn btn-danger btn-sm',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                title: 'Movimientos de Medicamentos Controlados',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                },
                customize: function(doc) {
                    doc.defaultStyle.fontSize = 8;
                    doc.styles.tableHeader.fontSize = 9;
                }
            }
        ],
        drawCallback: function() {
            // Reactivar tooltips después de cada redibujado
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

});
