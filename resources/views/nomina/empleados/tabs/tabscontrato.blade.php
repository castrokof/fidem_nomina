<div class="row">
    <div class="col-12">
        <div class="card card-info card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-one-datos-del-contrato-tab" data-toggle="pill"
                            href="#custom-tabs-one-datos-del-contrato" role="tab"
                            aria-controls="custom-tabs-one-datos-del-contrato" aria-selected="false">Crear contrato</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-datos-del-contrato-list-tab" data-toggle="pill"
                            href="#custom-tabs-one-datos-del-contrato-list" role="tab"
                            aria-controls="custom-tabs-one-datos-del-contrato-af" aria-selected="false">Lista de contratos de usuario</a>
                    </li>
                 
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                    <form id="form-general" class="form-horizontal" method="POST">
                        <div class="tab-pane fade active show" id="custom-tabs-one-datos-del-contrato" role="tabpanel"
                            aria-labelledby="custom-tabs-one-datos-del-contrato-tab">
                            <div class="card-body">


                                @include('nomina.empleados.form.formdatoscontratonew')

                            </div>
                        </div>

                        <div class="tab-pane fade  " id="custom-tabs-one-datos-del-empleado-list" role="tabpanel"
                            aria-labelledby="custom-tabs-one-datos-del-empleado-list-tab">
                            <div class="card-body">

                               

                            </div>
                        </div>
                 

                    </form>
                </div>
            </div>
            <!-- /.card -->
        </div>

    </div>

</div>
