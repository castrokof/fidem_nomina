@extends("theme.$theme.layout")

@section('titulo')
FidemContigo
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
 
        .timeline-item.entrega-incompleta {
            background-color: #f0f0f0 !important;
            border-left: 5px solid #ccc;
        }
        
        .timeline-icon.incompleto {
            background-color: #ccc !important;
            color: white;
        }
                
 
 
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

@endsection

@section('contenido')

 <div class="card-body col-mb-12" style="min-height: 543px;">
        <!-- Content Header (Page header) -->

        <div class="row">

            <div class="col-lg-12">
                <div class="card-header  ">
                    <h1 class="card-title" style="font-size: 40px; font-weight:bold;">Fidem Contigo</h1>

                </div>
            </div><!-- /.col  -->

            @csrf
            <div class="card-body">
                <div class="row col-lg-12">


                    
                </div>
            </div><!-- /.row -->



        </div>
        <!-- /.content-header -->

        <!-- Main content -->



<section class="content">
     @include('paliativos.fidemcontigo.cards.cards')
     @include('paliativos.fidemcontigo.form.form_consultas')
     @include('paliativos.fidemcontigo.tabs.tabsIndexAnalista')
    
    
 </section>    
    @include('paliativos.fidemcontigo.modal.modalindexaddseguimiento')
    @include('paliativos.fidemcontigo.modal.modalindexconsultaaddseguimiento')
    @include('paliativos.fidemcontigo.modal.modalindexresumenPsi')
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
    
    


<!-- Se incluye el js de Cards -->
<script src="{{asset("assets/pages/scripts/admin/fidemcontigo/cards/indexcards.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/pages/scripts/admin/fidemcontigo/tablas/indextables.js")}}" type="text/javascript"></script>
<script>

