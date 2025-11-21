<div class="row">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header bg-personal p-2 pt-2">
                <picture>
                    <img src="{{ asset('assets/img/banner_ava.jpeg') }}"
                        class="img-1 col-xs-12 col-sm-12 col-md-12 m-t-6 text-center" />

                </picture>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-tabs">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-one-datos-del-paciente-tab"
                                    data-toggle="pill" href="#custom-tabs-one-datos-del-paciente" role="tab"
                                    aria-controls="custom-tabs-one-datos-del-paciente"
                                    aria-selected="false">DEPRESION</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-one-datos-agendados-tab" data-toggle="pill"
                                    href="#custom-tabs-one-datos-agendados" role="tab"
                                    aria-controls="custom-tabs-one-datos-agendados" aria-selected="false">TRASTORNO DE
                                    ALIMENTACION ANOREXIA
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-one-datos-agendados1-tab" data-toggle="pill"
                                    href="#custom-tabs-one-datos-agendados1" role="tab"
                                    aria-controls="custom-tabs-one-datos-agendados1" aria-selected="false">TRASTORNO DE
                                    ALIMENTACION BULIMIA

                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-one-datos-agendados2-tab" data-toggle="pill"
                                    href="#custom-tabs-one-datos-agendados2" role="tab"
                                    aria-controls="custom-tabs-one-datos-agendados2" aria-selected="false">BULLYING O
                                    MATONEO

                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade active show" id="custom-tabs-one-datos-del-paciente"
                                role="tabpanel" aria-labelledby="custom-tabs-one-datos-del-paciente-tab">
                                <div class="card-body">


                                    <div class="row">
                                        <div class="col-sm-6 col-12">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="check1 custom-control-input" type="checkbox"
                                                        id="customCheckbox1" value="25">
                                                    <label for="customCheckbox1"
                                                        class="custom-control-label">SENTIMIENTO DE TRISTEZA
                                                    </label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="check1 custom-control-input" type="checkbox"
                                                        id="customCheckbox2" value="25">
                                                    <label for="customCheckbox2" class="custom-control-label">PESISMISMO
                                                    </label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="check1 custom-control-input" type="checkbox"
                                                        id="customCheckbox3" value="25">
                                                    <label for="customCheckbox3"
                                                        class="custom-control-label">FRUSTRACION
                                                    </label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="check1 custom-control-input" type="checkbox"
                                                        id="customCheckbox4" value="25">
                                                    <label for="customCheckbox4"
                                                        class="custom-control-label">INTRAQUILIDAD
                                                    </label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="check1 custom-control-input" type="checkbox"
                                                        id="customCheckbox5" value="25">
                                                    <label for="customCheckbox5" class="custom-control-label">DESINTERES
                                                    </label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="check1 custom-control-input" type="checkbox"
                                                        id="customCheckbox6" value="25">
                                                    <label for="customCheckbox6" class="custom-control-label">FATIGA
                                                    </label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="check1 custom-control-input" type="checkbox"
                                                        id="customCheckbox7" value="25">
                                                    <label for="customCheckbox7" class="custom-control-label">INSOMNIO
                                                    </label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="check1 custom-control-input" type="checkbox"
                                                        id="customCheckbox8" value="25">
                                                    <label for="customCheckbox8" class="custom-control-label">LARGAS
                                                        JORNADAS DE SUEÑO
                                                    </label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="check1 custom-control-input" type="checkbox"
                                                        id="customCheckbox9" value="25">
                                                    <label for="customCheckbox9" class="custom-control-label">DOLOR DE
                                                        CABEZA SIN CAUSA APARENTE
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-12">
                                            <div class="info-box bg-gradient-info">
                                                <span class="info-box-icon"><i class="fas fa-brain"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Depresión</span>
                                                    <span id="depresion_porcentaje" class="info-box-number"></span>
                                                    <div class="progress">
                                                        <div id='progress_bar_1' class="progress-bar" style="">
                                                        </div>
                                                    </div>
                                                    <span id="depresion_progreso" class="progress-description">
                                                        Porcentaje del diagnostico
                                                    </span>
                                                </div>
                                            </div>
                                        </div>




                                    </div>

                                </div>
                            </div>


                            <div class="tab-pane fade " id="custom-tabs-one-datos-agendados" role="tabpanel"
                                aria-labelledby="custom-tabs-one-datos-agendados-tab">
                                <div class="card-body">


                                    <div class="row">

                                        <div class="col-sm-6 col-12">
                                            <div class="card-header">
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="check2 custom-control-input" type="checkbox"
                                                            id="customCheckbox1-1" value="25">
                                                        <label for="customCheckbox1-1"
                                                            class="custom-control-label">PERDIDA EXAGERADA DE PESO

                                                        </label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="check2 custom-control-input" type="checkbox"
                                                            id="customCheckbox1-2" value="25">
                                                        <label for="customCheckbox1-2"
                                                            class="custom-control-label">ANEMIA LEVE

                                                        </label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="check2 custom-control-input" type="checkbox"
                                                            id="customCheckbox1-3" value="25">
                                                        <label for="customCheckbox1-3"
                                                            class="custom-control-label">DEBILIDAD MUSCULAR

                                                        </label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="check2 custom-control-input" type="checkbox"
                                                            id="customCheckbox1-4" value="25">
                                                        <label for="customCheckbox1-4"
                                                            class="custom-control-label">TEMOR INTENSO POR SUBIR DE
                                                            PESO

                                                        </label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="check2 custom-control-input" type="checkbox"
                                                            id="customCheckbox1-5" value="25">
                                                        <label for="customCheckbox1-5"
                                                            class="custom-control-label">IMAGEN CORPORAL DISTORSIONADA

                                                        </label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="check2 custom-control-input" type="checkbox"
                                                            id="customCheckbox1-6" value="25">
                                                        <label for="customCheckbox1-6"
                                                            class="custom-control-label">RECHAZO O NEGACION DE SU
                                                            CONDICION FISICA

                                                        </label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="check2 custom-control-input" type="checkbox"
                                                            id="customCheckbox1-7" value="25">
                                                        <label for="customCheckbox1-7"
                                                            class="custom-control-label">PIEL SECA Y AMARILLA

                                                        </label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="check2 custom-control-input" type="checkbox"
                                                            id="customCheckbox1-8" value="25">
                                                        <label for="customCheckbox1-8"
                                                            class="custom-control-label">LESTREÑIMIENTO GRAVE

                                                        </label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="check2 custom-control-input" type="checkbox"
                                                            id="customCheckbox1-9" value="25">
                                                        <label for="customCheckbox1-9"
                                                            class="custom-control-label">SENSACION DE FRIO CONSTANTE
                                                            (TEMPERATURA CORPORAL BAJA)

                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-12">
                                            <div class="info-box bg-gradient-warning">
                                                <span class="info-box-icon"><i class="fas fa-pills"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Trastorno de alimentación
                                                        anorexia</span>
                                                    <span id="anorexia_porcentaje" class="info-box-number"></span>
                                                    <div class="progress">
                                                        <div id='progress_bar_2' class="progress-bar" style="">
                                                        </div>
                                                    </div>
                                                    <span id="anorexia_progreso" class="progress-description">
                                                        Porcentaje del diagnostico
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>





                                </div>

                            </div>
                            <div class="tab-pane fade " id="custom-tabs-one-datos-agendados1" role="tabpanel"
                                aria-labelledby="custom-tabs-one-datos-agendados1-tab">
                                <div class="card-body">

                                    <div class="row">

                                        <div class="col-sm-6 col-12">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="check3 custom-control-input" type="checkbox"
                                                        id="customCheckbox2-1" value="25">
                                                    <label for="customCheckbox2-1" class="custom-control-label">DOLOR
                                                        E INFLAMACION DE GARGANTA

                                                    </label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="check3 custom-control-input" type="checkbox"
                                                        id="customCheckbox2-2" value="25">
                                                    <label for="customCheckbox2-2"
                                                        class="custom-control-label">INFLAMACION DE GLANDULAS SALIVARES

                                                    </label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="check3 custom-control-input" type="checkbox"
                                                        id="customCheckbox2-3" value="25">
                                                    <label for="customCheckbox2-3"
                                                        class="custom-control-label">DESGASTE Y SENSIBILIDAD DENTAL

                                                    </label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="check3 custom-control-input" type="checkbox"
                                                        id="customCheckbox2-4" value="25">
                                                    <label for="customCheckbox2-4"
                                                        class="custom-control-label">REFLUJO

                                                    </label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="check3 custom-control-input" type="checkbox"
                                                        id="customCheckbox2-5" value="25">
                                                    <label for="customCheckbox2-5"
                                                        class="custom-control-label">DESHIDRATACION

                                                    </label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="check3 custom-control-input" type="checkbox"
                                                        id="customCheckbox2-6" value="25">
                                                    <label for="customCheckbox2-6"
                                                        class="custom-control-label">GANANCIA EXCESIVA DE PESO

                                                    </label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="check3 custom-control-input" type="checkbox"
                                                        id="customCheckbox2-7" value="25">
                                                    <label for="customCheckbox2-7"
                                                        class="custom-control-label">CONSUMO EXCESIVO DE ALIMENTOS

                                                    </label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="check3 custom-control-input" type="checkbox"
                                                        id="customCheckbox2-8" value="25">
                                                    <label for="customCheckbox2-8" class="custom-control-label">COMER
                                                        MUY RAPIDO

                                                    </label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="check3 custom-control-input" type="checkbox"
                                                        id="customCheckbox2-9" value="25">
                                                    <label for="customCheckbox2-9" class="custom-control-label">COMER
                                                        HASTA SENTIR MALESTAR

                                                    </label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="check3 custom-control-input" type="checkbox"
                                                        id="customCheckbox2-10" value="25">
                                                    <label for="customCheckbox2-10"
                                                        class="custom-control-label">SENTIMIENTO DE ANGUSTIA POR COMER


                                                    </label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="check3 custom-control-input" type="checkbox"
                                                        id="customCheckbox2-11" value="25">
                                                    <label for="customCheckbox2-11"
                                                        class="custom-control-label">REALIZAR DIETAS FRECUENTES SIN
                                                        PERDER PESO


                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-12">
                                            <div class="info-box bg-gradient-success">
                                                <span class="info-box-icon"><i
                                                        class="fas fa-mortar-pestle"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Trastorno de alimentación
                                                        bulimia</span>
                                                    <span id="bulimia_porcentaje" class="info-box-number"></span>
                                                    <div class="progress">
                                                        <div id='progress_bar_3' class="progress-bar" style="">
                                                        </div>
                                                    </div>
                                                    <span id="bulimia_progreso" class="progress-description">
                                                        Porcentaje del diagnostico
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>




                                </div>

                            </div>
                            <div class="tab-pane fade " id="custom-tabs-one-datos-agendados2" role="tabpanel"
                                aria-labelledby="custom-tabs-one-datos-agendados2-tab">
                                <div class="card-body">


                                    <div class="row">

                                        <div class="col-sm-6 col-12">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="check4 custom-control-input" type="checkbox"
                                                        id="customCheckbox3-1" value="25">
                                                    <label for="customCheckbox3-1"
                                                        class="custom-control-label">DEPRESION O TRSITEZA

                                                    </label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="check4 custom-control-input" type="checkbox"
                                                        id="customCheckbox3-2" value="25">
                                                    <label for="customCheckbox3-2" class="custom-control-label">BAJA
                                                        AUTOESTIMA

                                                    </label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="check4 custom-control-input" type="checkbox"
                                                        id="customCheckbox3-3" value="25">
                                                    <label for="customCheckbox3-3"
                                                        class="custom-control-label">SILENCIO INUSUAL

                                                    </label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="check4 custom-control-input" type="checkbox"
                                                        id="customCheckbox3-4" value="25">
                                                    <label for="customCheckbox3-4" class="custom-control-label">DOLOR
                                                        DE CABEZA

                                                    </label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="check4 custom-control-input" type="checkbox"
                                                        id="customCheckbox3-5" value="25">
                                                    <label for="customCheckbox3-5" class="custom-control-label">VOMITO
                                                        SIN CAUSA APARENTE

                                                    </label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="check4 custom-control-input" type="checkbox"
                                                        id="customCheckbox3-6" value="25">
                                                    <label for="customCheckbox3-6"
                                                        class="custom-control-label">LESIONES SIN EXPLICACION

                                                    </label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="check4 custom-control-input" type="checkbox"
                                                        id="customCheckbox3-7" value="25">
                                                    <label for="customCheckbox3-7"
                                                        class="custom-control-label">PENSAMIENTOS SUICIDAS

                                                    </label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="check4 custom-control-input" type="checkbox"
                                                        id="customCheckbox3-8" value="25">
                                                    <label for="customCheckbox3-8"
                                                        class="custom-control-label">IRRITABILIDAD

                                                    </label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="check4 custom-control-input" type="checkbox"
                                                        id="customCheckbox3-9" value="25">
                                                    <label for="customCheckbox3-9"
                                                        class="custom-control-label">DIFICULTAD PARA DORMIR

                                                    </label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="check4 custom-control-input" type="checkbox"
                                                        id="customCheckbox3-10" value="25">
                                                    <label for="customCheckbox3-10"
                                                        class="custom-control-label">PERDIDA INESPERADA DE AMIGOS


                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-12">
                                            <div class="info-box bg-gradient-danger">
                                                <span class="info-box-icon"><i class="fas fa-diagnoses"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Bullying o Matoneo</span>
                                                    <span id="matoneo_porcentaje" class="info-box-number"></span>
                                                    <div class="progress">
                                                        <div id='progress_bar_4' class="progress-bar" style="">
                                                        </div>
                                                    </div>
                                                    <span id="matoneo_progreso" class="progress-description">
                                                        Porcentaje del diagnostico
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>




                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
