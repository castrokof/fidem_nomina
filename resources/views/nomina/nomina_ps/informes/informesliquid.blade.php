@extends("theme.$theme.layout")

@section('titulo')
    Informes
@endsection

@section("styles")
<link href="{{asset("assets/$theme/plugins/datatables-bs4/css/dataTables.bootstrap4.css")}}" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="{{asset("assets/css/custominfo.css")}}">
<link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/select2-bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />


<style>



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





<div class="content-wrapper col-mb-12" style="min-height: 543px;" >
    <!-- Content Header (Page header) -->
<div class="row">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-12">
          <div class="col-sm-12">
            <h1 class="m-0 text-dark">Informes de liquidacion</h1>
          </div><!-- /.col -->

          @csrf
          <div class="card-body">
          <div class="row col-lg-12">

            @include('nomina.nomina_fijos.informes.form')

          </tr>
          </td>
          </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
  </div>
</div>
    <!-- /.content-header -->

    <!-- Main content -->
<section class="content">




      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-2 col-6" id="detalle">
          </div>
          <div class="col-lg-2 col-6" id="detalle3">
          </div>
          <div class="col-lg-2 col-6" id="detalle4">
          </div>
          <div class="col-lg-2 col-6" id="detalle1">
          </div>
          <div class="col-lg-2 col-6" id="detalle5">
          </div>
          <div class="col-lg-2 col-6" id="detalle2">
          </div>
        </div>
      </div>


      <div class="row">
        <div class="col-12">
          <div class="card shadow-lg p-3 mb-5 card-success card-tabs">
            <div class="card-header p-0 pt-1">
              <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active"
                  id="custom-tabs-one-datos-del-pago-tab"
                  data-toggle="pill"
                  href="#custom-tabs-one-datos-del-pago"
                  role="tab"
                  aria-controls="custom-tabs-one-datos-del-pago"
                  aria-selected="false">Totales de nómina</a>
                </li>
              </ul>
            </div>


              <div class="tab-content" id="custom-tabs-one-tabContent">
                <div class="tab-pane fade active show" id="custom-tabs-one-datos-del-pago" role="tabpanel" aria-labelledby="custom-tabs-one-datos-del-pago-tab">


                      @csrf
                      @include('nomina.nomina_fijos.informes.tablenominafijosrevision')

                </div>
               </div>

            <!-- /.card -->
          </div>
        </div>
        <button type="button" class="btn-flotante tooltipsC" id="liquidar" title="Liquidar turno"><i class="fas fa-save fa-2x"></i></button>

      </div>



<!-- Modal que carga los form, tablas de las novedades -->
@include('nomina.novedades.modal.modalProcDetalleIndex')



</section>
    <!-- /.content -->

</div>
</div>



@endsection

@section("scriptsPlugins")
<script src="{{asset("assets/$theme/plugins/datatables/jquery.dataTables.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/$theme/plugins/datatables-bs4/js/dataTables.bootstrap4.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/js/jquery-select2/select2.min.js")}}" type="text/javascript"></script>

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
            
            //Consulta de datos de la tabla lista-detalle
            $("#quincena").select2({
                language: "es",
                theme: "bootstrap4",
                placeholder: 'Seleccione una quincena',
                allowClear: true,
                ajax: {
                    url: "{{ route('select_quincena') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term
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
            
            
                        //Consulta de datos de la tabla lista-detalle
            $("#empleado").select2({
                language: "es",
                theme: "bootstrap4",
                placeholder: 'Seleccione un empleado',
                allowClear: true,
                ajax: {
                    url: "{{ route('select_emp') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term
                               };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.array[0], function(datas) {

                                return {

                                    text: datas.documento + '---' + datas.pnombre + datas.apellido ,
                                    id: datas.documento

                                }
                            })
                        };
                    },
                    cache: true
                }
            });
            
            

    $(document).on('click', '.listasDetalleNove', function() {
        $('#modal-novedades-detalle').modal('show');

 var idn = $(this).attr('id');
 var idu = $(this).attr('value');

if (idn != '') {
    $('#tnovedades').DataTable().destroy();
    fill_tnovedades(idn,idu);
}
// if (idn != '' && idu != '') {
//     $('#tcuentasdecobro').DataTable().destroy();
//     fill_tcuentasdecobro(idn,idu);
// }


$.ajax({
    url: "editar_novedades/" + idu + "",
    dataType: "json",
    success: function(result) {
        $.each(result, function(i, items) {
            $('#title-novedades-detalle').text(items.nombre);
            $('#modal-novedades-detalle').modal({

                backdrop: 'static',
                keyboard: false
            });
            $('#modal-novedaes-detalle').modal('show');

        });
    }

}).fail(function(jqXHR, textStatus, errorThrown) {

    if (jqXHR.status === 403) {

        Manteliviano.notificaciones('No tienes permisos para realizar esta accion',
            'Sistema Historias Clínicas', 'warning');
    }
});

});

