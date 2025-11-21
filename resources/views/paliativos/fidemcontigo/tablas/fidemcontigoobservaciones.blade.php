<div class="row">
    <div class="col-lg-12">
        @include('includes.form-error')
        @include('includes.form-mensaje')
    <div class="card card-info">
        <div class="card-header with-border">
          <h3 class="card-title">Fidem Contigo</h3>
          <div class="card-tools pull-right">
            <!-- <button type="button" class="btn create_evolution btn-default" name="create_evolution" id="create_evolution" ><i class="fa fa-fw fa-plus-circle"></i>Nueva evolución</button> -->
           </div>
        </div>
      <div class="card-body table-responsive p-2">

      <table id="psicologica" class="table table-hover  text-nowrap">
       
        <thead>
            <tr>
              <th>Acciones</th>
              <th>N° Documento</th>
              <th>Apellido</th>
              <th>Nombre</th>
              <th>Cuestionario</th>
              <th>Respuesta</th>
              <th>Profesional</th>
              <th>FechaApertura</th>
              <th>FechaEvolución</th>
              <th>EPS</th>
              <th>Telefono</th>
              <th>Pertinencia</th>
              <th>Medicamento</th>
              <th>Nombre medicamento</th>
              <th>Observaciones</th>
             </tr>
        </thead>
        <tbody>
  @foreach ($registros as $registro)
    <tr>
        <td>
            <!-- Aquí irán tus acciones como botones de editar/eliminar -->
            <!-- Por ejemplo: -->
            <a href="#" class="btn btn-sm btn-primary">Editar</a>
        </td>
        <td>{{ $registro->documento ?? '' }}</td>
        <td>{{ $registro->apellido ?? '' }}</td>
        <td>{{ $registro->nombre ?? '' }}</td>
        <td>{{ $registro->cuestionario ?? '' }}</td>
        <td>{{ $registro->respuesta ?? '' }}</td>
        <td>{{ $registro->profesional ?? '' }}</td>
        <td>{{ $registro->fecha ?? '' }}</td>
        <td>{{ $registro->historia ?? '' }}</td>
        <td>{{ $registro->eps ?? '' }}</td>
        <td>{{ $registro->tel ?? '' }}</td>
        <td>{{ $registro->pertinencia ?? '' }}</td>
        <td>{{ $registro->medicamentos ?? '' }}</td>
        <td>{{ $registro->nombreMedicamento ?? '' }}</td>
        <td>{{ $registro->observacion ?? '' }}</td>
    </tr>
  @endforeach
</tbody>

      </table>
    </div>
  </form>
    <!-- /.card-body -->
</div>
</div>
</div>
