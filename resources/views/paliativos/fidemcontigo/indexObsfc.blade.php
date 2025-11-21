<!-- @extends("theme.$theme.layout")

@section('titulo')
Procedimientos
@endsection
@section("styles")


<link href="{{asset("assets/$theme/plugins/datatables-bs4/css/dataTables.bootstrap4.css")}}" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/$theme/plugins/icheck-bootstrap/icheck-bootstrap.min.css")}}" rel="stylesheet" type="text/css"/>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.css" rel="stylesheet" type="text/css" />

<link href="{{asset("assets/js/gijgo-combined-1.9.13/css/gijgo.min.css")}}" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/css/select2-bootstrap.min.css")}}" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/css/select2.min.css")}}" rel="stylesheet" type="text/css"/>



@endsection


@section('scripts')
<script src="{{asset("assets/pages/scripts/admin/psicologia/index.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/pages/scripts/admin/usuario/crearuser.js")}}" type="text/javascript"></script>
@endsection

@section('contenido') -->

    <!-- @include('paliativos.fidemcontigo.tablas.fidemcontigoobservaciones')
    @include('paliativos.fidemcontigo.modal.modalfidemcontigoobs')
    @include('paliativos.fidemcontigo.modal.modalindexresumenPsi') -->

<!-- @endsection

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

       // Funcion para pintar con data table
var datatable = $('#psicologica').DataTable({
            language: idioma_espanol,
            lengthMenu: [ [25, 50, 100, 500, -1 ], [25, 50, 100, 500, "Mostrar Todo"] ],
            
            
            

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
                       ]


 });



