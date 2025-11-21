@extends("theme.$theme.layout")

@section('titulo')
Procedimientos Auxiliar
@endsection
@section("styles")

<link href="{{asset("assets/$theme/plugins/datatables-bs4/css/dataTables.bootstrap4.css")}}" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/$theme/plugins/icheck-bootstrap/icheck-bootstrap.min.css")}}" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/$theme/plugins/fontawesome-free/css/all.min.css")}}" rel="stylesheet" type="text/css"/>


<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.css" rel="stylesheet" type="text/css" />

<link href="{{asset("assets/js/gijgo-combined-1.9.13/css/gijgo.min.css")}}" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/css/select2-bootstrap.min.css")}}" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/css/select2.min.css")}}" rel="stylesheet" type="text/css"/>


@endsection


@section('scripts')


<script src="{{asset("assets/pages/scripts/admin/usuario/crearuser.js")}}" type="text/javascript"></script>
@endsection

@section('contenido')
@include('lineaPsicologica.form.formAuxiliarEnfermeria')

@include('lineaPsicologica.tabs.tabsIndexAnalista')

@include('lineaPsicologica.modal.modalindexresumen')

@include('lineaPsicologica.modal.modalindexaddseguimiento')



@endsection

@section("scriptsPlugins")
<script src="{{asset("assets/$theme/plugins/datatables/jquery.dataTables.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/$theme/plugins/datatables-bs4/js/dataTables.bootstrap4.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/$theme/plugins/datatables-responsive/js/dataTables.responsive.min.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/js/jquery-select2/select2.min.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/js/gijgo-combined-1.9.13/js/gijgo.min.js")}}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js"></script>


<script src="https://cdn.datatables.net/plug-ins/1.10.20/api/sum().js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script>

$(document).ready(function(){
    
    
//Variables del form auxiliar Enfermeria y funcion de consulta

   var fechaini;
   var fechafin;
   var profesional;
   var eps;



fill_datatable_tabla();


// Callback para filtrar los datos de la tabla y detalle
$('#buscar').click(function(){

fechaini = $('#fechaini').val();
fechafin = $('#fechafin').val();
profesional = $('#profesional').val();
eps = $('#eps').val();


    if((fechaini != '' && fechafin != '') || profesional != '' ||  eps != ''){

        $('#psicologica').DataTable().destroy();

        fill_datatable_tabla(fechaini, fechafin, profesional, eps);

    }else{

        Swal.fire({
        title: 'Debes digitar fecha inicial y fecha final O profesional o eps',
        icon: 'warning',
        buttons:{
            cancel: "Cerrar"

                }
        })
    }

});

$('#reset').click(function(){

$('#fechaini').val('');
$('#fechafin').val('');
$('#profesional').val('');
$('#eps').val('');
$('#psicologica').DataTable().destroy();
fill_datatable_tabla();

});


// Función que envia el id al controlador y cambia el estado del registro
    $(document).on('click', '.agenda', function () {
    var data = {
            id: $(this).attr('value'),
        _token: $('input[name=_token]').val()
    };

     ajaxRequest('agendado', data);
});

function ajaxRequest (url, data) {
    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function (data) {
            $('#psicologica').DataTable().ajax.reload();
            $('#psicologicaAgendada').DataTable().ajax.reload();
            $('#psicologicaSeguimiento').DataTable().ajax.reload();
            Manteliviano.notificaciones(data.respuesta, data.titulo, data.icon);
        }
    });
}



