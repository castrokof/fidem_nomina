     <div class="row">
                <div class="content-header">
                  <div class="container-fluid">
                    <div class="row mb-12">
                      <div class="col-sm-12">
                        <h1 class="m-0 text-dark">Informes </h1>
                      </div><!-- /.col -->

                      @csrf
                      <div class="card-body">
                      <div class="row col-12">

                        <div class="form-group row col-lg-12">
                            <div class="col-lg-4">
                                   <label for="fechaini" class="col-xs-2 control-label requerido">Fecha de
                                    solicitud</label>
                                    <div class="form-group row">
                                    <input type="date" name="fechaini" id="fechaini" class="form-control col-md-6"
                                        value="">
                                    <input type="date" name="fechafin" id="fechafin" class="form-control col-md-6"
                                        value="">
                                    </div>
                            </div>
                            
           
                           <div class="col-lg-2">
                                   <label for="profesional" class="col-xs-2 control-label requerido">Profesionales</label>
                                   
                                    <select name="profesional" id="profesional" class="form-control select2bs4" style="width: 100%;" >
                                    <option value="">-seleccione-</option>
                                    <option value="SANTIAGO SANCHEZ">SANTIAGO SANCHEZ</option>
                                    <option value="LUIS FERNANDO ROMAN">LUIS FERNANDO ROMAN</option>
                                    <option value="SANDRA ROMANO">SANDRA ROMANO</option>
                                    <option value="ROLAND TREJOS">ROLAND TREJOS</option>
                                    <option value="VICTOR MARTINEZ">VICTOR MARTINEZ</option>
                                    <option value="JAVIER BENAVIDES">JAVIER BENAVIDES</option>
                                    <option value="DIANA LOPEZ">DIANA LOPEZ</option>
                                    <option value="LEONARDO ARCE">LEONARDO ARCE</option>
                                    </select>
                                   
                            </div>
                            <div class="col-lg-2">
                                   <label for="eps" class="col-xs-2 control-label requerido">EPS</label>
                                   
                                    <select name="eps" id="eps" class="form-control select2bs4" style="width: 100%;" >
                                        <option value="">-seleccione-</option>
                                        <option value="COMFENALCO">COMFENALCO</option>
                                        <option value="COOSALUD">COOSALUD</option>
                                        <option value="EMSSANAR">EMSSANAR</option>
                                        <option value="SOS">SOS</option>
                                        <option value="PARTICULAR">PARTICULAR</option>
                                    </select>
                                   
                            </div>

                            <div class="col-lg-4">
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