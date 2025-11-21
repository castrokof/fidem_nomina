@extends("theme.$theme.layout")
@section('titulo')
    Archivos de importación
@endsection

@section('scripts')
    <script src="{{ asset('assets/pages/scripts/admin/rol/index.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/pages/scripts/admin/archivo/crear.js') }}" type="text/javascript"></script>
@endsection
@section('styles')
    <link href="{{ asset("assets/$theme/plugins/datatables-bs4/css/dataTables.bootstrap4.css") }}" rel="stylesheet"
        type="text/css" />



    <style>
        .loader {
            visibility: hidden;
            background-color: rgba(255, 253, 253, 0.952);
            position: absolute;
            z-index: +100 !important;
            width: 100%;
            height: 100%;
        }

        .loader img {
            position: relative;
            top: 5%;
            left: 40%;
            width: 200px;
            height: 200px;
        }
    </style>
@endsection



@section('contenido')
    <div class="row">
        <div class="col-lg-12">
            @include('includes.form-error')
            @include('includes.form-mensaje')
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Cargue de bases xlsx</h3>
                    <div class="card-tools pull-right"></div>
                </div>
               @include('admin.import.tab.menu_tab')

            </div>
        </div>
    </div>
    <!--Modal-->
    
   
    @include('admin.import.modals.paliativos.componentModal')
   
@endsection

@section('scriptsPlugins')
    <script src="{{ asset("assets/$theme/plugins/datatables/jquery.dataTables.js") }}" type="text/javascript"></script>
    <script src="{{ asset("assets/$theme/plugins/datatables-bs4/js/dataTables.bootstrap4.js") }}" type="text/javascript">
    </script>


    <script src="https://cdn.datatables.net/plug-ins/1.10.20/api/sum().js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>

    <script>
        $(document).ready(function() {

                 // Asocia el evento click a todos los botones que comienzan con 'subir'
    $('[id^=subir]').click(function() {
        var btnId = $(this).attr('id'); // Ej: subir, subir1, subir2...
        var num = btnId.replace('subir', ''); // '' o '1', '2', etc.
        var formId = num ? 'Form' + num : 'Form';
        var modalId = num ? '#modal-lg' + num : '#modal-lg';

        // Define la ruta según el botón
        var rutas = {
            '': "{{ route('subirarchivo') }}",
            '1': "{{ route('subirarchivoupe') }}",
            '2': "{{ route('subirarchivopac') }}",
            '3': "{{ route('subirarchivouau') }}",
            '4': "{{ route('subirarchivoamb') }}",
            '5': "{{ route('subirarchivoeva') }}",
            '6': "{{ route('subirarchivoeva') }}"
            // Agrega más rutas si tienes más formularios
        };
        var url = rutas[num];

        var formData = new FormData(document.getElementById(formId));

        $.ajax({
            beforeSend: function() {
                $('.loader').css("visibility", "visible");
            },
            url: url,
            method: 'post',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.mensaje == 'ok') {
                    $(modalId).modal('hide');
                    Manteliviano.notificaciones('Archivo cargado exitosamente', 'Bd Paliativos Fidem', 'success');
                } else if (response.mensaje == 'vacio') {
                    Manteliviano.notificaciones('No seleccionaste ningun arhivo', 'Bd Paliativos Fidem', 'info');
                } else if (response.mensaje == 'ng') {
                    $(modalId).modal('hide');
                    Manteliviano.notificaciones('Registros duplicados en base de datos', 'Bd Paliativos Fidem', 'warning');
                }
            },
            complete: function() {
                $('.loader').css("visibility", "hidden");
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            if (jqXHR.status === 0) {
                alert('Not connect: Verify Network.');
            } else if (jqXHR.status == 404) {
                alert('Requested page not found [404]');
            } else if (jqXHR.status == 500) {
                Manteliviano.notificaciones('El archivo no tienen la estructura adecuada', 'Bd Paliativos Fidem', 'warning');
            } else if (textStatus === 'parsererror') {
                alert('Requested JSON parse failed.');
            } else if (textStatus === 'timeout') {
                alert('Time out error.');
            } else if (textStatus === 'abort') {
                alert('Ajax request aborted.');
            } else {
                Manteliviano.notificaciones('El campo file debe ser un archivo de tipo: xls, xlsx', 'Bd Paliativos Fidem', 'warning');
            }
        });
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
