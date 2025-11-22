@extends("theme.$theme.layout")
@section('titulo')
Movimientos de Medicamentos Controlados
@endsection

@section('styles')
<link href="{{asset('assets/css/custom/medicamentos-glassmorphism.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/$theme/plugins/datatables-bs4/css/dataTables.bootstrap4.css")}}" rel="stylesheet" type="text/css"/>
<style>
    .content-wrapper {
        background: linear-gradient(-45deg, #7F7FD5, #86A8E7, #91EAE4, #11998e, #38ef7d);
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
    }
</style>
@endsection

@section('contenido')
<div class="medicamentos-wrapper">
    <div class="row">
        <div class="col-lg-12">
            @include('includes.form-error')
            @include('includes.form-mensaje')

            <!-- Tarjetas de Estadísticas -->
            <div class="row mb-3" id="tarjetas-estadisticas">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="glass-card animate-in">
                        <div class="glass-card-body text-center">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <div class="rounded-circle p-3" style="background: rgba(40, 167, 69, 0.2);">
                                    <i class="fas fa-arrow-down fa-2x text-success"></i>
                                </div>
                            </div>
                            <h6 class="text-white mb-1">Total Entradas</h6>
                            <h3 class="mb-0 font-weight-bold" style="color: #28a745;">
                                <span id="total-entradas">-</span>
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="glass-card animate-in">
                        <div class="glass-card-body text-center">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <div class="rounded-circle p-3" style="background: rgba(220, 53, 69, 0.2);">
                                    <i class="fas fa-arrow-up fa-2x text-danger"></i>
                                </div>
                            </div>
                            <h6 class="text-white mb-1">Total Salidas</h6>
                            <h3 class="mb-0 font-weight-bold" style="color: #dc3545;">
                                <span id="total-salidas">-</span>
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="glass-card animate-in">
                        <div class="glass-card-body text-center">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <div class="rounded-circle p-3" style="background: rgba(23, 162, 184, 0.2);">
                                    <i class="fas fa-warehouse fa-2x text-info"></i>
                                </div>
                            </div>
                            <h6 class="text-white mb-1">Stock Actual</h6>
                            <h3 class="mb-0 font-weight-bold" style="color: #17a2b8;">
                                <span id="stock-actual">-</span>
                            </h3>
                            <small class="text-white-50" id="nombre-medicamento">Cargando...</small>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="glass-card animate-in">
                        <div class="glass-card-body text-center">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <div class="rounded-circle p-3" style="background: rgba(255, 193, 7, 0.2);">
                                    <i class="fas fa-exchange-alt fa-2x text-warning"></i>
                                </div>
                            </div>
                            <h6 class="text-white mb-1">Total Movimientos</h6>
                            <h3 class="mb-0 font-weight-bold" style="color: #ffc107;">
                                <span id="total-movimientos">-</span>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="filter-glass animate-in mb-3">
                <div class="card-header" style="background: rgba(255, 255, 255, 0.1); border-radius: 15px 15px 0 0; padding: 15px 20px; border: none;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" style="color: white; font-weight: 600;">
                            <i class="fas fa-filter mr-2"></i>Filtros de Búsqueda
                        </h5>
                        <button type="button" class="btn btn-sm glass-btn glass-btn-info" data-toggle="collapse" data-target="#filtros-collapse">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                </div>
                <div class="collapse" id="filtros-collapse">
                    <div style="padding: 20px;">
                        <form action="{{route('medicamento_controlado_movimiento')}}" method="GET">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group-glass">
                                        <label><i class="fas fa-pills mr-2"></i>Medicamento</label>
                                        <select name="medicamento_id" class="form-control glass-select">
                                            <option value="">Todos los medicamentos</option>
                                            @foreach($medicamentos as $med)
                                                <option value="{{$med->id}}" {{$medicamento_id == $med->id ? 'selected' : ''}}>
                                                    {{$med->nombre}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group-glass">
                                        <label><i class="fas fa-calendar-alt mr-2"></i>Fecha Desde</label>
                                        <input type="date" name="fecha_desde" class="form-control glass-input" value="{{$fecha_desde}}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group-glass">
                                        <label><i class="fas fa-calendar-check mr-2"></i>Fecha Hasta</label>
                                        <input type="date" name="fecha_hasta" class="form-control glass-input" value="{{$fecha_hasta}}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group-glass">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn-ios btn-ios-info btn-block" style="width: 100%; justify-content: center;">
                                            <i class="fas fa-search"></i>
                                            <span>Filtrar</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Listado de movimientos -->
            <div class="glass-card animate-in">
                <div class="glass-card-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h3><i class="fas fa-exchange-alt mr-2"></i> Historial de Movimientos</h3>
                        <div class="mt-2 mt-md-0">
                            <a href="{{route('medicamento_controlado')}}" class="btn-ios btn-ios-info">
                                <i class="fas fa-arrow-left mr-1"></i>
                                <span>Volver a Medicamentos</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="glass-card-body">
                    <div class="table-responsive">
                        <table class="table glass-table table-hover table-sm" id="tabla-movimientos">
                            <thead>
                                <tr>
                                    <th width="8%"><i class="fas fa-calendar"></i> Fecha</th>
                                    <th width="18%"><i class="fas fa-capsules"></i> Medicamento</th>
                                    <th width="8%" class="text-center"><i class="fas fa-exchange-alt"></i> Tipo</th>
                                    <th width="15%"><i class="fas fa-truck"></i> Proveedor</th>
                                    <th width="15%"><i class="fas fa-user-injured"></i> Paciente</th>
                                    <th width="8%" class="text-center"><i class="fas fa-plus"></i> Entrada</th>
                                    <th width="8%" class="text-center"><i class="fas fa-minus"></i> Salida</th>
                                    <th width="8%" class="text-center"><i class="fas fa-warehouse"></i> Saldo</th>
                                    <th width="12%" class="text-center"><i class="fas fa-info-circle"></i> Detalles</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- DataTables cargará los datos dinámicamente via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script src="{{asset("assets/$theme/plugins/datatables/jquery.dataTables.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/$theme/plugins/datatables-bs4/js/dataTables.bootstrap4.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/$theme/plugins/datatables-responsive/js/dataTables.responsive.min.js")}}" type="text/javascript"></script>
<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {

    // Pasar filtros desde PHP a JavaScript
    var filtroMedicamento = "{{ $medicamento_id ?? '' }}";
    var filtroFechaDesde = "{{ $fecha_desde ?? '' }}";
    var filtroFechaHasta = "{{ $fecha_hasta ?? '' }}";

    // Función para cargar estadísticas
    function cargarEstadisticas() {
        $.ajax({
            url: "{{ route('medicamento_controlado_movimiento') }}/estadisticas",
            method: 'GET',
            data: {
                medicamento_id: filtroMedicamento,
                fecha_desde: filtroFechaDesde,
                fecha_hasta: filtroFechaHasta
            },
            success: function(data) {
                // Actualizar tarjetas con animación
                $('#total-entradas').fadeOut(200, function() {
                    $(this).text(data.total_entradas).fadeIn(200);
                });
                $('#total-salidas').fadeOut(200, function() {
                    $(this).text(data.total_salidas).fadeIn(200);
                });
                $('#stock-actual').fadeOut(200, function() {
                    $(this).text(data.stock_actual).fadeIn(200);
                });
                $('#total-movimientos').fadeOut(200, function() {
                    $(this).text(data.total_movimientos).fadeIn(200);
                });
                $('#nombre-medicamento').fadeOut(200, function() {
                    $(this).text(data.nombre_medicamento).fadeIn(200);
                });
            },
            error: function(xhr) {
                console.error('Error al cargar estadísticas:', xhr);
            }
        });
    }

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
            url: "{{ route('medicamento_controlado_movimiento') }}",
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

    // Cargar estadísticas al inicio
    cargarEstadisticas();

    // Manejar el formulario de filtros con AJAX
    $('form[action*="medicamento_controlado_movimiento"]').on('submit', function(e) {
        e.preventDefault(); // Prevenir el submit normal

        // Actualizar variables de filtro
        filtroMedicamento = $('select[name="medicamento_id"]').val();
        filtroFechaDesde = $('input[name="fecha_desde"]').val();
        filtroFechaHasta = $('input[name="fecha_hasta"]').val();

        // Actualizar URL sin recargar la página
        var nuevaUrl = "{{ route('medicamento_controlado_movimiento') }}";
        var params = [];
        if (filtroMedicamento) params.push('medicamento_id=' + filtroMedicamento);
        if (filtroFechaDesde) params.push('fecha_desde=' + filtroFechaDesde);
        if (filtroFechaHasta) params.push('fecha_hasta=' + filtroFechaHasta);

        if (params.length > 0) {
            nuevaUrl += '?' + params.join('&');
        }

        window.history.pushState({}, '', nuevaUrl);

        // Recargar estadísticas
        cargarEstadisticas();

        // Recargar tabla DataTables
        tabla.ajax.reload();
    });

});
</script>
@endsection

