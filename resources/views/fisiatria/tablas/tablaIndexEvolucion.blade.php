<div class="row">
    <div class="col-lg-12">
        @include('includes.form-error')
        @include('includes.form-mensaje')
    <div class="card card-info">
        <div class="card-header with-border">
          <h3 class="card-title">Control Solicitudes Fisiatría</h3>
          <div class="card-tools pull-right">
            <button type="button" class="btn create_evolution btn-default" name="create_evolution" id="create_evolution"><i class="fa fa-fw fa-plus-circle"></i>Nueva Solicitud</button>
           </div>
        </div>
      <div class="card-body table-responsive p-2">
      <table id="fisiatria" class="table table-hover text-nowrap">
        <thead>
        <tr>
              <th>Acciones</th>
              <th>Id</th>
              <th>Primer nombre</th>
              <th>Segundo nombre</th>
              <th>Primer apellido</th>
              <th>Segundo apellido</th>
              <th>Tipo Documento</th>
              <th>Documento</th>
              <th>EAPB</th>
              <th>Fecha Solicitud</th>
              <th>Profesional</th>
              <th>Diagnóstico</th>
              <th>Dispositivo Silla</th>
              <th>Dispositivo Apoyo</th>
              <th>Otro Dispositivo</th>
              <th>Solicitud Dispositivo</th>
              <th>Antecedentes Cáncer</th>
              <th>Antecedentes Toxina</th>
              <th>Camilla/Ambulancia</th>
              <th>Tipo Solicitud</th>
              <th>Motivo Consulta</th>
              <th>Observación</th>
              <th>Fecha Creación</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
    <!-- /.card-body -->
</div>
</div>
</div>