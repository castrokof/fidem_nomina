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
    <div class="col-lg-2">
        <label for="type_document" class="col-xs-4 control-label requerido">Tipo de documento</label>
        <select name="type_document" id="type_document" class="form-control select2bs4" style="width: 100%;" required>
            <option value="">---seleccione---</option>
            <option value="AS">AS</option>
            <option value="CC">CC</option>
            <option value="CE">CE</option>
            <option value="MS">MS</option>
            <option value="NI">NI</option>
            <option value="NU">NU</option>
            <option value="PE">PE</option>
            <option value="RC">RC</option>
            <option value="TI">TI</option>
        </select>
    </div>
    <div class="col-lg-3">
        <label for="document" class="col-xs-4 control-label requerido">Documento</label>
        <input type="number" name="document" id="document" class="form-control" value="{{old('document')}}" minlength="5"  required >
    </div>

    <div class="col-lg-2">
        <label for="fecha_solicitud" class="col-xs-4 control-label requerido">Fecha Solicitud</label>
        <input type="date" name="fecha_solicitud" id="fecha_solicitud" class="form-control" value="{{old('fecha_solicitud')}}" required >
    </div>
    
    <div class="col-lg-3">
        <label for="profesional" class="col-xs-4 control-label">Profesional asigna cita</label>
        <select name="profesional" id="profesional" class="form-control select2bs4" style="width: 100%;" >
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
    <div class="col-lg-2">
        <label for="dx" class="col-xs-4 control-label requerido">Diagnóstico</label>
         <input type="text" name="dx" id="dx" class="form-control UpperCase" placeholder="Diligencie el Dx.." value="{{old('dx')}}" >
      
    </div>    
</div>

<div class="form-group row">
   <div class="card card-outline card-primary col-lg-12">
          <div class="card-header">
            <h3 class="card-title"> <label for="dis_apoyo" class="col-xs-4 control-label requerido">1. Paciente con uso de dispositivo de apoyo como:</label></h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
        <div class="card-body">
                
            <div class="card col-12">
               <div class="card-body p-2">
                <label>Silla de ruedas :</label>          
                 <div class="form-group">
                     
                        <div class="form-group row">
                       
                            
                            <div class="custom-control custom-radio col-lg-4">
                              <input class="custom-control-input" type="radio" id="silla_convencional" name="dispositivo_silla" value="Convencional">
                              <label for="silla_convencional" class="custom-control-label">Convencional</label>
                            </div>
                            <div class="custom-control custom-radio col-lg-4">
                              <input class="custom-control-input" type="radio" id="silla_electrica" name="dispositivo_silla" value="Eléctrica">
                              <label for="silla_electrica" class="custom-control-label">Eléctrica</label>
                            </div>
                            <div class="custom-control custom-radio col-lg-4">
                              <input class="custom-control-input" type="radio" id="silla_especial" name="dispositivo_silla" value="Especial (Neurologica)">
                              <label for="silla_especial" class="custom-control-label">Especial (Neurologica)</label>
                            </div>
                           </div>
                          
                </div>
                </div>
            </div>
            <div class="card col-12">
               <div class="card-body p-2">
               
                <div class="form-group row">
                    
                            <div class="custom-control custom-switch col-lg-4">
                              <input type="checkbox" class="custom-control-input dispositivo-apoyo" id="protesis" name="dispositivo_apoyo[]" value="Prótesis">
                              <label class="custom-control-label" for="protesis">Prótesis</label>
                            </div>
                       
                            <div class="custom-control custom-switch col-lg-4">
                              <input type="checkbox" class="custom-control-input dispositivo-apoyo" id="otro_dispositivo" name="dispositivo_apoyo[]" value="Otro">
                              <label class="custom-control-label" for="otro_dispositivo">Otro</label>
                            </div>
                            
                             <div class="col-lg-4" id="otro_dis" style="display: none;">
                                
                                <input type="text" name="other" id="other" class="form-control UpperCase" placeholder="Diligencie el otro dispositivo.." value="{{old('other')}}" >
                            </div>
                            
                         
                </div>
               
                
                <div class="form-group row">
                 <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success col-lg-3">
                             <input type="checkbox" class="custom-control-input dispositivo-apoyo" id="ninguno_anterior" name="dispositivo_apoyo[]" value="Ninguno de los anteriores">
                              <label class="custom-control-label" for="ninguno_anterior">Ninguno de los anteriores</label>
                               
                </div>
                </div>
                 </div>
                </div>
                
       </div>
       
       <div class="card-footer">
           <label>El paciente viene para solicitud de algún tipo de dispositivo.</label>
            <div class="form-group row">
                 <div class="custom-control custom-radio col-lg-4">
                      <input class="custom-control-input" type="radio" id="solicitud_si" name="solicitud_dispositivo" value="SI">
                     <label for="solicitud_si" class="custom-control-label">SI</label>
                    </div>
                    <div class="custom-control custom-radio col-lg-4">
                      <input class="custom-control-input" type="radio" id="solicitud_no" name="solicitud_dispositivo" value="NO">
                      <label for="solicitud_no" class="custom-control-label">NO</label>
                    </div>
            </div>
        </div>
            
    </div>
