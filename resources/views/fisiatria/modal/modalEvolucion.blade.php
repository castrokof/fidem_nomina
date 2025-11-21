<div class="modal fade" tabindex="-1" id ="modal-evolution" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog  modal-xl" role="document">
    <div class="modal-content">
    <div class="row">
        <div class="col-lg-12">

            <span id="form_result"></span>
             <div class="card card-primary" style="transition: all 0.15s ease 0s; height: inherit; width: inherit;">
            <div class="card-header with-border">
              <div class="fidem-header">
                <div class="row align-items-center">
                    <div class="col-md-2">
                        <div style="font-size: 2.5rem; font-weight: bold;">∩</div>
                    </div>
                    <div class="col-md-6">
                        <div class="fidem-title">FIDEM</div>
                        <div class="fidem-subtitle">CLÍNICA ESPECIALIZADA EN DOLOR</div>
                    </div>
                    <div class="col-md-4">
                        <h4>ENCUESTA PARA CITAS DE FISIATRÍA</h4>
                    </div>
                </div>
            </div>
          

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="maximize">
                    <i class="fas fa-expand"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>

                    <button type="button" class="btn btn-tool" data-dismiss="modal">
                      <i class="fas fa-times"></i>
                    </button>


                </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="card card-primary card-tabs">
                  <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active"
                        id="custom-tabs-one-datos-del-paciente-tab"
                        data-toggle="pill"
                        href="#custom-tabs-one-datos-del-paciente"
                        role="tab"
                        aria-controls="custom-tabs-one-datos-del-paciente"
                        aria-selected="false">Datos del paciente</a>
                        
                      </li>
                      <div class="col-md-6">
                          @include('fisiatria.form.formConsultaDocumento')
                      </div>  
                        
                      </ul>
                                           
                  </div>
                  <div class="card-body">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                      <div class="tab-pane fade active show" id="custom-tabs-one-datos-del-paciente" role="tabpanel" aria-labelledby="custom-tabs-one-datos-del-paciente-tab">
                        <div class="card-body">
                           
                        <form  id="form-general" class="form-horizontal" method="POST">
                            @csrf
                            @include('fisiatria.form.form')
                            @include('includes.boton-form-crear-empresa-empleado-usuario')
                        </form>
                        </div>
                      </div>

                     </form>
                   </div>
                  </div>
                  <!-- /.card -->
                </div>
              </div>

            </div>



      </div>
    </div>
  </div>
</div>
</div>
</div>
