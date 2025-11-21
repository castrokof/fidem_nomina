    <div class="container-fluid">
    <div class="row">
    <div class="col-md-3">

    <div class="card card-danger card-outline">
    <div class="card-body box-profile">
    <div class="text-center">
    <img class="profile-user-img img-fluid img-circle" src="{{asset("assets/$theme/dist/img/user_default.jpg  ")}}" alt="User profile picture">
    </div>

    <h2 id="namesfallecido" class="profile-username text-center text-muted"></h2>
    <p id="documentsfallecido" class="text-muted text-center"></p>
    </div>
    </div>
    </div>

    <div class="col-md-9">
    <div class="card card-danger card-outline">
    <div class="card-header p-2">
    <ul class="nav nav-pills">
    <li class="nav-item"><a class="nav-link active" data-toggle="tab" style="background-color: #dc3545">Fecha de fallecimiento</a>

    </li>
    </ul>
    </div>
    <div class="card-body">

    <div class="tab-pane active" id="activity">
    <div class="post">
        <div class="form-group row">
            <div class="col-lg-12 col-md-12 col-xs-12 p-0">
            <label for="date_dead" class="col-xs-4 control-label requerido">Fecha de fallecimiento</label>
           <input type="date" name="date_dead" id="date_dead"  class="form-control" value="{{old('date_dead')}}" required>
           <input type="hidden" name="dead" id="dead" class="form-control" value="SI" required>

        </div>

        </div>


        <input type="hidden" name="evo_id" id="evo_id" class="form-control" value="">
       


    </div>

    <div class="card-footer p-2">
        <span class="float-right"
        <div class="row">
            <div class="col-xs-3">
             <input type ="submit" name="action_dead" id="action_dead" class="updatedead btn btn-success" value="Add"/>
             <input type ="hidden" name="action_deadu" id="action_deadu" class="btn btn-success" />
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

