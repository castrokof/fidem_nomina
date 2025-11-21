<div class="row">
  <div class="col-12">
    <div class="card-ios">
      
      <!-- Header con Tabs estilo iOS -->
      <div class="tabs-ios-container">
        <ul class="nav-tabs-ios" id="custom-tabs-fisiatria" role="tablist">
          
          <!-- Tab Control Fisiatría -->
          <li class="nav-item-ios">
            <a class="nav-link-ios active" 
               id="tab-control-fisiatria" 
               data-toggle="pill" 
               href="#content-control-fisiatria" 
               role="tab" 
               aria-controls="content-control-fisiatria" 
               aria-selected="true">
              <div class="tab-icon-ios">
                <i class="fas fa-clipboard-list"></i>
              </div>
              <span class="tab-text-ios">Control Fisiatría</span>
              <span class="badge-ios badge-ios-primary" id="count-control">0</span>
            </a>
          </li>
          
          <!-- Tab Citas Agendadas -->
          <li class="nav-item-ios">
            <a class="nav-link-ios" 
               id="tab-citas-agendadas" 
               data-toggle="pill" 
               href="#content-citas-agendadas" 
               role="tab" 
               aria-controls="content-citas-agendadas" 
               aria-selected="false">
              <div class="tab-icon-ios">
                <i class="far fa-calendar-check"></i>
              </div>
              <span class="tab-text-ios">Citas Agendadas</span>
              <span class="badge-ios badge-ios-info" id="count-agendadas">0</span>
            </a>
          </li>
          
          <!-- Tab Seguimiento -->
          <li class="nav-item-ios">
            <a class="nav-link-ios" 
               id="tab-seguimiento" 
               data-toggle="pill" 
               href="#content-seguimiento" 
               role="tab" 
               aria-controls="content-seguimiento" 
               aria-selected="false">
              <div class="tab-icon-ios">
                <i class="fas fa-tasks"></i>
              </div>
              <span class="tab-text-ios">Seguimiento</span>
              <span class="badge-ios badge-ios-success" id="count-seguimiento">0</span>
            </a>
          </li>
          
        </ul>
        
        <!-- Indicador deslizante -->
        <div class="tab-indicator-ios"></div>
      </div>

      <!-- Contenido de los Tabs -->
      <div class="card-body-ios">
        <div class="tab-content-ios" id="custom-tabs-fisiatria-content">
          
          <!-- Contenido Tab Control Fisiatría -->
          <div class="tab-pane-ios fade show active" 
               id="content-control-fisiatria" 
               role="tabpanel" 
               aria-labelledby="tab-control-fisiatria">
            <div class="tab-header-ios">
              <h5 class="tab-title-ios">
                <i class="fas fa-clipboard-list"></i>
                Control de Fisiatría
              </h5>
              <p class="tab-subtitle-ios">
                Gestión y seguimiento de solicitudes de fisiatría
              </p>
            </div>
            @include('fisiatria.tablas.tablaIndexFisiatria')
          </div>

          <!-- Contenido Tab Citas Agendadas -->
          <div class="tab-pane-ios fade" 
               id="content-citas-agendadas" 
               role="tabpanel" 
               aria-labelledby="tab-citas-agendadas">
            <div class="tab-header-ios">
              <h5 class="tab-title-ios">
                <i class="far fa-calendar-check"></i>
                Citas Agendadas
              </h5>
              <p class="tab-subtitle-ios">
                Visualización de citas programadas para fisiatría
              </p>
            </div>
            @include('fisiatria.tablas.tablaIndexFisiatriaAgendado')
          </div>

          <!-- Contenido Tab Seguimiento -->
          <div class="tab-pane-ios fade" 
               id="content-seguimiento" 
               role="tabpanel" 
               aria-labelledby="tab-seguimiento">
            <div class="tab-header-ios">
              <h5 class="tab-title-ios">
                <i class="fas fa-tasks"></i>
                Seguimiento
              </h5>
              <p class="tab-subtitle-ios">
                Monitoreo y seguimiento de casos en proceso
              </p>
            </div>
            @include('fisiatria.tablas.tablaIndexFisiatriaSeguimiento')
          </div>

        </div>
      </div>

    </div>
  </div>
</div>