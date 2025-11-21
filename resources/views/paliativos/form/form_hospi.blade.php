            <div class="row">
                <div class="content-header">
                  <div class="container-fluid">
                    <div class="row mb-12">
                      <div class="col-sm-12">
                        <h1 class="m-0 text-dark">Informes </h1>
                      </div><!-- /.col -->

                      @csrf
                      <div class="card-body">
                      <div class="row col-lg-12">

                        <div class="form-group row col-lg-12">
                            <div class="col-md-6">
                                   <label for="fechaini" class="col-xs-2 control-label requerido">Fecha de
                                    Informes</label>
                                    <div class="form-group row">
                                    <input type="date" name="fechaini" id="fechaini" class="form-control col-md-6"
                                        value="">
                                    <input type="date" name="fechafin" id="fechafin" class="form-control col-md-6"
                                        value="">
                                    </div>
                            </div>

                            <div class="col-md-6">
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