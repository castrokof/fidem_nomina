    <div class="container-fluid">
    <div class="row">
    <div class="col-md-3">

    <div class="card card-warning card-outline">
    <div class="card-body box-profile">
    <div class="text-center">
    <img class="profile-user-img img-fluid img-circle" src="{{asset("assets/$theme/dist/img/user_default.jpg  ")}}" alt="User profile picture">
    </div>

    <h2 id="namesestado" class="profile-username text-center text-muted"></h2>
    <p id="documentestado" class="text-muted text-center"></p>
    </div>
    </div>
    </div>

    <div class="col-md-9">
    <div class="card card-warning card-outline">
    <div class="card-header p-2">
    <ul class="nav nav-pills">
    <li class="nav-item"><a class="nav-link navbar-warning active" style="background-color: #ffc107"; data-toggle="tab">Estado y Ambito</a>

    </li>
    </ul>
    </div>
    <div class="card-body">

    <div class="tab-pane active" id="activity">
    <div class="post">
        <div class="form-group row">
        <div class="col-lg-4">
            <label for="type_state" class="col-xs-4 control-label">Estado</label>
            <select name="state" id="type_state" class="form-control select2bs4" style="width: 100%;" required>
            </select>
        </div>
        <div id="subtype" class="col-lg-4">
            <label for="ambito_type" class="col-xs-4 control-label requerido">Ambito</label>
            <select name="type" id="ambito_type" class="form-control select2bs4" style="width: 100%;" required>
            </select>
        </div>
        </div>

        <input type="hidden" name="evo_id" id="evo_id" class="form-control" value="">
       


    </div>

    <div class="card-footer p-2">
        <span class="float-right">
            <div class="row">
            <div class="col-xs-3">
             <input type ="submit" name="action_estado" id="action_pro" class="updateestado btn btn-success" value="Add"/>
             <input type ="hidden" name="action_estadou" id="action_estadou" class="btn btn-success" />
           </div>
           </div>
        </span>
    </div>

    </div>





    </div>


    </div>
    </div>
    </div>
    </div>

