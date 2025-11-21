<div class="modal fade" tabindex="-1" id ="modal-evolution" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog  modal-xl" role="document">
    <div class="modal-content" style="background-color: #f8f9fa; border-radius: 12px; max-height: 90vh; overflow-y: auto;">
    <div class="row">
        <div class="col-lg-12">

            <span id="form_result"></span>
             <div class="card card-info" style="background-color: #ffffff; transition: all 0.15s ease 0s; height: inherit; width: inherit;">
            <div class="card-header with-border" style="background-color:#089ea3; color: #fff;">
              <h3 class="card-title">Evolución</h3>

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
                <div class="card" style=" background-color: #FFFFFF; ">
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
                          @include('paliativos.fidemcontigo.form.form')
                      </div>  
                        
                      </ul>
                                           
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