$('#form-general').on('submit', function(event){
            event.preventDefault();
            var url = '';
            var method = '';
            var text = '';


        if($('#action').val() == 'Add')
        {
            text = "Estás por crear una evolución"
            url = "{{route('guardar_evolucion')}}";
            method = 'post';
        }

        if ($('#surname').val() == '' || $('#fname').val()== '' || $('#type_document').val()== '' || $('#document').val()== '' ||
        $('#date_birth').val() == '' || $('#municipality').val()== '' || $('#address').val()== '' || $('#celular').val()== '' ||
         $('#sex').val()== '' || $('#eapb').val()== '' || $('#reason_consultation').val()== '' ||
         $('#consultation').val()== '' )
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
                data: $('#form-general').serialize(),
                dataType:"json",
                success:function(data){
                            if(data.success == 'ok') {
                            $('#form-general')[0].reset();
                            $('#modal-evolution').modal('hide');
                            $('#psicologica').DataTable().ajax.reload();
                            Swal.fire(
                                {
                                icon: 'success',
                                title: 'diagnostico creado correctamente',
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






     
 //Función para convertir minuscula en mayuscula en el form

    $(".UpperCase").on("keypress", function () {
        $input=$(this);
        setTimeout(function () {
         $input.val($input.val().toUpperCase());
        },50);
       });



    

    

    function ocultar(){

        if($('#municipality').val() != "Otro"){


           $("#municipio_otras").css("display", "none")
           $("#municipioo").prop("required", false);



        }else{

            $("#municipio_otras").css("display", "block");
            $("#municipioo").prop("required", true);


             }

    }

    function ocultar_radio(){
        
        var copago = $('#municipality').val();
        var nivel = $("#sex").val();

         if((copago == 'Contributivo Cotizante' && nivel != '')  || (copago == 'Subsidiado' && nivel == 'Nivel 1') ){
            $("#radio_button").css("display", "none")
            $('#radioPrimary1').prop('checked',false);
            $('#radioPrimary2').prop('checked',false);
            $('#diagnosis').val('NO');

            }else if(copago == 'Contributivo Beneficiario' || (copago == 'Subsidiado' && nivel == 'Nivel 2') || (copago == 'Subsidiado' && nivel == 'Nivel 3') ){

                $("#radio_button").css("display", "block");
                $('#radioPrimary1').prop('checked', true);
                $('#diagnosis').val('SI');

                }

    }

    function radio_button(){

        if($('#radioPrimary1').prop('checked')){
            $('#diagnosis').val('SI');
            

        }else{

            $('#diagnosis').val('NO');
            console.log( $('#diagnosis').val());


            }

    }

    $("#municipality").change(ocultar);
    $("#sex").change(ocultar_radio);
    $("#municipality").change(ocultar_radio);
    $("#radioPrimary1").change(radio_button);
    $("#radioPrimary2").change(radio_button);




    function edad(){

            let hoy = new Date();


            if($('#date_birth').val() != null){

            var nacimiento = new Date($('#date_birth').val());
            let edad = hoy.getFullYear() - nacimiento.getFullYear();
            let meses = hoy.getMonth() - nacimiento.getMonth();

            if (meses < 0 || (meses === 0 && hoy.getDate() < nacimiento.getDate())) {
                            edad--;
                        }
            console.log(edad);

            $('#edad').val(edad);

            }else{

                $('#edad').val();
            }
    }

    $("#date_birth").change(edad);
    
    $("#municipality").change(select_nivel);
    
    function select_nivel(){
        
                var municipality = $("#municipality").val(); // Obtiene el valor seleccionado del municipio
                var $sexo = $("#sex"); // Selecciona el elemento con id "sexo"
                $sexo.empty(); // Limpia las opciones existentes
                
                console.log(municipality + $sexo);
                
                if(municipality == 'Contributivo Cotizante' || municipality == 'Contributivo Beneficiario'){
                    
                   
                        
                   $sexo.append('<option value="">---Seleccione---</option>'+
                                '<option value="Nivel A">Nivel A</option>'+
                                '<option value="Nivel B">Nivel B</option>'+
                                '<option value="Nivel C">Nivel C</option>');
                                
                                
                }else if(municipality == 'Subsidiado'){
                    
                   $sexo.append('<option value="">---Seleccione---</option>'+
                                '<option value="Nivel 1">Nivel 1</option>'+
                                '<option value="Nivel 2">Nivel 2</option>'+
                                '<option value="Nivel 3">Nivel 3</option>');
                    
                }else if(municipality == 'Particular'){
                    
                   $sexo.append('<option value="">---Seleccione---</option>'+
                                '<option value="Particular">Particular</option>');
                                
                    
                }else{
                    
                   $sexo.append('<option value="">---Seleccione---</option>'+
                                '<option value="Otro">Otro</option>');
                }
            
        }

       
           //Función para abrir modal y prevenir el cierre obs fidemcontigo

        function limpiar() {

            $('#documento').val('');
            $('#evo').val('');
            $('#fecha').val('');
            $('#historia').val('');
            $('#apertura').val('');
            $('#cuestionario').val('');
            $('#respuesta').val('');
            $("#profesional").val('');
            $("#apellido").val('');
            $('#apellid').val('');
            $('#nombre').val('');
            $('#nombr').val('');
            $('#eps').val('');
            $('#tel').val('');

        }

        $(document).on('click', '.evolucion', function(){
            limpiar();
            var documento = $(this).attr('data-documento');
            var evo = $(this).attr('data-evo');
            var fecha = $(this).attr('data-fecha');
            var historia = $(this).attr('data-historia');
            var apertura = $(this).attr('data-apertura');
            var cuestionario = $(this).attr('data-cuestionario');
            var respuesta = $(this).attr('data-respuesta');
            var profesional = $(this).attr('data-profesional');
            var apellido = $(this).attr('data-apellido');
            var apellid = $(this).attr('data-apellid');
            var nombre = $(this).attr('data-nombre');
            var nombr = $(this).attr('data-nombr');
            var eps = $(this).attr('data-eps');
            var tel = $(this).attr('data-tel');
            
            
            $('#documento').val(documento);
            $('#evo').val(evo);
            $('#fecha').val(fecha);
            $('#historia').val(historia);
            $('#apertura').val(apertura);
            $('#cuestionario').val(cuestionario);
            $('#respuesta').val(respuesta);
            $("#profesional").val(profesional);
            $("#apellido").val(apellido);
            $('#apellid').val(apellid);
            $('#nombre').val(nombre);
            $('#nombr').val(nombr);
            $('#eps').val(eps);
            $('#tel').val(tel);
            $('#modal-evolution').modal('show');

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
@endsection -->
