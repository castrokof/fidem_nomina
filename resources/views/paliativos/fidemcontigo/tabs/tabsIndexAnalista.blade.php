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
        aria-selected="false">Activos Sin Seguimiento</a>
      </li>
      <li class="nav-item">
        <a class="nav-link"
        id="custom-tabs-one-datos-agendados-tab"
        data-toggle="pill"
        href="#custom-tabs-one-datos-agendados"
        role="tab"
        aria-controls="custom-tabs-one-datos-agendados"
        aria-selected="false">Activos Con Seguimiento</a>
      </li>
      <li class="nav-item">
        <a class="nav-link"
        id="custom-tabs-one-datos-seguimiento-tab"
        data-toggle="pill"
        href="#custom-tabs-one-datos-seguimiento"
        role="tab"
        aria-controls="custom-tabs-one-datos-seguimiento"
        aria-selected="false">Activos Sin Contacto</a>
      </li>
      <li class="nav-item">
        <a class="nav-link"
        id="custom-tabs-one-datos-priorizados-tab"
        data-toggle="pill"
        href="#custom-tabs-one-datos-priorizados"
        role="tab"
        aria-controls="custom-tabs-one-datos-priorizados"
        aria-selected="false">Activos Priorizados</a>
      </li>
      </ul>
  </div>
  <div class="card-body">
   <div class="tab-content" id="custom-tabs-one-tabContent">
      <div class="tab-pane fade active show" id="custom-tabs-one-datos-del-paciente" role="tabpanel" aria-labelledby="custom-tabs-one-datos-del-paciente-tab">
        <div class="card-body">
       @include('paliativos.fidemcontigo.tablas.fidemcontigo')
      </div>
      </div>

      <div class="tab-pane fade " id="custom-tabs-one-datos-agendados" role="tabpanel" aria-labelledby="custom-tabs-one-datos-agendados-tab">
        <div class="card-body">
       @include('paliativos.fidemcontigo.tablas.fidemcontigo_seguimiento')
      </div>

    </div>

    <div class="tab-pane fade " id="custom-tabs-one-datos-seguimiento" role="tabpanel" aria-labelledby="custom-tabs-one-datos-seguimiento-tab">
      <div class="card-body">
       @include('paliativos.fidemcontigo.tablas.fidemcontigo_sincontacto')
      </div>

    </div>
    <div class="tab-pane fade " id="custom-tabs-one-datos-priorizados" role="tabpanel" aria-labelledby="custom-tabs-one-datos-priorizados-tab">
      <div class="card-body">
       @include('paliativos.fidemcontigo.tablas.fidemcontigo_priorizados')
      </div>

    </div>
  </div>
  </div>
</div>
</div>
</div>
