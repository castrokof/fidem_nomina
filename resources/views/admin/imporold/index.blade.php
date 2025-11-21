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
                    <h3 class="card-title">Archivos</h3>
                    <div class="card-tools pull-right">

                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-3">
                    <ul class="card card-outline card-info p-5">
                        <li>
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-lg"><i
                                    class="fa fa-fw fa-plus-circle"></i> Subir Archivo Estados</button>
                        </li>
                        <br>
                        <li>
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-lg1"><i
                                    class="fa fa-fw fa-plus-circle"></i> Subir Archivo Ultima Consulta Paliativo y
                                experto</button>
                        </li>
                        <br>
                        <li>
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-lg3"><i
                                    class="fa fa-fw fa-plus-circle"></i> Subir Archivo Auxiliar</button>
                        </li>
                        <br>
                        <li>
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-lg2"><i
                                    class="fa fa-fw fa-plus-circle"></i> Subir Archivo Pacientes</button>
                        </li>
                         <br>
                        <li>
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-lg4"><i
                                    class="fa fa-fw fa-plus-circle"></i> Subir Archivo Ambito</button>
                        </li>
                        
                    </ul>
                </div>

            </div>
        </div>
    </div>
    <!--Modal-->

    <div class="modal fade" tabindex="-1" id="modal-lg" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title">Subir archivo</h3>
                                <div class="card-tools pull-right">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                            <form action="" id="Form" name="Form"class="form-horizontal" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="loader"> <img src="{{ asset("assets/$theme/dist/img/loader6.gif") }}"
                                        class="" /> </div>

                                <div class="card-body">
                                    @include('admin.import.form')
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">

                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        @include('includes.boton-form-crear')
                                    </div>
                                </div>
                                <!-- /.card-footer -->
                            </form>



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="modal-lg1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title">Subir archivo</h3>
                                <div class="card-tools pull-right">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                            <form action="" id="Form1" name="Form"class="form-horizontal" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="loader"> <img src="{{ asset("assets/$theme/dist/img/loader6.gif") }}"
                                        class="" /> </div>

                                <div class="card-body">
                                    @include('admin.import.form1')
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">

                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <button value="reset" id="reset" type="reset"
                                            class="btn btn-default">Limpiar</button>
                                        <button value="subir1" id="subir1" type="button"
                                            class="btn btn-success">Guardar</button>
                                    </div>
                                </div>
                                <!-- /.card-footer -->
                            </form>



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="modal-lg2" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title">Subir archivo</h3>
                                <div class="card-tools pull-right">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                            <form action="" id="Form2" name="Form"class="form-horizontal" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="loader"> <img src="{{ asset("assets/$theme/dist/img/loader6.gif") }}"
                                        class="" /> </div>

                                <div class="card-body">
                                    @include('admin.import.form2')
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">

                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <button value="reset" id="reset" type="reset"
                                            class="btn btn-default">Limpiar</button>
                                        <button value="subir2" id="subir2" type="button"
                                            class="btn btn-success">Guardar</button>
                                    </div>
                                </div>
                                <!-- /.card-footer -->
                            </form>



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="modal-lg3" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title">Subir archivo</h3>
                                <div class="card-tools pull-right">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                            <form action="" id="Form3" name="Form"class="form-horizontal" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="loader"> <img src="{{ asset("assets/$theme/dist/img/loader6.gif") }}"
                                        class="" /> </div>

                                <div class="card-body">
                                    @include('admin.import.form3')
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">

                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <button value="reset" id="reset" type="reset"
                                            class="btn btn-default">Limpiar</button>
                                        <button value="subir3" id="subir3" type="button"
                                            class="btn btn-success">Guardar</button>
                                    </div>
                                </div>
                                <!-- /.card-footer -->
                            </form>



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
     <div class="modal fade" tabindex="-1" id="modal-lg4" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title">Subir archivo</h3>
                                <div class="card-tools pull-right">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                            <form action="" id="Form4" name="Form"class="form-horizontal" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="loader"> <img src="{{ asset("assets/$theme/dist/img/loader6.gif") }}"
                                        class="" /> </div>

                                <div class="card-body">
                                    @include('admin.import.form4')
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">

                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <button value="reset" id="reset" type="reset"
                                            class="btn btn-default">Limpiar</button>
                                        <button value="subir4" id="subir4" type="button"
                                            class="btn btn-success">Guardar</button>
                                    </div>
                                </div>
                                <!-- /.card-footer -->
                            </form>



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
        jQuery(document).ready(function() {

            $('#subir').click(function() {

                var formData = new FormData(document.getElementById("Form"));




                $.ajax({
                    beforeSend: function() {
                        $('.loader').css("visibility", "visible");
                    },
                    url: "{{ route('subirarchivo') }}",
                    method: 'post',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {

                        if (response.mensaje == 'ok') {
                            $('#modal-lg').modal('hide');
                            Manteliviano.notificaciones('Archivo cargado exitosamente',
                                'Bd Paliativos Fidem', 'success');
                            //   $('#tarchivos').DataTable().ajax.reload();
                        } else if (response.mensaje == 'vacio') {

                            Manteliviano.notificaciones('No seleccionaste ningun arhivo',
                                'Bd Paliativos Fidem', 'info');
                        } else if (response.mensaje == 'ng') {
                            $('#modal-lg').modal('hide');
                            Manteliviano.notificaciones('Registros duplicados en base de datos',
                                'Bd Paliativos Fidem', 'warning');
                            //   $('#tarchivos').DataTable().ajax.reload();

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

                        Manteliviano.notificaciones('El archivo no tienen la estructura adecuada',
                            'Bd Paliativos Fidem', 'warning');
                        // $('#tarchivos').DataTable().ajax.reload();

                    } else if (textStatus === 'parsererror') {

                        alert('Requested JSON parse failed.');

                    } else if (textStatus === 'timeout') {

                        alert('Time out error.');

                    } else if (textStatus === 'abort') {

                        alert('Ajax request aborted.');

                    } else {


                        Manteliviano.notificaciones(
                            'El campo file debe ser un archivo de tipo: xls, xlsx',
                            'Bd Paliativos Fidem', 'warning');
                        // $('#tarchivos').DataTable().ajax.reload();

                    }

                });



            });

            $('#subir1').click(function() {

                var formData1 = new FormData(document.getElementById("Form1"));


                $.ajax({
                    beforeSend: function() {
                        $('.loader').css("visibility", "visible");
                    },
                    url: "{{ route('subirarchivoupe') }}",
                    method: 'post',
                    data: formData1,
                    contentType: false,
                    processData: false,
                    success: function(response) {

                        if (response.mensaje == 'ok') {
                            $('#modal-lg1').modal('hide');
                            Manteliviano.notificaciones('Archivo cargado exitosamente',
                                'Bd Paliativos Fidem', 'success');
                            //   $('#tarchivos').DataTable().ajax.reload();
                        } else if (response.mensaje == 'vacio') {

                            Manteliviano.notificaciones('No seleccionaste ningun arhivo',
                                'Bd Paliativos Fidem', 'info');
                        } else if (response.mensaje == 'ng') {
                            $('#modal-lg1').modal('hide');
                            Manteliviano.notificaciones('Registros duplicados en base de datos',
                                'Bd Paliativos Fidem', 'warning');
                            //   $('#tarchivos').DataTable().ajax.reload();

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

                        Manteliviano.notificaciones('El archivo no tienen la estructura adecuada',
                            'Bd Paliativos Fidem', 'warning');
                        // $('#tarchivos').DataTable().ajax.reload();

                    } else if (textStatus === 'parsererror') {

                        alert('Requested JSON parse failed.');

                    } else if (textStatus === 'timeout') {

                        alert('Time out error.');

                    } else if (textStatus === 'abort') {

                        alert('Ajax request aborted.');

                    } else {


                        Manteliviano.notificaciones(
                            'El campo file debe ser un archivo de tipo: xls, xlsx',
                            'Bd Paliativos Fidem', 'warning');
                        // $('#tarchivos').DataTable().ajax.reload();

                    }

                });



            });


            $('#subir2').click(function() {

                var formData2 = new FormData(document.getElementById("Form2"));


                $.ajax({
                    beforeSend: function() {
                        $('.loader').css("visibility", "visible");
                    },
                    url: "{{ route('subirarchivopac') }}",
                    method: 'post',
                    data: formData2,
                    contentType: false,
                    processData: false,
                    success: function(response) {

                        if (response.mensaje == 'ok') {
                            $('#modal-lg2').modal('hide');
                            Manteliviano.notificaciones('Archivo cargado exitosamente',
                                'Bd Paliativos Fidem', 'success');
                            //   $('#tarchivos').DataTable().ajax.reload();
                        } else if (response.mensaje == 'vacio') {

                            Manteliviano.notificaciones('No seleccionaste ningun arhivo',
                                'Bd Paliativos Fidem', 'info');
                        } else if (response.mensaje == 'ng') {
                            $('#modal-lg2').modal('hide');
                            Manteliviano.notificaciones('Registros duplicados en base de datos',
                                'Bd Paliativos Fidem', 'warning');
                            //   $('#tarchivos').DataTable().ajax.reload();

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

                        Manteliviano.notificaciones('El archivo no tienen la estructura adecuada',
                            'Bd Paliativos Fidem', 'warning');
                        // $('#tarchivos').DataTable().ajax.reload();

                    } else if (textStatus === 'parsererror') {

                        alert('Requested JSON parse failed.');

                    } else if (textStatus === 'timeout') {

                        alert('Time out error.');

                    } else if (textStatus === 'abort') {

                        alert('Ajax request aborted.');

                    } else {


                        Manteliviano.notificaciones(
                            'El campo file debe ser un archivo de tipo: xls, xlsx',
                            'Bd Paliativos Fidem', 'warning');
                        // $('#tarchivos').DataTable().ajax.reload();

                    }

                });



            });


            $('#subir3').click(function() {

var formData3 = new FormData(document.getElementById("Form3"));


$.ajax({
    beforeSend: function() {
        $('.loader').css("visibility", "visible");
    },
    url: "{{ route('subirarchivouau') }}",
    method: 'post',
    data: formData3,
    contentType: false,
    processData: false,
    success: function(response) {

        if (response.mensaje == 'ok') {
            $('#modal-lg3').modal('hide');
            Manteliviano.notificaciones('Archivo cargado exitosamente',
                'Bd Paliativos Fidem', 'success');
            //   $('#tarchivos').DataTable().ajax.reload();
        } else if (response.mensaje == 'vacio') {

            Manteliviano.notificaciones('No seleccionaste ningun arhivo',
                'Bd Paliativos Fidem', 'info');
        } else if (response.mensaje == 'ng') {
            $('#modal-lg3').modal('hide');
            Manteliviano.notificaciones('Registros duplicados en base de datos',
                'Bd Paliativos Fidem', 'warning');
            //   $('#tarchivos').DataTable().ajax.reload();

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

        Manteliviano.notificaciones('El archivo no tienen la estructura adecuada',
            'Bd Paliativos Fidem', 'warning');
        // $('#tarchivos').DataTable().ajax.reload();

    } else if (textStatus === 'parsererror') {

        alert('Requested JSON parse failed.');

    } else if (textStatus === 'timeout') {

        alert('Time out error.');

    } else if (textStatus === 'abort') {

        alert('Ajax request aborted.');

    } else {


        Manteliviano.notificaciones(
            'El campo file debe ser un archivo de tipo: xls, xlsx',
            'Bd Paliativos Fidem', 'warning');
        // $('#tarchivos').DataTable().ajax.reload();

    }

});



});

          $('#subir4').click(function() {

var formData4 = new FormData(document.getElementById("Form4"));


$.ajax({
    beforeSend: function() {
        $('.loader').css("visibility", "visible");
    },
    url: "{{ route('subirarchivoamb') }}",
    method: 'post',
    data: formData4,
    contentType: false,
    processData: false,
    success: function(response) {

        if (response.mensaje == 'ok') {
            $('#modal-lg4').modal('hide');
            Manteliviano.notificaciones('Archivo cargado exitosamente',
                'Bd Paliativos Fidem', 'success');
            //   $('#tarchivos').DataTable().ajax.reload();
        } else if (response.mensaje == 'vacio') {

            Manteliviano.notificaciones('No seleccionaste ningun arhivo',
                'Bd Paliativos Fidem', 'info');
        } else if (response.mensaje == 'ng') {
            $('#modal-lg4').modal('hide');
            Manteliviano.notificaciones('Registros duplicados en base de datos',
                'Bd Paliativos Fidem', 'warning');
            //   $('#tarchivos').DataTable().ajax.reload();

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

        Manteliviano.notificaciones('El archivo no tienen la estructura adecuada',
            'Bd Paliativos Fidem', 'warning');
        // $('#tarchivos').DataTable().ajax.reload();

    } else if (textStatus === 'parsererror') {

        alert('Requested JSON parse failed.');

    } else if (textStatus === 'timeout') {

        alert('Time out error.');

    } else if (textStatus === 'abort') {

        alert('Ajax request aborted.');

    } else {


        Manteliviano.notificaciones(
            'El campo file debe ser un archivo de tipo: xls, xlsx',
            'Bd Paliativos Fidem', 'warning');
        // $('#tarchivos').DataTable().ajax.reload();

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
