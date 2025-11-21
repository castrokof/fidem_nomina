@extends("theme.$theme.layout")

@section('titulo')
    Control de turnos
@endsection
@section("styles")
<link href="{{asset("assets/$theme/plugins/datatables-bs4/css/dataTables.bootstrap4.css")}}" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/js/gijgo-combined-1.9.13/css/gijgo.min.css")}}" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/css/select2-bootstrap.min.css")}}" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/css/select2.min.css")}}" rel="stylesheet" type="text/css"/>


@endsection


@section('scripts')
<script src="{{asset("assets/pages/scripts/admin/hours/crearuser.js")}}" type="text/javascript"></script>
@endsection

@section('contenido')
<div class="row">
  <div class="col-lg-12">
      <div class="card bg-info" id="form-card">

      <div class="card-header with-border">
        <h3 class="card-title">Registrar Turnos</h3>
      </div>
        <form  id="form-general" class="form-horizontal" method="POST">
            @csrf
        <div class="card-body">
            @include('nomina.control_turnos.form-registro')
            @include('nomina.control_turnos.boton-registrar-turno')
        </div>
       </form>
      </div>
    <div class="card-body table-responsive p-2">
    <table id="registro" class="table table-hover display responsive" cellspacing="0" width="100%">
     <thead>
      <tr>
            <th>action</th>
            <th>ID</th>
            <th>Fecha y Hora Ingreso</th>
            <th>Fecha y Hora Salida</th>
            <th>Horas Laboradas</th>
            <th>Jornada</th>
            <th>Quincena</th>
            <th>Observación</th>
            <th>Fecha y hora de registro</th>


      </tr>
      </thead>
      <tbody>

      </tbody>
    </table>
  </div>
  <!-- /.card-body -->
</div>
</div>







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

        $('#date_hour_initial_turn').datetimepicker(
          {
          footer: true,
          modal: true,
          format: 'yyyy-mm-dd HH:MM'

          });



        $('#date_hour_end_turn').datetimepicker(
          {
          footer: true,
          modal: true,
          format: 'yyyy-mm-dd HH:MM'

          });



// Calcular jornada
function jornada(){
    var fechaini = new Date($('#date_hour_initial_turn').val());
    var fechafin = new Date($('#date_hour_end_turn').val());

    var horaini = fechaini.getHours();
    var horafin = fechafin.getHours();

    var diaini = fechaini.getDate();
    var diafin = fechafin.getDate();
    var diac = diafin - diaini;

    console.log(horaini);
   if(horaini >= 0 && horafin <=13 && diac == 0){
    var working_type = "Mañana";
   }else if(horaini > 12 && horafin <=23 && diac == 0){

    var working_type = "Tarde";
   }else if(horaini >= 0 && horafin <=19 && diac == 0){

    var working_type = "Diurno";

   }else if(horaini > 12 && horafin < 12 && diac == 1){

    var working_type = "Nocturno";
   }


   $("#working_type").val(working_type);

}

$("#date_hour_initial_turn").change(jornada);
$("#date_hour_end_turn").change(jornada);





// Calcular quincena
function quincena(){
    var fecha = new Date($('#date_hour_initial_turn').val());

    var dia = fecha.getDate();
    var mes = parseFloat(fecha.getMonth());
    var año = fecha.getFullYear();
    var rango = "";

    var meses = ['ENE','FEB','MAR','ABR','MAY','JUN','JUL','AGO','SEP','OCT','NOV', 'DIC'];

    if(dia >= 1 && dia <= 15){

    rango = '1Q';

    }else{

    rango = '2Q';

    };

   var mesd = meses[mes];

   var quincena = rango.concat(mesd).concat(año);

   $("#quincena").val(quincena);

}

$("#date_hour_initial_turn").change(quincena);




