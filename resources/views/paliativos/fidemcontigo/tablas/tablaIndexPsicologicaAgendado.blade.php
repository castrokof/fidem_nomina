<div class="row">
    <div class="col-lg-12">
        @include('includes.form-error')
        @include('includes.form-mensaje')
    <div class="card card-primary">
        <div class="card-header with-border">
          <h3 class="card-title">Control Procedimientos agendadas</h3>  
          

        </div>
      <div class="card-body table-responsive p-2">

      <table id="psicologicaAgendada" class="table table-hover  text-nowrap">
        {{-- class="table table-hover table-bordered text-nowrap" --}}
        <thead>
        <tr>
              
              <th>Acciones</th>
              <th>Agendado</th>
              <th>Id</th>
              <th>Primer apellido</th>
              <th>Segundo apellido</th>
              <th>Primer nombre</th>
              <th>Segundo nombre</th>
              <th>Tipo Documento</th>
              <th>Documento</th>
              <th>Fecha de solicitud</th>
              <th>Regimen</th>
              <th>Otro</th>
              <th>Direccion</th>
              <th>Celular</th>
              <th>Telefono</th>
              <th>Correo</th>
              <th>Nivel</th>
              <th>Eapb</th>
              <th>Procedimiento/Obs.</th>
              <th>Profesional</th>
              <th>Copago</th>
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
