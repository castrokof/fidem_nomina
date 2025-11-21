<div class="row">
  <div class="col-lg-12">
    @include('includes.form-error')
    @include('includes.form-mensaje')
    
    <div class="card card-primary card-outline">
      <!-- Card Header -->
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-list-alt"></i> Lista de Solicitudes Fisiatría
        </h3>
        <div class="card-tools">
          <span class="badge badge-primary badge-pill" id="totalRegistros">0 registros</span>
          {{-- <button type="button" 
                  class="btn btn-sm btn-primary create_evolution" 
                  name="create_evolution" 
                  id="create_evolution">
            <i class="fa fa-plus-circle"></i> Nueva Evolución
          </button> --}}
        </div>
      </div>

      <!-- Card Body con tabla -->
      <div class="card-body p-0">
        <div class="table-responsive">
          <table id="fisiatria" class="table table-hover table-striped table-valign-middle mb-0">
            <thead class="thead-light">
              <tr>
                <th class="text-center" style="width: 100px;">
                  <i class="fas fa-cog"></i> Acciones
                </th>
                <th class="text-center" style="width: 60px;">ID</th>
                <th>
                  <i class="fas fa-user"></i> Primer Nombre
                </th>
                <th>Segundo Nombre</th>
                <th>Primer Apellido</th>
                <th>Segundo Apellido</th>
                <th class="text-center">
                  <i class="fas fa-id-card"></i> Tipo Doc.
                </th>
                <th>Documento</th>
                <th>
                  <i class="fas fa-hospital"></i> EAPB
                </th>
                <th class="text-center">
                  <i class="far fa-calendar-alt"></i> Fecha Solicitud
                </th>
                <th>
                  <i class="fas fa-user-md"></i> Profesional
                </th>
                <th>
                  <i class="fas fa-stethoscope"></i> Diagnóstico
                </th>
                <th class="text-center">
                  <i class="fas fa-wheelchair"></i> Disp. Silla
                </th>
                <th class="text-center">Disp. Apoyo</th>
                <th>Otro Dispositivo</th>
                <th>Solicitud Dispositivo</th>
                <th class="text-center">Ant. Cáncer</th>
                <th class="text-center">Ant. Toxina</th>
                <th class="text-center">
                  <i class="fas fa-ambulance"></i> Camilla/Amb.
                </th>
                <th>Tipo Solicitud</th>
                <th>Motivo Consulta</th>
                <th>
                  <i class="fas fa-comment-dots"></i> Observación
                </th>
                <th class="text-center">
                  <i class="far fa-clock"></i> Fecha Creación
                </th>
              </tr>
            </thead>
            <tbody>
              <!-- Los datos se cargan dinámicamente vía DataTables -->
            </tbody>
            <tfoot class="thead-light">
              <tr>
                <th colspan="23" class="text-center text-muted">
                  <small>Use los filtros superiores para buscar información específica</small>
                </th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>

      <!-- Card Footer con información adicional -->
      <div class="card-footer">
        <div class="row">
          <div class="col-sm-12 col-md-5">
            <div class="dataTables_info" role="status">
              <small class="text-muted">
                <i class="fas fa-info-circle"></i> 
                La tabla se actualiza automáticamente según los filtros aplicados
              </small>
            </div>
          </div>
          <div class="col-sm-12 col-md-7">
            <div class="text-right">
              <button type="button" class="btn btn-sm btn-outline-primary" id="exportarExcel">
                <i class="fas fa-file-excel"></i> Exportar Excel
              </button>
              <button type="button" class="btn btn-sm btn-outline-danger" id="exportarPDF">
                <i class="fas fa-file-pdf"></i> Exportar PDF
              </button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>