</div>
    
<div class="form-group row">
   <div class="card card-outline card-primary col-lg-12">
          <div class="card-header">
            <h3 class="card-title"> <label for="dis_apoyo" class="col-xs-4 control-label requerido">2. Paciente con antecedentes o diagnóstico de cáncer:</label></h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
        <div class="card-body">
       
            <div class="card col-12">
               <div class="card-body p-2">
               
                <div class="form-group row">
                    
                           <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success col-lg-3">
                             <input type="checkbox" class="custom-control-input" id="cancer_si" name="antecedentes_dx_cancer" value="SI">
                              <label class="custom-control-label" for="cancer_si">Si</label>
                           </div>
                         
                </div>
           
                 </div>
                </div>
                
       </div>
    </div>
</div> 

<div class="form-group row">
   <div class="card card-outline card-primary col-lg-12">
          <div class="card-header">
            <h3 class="card-title"> <label for="toxina" class="col-xs-4 control-label requerido">3. Paciente con antecedentes de aplicación de toxina botulinica o con Dx de espasticidad(Rigidez muscular):</label></h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
        <div class="card-body">
       
            <div class="card col-12">
               <div class="card-body p-2">
               
                <div class="form-group row">
                    
                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success col-lg-3">
                             <input type="checkbox" class="custom-control-input" id="toxina_si" name="antecedentes_toxina_espasticidad" value="SI">
                             <label class="custom-control-label" for="toxina_si">SI</label>
                            </div>
                            
                </div>
           
                 </div>
            </div>
                
       </div>
    </div>
</div>  

<div class="form-group row">
   <div class="card card-outline card-primary col-lg-12">
          <div class="card-header">
            <h3 class="card-title"> <label for="camilla" class="col-xs-4 control-label requerido">4. Paciente en camilla o cuenta con transporte de Ambulancia:</label></h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
        <div class="card-body">
       
            <div class="card col-12">
               <div class="card-body p-2">
               
                <div class="form-group row">
                    
                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success col-lg-3">
                             <input type="checkbox" class="custom-control-input" id="camilla_si" name="camilla_ambulancia" value="SI">
                              <label class="custom-control-label" for="camilla_si">SI</label>
                            </div>
                         
                </div>
           
                 </div>
                </div>
                
       </div>
    </div>
</div> 

<div class="form-group row">
    <div class="col-lg-6">
        <label for="tipo_solicitud" class="col-xs-4 control-label requerido">Tipo de Solicitud</label>
        <select name="tipo_solicitud" id="tipo_solicitud" class="form-control select2bs4" style="width: 100%;" required>
        <option value="">---seleccione---</option>
        <option value="N/A">N/A</option>
        <option value="JUNTA MEDICA">JUNTA MEDICA</option>
        <option value="DISPOSITIVO">DISPOSITIVO</option>
        </select>
    </div>
        <div class="col-lg-6">
        <label for="eapb" class="col-xs-4 control-label requerido">EPS</label>
        <select name="eapb" id="eapb" class="form-control select2bs4" style="width: 100%;" required>
        <option value="">---seleccione---</option>
        <option value="COMFENALCO">COMFENALCO</option>
        <option value="SOS">SOS</option>
        <option value="COOSALUD">COOSALUD</option>
        <option value="SALUD TOTAL">SALUD TOTAL</option>
        <option value="EMSSANAR">EMSSANAR</option>
        <option value="PARTICULAR">PARTICULAR</option>
        </select>
    </div>
</div>

<div class="form-group row">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <label for="reason_consultation" class="col-xs-4 control-label requerido">Motivo de consulta</label>
        <textarea name="reason_consultation" id="reason_consultation" class="form-control UpperCase" rows="3" placeholder="Ingrese el tipo de junta o dispositivo ..." required>{{old('reason_consultation')}}</textarea>
    </div>
</div>

<div class="form-group row">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <label for="observacion" class="col-xs-8 control-label requerido">Observación</label>
        <textarea name="observacion" id="observacion" class="form-control UpperCase" rows="3" placeholder="Ingrese la observación..." required>{{old('observacion')}}</textarea>
    </div>
</div>

<input type="hidden" name="user_id" id="user_id" class="form-control" value="{{Session()->get('usuario_id')}}" >