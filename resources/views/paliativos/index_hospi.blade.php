@extends("theme.$theme.layout")

@section('titulo')
    Hospitalizados
@endsection

@section('styles')
    <link href="{{ asset("assets/$theme/plugins/datatables-bs4/css/dataTables.bootstrap4.css") }}" rel="stylesheet"
        type="text/css" />


    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2-bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />



    <style>
        /* // Colores para las tarjetas widget */
        .card {
            background-color: #fff;
            border-radius: 10px;
            border: none;
            position: relative;
            margin-bottom: 30px;
            box-shadow: 0 0.46875rem 2.1875rem rgba(90, 97, 105, 0.1), 0 0.9375rem 1.40625rem rgba(90, 97, 105, 0.1), 0 0.25rem 0.53125rem rgba(90, 97, 105, 0.12), 0 0.125rem 0.1875rem rgba(90, 97, 105, 0.1);
        }

        .l-bg-blue-dark-card {
            background-color: linear-gradient(to right, #373b44, #4286f4) !important;
            color: #fff;
        }



        .l-bg-cherry {
            background: linear-gradient(to right, #493240, #f09) !important;
            color: #fff;
        }

        .l-bg-blue-dark {
            background: linear-gradient(to right, #373b44, #4286f4) !important;
            color: #fff;
        }

        .l-bg-blued-dark {
            background: linear-gradient(to right, #0d182f, #0d61e9) !important;
            color: #fff;
        }

        .l-bg-green-dark {
            background: linear-gradient(to right, #0a504a, #38ef7d) !important;
            color: #fff;
        }

        .l-bg-orange-dark {
            background: linear-gradient(to right, #a86008, #ffba56) !important;
            color: #fff;
        }

        .l-bg-red-dark {
            background: linear-gradient(to right, #a80d08, #ff6756) !important;
            color: #fff;
        }

        .l-bg-yellow-dark {
            background: linear-gradient(to right, #c6d106, #9b9107) !important;
            color: #fff;
        }

        .card .card-statistic-3 .card-icon-large .fas,
        .card .card-statistic-3 .card-icon-large .far,
        .card .card-statistic-3 .card-icon-large .fab,
        .card .card-statistic-3 .card-icon-large .fal {
            font-size: 110px;
        }

        .card .card-statistic-3 .card-icon {
            text-align: center;
            line-height: 50px;
            margin-left: 15px;
            color: #000;
            position: absolute;
            right: -5px;
            top: 20px;
            opacity: 0.1;
        }

        .l-bg-cyan {
            background: linear-gradient(135deg, #289cf5, #84c0ec) !important;
            color: #fff;
        }

        .l-bg-green {
            background: linear-gradient(135deg, #23bdb8 0%, #43e794 100%) !important;
            color: #fff;
        }

        .l-bg-orange {
            background: linear-gradient(to right, #f9900e, #ffba56) !important;
            color: #fff;
        }

        .l-bg-cyan {
            background: linear-gradient(135deg, #289cf5, #84c0ec) !important;
            color: #fff;
        }



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
            border-radius: 40px 0 40px 40px;
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
            transform: translateY(-5px);
        }

        @media only screen and (max-width: 600px) {
            .btn-flotante {
                font-size: 14px;
                padding: 12px 20px 0 0;
                bottom: 20px;
                right: 20px;
            }
        }

        /*btn flotante1*/
        .btn-flotante-1 {
            font-size: 14px;
            /* Cambiar el tamaño de la tipografia */
            text-transform: uppercase;
            /* Texto en mayusculas */
            font-weight: bold;
            /* Fuente en negrita o bold */
            color: #ffffff;
            /* Color del texto */
            border-radius: 40px 40px 40px 40px;
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

        .btn-flotante-1:hover {
            background-color: #2c2fa5;
            /* Color de fondo al pasar el cursor */
            box-shadow: 0px 15px 20px rgba(0, 0, 0, 0.3);
            transform: translateY(-5px);
        }

        @media only screen and (max-width: 600px) {
            .btn-flotante-1 {
                font-size: 14px;
                padding: 12px 20px 0 0;
                bottom: 20px;
                right: 20px;
            }
        }
    </style>
@endsection


@section('scripts')
    <script src="https://cdn.datatables.net/plug-ins/1.10.20/api/sum().js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
@endsection

@section('contenido')
    <div class="card-body col-mb-12" style="min-height: 543px;">
        <!-- Content Header (Page header) -->

        <div class="row">

            <div class="col-lg-12">
                <div class="card-header  ">
                    <h1 class="card-title" style="font-size: 40px; font-weight:bold;">Hospitalizados Paliativos</h1>

                </div>
            </div><!-- /.col  -->

            @csrf
            <div class="card-body">
                <div class="row col-lg-12">


                    </tr>
                    </td>
                </div>
            </div><!-- /.row -->



        </div>
        <!-- /.content-header -->

        <!-- Main content -->


        <section class="content">

            @include('paliativos.form.form_hospi')

            @include('paliativos.tablas.tablaHospiPaliativos')


        </section>
        <!-- /.content -->

       

    </div>
@endsection

@section('scriptsPlugins')
    <script src="{{ asset("assets/$theme/plugins/datatables/jquery.dataTables.js") }}" type="text/javascript"></script>
    <script src="{{ asset("assets/$theme/plugins/datatables-bs4/js/dataTables.bootstrap4.js") }}" type="text/javascript">
    </script>
    <script src="{{ asset('assets/js/jquery-select2/select2.min.js') }}" type="text/javascript"></script>

    <script>
        $(document).ready(function() {

          
           var fechaini;
           var fechafin;

            fill_datatable_hospipaliativos();

            // Función para pintar tabla de Paliativos

            function fill_datatable_hospipaliativos(fechaini = '', fechafin = '') {
                
               
                var datatable = $('#hospiPaliativos').DataTable({

                    language: idioma_espanol,
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
                        url: "{{ route('hospi1') }}",
                        data: {
                            fechaini: fechaini, fechafin: fechafin, _token:"{{csrf_token()}}"
                        },
                        
                        method: 'POST'
                    },
                    
                    columns: [
                        {
                            data: 'type_document', name: 'bdpaliativos.type_document'
                        },
                        {
                            data: 'document', name: 'bdpaliativos.document'
                        },
                        {
                            data: 'fname', name: 'bdpaliativos.fname'
                        },
                        {
                            data: 'sname', name: 'bdpaliativos.sname'
                        },
                        {
                            data: 'surname', name: 'bdpaliativos.surname'
                        },
                        {
                            data: 'ssurname', name: 'bdpaliativos.ssurname'
                        },
                        {
                            data: 'dx_principal', name:'cosultaspes.dx_principal'
                        },
                        {
                            data: 'dx_relacionado', name:'cosultaspes.dx_relacionado'
                        },
                        {
                            data: 'edad'
                        },
                        {
                            data: 'date_in', name:'bdpaliativos.date_in'
                        },
                        {
                            data: 'state', name:'bdpaliativos.state'
                        },
                        {
                            data: 'dead', name: 'bdpaliativos.dead'
                        },
                        {
                            data: 'date_dead', name: 'bdpaliativos.date_dead'
                        },
                        {
                            data: 'type', name: 'bdpaliativos.type'
                        },
                        {
                            data: 'observacionhospi'
                        },
                        {
                            data: 'creacion',
                        },
                        {
                            data: 'future1', name:'obspaliativos.future1'
                        },
                        {
                            data: 'type_obs' , name:'obspaliativos.type_obs'
                        },
                        {
                            data: 'subtype_obs' , name:'obspaliativos.subtype_obs'
                        },
                        
                        
                    ],
                  /* "createdRow": function(row, data, dataIndex) {
                  if (usuariosession == 7) {
                  $('#ocultarid', row).eq(0).css("display", "block");
                   }else{
                  $('#ocultarid', row).eq(0).css("display", "none");
                  }
                   },*/

                 //},


                    //Botones----------------------------------------------------------------------
                    "dom": '<"row"<"col-xs-1 form-inline"><"col-md-4 form-inline"l><"col-md-5 form-inline"f><"col-md-3 form-inline"B>>rt<"row"<"col-md-8 form-inline"i> <"col-md-4 form-inline"p>>',
                    buttons: [

                        {

                            extend: 'copyHtml5',
                            titleAttr: 'Copy',
                            className: "btn btn-info"


                        },
                        {

                            extend: 'excelHtml5',
                            titleAttr: 'Excel',
                            className: "btn btn-success"


                        },
                        {

                            extend: 'csvHtml5',
                            titleAttr: 'csv',
                            className: "btn btn-warning"


                        },
                        {

                            extend: 'pdfHtml5',
                            titleAttr: 'pdf',
                            className: "btn btn-primary"


                        }
                    ]
                });

            }





            $(document).on('click', '#buscar', function() {
               
                
                
                 fechaini = $('#fechaini').val();
                 fechafin = $('#fechafin').val();
                
                if(fechaini != '' && fechafin != ''){
                
                    $('#hospiPaliativos').DataTable().destroy();
                    
                 fill_datatable_hospipaliativos(fechaini, fechafin); 
                    
                     }else {

                    Swal.fire({
                        icon: 'warning',
                        title: 'Debes seleccionar un rango de fechas',
                        showConfirmButton: false,
                        timer: 1500

                    })

                }
                        
                
               
               

            });

           

        });


        // Variable con array de idioma de datatable
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
        };
    </script>
@endsection
