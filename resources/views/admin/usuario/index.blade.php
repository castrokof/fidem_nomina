@extends("theme.$theme.layout")

@section('titulo')
    Usuarios
@endsection
@section('styles')
    <link href="{{ asset("assets/$theme/plugins/datatables-bs4/css/dataTables.bootstrap4.css") }}" rel="stylesheet" type="text/css" />
        <link href="{{asset("assets/css/select2.min.css")}}" rel="stylesheet" type="text/css" />
        <link href="{{asset("assets/css/select2-bootstrap4.min.css")}}" rel="stylesheet" type="text/css" />
@endsection


@section('scripts')
    <script src="{{ asset('assets/pages/scripts/admin/empleado/crearempleado.js') }}" type="text/javascript"></script>
@endsection

@section('contenido')
    <div class="row">
        <div class="col-lg-12">
            @include('includes.form-error')
            @include('includes.form-mensaje')
            <div class="card card-info">
                <div class="card-header with-border">
                    <h3 class="card-title">Usuarios</h3>
                    <div class="card-tools pull-right">
                        <button type="button" class="btn btn-default" name="create_usuario" id="create_usuario"
                            data-toggle="modal" data-target="#modal-u"><i class="fa fa-fw fa-plus-circle"></i> Nuevo
                            Usuario</button>
                        </button>
                    </div>
                </div>
                <div class="card-body table-responsive p-2">

                    <table id="usuarios" class="table table-hover  text-nowrap">
                        {{-- class="table table-hover table-bordered text-nowrap" --}}
                        <thead>
                            <tr>
                                <th>Acciones</th>
                                <th>Id</th>
                                <th>1Nombre</th>
                                <th>2Nombre</th>
                                <th>1Apellido</th>
                                <th>2Apellido</th>
                                <th>Tipo documento</th>
                                <th>Documento</th>
                                <th>Usuario</th>
                                <th>Celular</th>
                                <th>Email</th>
                                <th>Ips</th>
                                <th>Activo</th>
                                <th>Rol</th>
                                <th>Fecha de creacion</th>

                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                </form>
                <!-- /.card-body -->
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="modal-u" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="row">
                    <div class="col-lg-12">
                        @include('includes.form-error')
                        @include('includes.form-mensaje')
                        <span id="form_result"></span>
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title"></h3>
                                <div class="card-tools pull-right">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                            <form id="form-general" class="form-horizontal" method="POST">
                                @csrf
                                <div class="card-body">
                                    @include('admin.usuario.form')
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">

                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        @include('includes.boton-form-crear-empresa-empleado-usuario')
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
    <div class="modal fade" tabindex="-1" id="modal-xlpass" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="row">
                    <div class="col-lg-12">

                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Editar Contraseña</h3>

                                <div class="card-tools pull-right">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                            <form action="" id="form-general-pass" class="form-horizontal" method="POST">
                                @csrf @method('put')
                                <div class="card-body">
                                    <div class="form-group row col-lg-12">
                                        <div class="col-lg-6">
                                            <label for="password" class="col-xs-12 control-label requerido">Usuario</label>
                                            <input type="Usuario" name="usuario_id" id="usuario_id" class="form-control"
                                                value="" minlength="6" required readonly>
                                        </div>

                                    </div>
                                    <div class="form-group row col-lg-12">
                                        <div class="col-lg-6">
                                            <label for="password" class="col-xs-12 control-label requerido">Password</label>
                                            <input type="password" name="password1" id="pass" class="form-control"
                                                value="" minlength="6" required>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="remenber_token" class="col-xs-12 control-label requerido">repita el
                                                password</label>
                                            <input type="password" name="remenber" id="remenber" class="form-control"
                                                value="" minlength="6" required>
                                        </div>

                                        <input type="hidden" name="idpass" id="idpass" class="form-control"
                                            value="" required>
                                    </div>


                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <button type="button" id="actualizarpass" name="actualizar"
                                            class="btn btn-success">Actualizar</button>
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
    <script src="{{asset("assets/js/jquery-select2/select2.min.js")}}" type="text/javascript"></script>



    <script src="https://cdn.datatables.net/plug-ins/1.10.20/api/sum().js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>

    <script>
        $(document).ready(function() {

            // // funcion para cargar el select de position
            // $.get('select_position',
            //     function(positions) {
            //         $('#cargo_id').empty();
            //         $('#cargo_id').append("<option value=''>---seleccione via---</option>")
            //         $.each(positions, function(position1, value) {
            //             $('#cargo_id').append("<option value='" + value.id + "'>" + value.position +
            //                 "</option>")
            //         });

            //     });
            //     $("#cargo_id").select2({
            //         theme: "bootstrap4",
            //     });

            //     $("#rol_id").select2({
            //         theme: "bootstrap4",
            //     });

                //Consulta de datos de la tabla lista-detalle
       $("#ips").select2({
        language: "es",
                theme: "bootstrap4",
                placeholder: 'Seleccione Ips',
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
   /* $("#name_bank").select2({
      language: "es",
      theme: "bootstrap4",
      placeholder: 'Seleccione un banco',
      ajax: {
        url: "{{ route('selectlist')}}",
        dataType: 'json',
        delay: 250,
        processResults: function(data) {
          return {
            results: $.map(data.bank, function(data) {

              return {

                text: data.nombre,
                id: data.nombre

              }
            })
          };
        },
        cache: true
      }
    });


                   //Consulta de datos de la tabla lista-detalle
                   $("#type_account").select2({
      language: "es",
      theme: "bootstrap4",
      placeholder: 'Seleccione tipo de cuenta',
      ajax: {
        url: "{{ route('selectlist')}}",
        dataType: 'json',
        delay: 250,
        processResults: function(data) {
          return {
            results: $.map(data.type_acc, function(data) {

              return {

                text: data.nombre,
                id: data.nombre

              }
            })
          };
        },
        cache: true
      }
    });*/

            //     $.get('select_position',
            //     function(positions) {  //initiate dataTables plugin
            // $.each(positions, function(position1, value) {
            var myTable =
                $('#usuarios').DataTable({
                        language: idioma_espanol,
                        processing: true,
                        lengthMenu: [
                            [25, 50, 100, 500, -1],
                            [25, 50, 100, 500, "Mostrar Todo"]
                        ],
                        processing: true,
                        serverSide: true,
                        aaSorting: [
                            [1, "asc"]
                        ],

                        ajax: {
                            url: "{{ route('usuario') }}",
                        },
                        columns: [{
                                data: 'action',
                                name: 'action',
                                orderable: false
                            },
                            {
                                data: 'id',
                                name: 'id'
                            },
                            {
                                data: 'pnombre',
                                name: 'pnombre'
                            },
                            {
                                data: 'snombre',
                                name: 'snombre'
                            },
                            {
                                data: 'papellido',
                                name: 'papellido'
                            },
                            {
                                data: 'sapellido',
                                name: 'sapellido'
                            },
                            {
                                data: 'tipo_documento',
                                name: 'tipo_documento'
                            },
                            {
                                data: 'documento',
                                name: 'documento'
                            },
                            {
                                data: 'usuario',
                                name: 'usuario'
                            },
                            {
                                data: 'celular',
                                name: 'celular'
                            },
                            {
                                data: 'email',
                                name: 'email'
                            },
                            {
                                data: 'ips',
                                name: 'ips'
                            },
                            {
                                data: 'activo',
                                name: 'activo'
                            },
                            {
                                data: 'roles1[0].nombre',
                                //name:'nombre'
                            },


                            {
                                data: 'created_at',
                                name: 'created_at'
                            },

                        ],

                        //Botones----------------------------------------------------------------------

                        "dom": '<"row"<"col-xs-1 form-inline"><"col-md-4 form-inline"l><"col-md-5 form-inline"f><"col-md-3 form-inline"B>>rt<"row"<"col-md-8 form-inline"i> <"col-md-4 form-inline"p>>',


                        buttons: [{

                                extend: 'copyHtml5',
                                titleAttr: 'Copiar Registros',
                                title: "seguimiento",
                                className: "btn  btn-outline-primary btn-sm"


                            },
                            {

                                extend: 'excelHtml5',
                                titleAttr: 'Exportar Excel',
                                title: "seguimiento",
                                className: "btn  btn-outline-success btn-sm"


                            },
                            {

                                extend: 'csvHtml5',
                                titleAttr: 'Exportar csv',
                                className: "btn  btn-outline-warning btn-sm"
                                //text: '<i class="fas fa-file-excel"></i>'

                            },
                            {

                                extend: 'pdfHtml5',
                                titleAttr: 'Exportar pdf',
                                className: "btn  btn-outline-secondary btn-sm"


                            }
                        ],

                        "columnDefs": [{

                                "render": function(data, type, row) {
                                    if (row["activo"] == 1) {
                                        return data + ' - Activo';
                                    } else {

                                        return data + ' - Inactivo';

                                    }

                                },
                                "targets": [12]
                            },





                    ],

                    "createdRow": function(row, data, dataIndex) {
                        if (data["activo"] == 1) {
                            $($(row).find("td")[12]).addClass("btn btn-sm btn-success rounded-lg");
                        } else {
                            $($(row).find("td")[12]).addClass("btn btn-sm btn-warning rounded-lg");
                        }
                        if (data["type_salary"] == 1) {
                            $($(row).find("td")[15]).addClass("btn btn-sm btn-info rounded-lg");
                        } else {
                            $($(row).find("td")[15]).addClass("btn btn-sm btn-dark rounded-lg");
                        }

                    }







                });

        //     });

        // });

        $('#create_usuario').click(function() {
            $('#form-general')[0].reset();
            $('#usuario').prop('disabled', false).prop('required', true);
            $('#email').prop('disabled', false).prop('required', true);
            $('#password').prop('disabled', false).prop('required', true);
            $('#remenber_token').prop('disabled', false).prop('required', true);
            $('.card-title').text('Agregar Nuevo usuario');
            $('#action_button').val('Add');
            $('#action').val('Add');
            $('#form_result').html('');
            $('#modal-u').modal('show');

        });

        $('#form-general').on('submit', function(event) {
            event.preventDefault();
            var url = '';
            var method = '';
            var text = '';

            if ($('#action').val() == 'Add') {
                text = "Estás por crear un usuario"
                url = "{{ route('guardar_usuario') }}";
                method = 'post';
            }

            if ($('#action').val() == 'Edit') {
                text = "Estás por actualizar un usuario"
                var updateid = $('#hidden_id').val();
                url = "usuario/" + updateid;
                method = 'put';
            }
            Swal.fire({
                title: "¿Estás seguro?",
                text: text,
                icon: "info",
                showCancelButton: true,
                showCloseButton: true,
                confirmButtonText: 'Aceptar',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: url,
                        method: method,
                        data: $(this).serialize(),
                        dataType: "json",
                        success: function(data) {
                            if (data.success == 'ok') {
                                $('#form-general')[0].reset();
                                $('#modal-u').modal('hide');
                                $('#usuarios').DataTable().ajax.reload();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'usuario creado correctamente',
                                    showConfirmButton: false,
                                    timer: 1500

                                })
                                // Manteliviano.notificaciones('cliente creado correctamente', 'Sistema Ventas', 'success');

                            } else if (data.success == 'ok1') {
                                $('#form-general')[0].reset();
                                $('#modal-u').modal('hide');
                                $('#usuarios').DataTable().ajax.reload();
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'usuario actualizado correctamente',
                                    showConfirmButton: false,
                                    timer: 1500

                                })
                                // Manteliviano.notificaciones('cliente actualizado correctamente', 'Sistema Ventas', 'success');

                            }
                        }


                    }).fail(function(jqXHR, textStatus, errorThrown) {

                        if (jqXHR.status === 422) {

                            var error = jqXHR.responseJSON;

                            $.each(error, function(i, items) {

                                var errores = [];
                                errores.push(items.celular + '<br>');
                                errores.push(items.activo + '<br>');
                                errores.push(items.documento + '<br>');
                                errores.push(items.email + '<br>');
                                errores.push(items.papellido + '<br>');
                                errores.push(items.pnombre + '<br>');
                                errores.push(items.password + '<br>');
                                errores.push(items.position + '<br>');
                                errores.push(items.tipo_documento + '<br>');
                                errores.push(items.usuario + '<br>');

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
                    });
                }
            });


        });


        // Edición de cliente

        $(document).on('click', '.edit', function() {
            var id = $(this).attr('id');

            $.ajax({
                url: "usuario/" + id + "/editar",
                dataType: "json",
                success: function(data) {
                    $('#pnombre').val(data.result.pnombre);
                    $('#snombre').val(data.result.snombre);
                    $('#papellido').val(data.result.papellido);
                    $('#sapellido').val(data.result.sapellido);
                    $('#tipo_documento').val(data.result.tipo_documento);
                    $('#documento').val(data.result.documento);
                    $('#usuario').val(data.result.usuario).prop('disabled', true).prop(
                        'required', false);
                    $('#email').val(data.result.email).prop('required', false);
                    $('#celular').val(data.result.celular);
                    $('#type_contrat').val(data.result.type_contrat);
                    $('#date_in').val(data.result.date_in);
                    $('#ips').append("<option value='" + data.result.ips + "'>" + data.result.ips +
                            "</option>");
                    $('#rol_id').val(data.result.rol_id).trigger('change.select2');
                    $('#activo').val(data.result.activo);
                    $('#cargo_id').val(data.result.cargo_id).trigger('change.select2');
                    $('#type_salary').val(data.result.type_salary);
                    $('#account').val(data.result.account);
                    $('#name_bank').append("<option value='" + data.result.name_bank + "'>" + data.result.name_bank +
                            "</option>");
                    $('#type_account').append("<option value='" + data.result.type_account + "'>" + data.result.type_account +
                            "</option>");
                    $('#observacion').val(data.result.observacion);
                    $('#password').val(data.result.password).prop('disabled', true).prop(
                        'required', false);
                    $('#remenber_token').val(data.result.remenber_token).prop('disabled',
                        true).prop('required', false);
                    $('#hidden_id').val(id)
                    $('.card-title').text('Editar usuario');
                    $('#action_button').val('Edit');
                    $('#action').val('Edit');
                    $('#modal-u').modal('show');

                },



            }).fail(function(jqXHR, textStatus, errorThrown) {

                if (jqXHR.status === 403) {

                    Manteliviano.notificaciones('No tienes permisos para realizar esta accion',
                        'Sistema Ventas', 'warning');

                }
            });

        });




        $(document).on('click', '.epassword', function() {
            var id = $(this).attr('id');
            var usuario = $(this).attr('usuario1');

            $('#usuario_id').val(usuario);
            $('#idpass').val(id);
            $('#modal-xlpass').modal('show');


        });


        //Actualizacion de password desde listado de usuarios
        $('#actualizarpass').click(function() {
            //    event.preventDefault();
            var id = $('#idpass').val();
            var password = $('#pass').val();
            var remenber_token = $('#remenber').val();

            if (password != remenber_token) {

                $('button[type="button"]').attr('enable', 'disabled');

            } else if (password == '' || remenber_token == '') {

                Swal.fire({
                    title: 'Los campos no pueden estar vacios',
                    icon: 'warning',
                    showCloseButton: true,
                    confirmButtonText: 'Aceptar',
                })


            } else {

                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "Estás por actualizar el password",
                    icon: "success",
                    showCancelButton: true,
                    showCloseButton: true,
                    confirmButtonText: 'Aceptar',
                }).then((result) => {
                    if (result.value) {

                        $.ajax({
                            url: "password1/" + id + "",
                            method: 'put',
                            data: {
                                password: password,
                                remenber_token: remenber_token,

                                "_token": $("meta[name='csrf-token']").attr("content")

                            },
                            success: function(respuesta) {
                                if (respuesta.mensaje = 'ok') {
                                    $('#modal-xlpass').modal('hide');
                                    $('#pass').val('');
                                    $('#remenber').val('');
                                    Manteliviano.notificaciones(
                                        'Password actualizado correctamente',
                                        'Sistema Catastro de usuario', 'success'
                                    );

                                } else if (respuesta.mensaje = 'ng') {


                                    Manteliviano.notificaciones(
                                        'Las contraseñas deben coincidir',
                                        'Sistema Catastro de usuario', 'error');
                                }
                            }
                        });

                    }


                });

            }
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