/*fill_tnovedades();

// Función para filtrar cargar los datos en la tabla de novedades

function fill_tnovedades(idn = '' )
         {
          var tnovedades = $('#tnovedades').DataTable
          ({
              language: idioma_espanol,
              lengthMenu: [ -1],
              processing: true,
              serverSide: true,
              aaSorting: [[ 2, "asc" ]],


          ajax:{
               url:"{{route('novedades')}}",
                data:{nove_id:idn}
              },
              columns: [
          {data:'action',
           orderable: false},
          {data:'id'},
          {data:'type_nove'},
          {data:'road', render: $.fn.dataTable.render.number( ',', '.' )},
          {data:'hours'},
          {data:'total_pac'},
          {data:'nove_observacion'},
          {data:'created_at'}

        ],
        "columnDefs": [


                      ],

              //Botones----------------------------------------------------------------------
        "dom":'Brtip',
               buttons: [

                   {

               extend:'copyHtml5',
               titleAttr: 'Copy',
               className: "btn btn-info"


                  },
                  {

               extend:'excelHtml5',
               titleAttr: 'Excel',
               className: "btn btn-success"


                  },
                   {

               extend:'csvHtml5',
               titleAttr: 'csv',
               className: "btn btn-warning"


                  },
                  {

               extend:'pdfHtml5',
               titleAttr: 'pdf',
               className: "btn btn-primary"


                  }
               ],
            //    "columnDefs": [

            //                         {
            //                         "render": function ( data, type, row ) {
            //                                 return data +' '+row["papellido"]+' '+row["sapellido"];
            //                             },
            //                             "targets":[3]
            //                         },
            //                         { "visible": false,  "targets": [4] },
            //                         { "visible": false,  "targets": [5] },
            //                         { "visible": false,  "targets": [6] },







            //                         ],

            //      "createdRow": function(row, data, dataIndex) {
            //         if (data["type_salary"] == 'FIJO-QUINCENAL') {
            //             $($(row).find("td")[5]).addClass("btn btn-sm bg-info rounded-lg");
            //         }else{
            //             $($(row).find("td")[5]).addClass("btn btn-sm bg-dark rounded-lg");
            //             }
            //         if (data["total_pagar"] > 1) {
            //         $($(row).find("td")[12]).addClass("btn btn-sm bg-success rounded-lg");
            //       }
            //       if (data["parafiscales"] > 1) {
            //             $($(row).find("td")[8]).addClass("btn btn-sm bg-danger rounded-lg ");
            //         }

            //      }

             });
 }*/


// variables globales
  
  var quincena = '';
  var usuario = '';


// Btn flotante
    $('.botonF1').hover(function(){

    })



    $("#selectall").on('click', function() {
    $(".case").prop("checked", this.checked);
    });






    fill_datatable_tabla();



// Callback para filtrar los datos de la tabla y detalle
    $('#buscar').click(function(){

    quincena = $('#quincena').val();
    usuario = $('#usuario').val();

    $("#selectall").prop("checked", false);

        if(quincena != '' || usuario != ''){

            $('#tturnos').DataTable().destroy();

            fill_datatable_tabla(quincena, usuario);

        }else{

            Swal.fire({
            title: 'Debes digitar fecha inicial, fecha final y usuario',
            icon: 'warning',
            buttons:{
                cancel: "Cerrar"

                    }
            })
        }

    });


