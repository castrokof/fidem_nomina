@extends("theme.$theme.layout")

@section('titulo')
    Validar y Bloquear Nómina
@endsection

@section('styles')
    <link href="{{ asset("assets/$theme/plugins/datatables-bs4/css/dataTables.bootstrap4.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2-bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('contenido')
<div class="row">
    <div class="col-lg-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Validar y Bloquear Nómina</h3>
            </div>
            <div class="card-body">
                <!-- Filtros -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="quincena_validar">Quincena</label>
                            <select id="quincena_validar" class="form-control select2">
                                <option value="">Seleccione quincena</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="ips_validar">IPS (Opcional)</label>
                            <input type="text" id="ips_validar" class="form-control" placeholder="Filtrar por IPS">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>&nbsp;</label><br>
                            <button type="button" id="btn_cargar_nomina" class="btn btn-primary">
                                <i class="fas fa-search"></i> Cargar Nómina
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Resumen -->
                <div class="row" id="resumen_nomina" style="display:none;">
                    <div class="col-md-12">
                        <h5>Resumen de Nómina</h5>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box bg-info">
                            <span class="info-box-icon"><i class="fas fa-users"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Empleados</span>
                                <span class="info-box-number" id="total_empleados">0</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box bg-success">
                            <span class="info-box-icon"><i class="fas fa-dollar-sign"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Salarios</span>
                                <span class="info-box-number" id="total_salarios">$0</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box bg-warning">
                            <span class="info-box-icon"><i class="fas fa-minus-circle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Descuentos</span>
                                <span class="info-box-number" id="total_descuentos">$0</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box bg-primary">
                            <span class="info-box-icon"><i class="fas fa-plus-circle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Bonos</span>
                                <span class="info-box-number" id="total_bonos">$0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla detallada -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="tabla_validacion_nomina" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Empleado</th>
                                        <th>Documento</th>
                                        <th>Cargo</th>
                                        <th>Salario Base</th>
                                        <th>Desc. Incap.</th>
                                        <th>Desc. Susp.</th>
                                        <th>Pago Vac.</th>
                                        <th>Otros Desc.</th>
                                        <th>Otros Bonos</th>
                                        <th>Neto a Pagar</th>
                                        <th>Novedades</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="row mt-3" id="acciones_nomina" style="display:none;">
                    <div class="col-md-12 text-center">
                        <button type="button" id="btn_bloquear_nomina" class="btn btn-success btn-lg">
                            <i class="fas fa-lock"></i> Bloquear Nómina
                        </button>
                        <button type="button" id="btn_exportar_excel" class="btn btn-primary btn-lg">
                            <i class="fas fa-file-excel"></i> Exportar Excel
                        </button>
                        <button type="button" id="btn_exportar_plano" class="btn btn-secondary btn-lg">
                            <i class="fas fa-file-alt"></i> Exportar Archivo Plano
                        </button>
                        @if(session()->get('rol_id') == 1 || session()->get('rol_id') == 4)
                        <button type="button" id="btn_desbloquear_nomina" class="btn btn-warning btn-lg">
                            <i class="fas fa-unlock"></i> Desbloquear Nómina
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scriptsPlugins')
    <script src="{{ asset("assets/$theme/plugins/datatables/jquery.dataTables.js") }}" type="text/javascript"></script>
    <script src="{{ asset("assets/$theme/plugins/datatables-bs4/js/dataTables.bootstrap4.js") }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/jquery-select2/select2.min.js') }}" type="text/javascript"></script>

    <script>
        $(document).ready(function() {
            // Cargar quincenas en select2
            $("#quincena_validar").select2({
                language: "es",
                theme: "bootstrap4",
                placeholder: 'Seleccione quincena',
                allowClear: true,
                ajax: {
                    url: "{{ route('selectlist') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            id: 9 // ID para quincenas
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.array[0], function(datas) {
                                return {
                                    text: datas.quincena,
                                    id: datas.quincena
                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            // Cargar nómina
            $('#btn_cargar_nomina').click(function() {
                var quincena = $('#quincena_validar').val();
                var ips = $('#ips_validar').val();

                if (!quincena) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Debe seleccionar una quincena',
                        showConfirmButton: true
                    });
                    return;
                }

                $.ajax({
                    url: "{{ route('nomina_validar_data') }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        quincena: quincena,
                        ips: ips
                    },
                    dataType: "json",
                    success: function(data) {
                        // Mostrar resumen
                        $('#total_empleados').text(data.total_registros);
                        $('#total_salarios').text('$' + new Intl.NumberFormat().format(data.total_salarios));

                        var totalDescuentos = data.total_descuentos_incapacidad + data.total_descuentos_suspension + data.total_otros_descuentos;
                        var totalBonos = data.total_pago_vacaciones + data.total_otros_bonos;

                        $('#total_descuentos').text('$' + new Intl.NumberFormat().format(totalDescuentos));
                        $('#total_bonos').text('$' + new Intl.NumberFormat().format(totalBonos));

                        $('#resumen_nomina').show();
                        $('#acciones_nomina').show();

                        // Llenar tabla
                        var tbody = $('#tabla_validacion_nomina tbody');
                        tbody.empty();

                        $.each(data.nominas, function(index, nomina) {
                            var empleado = nomina.empleadoid;
                            var salarioBase = nomina.salary || nomina.salary_ps;
                            var descuentos = parseFloat(nomina.descuento_incapacidad) + parseFloat(nomina.descuento_suspension) + parseFloat(nomina.otros_descuentos_novedades);
                            var bonos = parseFloat(nomina.pago_vacaciones) + parseFloat(nomina.otros_bonos_novedades);
                            var neto = salarioBase - descuentos + bonos;

                            var novedades = JSON.parse(nomina.novedades_aplicadas || '[]');
                            var novedadesHtml = '';
                            if (novedades.length > 0) {
                                novedadesHtml = '<span class="badge badge-info">' + novedades.length + ' novedades</span>';
                            }

                            var row = '<tr>' +
                                '<td>' + nomina.id + '</td>' +
                                '<td>' + empleado.pnombre + ' ' + empleado.papellido + '</td>' +
                                '<td>' + empleado.documento + '</td>' +
                                '<td>' + nomina.position + '</td>' +
                                '<td>$' + new Intl.NumberFormat().format(salarioBase) + '</td>' +
                                '<td>$' + new Intl.NumberFormat().format(nomina.descuento_incapacidad) + '</td>' +
                                '<td>$' + new Intl.NumberFormat().format(nomina.descuento_suspension) + '</td>' +
                                '<td>$' + new Intl.NumberFormat().format(nomina.pago_vacaciones) + '</td>' +
                                '<td>$' + new Intl.NumberFormat().format(nomina.otros_descuentos_novedades) + '</td>' +
                                '<td>$' + new Intl.NumberFormat().format(nomina.otros_bonos_novedades) + '</td>' +
                                '<td><strong>$' + new Intl.NumberFormat().format(neto) + '</strong></td>' +
                                '<td>' + novedadesHtml + '</td>' +
                                '</tr>';
                            tbody.append(row);
                        });
                    }
                });
            });

            // Bloquear nómina
            $('#btn_bloquear_nomina').click(function() {
                var quincena = $('#quincena_validar').val();
                var ips = $('#ips_validar').val();

                Swal.fire({
                    title: "¿Está seguro?",
                    text: "Está por bloquear la nómina de la quincena " + quincena + ". Una vez bloqueada, no se podrá modificar sin autorización.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: 'Sí, bloquear',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: "{{ route('nomina_bloquear') }}",
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                quincena: quincena,
                                ips: ips
                            },
                            dataType: "json",
                            success: function(data) {
                                Swal.fire({
                                    icon: 'success',
                                    title: data.message,
                                    showConfirmButton: true
                                });
                                $('#btn_cargar_nomina').click();
                            }
                        });
                    }
                });
            });

            // Desbloquear nómina
            $('#btn_desbloquear_nomina').click(function() {
                var quincena = $('#quincena_validar').val();

                Swal.fire({
                    title: "¿Está seguro?",
                    text: "Ingrese el motivo para desbloquear la nómina:",
                    input: 'text',
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: 'Sí, desbloquear',
                    cancelButtonText: 'Cancelar',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Debe ingresar un motivo'
                        }
                    }
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: "{{ route('nomina_desbloquear') }}",
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                quincena: quincena,
                                motivo: result.value
                            },
                            dataType: "json",
                            success: function(data) {
                                Swal.fire({
                                    icon: 'success',
                                    title: data.message,
                                    showConfirmButton: true
                                });
                                $('#btn_cargar_nomina').click();
                            }
                        });
                    }
                });
            });

            // Exportar Excel
            $('#btn_exportar_excel').click(function() {
                var quincena = $('#quincena_validar').val();
                var ips = $('#ips_validar').val();
                var url = "{{ route('nomina_exportar_excel') }}?quincena=" + quincena;
                if (ips) {
                    url += "&ips=" + ips;
                }
                window.location.href = url;
            });

            // Exportar Plano
            $('#btn_exportar_plano').click(function() {
                var quincena = $('#quincena_validar').val();
                var ips = $('#ips_validar').val();
                var url = "{{ route('nomina_exportar_plano') }}?quincena=" + quincena;
                if (ips) {
                    url += "&ips=" + ips;
                }
                window.location.href = url;
            });
        });

        var idioma_espanol = {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            }
        }
    </script>
@endsection