// Funcion para pintar con data table
  var datatable =
        $('#registro').DataTable({
        language: idioma_espanol,
        processing: true,
        lengthMenu: [ [25, 50, 100, 500, -1 ], [25, 50, 100, 500, "Mostrar Todo"] ],
        processing: true,
        serverSide: true,
        ajax:{
          url:"{{route('hours')}}",
              },
        columns: [
          {data:'action',
           orderable: false},
          {data:'id'},
          {data:'date_hour_initial_turn'},
          {data:'date_hour_end_turn'},
          {data:'hours'},
          {data:'working_type'},
          {data:'quincena'},
          {data:'observation'},
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
                   //text: '<i class="fas fa-file-excel"></i>'

                      },
                      {

                   extend:'pdfHtml5',
                   titleAttr: 'Exportar pdf',
                   className: "btn  btn-outline-secondary btn-sm"


                      }
                   ],




        });

//funciona para guardar el formulario

$('#form-general').on('submit', function(event){
    event.preventDefault();
    var url = '';
    var icon = '';

if($('#date_hour_initial_turn').val() == '' ||  $('#date_hour_end_turn').val() == '' ||  $('#working_type').val() == ''){

    Swal.fire({
                  icon: 'warning',
                  title: 'Debes rellenar los campos obligatorios',
                  showConfirmButton: true,
                  timer: 1500
            });

 }else if($('#date_hour_initial_turn').val() > $('#date_hour_end_turn').val()){

Swal.fire({
              icon: 'error',
              title: 'La fecha y hora inicial debe ser menor que la fecha y hora final',
              showConfirmButton: true,
              timer: 1500
        });

}else{

    if($('#action').val() == 'Add')
  {
    url = "{{route('guardar_turno')}}";
    method = 'post';
    text = "Estás por registrar un turno";
    icon = "warning";
  }

  if($('#action').val() == 'Edit')
  {
    var updateid = $('#hidden_id').val();
    url = "/hoursxuser/"+updateid;
    method = 'put';
    text = "Estás por actualizar un turno";
    icon = "danger";
  }
    Swal.fire({
     title: "¿Estás seguro?",
     text: text,
     icon: "warning",
     showCancelButton: true,
     showCloseButton: true,
     confirmButtonText: 'Aceptar',
     }).then((result)=>{
    if(result.value){
    $.ajax({
           url:url,
           method:method,
           data:$(this).serialize(),
           dataType:"json",
           success:function(data){
              var html = '';
                    if(data.errors){
                    html =
                    '<div class="alert alert-danger alert-dismissible" data-auto-dismiss="3000">'+
                      '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+
                        '<h5><i class="icon fas fa-check"></i> Mensaje coll nomina</h5>';

                    for (var count = 0; count < data.errors.length; count++)
                    {
                      html += '<p>' + data.errors[count]+'<p>';
                    }
                    html += '</div>';
                    }
                    else if(data.success == 'ok') {
                      $('#form-general')[0].reset();
                      $('#registro').DataTable().ajax.reload();
                      Swal.fire(
                        {
                          icon: 'success',
                          title: 'Turno registrado correctamente',
                          showConfirmButton: true,
                          timer: 1500
                        }
                      )

                    }else if(data.success == 'ok1'){
                      $('#form-general')[0].reset();
                      $('#registro').DataTable().ajax.reload();
                      Swal.fire(
                        {
                          icon: 'success',
                          title: 'Turno actualizado correctamente',
                          showConfirmButton: true,
                          timer: 1500
                        }
                      )


                    } else if(data.success == 'repeat') {
                     $('#registro').DataTable().ajax.reload();
                     Swal.fire(
                        {
                          icon: 'error',
                          title: 'No puedes registrar 2 turnos con la misma fecha',
                          showConfirmButton: true,
                          timer: 1500
                        }
                      )

                    }
                    $('#form_result').html(html)
              }


           });
          }
        });

    }
  });

//Edición de turnos

$(document).on('click', '.edit', function(){
    var idh = $(this).attr('id');

  $.ajax({
    url:"/hoursxuser/"+idh+"/editar",
    dataType:"json",
    success:function(data){
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

$('#form-general').on('reset', function(event){
      $('.card-title').text('Registrar Turnos');
      $('#form-card').removeClass('card bg-warning');
      $('#form-card').addClass('card bg-info');
      $('#action_button').val('Guardar').removeClass('btn-danger')
      $('#action_button').addClass('btn-success')
      $('#cancelar_button').css("display", "none")
      $('#action').val('Add');


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
