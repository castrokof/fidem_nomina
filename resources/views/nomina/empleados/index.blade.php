@extends("theme.$theme.layout")

@section('titulo')
    Empleados
@endsection
@section('styles')
    <link href="{{ asset("assets/$theme/plugins/datatables-bs4/css/dataTables.bootstrap4.css") }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2-bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection


@section('scripts')
    <script src="{{ asset('assets/pages/scripts/admin/usuario/crearempleado.js') }}" type="text/javascript"></script>
@endsection

@section('contenido')
    @include('nomina.empleados.tablas.tablaIndexEmpleados')
    @include('nomina.empleados.modal.modalEmpleado')
    @include('nomina.empleados.modal.modalContrato')
    @include('nomina.empleados.modal.modalNovedades')
@endsection



@section('scriptsPlugins')
    <script src="{{ asset("assets/$theme/plugins/datatables/jquery.dataTables.js") }}" type="text/javascript"></script>
    <script src="{{ asset("assets/$theme/plugins/datatables-bs4/js/dataTables.bootstrap4.js") }}" type="text/javascript">
    </script>
    <script src="{{ asset('assets/js/jquery-select2/select2.min.js') }}" type="text/javascript"></script>

    <script src="https://cdn.datatables.net/plug-ins/1.10.20/api/sum().js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>

    <script>
        $(document).ready(function() {



            function ocultarsalario() {

                if ($('#type_contrat').val() == "CT") {


                    $("#salaryform").css("display", "block");
                    $("#salary").prop("required", true);

                    $("#salarypsform").css("display", "none");
                    $("#salary_ps").prop("required", false);


                } else {

                    $("#salarypsform").css("display", "block");
                    $("#salary_ps").prop("required", true);

                    $("#salaryform").css("display", "none");
                    $("#salary").prop("required", false);

                }

            }

            $('#type_contrat').change(ocultarsalario);


            //Consulta de datos de la tabla lista-detalle
            $("#ips").select2({
                language: "es",
                theme: "bootstrap4",
                placeholder: 'Seleccione una empresa',
                allowClear: true,
                ajax: {
                    url: "{{ route('selectlist') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            id: 1
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.array[0], function(datas) {

                                return {

                                    text: datas.nombre,
                                    id: datas.nombre

                                }
                            })
                        };
                    },
                    cache: true
                }
            });


            //Consulta de datos de la tabla lista-detalle
            $("#name_bank").select2({
                language: "es",
                theme: "bootstrap4",
                placeholder: 'Seleccione un banco',
                allowClear: true,
                ajax: {
                    url: "{{ route('selectlist') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            id: 2
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.array[0], function(datas) {

                                return {

                                    text: datas.nombre,
                                    id: datas.nombre

                                }
                            })
                        };
                    },
                    cache: true
                }
            });


            //Consulta de datos de la tabla lista-detalle
            $("#type_account").select2({
                language: "es",
                theme: "bootstrap4",
                placeholder: 'Seleccione tipo de cuenta',
                allowClear: true,
                ajax: {
                    url: "{{ route('selectlist') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            id: 3
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.array[0], function(datas) {

                                return {

                                    text: datas.nombre,
                                    id: datas.nombre

                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            $("#cargo_id").select2({
                language: "es",
                theme: "bootstrap4",
                placeholder: 'Seleccione un cargo',
                allowClear: true,
                ajax: {
                    url: "{{ route('selectlist') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            id: 4
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.array[0], function(datas) {

                                return {

                                    text: datas.nombre,
                                    id: datas.nombre

                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            $("#eps").select2({
                language: "es",
                theme: "bootstrap4",
                placeholder: 'Seleccione una eps',
                allowClear: true,
                ajax: {
                    url: "{{ route('selectlist') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            id: 5
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.array[0], function(datas) {

                                return {

                                    text: datas.nombre,
                                    id: datas.nombre

                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            $("#arl").select2({
                language: "es",
                theme: "bootstrap4",
                placeholder: 'Seleccione arl',
                allowClear: true,
                ajax: {
                    url: "{{ route('selectlist') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            id: 6
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.array[0], function(datas) {

                                return {

                                    text: datas.nombre,
                                    id: datas.nombre

                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            $("#afp").select2({
                language: "es",
                theme: "bootstrap4",
                placeholder: 'Seleccione afp',
                allowClear: true,
                ajax: {
                    url: "{{ route('selectlist') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            id: 7
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.array[0], function(datas) {

                                return {

                                    text: datas.nombre,
                                    id: datas.nombre

                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            $("#fc").select2({
                language: "es",
                theme: "bootstrap4",
                placeholder: 'Seleccione fc',
                allowClear: true,
                ajax: {
                    url: "{{ route('selectlist') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            id: 8
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.array[0], function(datas) {

                                return {

                                    text: datas.nombre,
                                    id: datas.nombre

                                }
                            })
                        };
                    },
                    cache: true
                }
            });
            //     $.get('select_position',
            //     function(positions) {  //initiate dataTables plugin
            // $.each(positions, function(position1, value) {
            var myTable =
                $('#empleados').DataTable({
                    language: idioma_espanol,
                    processing: true,
                    lengthMenu: [
                        [25, 50, 100, 500, -1],
                        [25, 50, 100, 500, "Mostrar Todo"]
                    ],
                    processing: true,
                    serverSide: true,
                    aaSorting: [
                        [1, "asc"]
                    ],

                    ajax: {
                        url: "{{ route('empleado') }}",
                    },
                    columns: [{
                            data: 'action',
                            name: 'action',
                            orderable: false
                        },
                        {
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'pnombre',
                            name: 'pnombre'
                        },
                        {
                            data: 'snombre',
                            name: 'snombre'
                        },
                        {
                            data: 'papellido',
                            name: 'papellido'
                        },
                        {
                            data: 'sapellido',
                            name: 'sapellido'
                        },
                        {
                            data: 'tipo_documento',
                            name: 'tipo_documento'
                        },
                        {
                            data: 'documento',
                            name: 'documento'
                        },
                        {
                            data: 'celular',
                            name: 'celular'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'ips',
                            name: 'ips'
                        },
                        {
                            data: 'activo',
                            name: 'activo'
                        },
                        {
                            data: 'position'

                        },

                        {
                            data: 'type_salary',
                            name: 'type_salary'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },

                    ],

                    //Botones----------------------------------------------------------------------

                    "dom": '<"row"<"col-xs-1 form-inline"><"col-md-4 form-inline"l><"col-md-5 form-inline"f><"col-md-3 form-inline"B>>rt<"row"<"col-md-8 form-inline"i> <"col-md-4 form-inline"p>>',


                    buttons: [{

                            extend: 'copyHtml5',
                            titleAttr: 'Copiar Registros',
                            title: "seguimiento",
                            className: "btn  btn-outline-primary btn-sm"


                        },
                        {

                            extend: 'excelHtml5',
                            titleAttr: 'Exportar Excel',
                            title: "seguimiento",
                            className: "btn  btn-outline-success btn-sm"


                        },
                        {

                            extend: 'csvHtml5',
                            titleAttr: 'Exportar csv',
                            className: "btn  btn-outline-warning btn-sm"
                            //text: '<i class="fas fa-file-excel"></i>'

                        },
                        {

                            extend: 'pdfHtml5',
                            titleAttr: 'Exportar pdf',
                            className: "btn  btn-outline-secondary btn-sm"


                        }
                    ],

                    "columnDefs": [{

                            "render": function(data, type, row) {
                                if (row["activo"] == 1) {
                                    return data + ' - Activo';
                                } else {

                                    return data + ' - Inactivo';

                                }

                            },
                            "targets": [11]
                        },





                    ],

                    "createdRow": function(row, data, dataIndex) {
                        if (data["activo"] == 1) {
                            $($(row).find("td")[11]).addClass("btn btn-sm btn-success rounded-lg");
                        } else {
                            $($(row).find("td")[11]).addClass("btn btn-sm btn-warning rounded-lg");
                        }
                        if (data["type_salary"] == 1) {
                            $($(row).find("td")[15]).addClass("btn btn-sm btn-info rounded-lg");
                        } else {
                            $($(row).find("td")[15]).addClass("btn btn-sm btn-dark rounded-lg");
                        }

                    }



                });

            //     });

            // });

            $('#create_empleado').click(function() {
                $('#form-general')[0].reset();
                $('#email').prop('disabled', false).prop('required', true);
                $('.card-title').text('Estas creando un nuevo empleado');
                $('#action_button').val('Add');
                $('#action').val('Add');
                $('#form_result').html('');
                $('#modal-u').modal({
                    backdrop: 'static',
                    keyboard: false
                });



            });


            $(document).on('click', '.addempleado', function(event) {
                event.preventDefault();
                var url = '';
                var method = '';
                var text = '';

                if ($('#action').val() == 'Add') {
                    text = "Estás por crear un empleado"
                    url = "{{ route('guardar_empleado') }}";
                    method = 'post';
                }

                if ($('#action').val() == 'Edit') {
                    text = "Estás por actualizar un empleado"
                    var updateid = $('#hidden_id').val();
                    url = "empleado/" + updateid;
                    method = 'put';
                }
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: text,
                    icon: "success",
                    showCancelButton: true,
                    showCloseButton: true,
                    confirmButtonText: 'Aceptar',
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: url,
                            method: method,
                            data: $('#form-general').serialize(),
                            dataType: "json",
                            success: function(data) {
                                if (data.success == 'ok') {
                                    $('#form-general')[0].reset();
                                    $('#modal-u').modal('hide');
                                    $('#empleados').DataTable().ajax.reload();
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'empleado creado correctamente',
                                        showConfirmButton: false,
                                        timer: 1500

                                    })
                                    // Manteliviano.notificaciones('cliente creado correctamente', 'Sistema Ventas', 'success');

                                } else if (data.success == 'ok1') {
                                    $('#form-general')[0].reset();
                                    $('#modal-u').modal('hide');
                                    $('#empleados').DataTable().ajax.reload();
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'empleado actualizado correctamente',
                                        showConfirmButton: false,
                                        timer: 1500

                                    })
                                    // Manteliviano.notificaciones('cliente actualizado correctamente', 'Sistema Ventas', 'success');

                                }
                            }


                        }).fail(function(jqXHR, textStatus, errorThrown) {

                            if (jqXHR.status === 422) {

                                var error = jqXHR.responseJSON;

                                $.each(error, function(i, items) {

                                    var errores = [];
                                    errores.push(items.celular + '<br>');
                                    errores.push(items.activo + '<br>');
                                    errores.push(items.documento + '<br>');
                                    errores.push(items.email + '<br>');
                                    errores.push(items.papellido + '<br>');
                                    errores.push(items.pnombre + '<br>');
                                    errores.push(items.position + '<br>');
                                    errores.push(items.tipo_documento + '<br>');
                                    errores.push(items.user_id + '<br>');

                                    console.log(errores);

                                    var filtered = errores.filter(function(el) {
                                        return el != "undefined<br>";
                                    });

                                    console.log(filtered);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'El formulario contiene errores',
                                        html: filtered,
                                        showConfirmButton: true,
                                        //timer: 1500
                                    })


                                    //Manteliviano.notificaciones(items, 'Sistema Ventas', 'warning');

                                });
                            }
                        });
                    }
                });


            });
            
            // Adicionar contrato
            
            $(document).on('click', '.addcontrato', function() {
                
                var id = $(this).attr('id');
                
                $('#form-general')[0].reset();
                
             $.ajax({
                    url: "empleado/" + id + "/editar",
                    dataType: "json",
                    success: function(data) {


                        // Primer form de información empleado
                        $('#pnombre').val(data.empleado.pnombre);
                        $('#snombre').val(data.empleado.snombre);
                        $('#papellido').val(data.empleado.papellido);
                        $('#sapellido').val(data.empleado.sapellido);
                        $('#tipo_documento').val(data.empleado.tipo_documento);
                        $('#documento').val(data.empleado.documento);
                        $('#email').val(data.empleado.email).prop('required', false);
                        $('#celular').val(data.empleado.celular);

                        var newips = new Option(data.empleado.ips, data.empleado.ips, true, true);
                        $('#ips').append(newips).trigger('change');

                        //Segundo form afiliaciones
                        var newposition = new Option(data.empleado.position, data.empleado.position, true, true);
                        $('#cargo_id').append(newposition).trigger('change');

                        var neweps = new Option(data.empleado.eps, data.empleado.eps, true, true);
                        $('#eps').append(neweps).trigger('change');

                        var newfc = new Option(data.empleado.fc, data.empleado.fc, true, true);
                        $('#fc').append(newfc).trigger('change');

                        var newafp = new Option(data.empleado.afp, data.empleado.afp, true, true);
                        $('#afp').append(newafp).trigger('change');

                        var newarl = new Option(data.empleado.arl, data.empleado.arl, true, true);
                        $('#arl').append(newarl).trigger('change');

                        //Tercer form contrato

                        $('#type_contrat').val(data.empleado.type_contrat);

                        $('#date_in').val(data.empleado.date_in);

                        $('#activo').val(data.empleado.activo);

                        $('#type_salary').val(data.empleado.type_salary);

                        if ($('#type_contrat').val() == "CT") {
                            $("#salaryform").css("display", "block");
                            $("#salary").prop("required", true).val(data.empleado.salary);

                            $("#salarypsform").css("display", "none");
                            $("#salary_ps").prop("required", false);

                        } else {
                            $("#salarypsform").css("display", "block");
                            $("#salary_ps").prop("required", true).val(data.empleado.salary_ps);

                            $("#salaryform").css("display", "none");
                            $("#salary").prop("required", false);

                        }

                        $('#date_incontract').val(data.empleado.date_incontract);
                        $('#date_endcontract').val(data.empleado.date_endcontract);
                        $('#date_out').val(data.empleado.date_out);

                        //Cuarto form salarios


                        var newbank = new Option(data.empleado.name_bank, data.empleado.name_bank, true, true);
                        $('#name_bank').append(newbank).trigger('change');

                        var newtypea = new Option(data.empleado.type_account, data.empleado.type_account, true, true);
                        $('#type_account').append(newtypea).trigger('change');

                        $('#account').val(data.empleado.account);

                        $('#value_transporte').val(data.empleado.value_transporte);

                        $('#value_salary_add').val(data.empleado.value_salary_add);

                        $('#value_hour').val(data.empleado.value_hour);

                        $('#value_patient_attended').val(data.empleado.value_patient_attended);

                        $('#value_add_security_social').val(data.empleado.value_add_security_social);


                        $('#hidden_id').val(id)
                        $('.card-title').text("Adicionando contrato: " + data.empleado.pnombre +
                            "-" + data.empleado.papellido);
                        $('#action_button').val('Add');
                        $('#action').val('Add');
                        $('#modal-c').modal('show');

                    },



                }).fail(function(jqXHR, textStatus, errorThrown) {

                    if (jqXHR.status === 403) {

                        Manteliviano.notificaciones('No tienes permisos para realizar esta accion',
                            'Call Nomina', 'warning');

                    }
                });
            });
       


            // Edición de cliente

            $(document).on('click', '.editemployed', function() {

                $('#form-general')[0].reset();
                var id = $(this).attr('id');

                $.ajax({
                    url: "empleado/" + id + "/editar",
                    dataType: "json",
                    success: function(data) {


                        // Primer form de información empleado
                        $('#pnombre').val(data.empleado.pnombre);
                        $('#snombre').val(data.empleado.snombre);
                        $('#papellido').val(data.empleado.papellido);
                        $('#sapellido').val(data.empleado.sapellido);
                        $('#tipo_documento').val(data.empleado.tipo_documento);
                        $('#documento').val(data.empleado.documento);
                        $('#email').val(data.empleado.email).prop('required', false);
                        $('#celular').val(data.empleado.celular);

                        var newips = new Option(data.empleado.ips, data.empleado.ips, true, true);
                        $('#ips').append(newips).trigger('change');

                        //Segundo form afiliaciones
                        var newposition = new Option(data.empleado.position, data.empleado.position, true, true);
                        $('#cargo_id').append(newposition).trigger('change');

                        var neweps = new Option(data.empleado.eps, data.empleado.eps, true, true);
                        $('#eps').append(neweps).trigger('change');

                        var newfc = new Option(data.empleado.fc, data.empleado.fc, true, true);
                        $('#fc').append(newfc).trigger('change');

                        var newafp = new Option(data.empleado.afp, data.empleado.afp, true, true);
                        $('#afp').append(newafp).trigger('change');

                        var newarl = new Option(data.empleado.arl, data.empleado.arl, true, true);
                        $('#arl').append(newarl).trigger('change');

                        //Tercer form contrato

                        $('#type_contrat').val(data.empleado.type_contrat);

                        $('#date_in').val(data.empleado.date_in);

                        $('#activo').val(data.empleado.activo);

                        $('#type_salary').val(data.empleado.type_salary);

                        if ($('#type_contrat').val() == "CT") {
                            $("#salaryform").css("display", "block");
                            $("#salary").prop("required", true).val(data.empleado.salary);

                            $("#salarypsform").css("display", "none");
                            $("#salary_ps").prop("required", false);

                        } else {
                            $("#salarypsform").css("display", "block");
                            $("#salary_ps").prop("required", true).val(data.empleado.salary_ps);

                            $("#salaryform").css("display", "none");
                            $("#salary").prop("required", false);

                        }

                        $('#date_incontract').val(data.empleado.date_incontract);
                        $('#date_endcontract').val(data.empleado.date_endcontract);
                        $('#date_out').val(data.empleado.date_out);

                        //Cuarto form salarios


                        var newbank = new Option(data.empleado.name_bank, data.empleado.name_bank, true, true);
                        $('#name_bank').append(newbank).trigger('change');

                        var newtypea = new Option(data.empleado.type_account, data.empleado.type_account, true, true);
                        $('#type_account').append(newtypea).trigger('change');

                        $('#account').val(data.empleado.account);

                        $('#value_transporte').val(data.empleado.value_transporte);

                        $('#value_salary_add').val(data.empleado.value_salary_add);

                        $('#value_hour').val(data.empleado.value_hour);

                        $('#value_patient_attended').val(data.empleado.value_patient_attended);

                        $('#value_add_security_social').val(data.empleado.value_add_security_social);


                        $('#hidden_id').val(id)
                        $('.card-title').text("Editando empleado: " + data.empleado.pnombre +
                            "-" + data.empleado.papellido);
                        $('#action_button').val('Edit');
                        $('#action').val('Edit');
                        $('#modal-u').modal('show');

                    },



                }).fail(function(jqXHR, textStatus, errorThrown) {

                    if (jqXHR.status === 403) {

                        Manteliviano.notificaciones('No tienes permisos para realizar esta accion',
                            'Call Nomina', 'warning');

                    }
                });

            });

            // ========== FUNCIONALIDAD DE NOVEDADES ==========

            // Abrir modal de novedades
            $(document).on('click', '.addnovedad', function() {
                var id = $(this).attr('id');
                $('#form-novedad')[0].reset();
                $('#empleado_id_novedad').val(id);

                $.ajax({
                    url: "empleado/" + id + "/editar",
                    dataType: "json",
                    success: function(data) {
                        $('.card-title-novedad').text("Novedades: " + data.empleado.pnombre + " " + data.empleado.papellido);
                        $('#empleado-info-novedad').text(data.empleado.pnombre + " " + data.empleado.papellido + " - " + data.empleado.documento);
                        $('#action_novedad').val('Add');

                        // Cargar tabla de novedades del empleado
                        if ($.fn.DataTable.isDataTable('#tabla-novedades-empleado')) {
                            $('#tabla-novedades-empleado').DataTable().destroy();
                        }

                        $('#tabla-novedades-empleado').DataTable({
                            language: idioma_espanol,
                            processing: true,
                            serverSide: true,
                            ajax: {
                                url: "{{ route('empleados_novedades') }}",
                                data: { empleado_id: id }
                            },
                            columns: [
                                { data: 'action', name: 'action', orderable: false },
                                { data: 'tipo_badge', name: 'tipo_novedad' },
                                { data: 'fecha_inicio', name: 'fecha_inicio' },
                                { data: 'fecha_fin', name: 'fecha_fin' },
                                { data: 'dias', name: 'dias' },
                                { data: 'valor', name: 'valor' },
                                { data: 'estado_badge', name: 'estado' }
                            ],
                            dom: 't'
                        });

                        $('#modal-novedad').modal('show');
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    if (jqXHR.status === 403) {
                        Swal.fire({
                            icon: 'error',
                            title: 'No tienes permisos para realizar esta acción',
                            showConfirmButton: true
                        });
                    }
                });
            });

            // Calcular días automáticamente
            $('#fecha_inicio_novedad, #fecha_fin_novedad').change(function() {
                var fecha_inicio = $('#fecha_inicio_novedad').val();
                var fecha_fin = $('#fecha_fin_novedad').val();

                if (fecha_inicio && fecha_fin) {
                    var inicio = new Date(fecha_inicio);
                    var fin = new Date(fecha_fin);
                    var dias = Math.floor((fin - inicio) / (1000 * 60 * 60 * 24)) + 1;
                    $('#dias_novedad').val(dias);
                }
            });

            // Guardar novedad
            $(document).on('click', '.addnovedad-empleado', function(event) {
                event.preventDefault();
                var url = '';
                var method = '';
                var text = '';

                if ($('#action_novedad').val() == 'Add') {
                    text = "Estás por crear una novedad"
                    url = "{{ route('guardar_empleado_novedad') }}";
                    method = 'post';
                }

                if ($('#action_novedad').val() == 'Edit') {
                    text = "Estás por actualizar una novedad"
                    var updateid = $('#hidden_id_novedad').val();
                    url = "empleados-novedades/" + updateid;
                    method = 'put';
                }

                Swal.fire({
                    title: "¿Estás seguro?",
                    text: text,
                    icon: "question",
                    showCancelButton: true,
                    showCloseButton: true,
                    confirmButtonText: 'Aceptar',
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: url,
                            method: method,
                            data: {
                                _token: '{{ csrf_token() }}',
                                tipo_novedad: $('#tipo_novedad').val(),
                                fecha_inicio: $('#fecha_inicio_novedad').val(),
                                fecha_fin: $('#fecha_fin_novedad').val(),
                                dias: $('#dias_novedad').val(),
                                valor: $('#valor_novedad').val(),
                                observacion: $('#observacion_novedad').val(),
                                documento_soporte: $('#documento_soporte_novedad').val(),
                                estado: $('#estado_novedad').val(),
                                empleado_id: $('#empleado_id_novedad').val()
                            },
                            dataType: "json",
                            success: function(data) {
                                if (data.success == 'ok') {
                                    $('#form-novedad')[0].reset();
                                    $('#tabla-novedades-empleado').DataTable().ajax.reload();
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Novedad creada correctamente',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                } else if (data.success == 'ok1') {
                                    $('#form-novedad')[0].reset();
                                    $('#tabla-novedades-empleado').DataTable().ajax.reload();
                                    $('#action_novedad').val('Add');
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Novedad actualizada correctamente',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                }
                            }
                        }).fail(function(jqXHR, textStatus, errorThrown) {
                            if (jqXHR.status === 422) {
                                var error = jqXHR.responseJSON;
                                var errores = [];
                                $.each(error.errors, function(i, item) {
                                    errores.push(item + '<br>');
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'El formulario contiene errores',
                                    html: errores.join(''),
                                    showConfirmButton: true
                                });
                            }
                        });
                    }
                });
            });

            // Editar novedad
            $(document).on('click', '.editnovedad', function() {
                var id = $(this).attr('id');
                $.ajax({
                    url: "empleados-novedades/" + id + "/editar",
                    dataType: "json",
                    success: function(data) {
                        $('#tipo_novedad').val(data.novedad.tipo_novedad);
                        $('#fecha_inicio_novedad').val(data.novedad.fecha_inicio);
                        $('#fecha_fin_novedad').val(data.novedad.fecha_fin);
                        $('#dias_novedad').val(data.novedad.dias);
                        $('#valor_novedad').val(data.novedad.valor);
                        $('#observacion_novedad').val(data.novedad.observacion);
                        $('#documento_soporte_novedad').val(data.novedad.documento_soporte);
                        $('#estado_novedad').val(data.novedad.estado);
                        $('#hidden_id_novedad').val(id);
                        $('#action_novedad').val('Edit');
                    }
                });
            });

            // Eliminar novedad
            $(document).on('click', '.deletenovedad', function() {
                var id = $(this).attr('id');
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "Estás por eliminar esta novedad",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: "empleados-novedades/" + id,
                            method: 'delete',
                            data: { _token: '{{ csrf_token() }}' },
                            dataType: "json",
                            success: function(data) {
                                $('#tabla-novedades-empleado').DataTable().ajax.reload();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Novedad eliminada correctamente',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        });
                    }
                });
            });


        });


        var idioma_espanol = {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla =(",
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
            },
            "buttons": {
                "copy": "Copiar",
                "colvis": "Visibilidad"
            }
        }
    </script>
@endsection
