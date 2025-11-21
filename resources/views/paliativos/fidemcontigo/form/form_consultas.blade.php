            <div class="row">
                <div class="content-header">
                  <div class="container-fluid">
                    <div class="row mb-12">
                      <div class="col-sm-12">
                       
                      </div><!-- /.col -->

                      @csrf
                      <div class="card-body">
                      <div class="row col-lg-12">

                        <div class="form-group row col-lg-12">
                            <div class="col-lg-4">
                                   <label for="fechaini" class="col-xs-2 control-label requerido">Fecha de
                                    Atención</label>
                                    <div class="form-group row">
                                    <input type="date" name="fechaini" id="fechaini" class="form-control col-md-6"
                                        value="">
                                    <input type="date" name="fechafin" id="fechafin" class="form-control col-md-6"
                                        value="">
                                    </div>
                            </div>
                            <div class="col-lg-2">
                                   <label for="evac" class="col-xs-2 control-label requerido">Eva</label>
                                   
                                    <select name="evac" id="evac" class="form-control select2bs4" style="width: 100%;" >
                                    <option value="">-seleccione-</option>
                                    <option value="1">EVA-1</option>
                                    <option value="2">EVA-2</option>
                                    <option value="3">EVA-3</option>
                                    <option value="4">EVA-4</option>
                                    <option value="5">EVA-5</option>
                                    <option value="6">EVA-6</option>
                                    <option value="7">EVA-7</option>
                                    <option value="8">EVA-8</option>
                                    <option value="9">EVA-9</option>
                                    </select>
                                   
                            </div>
                            <div class="col-lg-2">
                                   <label for="epsselect" class="col-xs-2 control-label requerido">EPS</label>
                                   
                                    <select name="epsselect" id="epsselect" class="form-control select2bs4" style="width: 100%;" >
                                        <option value="">-seleccione-</option>
                                        <option value="COMFENALCO">COMFENALCO</option>
                                        <option value="COOSALUD">COOSALUD</option>
                                        <option value="SOS">SOS</option>
                                        <option value="PARTICULAR">PARTICULAR</option>
                                    </select>
                                   
                            </div>
                            
                            <div class="col-2">
                                   <label for="notaevo" class="col-xs-2 control-label requerido">TIPO EVOLUCION</label>
                                   
                                    <select name="notaevo" id="notaevo" class="form-control select2bs4" style="width: 100%;" >
                                        <option value="">-seleccione-</option>
                                        <option value="97">HISTORIA DEL DOLOR</option>
                                        <option value="72">NOTA ENF PROCEDIMIENTOS</option>
                                        <option value="HCMR">HISTORIA FISIATRIA</option>
                                    </select>    
                                   
                            </div>

                            <div class="col-2">
                                <label>&nbsp;</label>
                                <div class="form-group row">
                                    <button type="submit" name="reset" id="reset"  class="btn btn-default btn-xl col-md-6">Limpiar</button>
                                    <button type="submit" name="buscar" id="buscar" class="btn btn-success btn-xl col-md-6">Buscar</button>
                                </div>
                        </div>

                        </div>


                      </tr>
                      </td>
                      </div>
                    </div><!-- /.row -->
                  </div><!-- /.container-fluid -->
                </div>
              </div>
            </div>