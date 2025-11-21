@extends("theme.$theme.layout")

@section('titulo')
    Control de turnos
@endsection
@section("styles")
<link href="{{asset("assets/$theme/plugins/datatables-bs4/css/dataTables.bootstrap4.css")}}" rel="stylesheet" type="text/css"/>
<link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/css/select2-bootstrap.min.css")}}" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/css/select2.min.css")}}" rel="stylesheet" type="text/css"/>


@endsection


@section('scripts')

@endsection

@section('contenido')
<div class="row">
  <div class="col-lg-12">
      <div class="card card-info">
      <div class="card-header with-border p-2">
        <h3 class="card-title">Agregar Cargo</h3>

        <div class="card-body">
            <form id="form-general" class="form-horizontal">
            @csrf
            @include('nomina.cargos.form-registro-cargo')
            @include('nomina.cargos.boton-registrar-cargo')
            </form>
        </div>
      </div>
    <div class="card-body table-responsive p-2">

    <table id="registro" class="table table-hover display responsive" cellspacing="0" width="100%">
     <thead>
      <tr>
            <th>Accion</th>
            <th>ID</th>
            <th>Cargo</th>
            <th>Salario</th>
            <th>Valor Hora</th>
            <th>Valor Paciente Atendido</th>
            <th>Rodamiento</th>
            <th>Fecha Creacion</th>


      </tr>
      </thead>
      <tbody>

      </tbody>
    </table>
  </div>
  <!-- /.card-body -->
</div>
</div>
</div>






@endsection



@section("scriptsPlugins")
<script src="{{asset("assets/$theme/plugins/datatables/jquery.dataTables.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/$theme/plugins/datatables-bs4/js/dataTables.bootstrap4.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/$theme/plugins/datatables-responsive/js/dataTables.responsive.min.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/js/jquery-select2/select2.min.js")}}" type="text/javascript"></script>
<script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js"></script>


<script src="https://cdn.datatables.net/plug-ins/1.10.20/api/sum().js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>

<script>

$(document).ready(function(){

// Funcion para pintar con data table
  var datatable =
        $('#registro').DataTable({
        language: idioma_espanol,
        processing: true,
        lengthMenu: [ [25, 50, 100, 500, -1 ], [25, 50, 100, 500, "Mostrar Todo"] ],
        processing: true,
        serverSide: true,
        ajax:{
          url:"{{ route('position')}}",
              },
        columns: [
          {data:'action',
           orderable: false},
          {data:'id'},
          {data:'position'},
          {data:'salary'},
          {data:'value_hour'},
          {data:'value_patient_attended'},
          {data:'value_salary_add'},
          {data:'created_at',
           name:'created_at'}
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

  if($('#action').val() == 'Add')
  {
    url = "{{route('guardar_cargo')}}";
    method = 'post';
    text = "Estás por crear un Cargo";
  }

  if($('#action').val() == 'Edit')
  {
    var updateid = $('#hidden_id').val();
    url = "/turno/"+updateid;
    method = 'put';
    text = "Estás por actualizar un Cargo";
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

                    html = '<div class="alert alert-danger alert-dismissible" data-auto-dismiss="3000">'
                      '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
                        '<h5><i class="icon fas fa-check"></i> Mensaje Ventas</h5>';

                    for (var count = 0; count < data.errors.length; count++)
                    {
                      html += '<p>' + data.errors[count]+'<p>';
                    }
                    html += '</div>';
                    }
                    if(data.success == 'ok') {
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


                    }
                    $('#form_result').html(html)
              }


           });
          }
        });


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
