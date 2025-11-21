    <div class="container-fluid">
    <div class="row">
    <div class="col-md-3">

    <div class="card card-primary card-outline">
    <div class="card-body box-profile">
    <div class="text-center">
    <img class="profile-user-img img-fluid img-circle" src="{{asset("assets/$theme/dist/img/user_default.jpg  ")}}" alt="User profile picture">
    </div>

    <h2 id="names" class="profile-username text-center text-muted"></h2>
    <p id="documents" class="text-muted text-center"></p>
    <ul class="list-group list-group-unbordered mb-3">
    <li class="list-group-item accent-blue">
    <b  class="text-muted">F Solicitud</b> <a id="date_birth" class="float-right"></a>
    </li>
    <li class="list-group-item accent-blue">
        <b  class="text-muted">EPS</b> <a id="celular" class="float-right"></a>
    </li>
    <li class="list-group-item accent-blue">
        <b  class="text-muted">MEDICO ASIGNADO</b> <a id="sex" class="float-right"></a>
    </li>
    </ul>

    </div>
    </div>
    </div>

    <div class="col-md-9">
    <div class="card card-primary card-outline card-tabs">
        {{-- <div class="card card-primary card-outline card-tabs"> --}}
    <div class="card-header p-0 pt-1">
         
    <ul class="nav nav-pills">
    <li class="nav-item"><a class="nav-link active" id="consultation" data-toggle="tab"></a></li>
    </ul>
   
    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
        <li class="nav-item">
              <a class="nav-link active"
              id="custom-tabs-one-datos-agendados1-tab"
              data-toggle="pill"
              href="#custom-tabs-one-datos-agendados1"
              role="tab"
              aria-controls="custom-tabs-one-datos-agendados1"
              aria-selected="false">Solicitud</a>
        </li>
        <li class="nav-item">
            <a class="nav-link "
            id="custom-tabs-one-datos-del-paciente1-tab"
            data-toggle="pill"
            href="#custom-tabs-one-datos-del-paciente1"
            role="tab"
            aria-controls="custom-tabs-one-datos-del-paciente1"
            aria-selected="false">Observaciones</a>
     </li>

    </ul>


    </div>
    <div class="card-body">
    <div class="tab-content" id="custom-tabs-one-tabContent">
    <div class="tab-pane fade active show" id="custom-tabs-one-datos-agendados1" role="tabpanel" aria-labelledby="custom-tabs-one-datos-agendados1-tab">
    <div class="card-body">
    <div class="post">
    <div class="user-block">
     <span class="username">
    <a id="names1"></a>
    </span>
    <span id ="address" class="description"></span>
    </div>
    <b  class="text-muted">Solicitud:</b>
    <p id="evolution">

    </p>
    
    <b  class="text-muted">Observación:</b>
    <p id="observacion">

    </p>

    </div>


    <div class="card-footer card-blue p-2">
        <span id ="created_at" class="text-muted card-primary card-outline float-right"></span>
    </div>

    </div>
    </div>

    <div class="tab-pane fade" id="custom-tabs-one-datos-del-paciente1" role="tabpanel" aria-labelledby="custom-tabs-one-datos-del-paciente1-tab">
        <div class="card-body accent-blue">
            <div class="direct-chat-messages" id="observaciones_chat">

            </div>

        </div>


    </div>
    </div>


    </div>
    </div>
    </div>
    </div>
</div>

