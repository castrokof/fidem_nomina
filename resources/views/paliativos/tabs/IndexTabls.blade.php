<div class="row">
    <div class="col-12">
        <div class="card shadow-lg p-3 mb-5 card-info card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-one-datos-de-bdpaliativos-tab" data-toggle="pill"
                            href="#custom-tabs-one-datos-de-bdpaliativos" role="tab"
                            aria-controls="custom-tabs-one-datos-de-bdpaliativos" aria-selected="false">Pacientes
                            Totales Paliativos</a>
                    </li>
                     @if(session()->get('rol_id') == 1 || session()->get('rol_id') == 2) <li class="nav-item">
                        <a class="nav-link " id="custom-tabs-one-datos-de-bdpaliativos-sincontac-tab" data-toggle="pill"
                            href="#custom-tabs-one-datos-de-bdpaliativos-sincontac" role="tab"
                            aria-controls="custom-tabs-one-datos-de-bdpaliativos-sincontac" aria-selected="false">Pacientes
                            Sin contacto => seguimiento</a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link " id="custom-tabs-one-datos-de-bdpaliativos-domi-tab" data-toggle="pill"
                            href="#custom-tabs-one-datos-de-bdpaliativos-domi" role="tab"
                            aria-controls="custom-tabs-one-datos-de-bdpaliativos-domi" aria-selected="false">Pacientes
                            Domiciliarios</a>
                    </li>
                     @if(session()->get('rol_id') == 1 || session()->get('rol_id') == 2)
                    <li class="nav-item">
                        <a class="nav-link " id="custom-tabs-one-datos-de-bdpaliativos-upe-tab" data-toggle="pill"
                            href="#custom-tabs-one-datos-de-bdpaliativos-upe" role="tab"
                            aria-controls="custom-tabs-one-datos-de-bdpaliativos-upe" aria-selected="false">Ultima
                            Cita Paliativos - Experto</a>
                    </li>
                    @endif
                    @if(session()->get('rol_id') == 1 || session()->get('rol_id') == 2)
                     <li class="nav-item">
                        <a class="nav-link " id="custom-tabs-one-datos-de-bdpaliativos-upef-tab" data-toggle="pill"
                            href="#custom-tabs-one-datos-de-bdpaliativos-upef" role="tab"
                            aria-controls="custom-tabs-one-datos-de-bdpaliativos-upef" aria-selected="false">filtro
                            Cita Paliativos - Experto</a>
                    </li>
                    @endif
                     <li class="nav-item">
                        <a class="nav-link " id="custom-tabs-one-datos-de-bdpaliativos-ua-tab" data-toggle="pill"
                            href="#custom-tabs-one-datos-de-bdpaliativos-ua" role="tab"
                            aria-controls="custom-tabs-one-datos-de-bdpaliativos-ua" aria-selected="false">filtro
                            Cita Aux</a>
                    </li>
                    @if(session()->get('rol_id') == 3 )
                    <li class="nav-item">
                        <a class="nav-link " id="custom-tabs-one-datos-de-bdpaliativos-a-tab" data-toggle="pill"
                            href="#custom-tabs-one-datos-de-bdpaliativos-a" role="tab"
                            aria-controls="custom-tabs-one-datos-de-bdpaliativos-a" aria-selected="false">filtro
                            Aux</a>
                    </li>
                     @endif
                      @if(session()->get('rol_id') == 1 || session()->get('rol_id') == 2)
                    <li class="nav-item">
                        <a class="nav-link " id="custom-tabs-one-datos-de-bdpaliativos-ue-tab" data-toggle="pill"
                            href="#custom-tabs-one-datos-de-bdpaliativos-ue" role="tab"
                            aria-controls="custom-tabs-one-datos-de-bdpaliativos-ue" aria-selected="false">Ultima
                            Cita Experto</a>
                    </li>
                    @endif
                </ul>
            </div>


            <div class="tab-content" id="custom-tabs-one-tabContent">
                <div class="tab-pane fade active show" id="custom-tabs-one-datos-de-bdpaliativos" role="tabpanel"
                    aria-labelledby="custom-tabs-one-datos-de-bdpaliativos-tab">

                    @include('paliativos.form.formConsultaAdd')

                    @csrf
                    @include('paliativos.tablas.tablaPaliativos')

                </div>


                <div class="tab-pane fade " id="custom-tabs-one-datos-de-bdpaliativos-sincontac" role="tabpanel"
                    aria-labelledby="custom-tabs-one-datos-de-bdpaliativos-sincontac-tab">


                    @csrf
                    @include('paliativos.tablas.tablaPaliativosSinC')

                </div>
                <div class="tab-pane fade " id="custom-tabs-one-datos-de-bdpaliativos-domi" role="tabpanel"
                aria-labelledby="custom-tabs-one-datos-de-bdpaliativos-domi-tab">


                @csrf
                @include('paliativos.tablas.tablaPaliativosDomi')

                </div>
                <div class="tab-pane fade " id="custom-tabs-one-datos-de-bdpaliativos-upe" role="tabpanel"
                aria-labelledby="custom-tabs-one-datos-de-bdpaliativos-upe-tab">
    
    
                @csrf
                @include('paliativos.tablas.tablaPaliativosUltimaPaliExpe')
    
                 </div>
                <div class="tab-pane fade " id="custom-tabs-one-datos-de-bdpaliativos-upef" role="tabpanel"
                    aria-labelledby="custom-tabs-one-datos-de-bdpaliativos-upef-tab">
        
        
                    @csrf
                    @include('paliativos.tablas.tablaPaliativosUltimaPaliExpef')
        
                </div>
                <div class="tab-pane fade " id="custom-tabs-one-datos-de-bdpaliativos-ua" role="tabpanel"
                    aria-labelledby="custom-tabs-one-datos-de-bdpaliativos-ua-tab">


                    @csrf
                    @include('paliativos.tablas.tablaPaliativosUltimaAux')

                </div>
                <div class="tab-pane fade " id="custom-tabs-one-datos-de-bdpaliativos-a" role="tabpanel"
                    aria-labelledby="custom-tabs-one-datos-de-bdpaliativos-a-tab">


                    @csrf
                    @include('paliativos.tablas.tablaPaliativosAux')

                </div>
                <div class="tab-pane fade " id="custom-tabs-one-datos-de-bdpaliativos-ue" role="tabpanel"
                 aria-labelledby="custom-tabs-one-datos-de-bdpaliativos-ue-tab">

        
                    @csrf
                    @include('paliativos.tablas.tablaPaliativosUltimaExpe')
        
                </div>
            </div>

            <!-- /.card -->
        </div>
    </div>
    <button type="button" class="btn-flotante tooltipsC" id="agregar_paciente" title="Agregar paciente"><i
            class="fa fa-fw fa-plus-circle fa-2x"></i><i class="fa fa-user fa-2x"></i></button>

</div>