function  fill_datatable_tabla(fechaini = '', fechafin = '', profesional = '', eps = ''){

      // Funcion para pintar con data table la pestaña de linea psicologica
      var datatable =
    $('#psicologica').DataTable({
    language: idioma_espanol,
    processing: true,
    lengthMenu: [ [25, 50, 100, 500, -1 ], [25, 50, 100, 500, "Mostrar Todo"] ],
    processing: true,
    serverSide: true,
    aaSorting: [[ 20, "desc" ]],
    ajax:{
      url:"{{route('analistapsico1')}}",
      data:{ fechaini:fechaini, fechafin:fechafin, profesional:profesional, eps:eps,  _token:"{{ csrf_token() }}" },
              method: 'post'
          },
    columns: [
      {data:'action',
       orderable: false},
      {data:'id'},
      {data:'surname'},
      {data:'ssurname'},
      {data:'fname'},
      {data:'sname'},
      {data:'type_document'},
      {data:'document'},
      {data:'date_birth'},
      {data:'municipality'},
      {data:'other'},
      {data:'address'},
      {data:'celular'},
      {data:'phone'},
      {data:'email'},
      {data:'sex'},
      {data:'eapb'},
      {data:'reason_consultation'},
      {data:'consultation'},
      {data:'diagnosis'},
      {data:'created_at'}
    ],

     //Botones----------------------------------------------------------------------

     "dom":'<"row"<"col-xs-1 form-inline"><"col-md-4 form-inline"l><"col-md-5 form-inline"f><"col-md-3 form-inline"B>>rt<"row"<"col-md-8 form-inline"i> <"col-md-4 form-inline"p>>',

     buttons: [
                  {

               extend:'copyHtml5',
               titleAttr: 'Copiar Registros',
               title:"Control de horas",
               className: "btn  btn-outline-primary btn-sm"


                  },
                  {

               extend:'excelHtml5',
               titleAttr: 'Exportar Excel',
               title:"Control de horas",
               className: "btn  btn-outline-success btn-sm"


                  },
                   {

               extend:'csvHtml5',
               titleAttr: 'Exportar csv',
               className: "btn  btn-outline-warning btn-sm"

                  },
                  {

               extend:'pdfHtml5',
               titleAttr: 'Exportar pdf',
               className: "btn  btn-outline-secondary btn-sm"


                  }
               ],
               "columnDefs": [
                {

                            "render": function ( data, type, row ) {
                                if (row["consultation"] == 1) {
                                return data +' - Orientación Psicológica';

                            }else{

                                    return data +' - Call center';

                                }

                                },
                                "targets":[18]
                }


               ],

                "createdRow": function(row, data, dataIndex) {
                if (data["consultation"] == 1) {
                    $($(row).find("td")[18]).addClass("btn btn-sm btn-danger rounded-lg");
                }else{
                    $($(row).find("td")[18]).addClass("btn btn-sm btn-dark rounded-lg");
                    }

                }


    });

}
      // Funcion para pintar con data table la pestaña de citas agendadas
      var datatable =
    $('#psicologicaAgendada').DataTable({
    language: idioma_espanol,
    processing: true,
    lengthMenu: [ [25, 50, 100, 500, -1 ], [25, 50, 100, 500, "Mostrar Todo"] ],
    processing: true,
    serverSide: true,
    aaSorting: [[ 21, "desc" ]],
    ajax:{
      url:"{{route('analistapsicoa1')}}",
      data:{  _token:"{{ csrf_token() }}" },
              method: 'post'
          },
    columns: [
      {data:'action',
       orderable: false},
      {data:'actions',
       orderable: false},
      {data:'id'},
      {data:'surname'},
      {data:'ssurname'},
      {data:'fname'},
      {data:'sname'},
      {data:'type_document'},
      {data:'document'},
      {data:'date_birth'},
      {data:'municipality'},
      {data:'other'},
      {data:'address'},
      {data:'celular'},
      {data:'phone'},
      {data:'email'},
      {data:'sex'},
      {data:'eapb'},
      {data:'reason_consultation'},
      {data:'consultation'},
      {data:'diagnosis'},
      {data:'observaciones'},
      {data:'created_at'}
    ],

     //Botones----------------------------------------------------------------------

     "dom":'<"row"<"col-xs-1 form-inline"><"col-md-4 form-inline"l><"col-md-5 form-inline"f><"col-md-3 form-inline"B>>rt<"row"<"col-md-8 form-inline"i> <"col-md-4 form-inline"p>>',

     buttons: [
                  {

               extend:'copyHtml5',
               titleAttr: 'Copiar Registros',
               title:"Control de horas",
               className: "btn  btn-outline-primary btn-sm"


                  },
                  {

               extend:'excelHtml5',
               titleAttr: 'Exportar Excel',
               title:"Control de horas",
               className: "btn  btn-outline-success btn-sm"


                  },
                   {

               extend:'csvHtml5',
               titleAttr: 'Exportar csv',
               className: "btn  btn-outline-warning btn-sm"

                  },
                  {

               extend:'pdfHtml5',
               titleAttr: 'Exportar pdf',
               className: "btn  btn-outline-secondary btn-sm"


                  }
               ],
               "columnDefs": [
                {

                            "render": function ( data, type, row ) {
                                if (row["consultation"] == 1) {
                                return data +' - Orientación Psicológica';

                            }else{

                                    return data +' - Call center';

                                }

                                },
                                "targets":[19]
                }


               ],

                "createdRow": function(row, data, dataIndex) {
                if (data["consultation"] == 1) {
                    $($(row).find("td")[19]).addClass("btn btn-sm btn-danger rounded-lg");
                }else{
                    $($(row).find("td")[19]).addClass("btn btn-sm btn-dark rounded-lg");
                    }

                }


    });

 // Funcion para pintar con data table la pestaña de seguimiento
 var datatable =
    $('#psicologicaSeguimiento').DataTable({
    language: idioma_espanol,
    processing: true,
    lengthMenu: [ [25, 50, 100, 500, -1 ], [25, 50, 100, 500, "Mostrar Todo"] ],
    processing: true,
    serverSide: true,
    aaSorting: [[ 20, "desc" ]],
    ajax:{
      url:"{{route('analistapsicos1')}}",
      data:{  _token:"{{ csrf_token() }}" },
              method: 'post'
          },
    columns: [
      {data:'action',
       orderable: false},
      {data:'id'},
      {data:'surname'},
      {data:'ssurname'},
      {data:'fname'},
      {data:'sname'},
      {data:'type_document'},
      {data:'document'},
      {data:'date_birth'},
      {data:'municipality'},
      {data:'other'},
      {data:'address'},
      {data:'celular'},
      {data:'phone'},
      {data:'email'},
      {data:'sex'},
      {data:'eapb'},
      {data:'reason_consultation'},
      {data:'consultation'},
      {data:'diagnosis'},
      {data:'created_at'}
    ],

     //Botones----------------------------------------------------------------------

     "dom":'<"row"<"col-xs-1 form-inline"><"col-md-4 form-inline"l><"col-md-5 form-inline"f><"col-md-3 form-inline"B>>rt<"row"<"col-md-8 form-inline"i> <"col-md-4 form-inline"p>>',

     buttons: [
                  {

               extend:'copyHtml5',
               titleAttr: 'Copiar Registros',
               title:"Control de horas",
               className: "btn  btn-outline-primary btn-sm"


                  },
                  {

               extend:'excelHtml5',
               titleAttr: 'Exportar Excel',
               title:"Control de horas",
               className: "btn  btn-outline-success btn-sm"


                  },
                   {

               extend:'csvHtml5',
               titleAttr: 'Exportar csv',
               className: "btn  btn-outline-warning btn-sm"

                  },
                  {

               extend:'pdfHtml5',
               titleAttr: 'Exportar pdf',
               className: "btn  btn-outline-secondary btn-sm"


                  }
               ],
               "columnDefs": [
                {

                            "render": function ( data, type, row ) {
                                if (row["consultation"] == 1) {
                                return data +' - Orientación Psicológica';

                            }else{

                                    return data +' - Call center';

                                }

                                },
                                "targets":[18]
                }


               ],

                "createdRow": function(row, data, dataIndex) {
                if (data["consultation"] == 1) {
                    $($(row).find("td")[18]).addClass("btn btn-sm btn-danger rounded-lg");
                }else{
                    $($(row).find("td")[18]).addClass("btn btn-sm btn-dark rounded-lg");
                    }

                }


    });



