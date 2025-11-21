    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">

                <div class="card card-success card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle"
                                src="{{ asset("assets/$theme/dist/img/user_default.jpg  ") }}" alt="User profile picture">
                        </div>

                        <h2 id="namesadd" class="profile-username text-center text-muted"></h2>
                        <p id="documentsadd" class="text-muted text-center"></p>
                        <div id="totala" class="info-box">
                        </div>
                        <div class="direct-chat-messages" id="btnalert">


                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card card-success card-outline">
                    <div class="card-header p-2 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">

                            <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-one-datos-agendados1-tab" data-toggle="pill"
                                    href="#custom-tabs-one-datos-agendados1" role="tab"
                                    aria-controls="custom-tabs-one-datos-agendados1" aria-selected="false" style="background-color: #28a745">Novedades</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link "id="custom-tabs-one-datos-del-paciente1-tab" data-toggle="pill"
                                     href="#custom-tabs-one-datos-del-paciente1" role="tab"
                                    aria-controls="custom-tabs-one-datos-del-paciente1"  aria-selected="false" style="background-color: #28a745">Detalle Novedades</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">


                            <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade active show" id="custom-tabs-one-datos-agendados1" role="tabpanel" aria-labelledby="custom-tabs-one-datos-agendados1-tab">
                            <div class="post">
                                <div class="form-group row">
                                    <div class="col-lg-3">
                                        <label for="type_obs" class="col-xs-4 control-label requerido">Tipo
                                            Observacion</label>
                                        <select name="type_obs" id="type_obs" class="form-control select2bs4"
                                            style="width: 100%;" required>
                                        </select>
                                    </div>
                                    <div id="subtype" class="col-lg-3" style="display:none;">
                                        <label for="subtype_obs" class="col-xs-4 control-label requerido">Sub
                                            tipo</label>
                                        <select name="subtype_obs" id="subtype_obs" class="form-control select2bs4"
                                            style="width: 100%;">
                                        </select>
                                    </div>
                                     <div id="datehospi" class="col-lg-4" style="display:none;">
                                     <label for="date_hospi" class="col-xs-4 control-label requerido">Fecha de Hospitalizacion</label>
                                     <input type="date" name="future1" id="date_hospi"  class="form-control" value="{{old('date_dead')}}" required>
                                     </div>
                                     
                                     <div id="dateegreso" class="col-lg-4" style="display:none;">
                                     <label for="date_egreso" class="col-xs-4 control-label requerido">Fecha de egreso</label>
                                     <input type="date" name="future2" id="date_egreso"  class="form-control" value="{{old('date_dead')}}" required>
                                     </div> 
                                   
                                  
                                    <div id="subtype1" class="col-lg-3" style="display:none;">
                                        <label for="subtype_obs1" class="col-xs-4 control-label requerido">Sub
                                            tipo</label>
                                        <select name="subtype_obs" id="subtype_obs1" class="form-control select2bs4"
                                            style="width: 100%;">
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-xs-12 p-0">
                                    <textarea name="observacion" id="addobservacion" class="form-control UpperCase select2bs4" rows="5"
                                        placeholder="Ingrese la observación ..." value="{{ old('addobservacion') }}"></textarea>
                                </div>

                                <input type="hidden" name="pac_id" id="evo_id" class="form-control" value="">
                                <input type="hidden" name="user_id" id="user_id" class="form-control"
                                    value="{{ Session()->get('usuario_id') }}">


                            </div>

                            <div class="card-footer p-2">
                                <span class="float-right">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <input type="submit" name="action_seguimiento" id="action_seguimiento"
                                                class="updateseguimiento btn btn-success" value="Add" />
                                            <input type="hidden" name="action_seguimientou" id="action_seguimientou"
                                                class="btn btn-success" />
                                        </div>
                                    </div>
                                </span>
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
