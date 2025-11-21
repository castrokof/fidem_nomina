    <div class="container-fluid">
    <div class="row">
    <div class="col-md-3">

    <div class="card card-primary card-outline">
    <div class="card-body box-profile">
    <div class="text-center">
    <img class="profile-user-img img-fluid img-circle" src="{{asset("assets/$theme/dist/img/user_default.jpg  ")}}" alt="User profile picture">
    </div>

    <h2 id="namesaddp" class="profile-username text-center text-muted"></h2>
    <p id="documentsaddp" class="text-muted text-center"></p>
    </div>
    </div>
    </div>

    <div class="col-md-9">
    <div class="card card-primary card-outline">
    <div class="card-header p-2">
    <ul class="nav nav-pills">
    <li class="nav-item"><a class="nav-link active" data-toggle="tab">Observación</a>

    </li>
    </ul>
    </div>
    <div class="card-body">

    <div class="tab-pane active" id="activity">
    <div class="post">
        
        <div class="col-lg-12 col-md-12 col-xs-12 p-0">
        <label for="profesionalp" class="col-xs-4 control-label">Profesional asigna cita</label>
        <select name="profesionalp" id="profesionalp" class="form-control select2bs4" style="width: 100%;" >
            <option value="">---seleccione---</option>
                <option value="SANDRA ROMANO">SANDRA ROMANO</option>
                <option value="VICTOR MARTINEZ">VICTOR MARTINEZ</option>
                <option value="JAVIER BENAVIDES">JAVIER BENAVIDES</option>
                <option value="DIANA LOPEZ">DIANA LOPEZ</option>
                <option value="LEONARDO ARCE">LEONARDO ARCE</option>
                <option value="KATALINA ESPINOSA">KATALINA ESPINOSA</option>
                <option value="DIANA MURCIA">DIANA MURCIA</option>
            
        </select>
    </div>    
        <div class="col-lg-12 col-md-12 col-xs-12 p-0">
               <textarea name="addobservacionp" id="addobservacionp" class="form-control UpperCase" rows="5" placeholder="Ingrese la observación ..." value="{{old('addobservacionp')}}" ></textarea>
        </div>

        <input type="hidden" name="enc_idp" id="enc_idp" class="form-control" value="">
        <input type="hidden" name="user_idp" id="user_idp" class="form-control" value="">


    </div>

    <div class="card-footer p-2">
       <button type="button" name="agendado" class="agendado btn btn-app bg-warning tooltipsC" title="Clic para agendar" value="" ><span class="badge bg-teal">Fisiatria</span><i class="fas fa-file-medical"></i> Agendar </button>
    </div>

    </div>





    </div>


    </div>
    </div>
    </div>
    </div>

