<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle" src="{{asset("assets/$theme/dist/img/user_default.jpg")}}" alt="User profile picture">
                    </div>
                    <h2 id="names2" class="profile-username text-center text-muted"></h2>
                    <p id="documents1" class="text-muted text-center"></p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item accent-blue">
                            <b class="text-muted">F Solicitud</b> <a id="fecha_solicitud1" class="float-right"></a>
                        </li>
                        <li class="list-group-item accent-blue">
                            <b class="text-muted">Profesional</b> <a id="profesional1" class="float-right"></a>
                        </li>
                        <li class="list-group-item accent-blue">
                            <b class="text-muted">Diagnóstico</b> <a id="dx1" class="float-right"></a>
                        </li>
                        <li class="list-group-item accent-blue">
                            <b class="text-muted">Tipo Solicitud</b> <a id="tipo_solicitud1" class="float-right"></a>
                        </li>
                        <li class="list-group-item accent-blue">
                            <b class="text-muted">EPS</b> <a id="eapb1" class="float-right"></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card card-primary card-outline">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" id="consultation1" data-toggle="tab">Detalle Solicitud Fisiatría</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="activity">
                            <div class="post">
                                <div class="user-block">
                                    <span class="username">
                                        <a id="names3"></a>
                                        <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                                    </span>
                                    <span id="address1" class="description"></span>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <b class="text-muted">Dispositivos:</b>
                                        <p id="dispositivos1"></p>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <b class="text-muted">Solicitud Dispositivo:</b>
                                        <p id="solicitud1"></p>
                                    </div>
                                    <div class="col-6">
                                        <b class="text-muted">Camilla/Ambulancia:</b>
                                        <p id="camilla1"></p>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <b class="text-muted">Antecedentes:</b>
                                        <p id="antecedentes1"></p>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <b class="text-muted">Motivo de Consulta:</b>
                                        <p id="evolution1"></p>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <b class="text-muted">Observación:</b>
                                        <p id="observacion1"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer card-blue p-2">
                    <span id="created_at1" class="text-muted card-primary card-outline float-right"></span>
                </div>
            </div>
        </div>
    </div>
</div>