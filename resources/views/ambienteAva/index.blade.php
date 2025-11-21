@extends("theme.$theme.layout")

@section('titulo')
AVA
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


<style>

.img-1{
  width: 100%;
  height: 250px;
  background-color: #a83cd3a5; url(../img/banner_ava.jpeg);
}

</style>
@endsection


@section('scripts')

@endsection

@section('contenido')

@include('ambienteAva.tabs.tabsIndexAva')

@include('lineaPsicologica.modal.modalindexresumen')



@endsection

@section("scriptsPlugins")
<script src="{{asset("assets/$theme/plugins/datatables/jquery.dataTables.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/$theme/plugins/datatables-bs4/js/dataTables.bootstrap4.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/$theme/plugins/datatables-responsive/js/dataTables.responsive.min.js")}}" type="text/javascript"></script>

<script src="{{asset("assets/js/gijgo-combined-1.9.13/js/gijgo.min.js")}}" type="text/javascript"></script>




<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script>

$(document).ready(function(){

var porcentaje = 0;
var porcentaje1 = 0;
var porcentaje2 = 0;
var porcentaje3 = 0;

function depresion(){

    var contador;

    if ($(this).is(':checked') ) {
        $('#depresion_porcentaje').empty();
        contador = parseInt($(this).val());
        porcentaje = contador +  porcentaje;

        $("#progress_bar_1").css({'width': porcentaje+'%'})
        $('#depresion_porcentaje').append(porcentaje+'%');

        }else{
        $('#depresion_porcentaje').empty();
        contador = parseInt($(this).val());
        porcentaje = porcentaje - contador;
        $("#progress_bar_1").css({'width': porcentaje+'%'})
        $('#depresion_porcentaje').append(porcentaje+'%');

      }


}

$('.check1').change(depresion);




function anorexia(){

var contador;

if ($(this).is(':checked') ) {
    $('#anorexia_porcentaje').empty();
    contador = parseInt($(this).val());
    porcentaje1 = contador +  porcentaje1;
    console.log(porcentaje1);
    $("#progress_bar_2").css({'width': porcentaje1+'%'})
    $('#anorexia_porcentaje').append(porcentaje1+'%');

    }else{
    $('#anorexia_porcentaje').empty();
    porcentaje1 = porcentaje1 - 25;
    $("#progress_bar_2").css({'width': porcentaje1+'%'})
    $('#anorexia_porcentaje').append(porcentaje1+'%');

  }


}

$('.check2').change(anorexia);



function bulimia(){

var contador;

if ($(this).is(':checked')) {
    $('#bulimia_porcentaje').empty();
    contador = parseInt($(this).val());
    porcentaje2 = contador +  porcentaje2;
    console.log(porcentaje2);
    $("#progress_bar_3").css({'width': porcentaje2+'%'})
    $('#bulimia_porcentaje').append(porcentaje2+'%');

    }else{
    $('#bulimia_porcentaje').empty();
    porcentaje2 = porcentaje2 - 25;
    $("#progress_bar_3").css({'width': porcentaje2+'%'})
    $('#bulimia_porcentaje').append(porcentaje2+'%');

  }

}

$('.check3').change(bulimia);



function matoneo(){

var contador;

if ($(this).is(':checked') ) {
    $('#matoneo_porcentaje').empty();
    contador = parseInt($(this).val());
    porcentaje3 = contador +  porcentaje3;
    console.log(porcentaje3);
    $("#progress_bar_4").css({'width': porcentaje3+'%'})
    $('#matoneo_porcentaje').append(porcentaje3+'%');

    }else{
    $('#matoneo_porcentaje').empty();
    porcentaje3 = porcentaje3 - 25;
    $("#progress_bar_4").css({'width': porcentaje3+'%'})
    $('#matoneo_porcentaje').append(porcentaje3+'%');

  }


}

$('.check4').change(matoneo);


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
