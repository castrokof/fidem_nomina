@extends("theme.$theme.layout")

@section('titulo')
    Creación de nomina
@endsection
@section('styles')
    <link href="{{ asset("assets/$theme/plugins/datatables-bs4/css/dataTables.bootstrap4.css") }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/js/gijgo-combined-1.9.13/css/gijgo.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2-bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        /*btn flotante*/
        .btn-flotante {
            font-size: 14px;
            /* Cambiar el tamaño de la tipografia */
            text-transform: uppercase;
            /* Texto en mayusculas */
            font-weight: bold;
            /* Fuente en negrita o bold */
            color: #ffffff;
            /* Color del texto */
            border-radius: 120px;
            /* Borde del boton */
            letter-spacing: 2px;
            /* Espacio entre letras */
            background: linear-gradient(to right, #a80d08, #ff6756) !important;
            /* Color de fondo */
            /*background-color: #e9321e; /* Color de fondo */
            padding: 18px 30px;
            /* Relleno del boton */
            position: fixed;
            bottom: 40px;
            right: 40px;
            transition: all 300ms ease 0ms;
            box-shadow: 0px 15px 20px rgba(0, 0, 0, 0.5);
            z-index: 99;
            border: none;
            outline: none;
        }

        .btn-flotante:hover {
            background-color: #2c2fa5;
            /* Color de fondo al pasar el cursor */
            box-shadow: 0px 15px 20px rgba(0, 0, 0, 0.3);
            transform: translateY(-7px);
        }

        @media only screen and (max-width: 600px) {
            .btn-flotante {
                font-size: 14px;
                padding: 12px 20px;
                bottom: 20px;
                right: 20px;
            }
        }
    </style>
@endsection


@section('scripts')
    <script src="{{ asset('assets/pages/scripts/admin/hours/crearuser.js') }}" type="text/javascript"></script>
@endsection

@section('contenido')
    <div class="row">
        <div class="col-lg-12">
            <div class="card bg-info" id="form-card">

                <div class="card-header with-border">
                    <h3 class="card-title">Crear nomina</h3>
                </div>
                <form id="form-general" class="form-horizontal" method="POST">
                    @csrf
                    <div class="card-body">
                        @include('nomina.nomina_fijos.form-usuario')
                        @include('nomina.nomina_fijos.boton-consultar-usuario-fijo')
                    </div>
                </form>
            </div>
            <div class="card-body table-responsive p-2">
                <table id="usuarios" class="table table-hover  text-nowrap">
                    <thead>
                        <tr>
                            <th>Acciones</th>
                            <th>Id</th>
                            <th>1Nombre</th>
                            <th>2Nombre</th>
                            <th>1Apellido</th>
                            <th>2Apellido</th>
                            <th>Tipo documento</th>
                            <th>Documento</th>
                            <th>Ips</th>
                            <th>Cargo</th>
                            <th>Tipo de salario</th>
                            <th>Salario</th>
                            <th>Activo</th>

                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <button type="button" class="btn-flotante tooltipsC" id="liquidar" title="adicionar nomina"><i
                class="fas fa-save fa-2x"></i></button>
    </div>
@endsection



@section('scriptsPlugins')
    <script src="{{ asset("assets/$theme/plugins/datatables/jquery.dataTables.js") }}" type="text/javascript"></script>
    <script src="{{ asset("assets/$theme/plugins/datatables-bs4/js/dataTables.bootstrap4.js") }}" type="text/javascript">
    </script>
    <script src="{{ asset("assets/$theme/plugins/datatables-responsive/js/dataTables.responsive.min.js") }}"
        type="text/javascript"></script>
    <script src="{{ asset('assets/js/jquery-select2/select2.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/gijgo-combined-1.9.13/js/gijgo.min.js') }}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js"></script>


    <script src="https://cdn.datatables.net/plug-ins/1.10.20/api/sum().js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>

    <script>
        $(document).ready(function() {



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

            // Btn flotante
            $('.botonF1').hover(function() {

            })



            $("#selectall").on('click', function() {
                $(".case").prop("checked", this.checked);
            });

            $('#date_hour_initial_turn').datetimepicker({
                footer: true,
                modal: true,
                format: 'yyyy-mm-dd HH:MM'

            });



            $('#date_hour_end_turn').datetimepicker({
                footer: true,
                modal: true,
                format: 'yyyy-mm-dd HH:MM'

            });



            // Calcular jornada
            function jornada() {
                var working_type = "PagoFijo";
                $("#working_type").val(working_type);
            }


            $("#date_hour_initial_turn").change(jornada);
            $("#date_hour_end_turn").change(jornada);





            // Calcular quincena
            function quincena() {
                var fecha = new Date($('#date_hour_initial_turn').val());

                var dia = fecha.getDate();
                var mes = parseFloat(fecha.getMonth());
                var año = fecha.getFullYear();
                var rango = "";

                var meses = ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC'];

                if (dia >= 1 && dia <= 15) {

                    rango = '1Q';

                } else {

                    rango = '2Q';

                };

                var mesd = meses[mes];

                var quincena = rango.concat(mesd).concat(año);

                $("#quincena").val(quincena);

            }

            $("#date_hour_initial_turn").change(quincena);


            fill_datatable_tabla();
            //Callback para consultar los datos por filtro desede la funcion fill_datatable_tabla

            $('#consultar_button').click(function() {

                ips = $('#ips').val();

                quincena = $('#quincena').val();

                $("#selectall").prop("checked", false);

                if (ips != '' && quincena != '') {

                    $('#usuarios').DataTable().destroy();

                    fill_datatable_tabla(ips, quincena);


                } else {

                    Swal.fire({
                        title: 'Debes seleccionar una ips y seleccionar la quincena',
                        icon: 'warning',
                        buttons: {
                            cancel: "Cerrar"

                        }
                    })
                }

            });


            // Funcion para pintar con data table y parametro de ips
            function fill_datatable_tabla(ips = '', quincena = '') {


                var datatable = $('#usuarios').DataTable({
                    language: idioma_espanol,
                    processing: true,
                    lengthMenu: [
                        [25, 50, 100, 500, -1],
                        [25, 50, 100, 500, "Mostrar Todo"]
                    ],
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('nominaf') }}",
                        data: {
                            ips: ips,
                            quincena: quincena
                        }
                    },
                    columns: [{
                            data: 'action',
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
                            data: 'ips',
                            name: 'ips'
                        },
                        {
                            data: 'position',
                            name: 'position'
                        },
                        {
                            data: 'type_salary',
                            name: 'type_salary'
                        },
                        {
                            data: 'salary',
                            render: $.fn.dataTable.render.number(',', '.'),
                            name: 'salary'
                        },
                        {
                            data: 'activo',
                            name: 'activo'
                        },
                    ],

                    //Botones----------------------------------------------------------------------

                    "dom": '<"row"<"col-xs-1 form-inline"><"col-md-4 form-inline"l><"col-md-5 form-inline"f><"col-md-3 form-inline"B>>rt<"row"<"col-md-8 form-inline"i> <"col-md-4 form-inline"p>>',

                    buttons: [{

                            extend: 'copyHtml5',
                            titleAttr: 'Copiar Registros',
                            title: "Control de horas",
                            className: "btn  btn-outline-primary btn-sm"


                        },
                        {

                            extend: 'excelHtml5',
                            titleAttr: 'Exportar Excel',
                            title: "Control de horas",
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
                    "columnDefs": [

                        {

                            "render": function(data, type, row) {
                                if (row["activo"] == 1) {
                                    return data + ' - Activo';
                                } else {

                                    return data + ' - Inactivo';

                                }

                            },
                            "targets": [12]
                        },
                        {

                            "render": function(data, type, row) {
                                if (row["type_salary"] == 'FIJO-QUINCENAL-MENSUAL') {
                                    return data + ' - Fijo';
                                } else {

                                    return data + ' - Por horas';

                                }

                            },
                            "targets": [10]
                        }




                    ],

                    "createdRow": function(row, data, dataIndex) {
                        if (data["activo"] == 1) {
                            $($(row).find("td")[12]).addClass("btn btn-sm btn-success rounded-lg");
                        } else {
                            $($(row).find("td")[12]).addClass("btn btn-sm btn-warning rounded-lg");
                        }
                        if (data["type_salary"] == 1) {
                            $($(row).find("td")[10]).addClass("btn btn-sm btn-info rounded-lg");
                        } else {
                            $($(row).find("td")[10]).addClass("btn btn-sm btn-dark rounded-lg");
                        }

                    }





                });
            }



            //funciona para guardar el formulario

            $('#liquidar').click(function() {

                var id = [];
                var supervisor = "{{ Session()->get('usuario') ?? '' }}";
                var dateini = $('#date_hour_initial_turn').val();
                var datefin = $('#date_hour_end_turn').val();
                var quincena = $("#quincena").val();
                var jornada = $("#working_type").val();
                var observation = $("#observation").val();
                var ips = $("#ips").val();
                var url = '';
                var icon = '';

                var fecha = new Date($('#date_hour_initial_turn').val());
                var fechaf = new Date($('#date_hour_end_turn').val());

                var mes = parseFloat(fecha.getMonth());
                var mesf = parseFloat(fechaf.getMonth());
                var dia = fecha.getDate();
                var diaf = fechaf.getDate();
                var año = fecha.getFullYear();

                var mesadd = parseFloat(fecha.getMonth() + 1);

                var diasxmes = new Date(año, mesadd, 0).getDate();

                if ($('#date_hour_initial_turn').val() > $('#date_hour_end_turn').val()) {

                    Swal.fire({
                        icon: 'error',
                        title: 'La fecha y hora inicial debe ser menor que la fecha y hora final',
                        showConfirmButton: true,
                        timer: 1500
                    });

                } else if ((dia == 1 && mes == mesf && diaf == 15) || (dia == 16 && mes == mesf &&
                        diasxmes == 30 && diaf > 29 && diaf < 31) || (dia == 16 && mes == mesf &&
                        diasxmes == 31 && diaf > 30) || (dia == 16 && mes == mesf && mes == 1 && diaf ==
                    28) || (dia == 16 && mes == mesf && mes == 1 && diaf == 29)) {


                    url = "{{ route('guardar_nomina') }}";
                    method = 'post';
                    text = "Estás por crear una nomina";
                    icon = "warning";

                    Swal.fire({
                        title: "¿Estás seguro?",
                        text: text,
                        icon: "warning",
                        showCancelButton: true,
                        showCloseButton: true,
                        confirmButtonText: 'Aceptar',
                    }).then((result) => {
                        if (result.value) {
                            $('input:checkbox:checked').each(function() {
                                id.push($(this).val());

                            });

                            if (id.length > 0) {
                                Swal.fire({
                                        title: 'Espere por favor !',
                                        html: 'Realizando la nomina', // add html attribute if you want or remove
                                        showConfirmButton: false,
                                        allowOutsideClick: false,
                                        willOpen: () => {
                                            Swal.showLoading()
                                        },
                                    }),

                                    $.ajax({
                                        url: url,
                                        method: method,
                                        data: {
                                            id: id,
                                            supervisor: supervisor,
                                            date_hour_initial_turn: dateini,
                                            date_hour_end_turn: datefin,
                                            working_type: jornada,
                                            quincena: quincena,
                                            observation: observation,
                                            ips: ips,

                                            "_token": $("meta[name='csrf-token']").attr(
                                                "content")

                                        },
                                        dataType: "json",
                                        success: function(data) {

                                            var usu = data.usuarios1;
                                            var usur = data.usuarios;

                                            if (usu.length == 0 || usu == undefined) {

                                                usu = 0;
                                            } else {
                                                usu = usu.length;
                                            }

                                            if (usur.length == 0 || usur == undefined) {

                                                usur = 0;
                                            } else {
                                                usur = usur.length;
                                            }


                                            var html = '';
                                            if (data.errors) {
                                                html =
                                                    '<div class="alert alert-danger alert-dismissible" data-auto-dismiss="3000">' +
                                                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                                                    '<h5><i class="icon fas fa-check"></i> Mensaje coll nomina</h5>';

                                                for (var count = 0; count < data.errors
                                                    .length; count++) {
                                                    html += '<p>' + data.errors[count] +
                                                        '<p>';
                                                }
                                                html += '</div>';
                                            } else if (data.success == 'ok') {
                                                $('#usuarios').DataTable().ajax.reload();
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Nomina creada correctamente con: ' +
                                                        usu + ' empleado(s)' +
                                                        'empleados repetidos : ' +
                                                        usur,
                                                    showConfirmButton: true,
                                                    timer: 1500
                                                })

                                            }
                                            $('#form_result').html(html)
                                        }


                                    });

                            } else {

                                Swal.fire({
                                    title: 'Por favor seleccione un turno del checkbox',
                                    icon: 'warning',
                                    buttons: {
                                        cancel: "Cerrar"

                                    }
                                })
                            }
                        }
                    });

                } else {

                    Swal.fire({
                        icon: 'error',
                        title: 'Solo puedes escoger un rango quincenal del mismo mes',
                        showConfirmButton: true,
                        timer: 1500
                    })
                }
            });

            //Edición de turnos

            $(document).on('click', '.edit', function() {
                var idh = $(this).attr('id');

                $.ajax({
                    url: "/hoursxuser/" + idh + "/editar",
                    dataType: "json",
                    success: function(data) {
                        $('#date_hour_initial_turn').val(data.result.date_hour_initial_turn);
                        $('#date_hour_end_turn').val(data.result.date_hour_end_turn);
                        $('#quincena').val(data.result.quincena);
                        $('#working_type').val(data.result.working_type);
                        $('#observation').val(data.result.observation);
                        $('.card-title').text('Editar Turno');
                        $('#form-card').removeClass('card bg-info');
                        $('#form-card').addClass('card bg-warning');
                        $('#action_button').val('Editar').removeClass('btn-sucess')
                        $('#action_button').addClass('btn-danger')
                        $('#cancelar_button').css("display", "block")
                        $('#action').val('Edit');
                        $('#hidden_id').val(idh);

                    }


                })


            });

            $('#form-general').on('reset', function(event) {
                $('.card-title').text('Registrar Turnos');
                $('#form-card').removeClass('card bg-warning');
                $('#form-card').addClass('card bg-info');
                $('#action_button').val('Guardar').removeClass('btn-danger')
                $('#action_button').addClass('btn-success')
                $('#cancelar_button').css("display", "none")
                $('#action').val('Add');


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