// Función para filtrar cargar los datos en la tabla

 function fill_datatable_tabla(quincena = '', usuario = '', ips = '' )
         {
          var datatable = $('#tturnos').DataTable
          ({
              language: idioma_espanol,
              lengthMenu: [ -1],
              processing: true,
              serverSide: true,
              aaSorting: [[ 2, "asc" ]],


          ajax:{
               url:"{{route('liquidinfo')}}",
                data:{quincena:quincena,ips:ips, usuario:usuario }
              },
              columns: [
          {data:'action',
           orderable: false},
          {data:'id'},
          {data:'ips'},
          {data:'pnombre'},
          {data:'snombre'},
          {data:'papellido'},
          {data:'sapellido'},
          {data:'quincena'},
          {data:'type_salary'},
          {data:'salary', render: $.fn.dataTable.render.number( ',', '.' )},
          {data:'value_transporte', render: $.fn.dataTable.render.number( ',', '.' )},
          {data:'rodamiento', render: $.fn.dataTable.render.number( ',', '.' )},
          {data:'parafiscales', render: $.fn.dataTable.render.number( ',', '.' )},
          {data:'retencion', render: $.fn.dataTable.render.number( ',', '.' )},
          {data:'horas', render: $.fn.dataTable.render.number( ',', '.' )},
          {data:'valor_hora', render: $.fn.dataTable.render.number( ',', '.' )},
          {data:'total', render: $.fn.dataTable.render.number( ',', '.' )},
          {data:'total_pagar', render: $.fn.dataTable.render.number( ',', '.' )}

        ],
        "columnDefs": [


                      ],

              //Botones----------------------------------------------------------------------
        "dom":'Brtip',
               buttons: [

                   {

               extend:'copyHtml5',
               titleAttr: 'Copy',
               className: "btn btn-info"


                  },
                  {

               extend:'excelHtml5',
               titleAttr: 'Excel',
               className: "btn btn-success"


                  },
                   {

               extend:'csvHtml5',
               titleAttr: 'csv',
               className: "btn btn-warning"


                  },
                  {

               extend:'pdfHtml5',
               titleAttr: 'pdf',
               className: "btn btn-primary"


                  }
               ],
               "columnDefs": [

                                    {
                                    "render": function ( data, type, row ) {
                                            return data +' '+row["papellido"]+' '+row["sapellido"];
                                        },
                                        "targets":[3]
                                    },
                                    { "visible": false,  "targets": [4] },
                                    { "visible": false,  "targets": [5] },
                                    { "visible": false,  "targets": [6] },







                                    ],

                 "createdRow": function(row, data, dataIndex) {
                    if (data["type_salary"] == 'FIJO-QUINCENAL-MENSUAL') {
                        $($(row).find("td")[5]).addClass("btn btn-sm bg-info rounded-lg");
                    }else{
                        $($(row).find("td")[5]).addClass("btn btn-sm bg-dark rounded-lg");
                        }
                    if (data["total_pagar"] > 1) {
                    $($(row).find("td")[14]).addClass("btn btn-sm bg-success rounded-lg");
                  }
                  if (data["parafiscales"] > 1) {
                        $($(row).find("td")[9]).addClass("btn btn-sm bg-danger rounded-lg ");
                    }
                    if (data["retencion"] >= 0) {
                        $($(row).find("td")[11]).addClass("btn btn-sm bg-warning rounded-lg ");
                    }

                 },
                 "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
  
          
            valorpo = api
                .column(17, { page: 'current'})
                .data()
                .reduce(function (a, b) {
                    return parseInt(a) + parseInt(b);
                }, 0);

            
            //$(api.column(17).footer()).html(valorpo)
            
             $(api.column(17).footer()).html(
                '$' + $.fn.dataTable.render.number(',', '.', 0, '').display(valorpo)
            );
                  

          },

             });
 }




 });
   var idioma_espanol =
                 {
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla =(",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                },
                "buttons": {
                    "copy": "Copiar",
                    "colvis": "Visibilidad"
                }
                } ;



  </script>



@endsection
