<div class="row">
        <div class="col-lg-12">
            @include('includes.form-error')
            @include('includes.form-mensaje')
            <div class="card card-info">
                <div class="card-header with-border">
                    <h3 class="card-title-1">Empleados</h3>
                    <div class="card-tools pull-right">
                        <button type="button" class="btn btn-default" name="create_empleado" id="create_empleado"
                            data-toggle="modal" data-target="#modal-u"><i class="fa fa-fw fa-plus-circle"></i> Nuevo Empleado</button>
                        </button>
                    </div>
                </div>
                <div class="card-body table-responsive p-2">

                    <table id="empleados" class="table table-hover  text-nowrap">
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
                                <th>Celular</th>
                                <th>Email</th>
                                <th>Ips</th>
                                <th>Activo</th>
                                <th>Cargo</th>
                                <th>Tipo de salario</th>
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