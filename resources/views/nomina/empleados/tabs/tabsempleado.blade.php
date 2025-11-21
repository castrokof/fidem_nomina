<div class="row">
    <div class="col-12">
        <div class="card card-info card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-one-datos-del-empleado-tab" data-toggle="pill"
                            href="#custom-tabs-one-datos-del-empleado" role="tab"
                            aria-controls="custom-tabs-one-datos-del-empleado" aria-selected="false">Información
                            empleado</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-datos-del-empleado-af-tab" data-toggle="pill"
                            href="#custom-tabs-one-datos-del-empleado-af" role="tab"
                            aria-controls="custom-tabs-one-datos-del-empleado-af" aria-selected="false">Afiliaciones</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " id="custom-tabs-one-datos-del-empleado-contrac-tab" data-toggle="pill"
                            href="#custom-tabs-one-datos-del-empleado-contrac" role="tab"
                            aria-controls="custom-tabs-one-datos-del-empleado-contrac"
                            aria-selected="false">Contrato</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " id="custom-tabs-one-datos-del-empleado-salario-tab" data-toggle="pill"
                            href="#custom-tabs-one-datos-del-empleado-salario" role="tab"
                            aria-controls="custom-tabs-one-datos-del-empleado-salario" aria-selected="false">Salario</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                    <form id="form-general" class="form-horizontal" method="POST">
                        <div class="tab-pane fade active show" id="custom-tabs-one-datos-del-empleado" role="tabpanel"
                            aria-labelledby="custom-tabs-one-datos-del-empleado-tab">
                            <div class="card-body">


                                @include('nomina.empleados.form.formdatosbasicos')

                            </div>
                        </div>

                        <div class="tab-pane fade  " id="custom-tabs-one-datos-del-empleado-af" role="tabpanel"
                            aria-labelledby="custom-tabs-one-datos-del-empleado-af-tab">
                            <div class="card-body">

                                @include('nomina.empleados.form.formdatosafiliaciones')

                            </div>
                        </div>
                        <div class="tab-pane fade " id="custom-tabs-one-datos-del-empleado-contrac"
                            role="tabpanel" aria-labelledby="custom-tabs-one-datos-del-empleado-contrac-tab">
                            <div class="card-body">


                                @include('nomina.empleados.form.formdatoscontrato')

                            </div>
                        </div>
                        <div class="tab-pane fade"  id="custom-tabs-one-datos-del-empleado-salario"
                            role="tabpanel" aria-labelledby="custom-tabs-one-datos-del-empleado-salario-tab">
                            <div class="card-body">


                                @include('nomina.empleados.form.formdatossalarios')

                            </div>
                        </div>

                    </form>
                </div>
            </div>
            <!-- /.card -->
        </div>

    </div>

</div>