//Función para abrir modal del detalle de la evolución y muestra las observaciones agregadas
$(document).on('click', '.resumen', function(){
             var idevo = $(this).attr('id');
             $('#names').empty();
             $('#documents').empty();
             $('#evolution').empty();
             $('#observacion').empty();
             $('#names1').empty();
             $('#address').empty();
             $('#date_birth').empty();
             $('#celular').empty();
             $('#sex').empty();
             $('#consultation').empty();
             $('#created_at').empty();
             $('#observaciones_chat').empty();

                
                $.ajax({
                  url:"evolucion/"+idevo+"",
                  dataType:"json",
                  success:function(data){
                
                
                    var usuarios = data[1];
                    console.log(usuarios);
                    console.log(data[0]);
                    $.each(data[0], function(i, items){
                    $('#names').append(items.surname + " " + items.fname);
                    $('#documents').append(items.type_document + "-" + items.document);
                    $('#evolution').append(items.reason_consultation);
                    $('#observacion').append(items.future1);
                    $('#names1').append(items.surname  + " " +  items.fname);
                    $('#address').append("Regimen: "+ items.municipality + " | Dirección: "+items.address + " | Eapb: " +items.eapb);
                    $('#date_birth').append(items.date_birth);
                    $('#celular').append(items.celular);
                    $('#sex').append(items.municipality+"-"+items.sex);
                    $('#created_at').append("Fecha de evolución: "+ " " + items.created_at);
                    $('#consultation').append("Procedimiento del profesional--> "+items.consultation);
                     });
                
                
                    $.each(usuarios, function(i, items1){
                    $.each(data[0], function(i, items){
                    $.each(items.observacionadd, function(i, itemobs){
                    var filtered =  items1.filter( el => el.id == itemobs.user_id);
                    $.each(filtered, function(i, itemsusu){
                    $('#observaciones_chat').append(
                    '<div class="direct-chat-msg">'+
                    '<div class="direct-chat-infos clearfix">'+
                    '<span class="direct-chat-name float-left">'+'Usuario: '+itemsusu.usuario+'</span>'+
                    '<span class="direct-chat-timestamp float-right">'+'Fecha creación: '+itemobs.created_at+'</span>'+
                    '</div>'+
                    '<div class="direct-chat-text">'+'Observación: '+
                        itemobs.addobservacion
                    +'</div>'+
                   '</div>');
                });
                });

    });
    });




  $('.modal-title-resumen').text('Evolución');
  $('#modal-resumen').modal({backdrop: 'static', keyboard: false});
  $('#modal-resumen').modal('show');
  }


}).fail( function( jqXHR, textStatus, errorThrown ) {

if (jqXHR.status === 403) {

Manteliviano.notificaciones('No tienes permisos para realizar esta accion', 'Sistema Ventas', 'warning');

}});


});


