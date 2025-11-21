<div class="modal fade" tabindex="-1" id="modal-u" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="row">
                    <div class="col-lg-12">
                        @include('includes.form-error')
                        @include('includes.form-mensaje')
                        <span id="form_result"></span>
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Crear Novedad</h3>
                                <div class="card-tools pull-right">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                            <form id="form-general" class="form-horizontal" method="POST">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group row">
                                <div class="col-lg-3">
                                    <label for="surname" class="col-xs-4 control-label requerido">Primer nombre</label>
                                    <input type="text" name="surname" id="surname" class="form-control UpperCase" value="{{old('surname')}}" required >
                                </div>
                                <div class="col-lg-3">
                                    <label for="ssurname" class="col-xs-4 control-label ">Segundo nombre</label>
                                    <input type="text" name="ssurname" id="ssurname" class="form-control UpperCase" value="{{old('ssurname')}}"  >
                                </div>
                                <div class="col-lg-3">
                                    <label for="fname" class="col-xs-4 control-label requerido">Primer apellido</label>
                                    <input type="text" name="fname" id="fname" class="form-control UpperCase" value="{{old('fname')}}" required >
                                </div>
                                <div class="col-lg-3">
                                    <label for="sname" class="col-xs-4 control-label ">Segundo apellido</label>
                                    <input type="text" name="sname" id="sname" class="form-control UpperCase" value="{{old('sname')}}"  >
                                </div>
                                </div>
                                <div class="form-group row">
                                <div class="col-lg-3">
                                    <label for="type_document" class="col-xs-4 control-label requerido">Tipo de documento</label>
                                    <select name="type_document" id="type_document" class="form-control select2bs4" style="width: 100%; font-size:10px;" required>
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label for="document" class="col-xs-4 control-label requerido">Documento</label>
                                    <input type="number" name="document" id="document" class="form-control" value="{{old('document')}}" minlength="5"  required >
                                </div>
                            
                               
                            </div>
                                <div class="form-group row">
                                <div class="col-lg-3">
                                <label for="address" class="col-xs-4 control-label requerido">Fecha inicial</label>
                                <input type="date" name="address" id="address" class="form-control UpperCase" value="{{old('address')}}" minlength="6" required >
                                </div>
                                <div class="col-lg-3">
                                    <label for="celular" class="col-xs-4 control-label requerido">Fecha final</label>
                                    <input type="date" name="celular" id="celular" class="form-control" value="{{old('celular')}}" required>
                                </div>
                                <div class="col-lg-3">
                                    <label for="telefono" class="col-xs-4 control-label requerido">Telefono</label>
                                    <input type="number" name="phone" id="phone" class="form-control" value="{{old('phone')}}" required>
                                </div>
                            
                                <div class="col-lg-3">
                                    <label for="correo" class="col-xs-4 control-label ">E-mail</label>
                                    <input type="email" name="email" id="email" class="form-control" value="{{old('email')}}">
                                </div>
                            
                                </div>
                           
                                <div class="form-group row">
                                    <div class="col-lg-3">
                                            <label for="estado_paci" class="col-xs-4 control-label requerido">Estado</label>
                                            <select name="estado_paci" id="estado_paci" class="form-control select2bs4" style="width: 100%;" required>
                                            <option>--seleccione--</option>
                                            <option value="S">Soporte</option>    
                                            <option value="1">1</option>    
                                            <option value="2">2</option>    
                                            <option value="3">3</option>    
                                            </select>
                                        </div>
                                    
                                </div>
                                
                                <div class="form-group row">
                                    <div class="col-lg-12 col-md-12 col-xs-12">
                                        <label for="observacion" class="col-xs-8 control-label requerido">Obs. ingreso corte</label>
                                        <textarea name="observacion" id="observacion" class="form-control UpperCase" rows="3" placeholder="Ingrese el motivo de consulta ..." value="{{old('reason_consultation')}}" required></textarea>
                                    </div>
                                </div>
                            
                                <input type="hidden" name="user_id" id="user_id" class="form-control" value="{{Session()->get('usuario_id')}}" >
                                <input type="hidden" name="usuario_id" id="usuario_id" class="form-control" value="{{Session()->get('usuario')}}">
                            
                            
                            
                            


                                   
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">

                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        @include('includes.boton-form-crear-empresa-empleado-usuario')
                                    </div>
                                </div>
                                <!-- /.card-footer -->
                            </form>



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