$(document).ready(function(){
    
    
    //funcion para filtrar fidemcontigo
    
        //Variables para capturar los valores de los INPUT a filtrar.
        
        let fechaini, fechafin, evac, epsselect, notaevo;
    
          $(document).on('click', '#buscar', function() {
               
                
                
                 fechaini = $('#fechaini').val();
                 fechafin = $('#fechafin').val();
                 evac = $('#evac').val();
                 epsselect = $('#epsselect').val();
                 notaevo = $('#notaevo').val();
                
                if((fechaini != '' && fechafin != '') || evac != '' || epsselect != '' || notaevo != '' ){
                
                    $('#fidemcontigo').DataTable().destroy();
                    
                 fill_datatable_tabla(fechaini, fechafin, evac, epsselect, notaevo); 
                    
                     }else {

                    Swal.fire({
                        icon: 'warning',
                        title: 'Debes seleccionar un rango de fechas o seleccionar un filtro',
                        showConfirmButton: false,
                        timer: 1500

                    })

                }
                        
                
               
               

            });
    
    
    //función para el eva
    
    function eva(){

    $('#progress_bar_icon').empty();
    var escalas = parseInt($(this).val());
    var escala = parseInt($(this).val()+0);
    console.log(escala);
    $("#progress_bar_2").css({'width': escala+'%'})
    $('#progress_bar_icon').append(escalas);

    
  }




$('#evaescala').change(eva);
    
    
    
    // Funcion para pintar card
    
     Fidemcontigo.fill_datatable1_resumen();
 

       // Funcion para pintar con data table
            $('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
                
            var target = $(e.target).attr("href"); // ej: "#custom-tabs-one-datos-agendados"
        
            if (target === "#custom-tabs-one-datos-agendados") {
                $('#fidemcontigo_seguimiento').DataTable().destroy();
                Fidemcontigo_table.fill_datatable_tabla_seguimiento();
            }
        
            if (target === "#custom-tabs-one-datos-seguimiento") {
                $('#fidemcontigo_sincontacto').DataTable().destroy();
                Fidemcontigo_table.fill_datatable_tabla_sincontacto();
            }
            
            if (target === "#custom-tabs-one-datos-priorizados") {
                $('#fidemcontigo_priorizados').DataTable().destroy();
                Fidemcontigo_table.fill_datatable_tabla_priorizados();
            }
            });
               
      
// Se llama primero a la función sin parametros para traer todos los activos      
      fill_datatable_tabla();     

 function  fill_datatable_tabla(fechaini = '', fechafin = '', evac = '', epsselect = '', notaevo = ''){      
 
 var datatable =
$('#fidemcontigo').DataTable({
    language: idioma_espanol,
    processing: true,
    lengthMenu: [ [25, 50, 100, 500, -1 ], [25, 50, 100, 500, "Mostrar Todo"] ],
    processing: true,
    serverSide: true,
    aaSorting: [[ 15, "desc" ]],
    ajax:{
      url:"{{route('fidemcontigo.indexfidem1')}}",
      data:{   fechaini: fechaini, fechafin: fechafin, evac: evac, epsselect:epsselect, notaevo:notaevo,
          
          _token:"{{ csrf_token() }}" },
          
      method: 'post'
          },
    columns: [
      {data:'action',
       orderable: false},
      {data:'id'},
      {data:'tipdocum'},
      {data:'numdocum'},
      {data:'numhistoria'},
      {data:'apellido1'},
      {data:'apellido2'},
      {data:'nombre1'},
      {data:'nombre2'},
      {data:'entidad_salud'},
      {data:'telefono'},
      {data:'telefono_avi'},
      {data:'telefono_residencia'},
      {data:'telefono_movil'},
      {data:'estado'},
      {data:'fecha_ultima_evolucion'},
      {data:'eva'},
      {data:'tipo_evolucion'},
      {data:'created_at'}
    ],
    rowCallback: function(row, data) {
  let evaValue = parseFloat(data.eva);
  if (!isNaN(evaValue) && evaValue >= 6 && evaValue <= 9) {
    let color = '';
    if (evaValue < 6.75) {
      color = '#ffe5ec';
    } else if (evaValue < 7.5) {
      color = '#ffc2d1';
    } else if (evaValue < 8.25) {
      color = '#ffb3c6';
    } else if (evaValue < 9) {
      color = '#ff8fab';
    } else {
      color = '#fb6f92';
    }
    $(row).css('background-color', color);
    $(row).css('color', '#000'); // negro para buen contraste en rosas claros
  } else {
    $(row).css('background-color', '');
    $(row).css('color', '');
  }
},

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

 }





     
 //Función para convertir minuscula en mayuscula en el form

    $(".UpperCase").on("keypress", function () {
        $input=$(this);
        setTimeout(function () {
         $input.val($input.val().toUpperCase());
        },50);
       });


//función para pintar el icono del thermomete según el rango del eva e intensidad de color
        
        function obtenerTermometroEva(eva) {
            eva = parseInt(eva);
        
            let icono = 'fa-thermometer-empty';
            let color = 'text-success'; // verde por defecto
        
            if (eva >= 8) {
                icono = 'fa-thermometer-full';
                color = 'text-danger'; // rojo
            } else if (eva >= 6) {
                icono = 'fa-thermometer-three-quarters';
                color = 'text-warning'; // naranja
            } else if (eva >= 4) {
                icono = 'fa-thermometer-half';
                color = 'text-yellow'; // amarillo (puedes ajustar)
            } else if (eva >= 2) {
                icono = 'fa-thermometer-quarter';
                color = 'text-success'; // verde claro
            } else {
                icono = 'fa-thermometer-empty';
                color = 'text-success'; // verde
            }
        
            return { icono, color };
        }
    
        //Función para abrir modal y prevenir el cierre

      

        //funcion para abrir el modal de observacion
    $(document).on('click', '.consultaseguimiento', function () {
    
    var idas = $(this).attr('id');
    
    $('#timeline-seguimientos').html('');

    $.ajax({
        url: "consultar_addseguimiento_fidemcontigo/" + idas,
        dataType: "json",
        success: function (data) {
            $('#timeline-seguimientos').html('');
            
           
            
            let paciente = data.consultaadd;

            if (paciente.seguimientos?.length) {
                paciente.seguimientos.forEach(function (seg) {
                    let user = seg.user_seguimiento;
                    let nombreUsuario = user 
                        ? `${user.pnombre ?? ''} ${user.snombre ?? ''} ${user.papellido ?? ''} ${user.sapellido ?? ''}`.trim()
                        : 'Usuario';
                    let fecha = seg.created_at ?? '';
                    let observacion = seg.observacion_general ?? 'Sin observación';
                    let estado = seg.estado_contacto ?? '';
                    
                    let entregado = seg.todos_entregados?.toLowerCase() ?? 'n/a';
                    
                    let textoEntregado = entregado === 'si' ? 'Sí' : (entregado === 'no' ? 'No' : 'No aplica');
                    let claseEntrega = entregado === 'n/a' ? 'entrega-incompleta' : '';
                    let claseIcono = entregado === 'n/a' ? 'incompleto' : 'bg-primary';
                    
                    let evanew = seg.new_eva ?? '0';
                    let { icono, color } = obtenerTermometroEva(evanew);

                    // Agregar lista de medicamentos entregados
                    let medicamentos = '';
                    if (seg.medicamentosegui && seg.medicamentosegui.length > 0) {
                        medicamentos += '<ul>';
                        seg.medicamentosegui.forEach(function (med) {
                            medicamentos += `<li>${med.nombre} - ${med.observacion_entrega ?? ''} - 
                            <strong>Entregado:</strong> ${med.entregado == 1 || med.entregado === "1" || med.entregado === "si" ? '✅' : '❌'}</li>`;
                        });
                        medicamentos += '</ul>';
                    } else {
                        medicamentos = '<p>No se registraron medicamentos en este seguimiento.</p>';
                    }

                    let html = `
                    <div>
                        <i class="fas fa-notes-medical timeline-icon ${claseIcono}"></i>
                        <div class="timeline-item ${claseEntrega}">
                            <span class="time"><i class="fas fa-clock"></i> ${fecha}</span>
                            <h3 class="timeline-header"><strong>${nombreUsuario}</strong> realizó seguimiento</h3>
                            <div class="timeline-body">
                                <p><strong>Estado contacto:</strong> ${estado}</p>
                                <p><strong>EVA en seguimiento:</strong> <i class="fas ${icono} ${color}"></i> (${evanew})</p>
                                <p><strong>¿Todos los medicamentos entregados?:</strong> ${textoEntregado}</p>
                                <p><strong>Observación:</strong> ${observacion}</p>
                                <p><strong>Medicamentos entregados:</strong></p>
                                ${medicamentos}
                            </div>
                        </div>
                    </div>`;

                    $('#timeline-seguimientos').append(html);
                });
            } else {
                $('#timeline-seguimientos').html('<li><div class="timeline-item"><div class="timeline-body text-muted">No hay seguimientos aún.</div></div></li>');
            }

            $('#modal-seguimientos').modal('show');
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        if (jqXHR.status === 403) {
            Manteliviano.notificaciones('No tienes permisos para realizar esta acción', 'Sistema Ventas', 'warning');
        }
    });
});

    
     

        
        function limpiar_add(){
            
            $('#namesadd').empty();
            $('#documentsadd').empty();
            $('#celular').empty();
            $('#estado').empty();
            $('#evolucion').empty();
            $('#profesional').empty();
            $('#eva').empty();
            $('#eps').empty();
            $('#medicamentos_add').empty();
            $('#seguimiento_estado').val('');
            $('#medicamento_entregado_todos').val('');
            $('#addobservacion').val('');
            $('#pac_id').val('');
            $('#evo_id').val('');
            $('#user_id').val('');
            $('#evaescala').val('');
            $('#epsselect').val('');
            
            $('#tipo_evolucion').empty();
        }
      
        
        
        //Función para abrir modal y prevenir el cierre de agregar observaciones
        
        $(document).on('click', '.seguimientoadd', function () {
                    var idas = $(this).attr('id');
                    
                    limpiar_add();
                   
                    
                    $('#pac_id').val(idas);
                    $('#user_id').val({{ Session()->get('usuario_id') ?? '' }});
                
                    $.ajax({
                        url: "addseguimiento_fidemcontigo/" + idas,
                        dataType: "json",
                        success: function (data) {
                            const paciente = data.add;
                            
                
                            $('#namesadd').append(paciente.nombre1  +" " + paciente.apellido1);
                            $('#documentsadd').append(
                                'Tipo documento:<strong>'+ paciente.tipdocum  + " </strong><br>" +  
                                'Numero ducumento:<strong>'+ paciente.numdocum + "</strong>");
                                
                                if (paciente.evoluciones.length > 0) {
                                    const profesionales = paciente.evoluciones[0];
                                    const profesional = profesionales.codigo_profesional;
                                    $('#profesional').append("<strong>"+ profesional + "</strong>");
                                }
                            
                            $('#celular').append("<strong>"+ paciente.telefono +"<br>" + paciente.telefono_avi);
                            $('#estado').append("<strong>"+ paciente.estado + "</strong>");
                            $('#evolucion').append("<strong>"+ paciente.fecha_ultima_evolucion + "</strong>");
                            $('#eva').append("<strong>"+ paciente.eva +"</strong>" );
        
                            $('#eps').append('<strong>'+ paciente.entidad_salud  + "</strong>");
                            
                     
                            let tipoevo = paciente.tipo_evolucion;
                            let desc_tipoevo;
                            
                            if(tipoevo == 72){desc_tipoevo = "NOTA ENFERMERA PROCEDIMIENTOS";
                            }else if(tipoevo == "HCMR"){desc_tipoevo = "HC ESPECIALIZADA EN FISIATRA";}
                            else if(tipoevo == 97){desc_tipoevo = "HC ESPECIALIZADA EN DOLOR";}
                            
                            
                            $('#tipo_evolucion').append('<strong>'+ desc_tipoevo + "</strong>");
                            
                            $('.modal-title-addseguimiento').text('Add Seguimiento');
                            $('#modal-addseguimiento').modal({ backdrop: 'static', keyboard: false });
                            $('#modal-addseguimiento').modal('show');
                            
                        if (paciente.evoluciones.length > 0) {
                            const ultimaEvolucion = paciente.evoluciones[0];
                            let idevolucion = ultimaEvolucion.id;
                            $('#evo_id').val(idevolucion);
                            
                        
                            if (ultimaEvolucion.medicamentos.length > 0) {
                                let contenidoMedicamentos = '<ul class="mb-0">';
                                ultimaEvolucion.medicamentos.forEach(function (med, index) {
                                    contenidoMedicamentos += `
                                        <li class="mb-3">
                                            <div style="font-size: 0.70rem;">
                                                <strong>${med.nombre}</strong> - ' - Cantidad total: '${med.cantidad ?? ''} - 'dosis' ${med.dosis_cant ?? ''}
                                                ${med.administracion ? ' - Vía: ' + med.administracion : ''}
                                                ${med.posologia ? ' - Duración: ' + med.posologia : ''}
                                            </div>
                                            <div class="form-check mt-1" style="font-size: 0.70rem;">
                                                <input class="form-check-input" type="checkbox" id="entregado_${index}" name="entregado_${index}">
                                                <label class="form-check-label" for="entregado_${index}">¿Entregado?</label>
                                            </div>
                                            <input type="text" class="form-control form-control-sm mt-1" id="observacion_${index}" name="observacion_${index}" placeholder="Observación..." style="font-size: 0.70rem;">
                                            <input type="hidden" id="medicamento_id_${index}" value="${med.id}">
                                        </li>`;
                                });
                                contenidoMedicamentos += '</ul>';
                                $('#medicamentos_add').append(contenidoMedicamentos);
                            } else {
                                $('#medicamentos_add').append('<p class="text-muted">No hay medicamentos registrados.</p>');
                            }
                        }

                            
                            

                
                            // Si quieres mostrar la última evolución, puedes hacer algo como:
                            if (paciente.evoluciones.length > 0) {
                                const ultimaEvolucion = paciente.evoluciones[0];
                
                                console.log("Fecha evolución:", ultimaEvolucion.fechahora_evolucion);
                                console.log("Respuesta EVA:", ultimaEvolucion.respuesta);
            
                            // También podrías mostrar los medicamentos:
                            if (ultimaEvolucion.medicamentos.length > 0) {
                                const primerMedicamento = ultimaEvolucion.medicamentos[0];
                                console.log("Primer medicamento:", primerMedicamento.nombre);
                            }
                        }
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    if (jqXHR.status === 403) {
                        Manteliviano.notificaciones('No tienes permisos para realizar esta acción', 'Sistema Ventas', 'warning');
                    }
                });
            });

          
               $(document).on('click', '#guardarSeguimiento',function () {
                                        let pacId = $('#pac_id').val();
                                        let evoId = $('#evo_id').val();
                                        let evaescala = $('#evaescala').val();
                                        let seguimientoEstado = $('#seguimiento_estado').val();
                                        let medicamentoEntregadoTodos = $('#medicamento_entregado_todos').val();
                                        let addObservacion = $('#addobservacion').val();
                                        let userId = '{{ auth()->user()->id }}'; // O pásalo dinámicamente desde el backend
                                    
                                        let medicamentos = [];
                                        let index = 0;
                                    
                                        while (document.getElementById(`entregado_${index}`)) {
                                                let entregado = document.getElementById(`entregado_${index}`).checked;
                                                let observacion = document.getElementById(`observacion_${index}`).value;
                                                let medicamento_id = document.getElementById(`medicamento_id_${index}`).value;
                                            
                                                medicamentos.push({
                                                    id: medicamento_id,
                                                    entregado: entregado,
                                                    observacion: observacion
                                                });
                                            
                                                index++;
                                            }
                                    
                                        $.ajax({
                                            url: "{{ route('seguimiento.store') }}",
                                            method: "POST",
                                            contentType: "application/json",
                                            headers: {
                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                            },
                                            data: JSON.stringify({
                                                fidemcontigos_id: pacId,
                                                evoluciones_id : evoId,
                                                user_id: userId,
                                                evaescala: evaescala,
                                                estado_contacto: seguimientoEstado,
                                                todos_entregados: medicamentoEntregadoTodos,
                                                observacion_general: addObservacion,
                                                medicamentos: medicamentos
                                            }),
                                            success: function (data) {
                                                
                                                $('#modal-addseguimiento').modal('hide');
                                                $('#basePaliativos').DataTable().ajax.reload();
                                                
                                                if (data.success) {
                                                    
                                                    $('#modal-paciente').modal('hide');
                                                    limpiar_add();
                                                    $('#fidemcontigo').DataTable().ajax.reload();
                                                    Fidemcontigo.fill_datatable1_resumen();
                                                    
                                                    Swal.fire({
                                                        icon: 'success',
                                                        title: 'Seguimiento guardado correctamente.',
                                                        showConfirmButton: false,
                                                        timer: 1500
                
                                                    })
                                                  
                                                  
                                                } else {
                                                     Swal.fire({
                                                        icon: 'warning',
                                                        title: 'Error al guardar el seguimiento.',
                                                        showConfirmButton: false,
                                                        timer: 1500
                
                                                    })
                                                  
                                                    
                                                }
                                            },
                                            error: function (jqXHR, status, error) {
                                            
                                            if (jqXHR.status === 422) {
                    
                                                var error = jqXHR.responseJSON;
                    
                                                $.each(error, function(i, items) {
                    
                                                    var errores = [];
                                                    errores.push(items.estado_contacto + '<br>');
                                                    errores.push(items.evoluciones_id + '<br>');
                                                    errores.push(items.user_id + '<br>');
                                                    errores.push(items.todos_entregados + '<br>');
                                                    errores.push(items.observacion_general + '<br>');
                                                    errores.push(items.medicamentos + '<br>');
                                                   
                                                    console.log(errores);
                    
                                                    var filtered = errores.filter(function(el) {
                                                        return el != "undefined<br>";
                                                    });
                    
                                                    console.log(filtered);
                                                    Swal.fire({
                                                        icon: 'danger',
                                                        title: 'El formulario contiene errores',
                                                        html: filtered,
                                                        showConfirmButton: true,
                                                        //timer: 1500
                                                    })
                    
                    
                                                    //Manteliviano.notificaciones(items, 'Sistema Ventas', 'warning');
                    
                                                });
                                            }
                                            
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