//Función para abrir modal y prevenir el cierre de agregar observaciones

$(document).on('click', '.seguimientoadd', function(){
 var idas = $(this).attr('value');

 $('#namesadd').empty();
 $('#documentsadd').empty();
 $('#evo_id').val(idas);
 $('#user_id').val({{Session()->get('usuario_id') ?? ''}});

$.ajax({
    url:"addseguimiento/"+idas+"",
  dataType:"json",
  success:function(data){
    $.each(data.add, function(i, items){
    $('#namesadd').append(items.surname + " " + items.fname);
    $('#documentsadd').append(items.type_document + "-" + items.document);
    $('.modal-title-addseguimiento').text('Add Seguimiento');
    $('#modal-addseguimiento').modal({backdrop: 'static', keyboard: false});
    $('#modal-addseguimiento').modal('show');

  });
  }


}).fail( function( jqXHR, textStatus, errorThrown ) {

if (jqXHR.status === 403) {

Manteliviano.notificaciones('No tienes permisos para realizar esta accion', 'Sistema Ventas', 'warning');

}});


});

//Función para abrir modal y prevenir el cierre
   $(document).on('click', '.observacion', function(){
 var idas = $(this).attr('value');

 $('#namesadd').empty();
 $('#documentsadd').empty();
 $('#evo_id').val(idas);
 $('#user_id').val({{Session()->get('usuario_id') ?? ''}});

$.ajax({
    url:"addseguimiento/"+idas+"",
  dataType:"json",
  success:function(data){
    $.each(data.add, function(i, items){
    $('#namesadd').append(items.surname + " " + items.fname);
    $('#documentsadd').append(items.type_document + "-" + items.document);
    $('.modal-title-addseguimiento').text('Add Seguimiento');
    $('#modal-addseguimiento').modal({backdrop: 'static', keyboard: false});
    $('#modal-addseguimiento').modal('show');

  });
  }


}).fail( function( jqXHR, textStatus, errorThrown ) {

if (jqXHR.status === 403) {

Manteliviano.notificaciones('No tienes permisos para realizar esta accion', 'Sistema Ventas', 'warning');

}});


});

// Función que envían los datos de la obervación al controlador
$('#form-generaladd').on('submit', function(event){
            event.preventDefault();
            var url = '';
            var method = '';
            var text = '';


        if($('#action').val() == 'Add')
        {
            text = "Estás por crear una observación"
            url = "{{route('guardar_observacion')}}";
            method = 'post';
        }

        if ($('#addobservacion').val() == '')
        {
        Swal.fire({
            title: "Debes de rellenar todos los campos del formulario",
            text: "Respuesta Linea Psicologica",
            icon: "warning",
            showCloseButton: true,
            confirmButtonText: 'Aceptar',
            });
         }else{

            Swal.fire({
            title: "¿Estás seguro?",
            text: text,
            icon: "success",
            showCancelButton: true,
            showCloseButton: true,
            confirmButtonText: 'Aceptar',
            }).then((result)=>{
            if(result.value){
            $.ajax({
                url:url,
                method:method,
                data: $('#form-generaladd').serialize(),
                dataType:"json",
                success:function(data){
                            if(data.success == 'ok') {
                            $('#form-generaladd')[0].reset();
                            $('#modal-addseguimiento').modal('hide');
                            $('#psicologica').DataTable().ajax.reload();
                            $('#psicologicaSeguimiento').DataTable().ajax.reload();
                            Swal.fire(
                                {
                                icon: 'success',
                                title: 'Observación agregada correctamente y estado en seguimiento',
                                showConfirmButton: false,
                                timer: 2000
                                }    )}else
                            if(data.success == 'ok1') {
                            $('#form-generaladd')[0].reset();
                            $('#modal-addseguimiento').modal('hide');
                            $('#psicologicaSeguimiento').DataTable().ajax.reload();
                            Swal.fire(
                                {
                                icon: 'success',
                                title: 'Observación agregada correctamente',
                                showConfirmButton: false,
                                timer: 2000
                                }    )}
                            else if(data.errors != null) {
                            Swal.fire(
                                {
                                icon: 'error',
                                title: data.errors,
                                showConfirmButton: false,
                                timer: 3000
                                })
                            }
                        }

                  });
                            }
                 });

                }

      });









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
                }
</script>
@endsection
