@extends("theme.$theme.layout")

@section('titulo')
Encuestas fisiatria
@endsection
@section("styles")


<link href="{{asset("assets/$theme/plugins/datatables-bs4/css/dataTables.bootstrap4.css")}}" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/$theme/plugins/icheck-bootstrap/icheck-bootstrap.min.css")}}" rel="stylesheet" type="text/css"/>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.css" rel="stylesheet" type="text/css" />

<link href="{{asset("assets/js/gijgo-combined-1.9.13/css/gijgo.min.css")}}" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/css/select2-bootstrap.min.css")}}" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/css/select2.min.css")}}" rel="stylesheet" type="text/css"/>

<style>
        .fidem-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .fidem-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0;
        }
        
        .fidem-subtitle {
            font-size: 0.9rem;
            opacity: 0.9;
            margin: 5px 0 0 0;
        }
</style>

@endsection


@section('scripts')
<script src="{{asset("assets/pages/scripts/admin/psicologia/index.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/pages/scripts/admin/usuario/crearuser.js")}}" type="text/javascript"></script>
@endsection

@section('contenido')

    @include('fisiatria.tablas.tablaIndexEvolucion')
    @include('fisiatria.modal.modalEvolucion')
    @include('fisiatria.modal.modalindexresumenEnc')

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

    // Funcion para pintar con data table
    var datatable = $('#fisiatria').DataTable({
        language: idioma_espanol,
        processing: true,
        lengthMenu: [ [25, 50, 100, 500, -1 ], [25, 50, 100, 500, "Mostrar Todo"] ],
        processing: true,
        serverSide: true,
        aaSorting: [[ 21, "desc" ]],
        ajax:{
          url:"{{route('fisiatria1')}}",
          data:{  _token:"{{ csrf_token() }}" },
          method: 'post'
        },
        columns: [
          {data:'action', order: false, searchable: false},
          {data:'id'},
          {data:'surname'},
          {data:'ssurname'},
          {data:'fname'},
          {data:'sname'},
          {data:'type_document'},
          {data:'document'},
          {data:'eapb'},
          {data:'fecha_solicitud'},
          {data:'profesional'},
          {data:'dx'},
          {data:'dispositivo_silla'},
          {data:'dispositivo_apoyo'},
          {data:'other'},
          {data:'solicitud_dispositivo'},
          {data:'antecedentes_dx_cancer'},
          {data:'antecedentes_toxina_espasticidad'},
          {data:'camilla_ambulancia'},
          {data:'tipo_solicitud'},
          {data:'reason_consultation'},
          {data:'observacion'},
          {data:'created_at'}
        ],

        //Botones
        "dom":'<"row"<"col-xs-1 form-inline"><"col-md-4 form-inline"l><"col-md-5 form-inline"f><"col-md-3 form-inline"B>>rt<"row"<"col-md-8 form-inline"i> <"col-md-4 form-inline"p>>',

        buttons: [
            {
                extend:'copyHtml5',
                titleAttr: 'Copiar Registros',
                title:"Control de Fisiatría",
                className: "btn btn-outline-primary btn-sm"
            },
            {
                extend:'excelHtml5',
                titleAttr: 'Exportar Excel',
                title:"Control de Fisiatría",
                className: "btn btn-outline-success btn-sm"
            },
            {
                extend:'csvHtml5',
                titleAttr: 'Exportar csv',
                className: "btn btn-outline-warning btn-sm"
            },
            {
                extend:'pdfHtml5',
                titleAttr: 'Exportar pdf',
                className: "btn btn-outline-secondary btn-sm"
            }
        ],
        "columnDefs": [
            {
                'targets': [0],
                'visible': true,
                'searchable': false
            }
        ]
    });

    // Manejar el mostrar/ocultar del campo "Otro" dispositivo
    $('#otro_dispositivo').change(function(){
        if(this.checked){
            $("#otro_dis").show();
            $("#other").prop("required", true);
        } else {
            $("#otro_dis").hide();
            $("#other").prop("required", false);
            $("#other").val('');
        }
    });

    // Manejar checkboxes mutuamente excluyentes para dispositivos de apoyo
    $('.dispositivo-apoyo').change(function(){
        if($(this).attr('id') === 'ninguno_anterior' && this.checked){
            $('.dispositivo-apoyo').not(this).prop('checked', false);
            $("#otro_dis").hide();
            $("#other").val('');
        } else if(this.checked && $(this).attr('id') !== 'ninguno_anterior'){
            $('#ninguno_anterior').prop('checked', false);
        }
    });

    // Función para procesar array de dispositivos de apoyo antes del envío
    function procesarDispositivoApoyo(){
        var dispositivos = [];
        $('.dispositivo-apoyo:checked').each(function(){
            dispositivos.push($(this).val());
        });
        return dispositivos.join(',');
    }

    // Submit del formulario
    $('#form-general').on('submit', function(event){
        event.preventDefault();
        var url = '';
        var method = '';
        var text = '';

        if($('#action').val() == 'Add'){
            text = "Estás por crear una solicitud de fisiatría"
            url = "{{route('guardar_encuesta')}}",
            method = 'post';
        }

        // Validación de campos requeridos
        if($('#surname').val() == '' || $('#fname').val()== '' || $('#type_document').val()== '' || 
           $('#document').val()== '' || $('#fecha_solicitud').val() == ''  || 
           $('#dx').val()== '' || $('#tipo_solicitud').val()== '' || $('#reason_consultation').val()== '' ||
           $('#observacion').val()== '' )
        {
            Swal.fire({
                title: "Debes de rellenar todos los campos obligatorios del formulario",
                text: "Sistema de Fisiatría",
                icon: "warning",
                showCloseButton: true,
                confirmButtonText: 'Aceptar',
            });
        } else {
            // Procesar dispositivos de apoyo
            var dispositivoApoyo = procesarDispositivoApoyo();
            
            // Obtener valores de checkboxes individuales
            var antecedenteCancer = $('#cancer_si').is(':checked') ? 'SI' : 'NO';
            var antecedenteToxina = $('#toxina_si').is(':checked') ? 'SI' : 'NO';
            var camillaAmbulancia = $('#camilla_si').is(':checked') ? 'SI' : 'NO';

            Swal.fire({
                title: "¿Estás seguro?",
                text: text,
                icon: "success",
                showCancelButton: true,
                showCloseButton: true,
                confirmButtonText: 'Aceptar',
            }).then((result)=>{
                if(result.value){
                    // Crear FormData con todos los datos
                    var formData = new FormData(this);
                    formData.set('dispositivo_apoyo', dispositivoApoyo);
                    formData.set('antecedentes_dx_cancer', antecedenteCancer);
                    formData.set('antecedentes_toxina_espasticidad', antecedenteToxina);
                    formData.set('camilla_ambulancia', camillaAmbulancia);

                    $.ajax({
                        url: url,
                        method: method,
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: "json",
                        success: function(data){
                            if(data.success == 'ok') {
                                $('#form-general')[0].reset();
                                $('#modal-evolution').modal('hide');
                                $('#fisiatria').DataTable().ajax.reload();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Solicitud de fisiatría creada correctamente',
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            } else if(data.errors != null) {
                                Swal.fire({
                                    icon: 'error',
                                    title: data.errors,
                                    showConfirmButton: false,
                                    timer: 3000
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error al guardar los datos',
                                text: error,
                                showConfirmButton: true
                            });
                        }
                    });
                }
            });
        }
    });

   //Función para abrir detalle del registro
    $(document).on('click', '.resumen', function(){
        var idevo = $(this).attr('id');
        
        // Limpiar campos
        $('#names2, #documents1, #evolution1, #names3, #address1, #fecha_solicitud1, #profesional1, #dx1, #dispositivos1, #solicitud1, #antecedentes1, #toxina1, #camilla1, #tipo_solicitud1, #observacion1, #created_at1').empty();

        $.ajax({
            url:"encuesta/"+idevo+"",
            dataType:"json",
            success:function(data){
                $.each(data[0], function(i, items){
                    $('#names2').append(items.surname + " " + items.fname);
                    $('#documents1').append(items.type_document + "-" + items.document);
                    $('#evolution1').append(items.reason_consultation);
                    $('#observacion1').append(items.observacion);
                    $('#names3').append(items.surname  + " " +  items.fname);
                    $('#fecha_solicitud1').append(items.fecha_solicitud);
                    $('#profesional1').append(items.profesional);
                    $('#dx1').append(items.dx);
                    $('#eapb1').append(items.eapb);
                    $('#dispositivos1').append("Silla: " + (items.dispositivo_silla || 'N/A') + " | Apoyo: " + (items.dispositivo_apoyo || 'N/A'));
                    $('#solicitud1').append("Solicitud dispositivo: " + items.solicitud_dispositivo);
                    $('#antecedentes1').append("Cáncer: " + items.antecedentes_dx_cancer + " | Toxina: " + items.antecedentes_toxina_espasticidad);
                    $('#camilla1').append("Camilla/Ambulancia: " + items.camilla_ambulancia);
                    $('#tipo_solicitud1').append(items.tipo_solicitud);
                    $('#created_at1').append("Fecha de creación: " + items.created_at);
                    
                    $('.modal-title-resumen1').text('Solicitud de fisiatría creada');
                    $('#modal-resumen1').modal({backdrop: 'static', keyboard: false});
                    $('#modal-resumen1').modal('show');
                });
            }
        }).fail(function(jqXHR, textStatus, errorThrown){
            if (jqXHR.status === 403) {
                Manteliviano.notificaciones('No tienes permisos para realizar esta accion', 'Sistema Ventas', 'warning');
            }
        });
    });

    //Función para consulta el documento del paciente y pintar los datos en el formulario
    $(document).on('click', '#buscarp', function(){
        var documento = $('#key').val();
        
        $.ajax({
            url:"consultardocumentof",
            data: {documento:documento},
            dataType:"json",
            success: function(response) {
                if (response.data === 'vacio' || response.data.length === 0) {
                    alert('No se encontraron datos para el documento proporcionado.');
                } else {
                    var items = response.data[0];
                    
                    // Llenar el formulario con los datos recibidos
                    $('#surname').val(items.surname);
                    $('#ssurname').val(items.ssurname);
                    $('#fname').val(items.fname);
                    $('#sname').val(items.sname);
                    $('#type_document').val(items.type_document);
                    $('#document').val(items.document);
                }
            }
        }).fail(function(jqXHR, textStatus, errorThrown){
            if (jqXHR.status === 403) {
                Manteliviano.notificaciones('No tienes permisos para realizar esta accion', 'Sistema Ventas', 'warning');
            }
        });
    });

    //Función para convertir minuscula en mayuscula en el form
    $(".UpperCase").on("keypress", function () {
        $input=$(this);
        setTimeout(function () {
         $input.val($input.val().toUpperCase());
        },50);
    });

    //Función para anular la solicitud
    $(document).on('click', '.anular', function(){
        var idevoa = $(this).attr('id');
        
        Swal.fire({
            title: "¿Estás seguro?",
            text: 'Estas por anular una solicitud de fisiatría',
            icon: "warning",
            showCancelButton: true,
            showCloseButton: true,
            confirmButtonText: 'Aceptar',
        }).then((result)=>{
            if(result.value){
                $.ajax({
                    url:"anular_fisiatria/"+idevoa+"",
                    method:'put',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType:"json",
                    success:function(data){
                        $('#fisiatria').DataTable().ajax.reload();
                        Manteliviano.notificaciones(data.respuesta, data.titulo, data.icon);
                    }
                });
            }
        });
    });

    //Función para abrir modal y limpiar campos
    $(document).on('click', '.create_evolution', function(){
        // Limpiar formulario
        $('#form-general')[0].reset();
        $('.dispositivo-apoyo').prop('checked', false);
        $("#otro_dis").hide();
        
        $('#modal-evolution').modal({backdrop: 'static', keyboard: false});
        $('#modal-evolution').modal('show');
    });

});

var idioma_espanol = {
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
