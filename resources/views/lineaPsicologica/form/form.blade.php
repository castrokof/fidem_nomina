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
        <label for="date_birth" class="col-xs-4 control-label requerido">Fecha Solicitud</label>
        <input type="date" name="date_birth" id="date_birth" class="form-control" value="{{old('date_birth')}}" required >
    </div>
    
    <div class="col-lg-3">
        <label for="municipality" class="col-xs-4 control-label requerido">Regimen Nivel</label>
        <select name="municipality" id="municipality" class="form-control select2bs4" style="width: 100%;" required>
        <option value="">---seleccione---</option>
        <option value="Contributivo Cotizante">Contributivo Cotizante</option>
        <option value="Contributivo Beneficiario">Contributivo Beneficiario</option>
        <option value="Subsidiado">Subsidiado</option>
        <option value="Particular">Particular</option>
        <option value="Otro">Otro</option>
        </select>
    </div>

    <div class="col-lg-2" id="municipio_otras" style="display: none;">
        <label for="other" class="col-xs-4 control-label requerido">Otro</label>
        <input type="text" name="other" id="other" class="form-control UpperCase" value="{{old('other')}}" >
    </div>

    </div>
    <div class="form-group row">
        <div class="col-lg-3">
            <label for="sexo" class="col-xs-4 control-label requerido">Nivel</label>
            <select name="sex" id="sex" class="form-control select2bs4" style="width: 100%;" required>
                <option value="">---seleccione---</option>
            </select>
        </div>
    <div class="col-lg-3">
        <label for="address" class="col-xs-4 control-label requerido">Direccion</label>
        <input type="text" name="address" id="address" class="form-control UpperCase" value="{{old('address')}}" minlength="6" required >
    </div>
    <div class="col-lg-3">
        <label for="celular" class="col-xs-4 control-label requerido">Celular</label>
        <input type="number" name="celular" id="celular" class="form-control" value="{{old('celular')}}" required>
    </div>
    <div class="col-lg-3">
        <label for="telefono" class="col-xs-4 control-label requerido">Telefono</label>
        <input type="number" name="phone" id="phone" class="form-control" value="{{old('phone')}}" >
    </div>

    

    </div>
    <div class="form-group row">
        <div class="col-lg-3">
        <label for="correo" class="col-xs-4 control-label ">E-mail</label>
        <input type="email" name="email" id="email" class="form-control" value="{{old('email')}}">
    </div>
        
        <div class="col-lg-3">
            <label for="eps" class="col-xs-4 control-label requerido">EAPB</label>
            <select name="eapb" id="eapb" class="form-control select2bs4" style="width: 100%;" required>
            <option value="">---seleccione---</option>
            <option value="COMFENALCO">COMFENALCO</option>
            <option value="COOSALUD">COOSALUD</option>
            <option value="SOS">SOS</option>
            <option value="SALUD TOTAL">SALUD TOTAL</option>
            <option value="PARTICULAR">PARTICULAR</option>

        </select>
        </div>

            <div class="col-lg-3">
                <label for="consultation" class="col-xs-4 control-label requerido">Doctor Encargado</label>
                <select name="consultation" id="consultation" class="form-control select2bs4" style="width: 100%;" required>
                <option value="">---seleccione---</option>
                <option value="SANTIAGO SANCHEZ">SANTIAGO SANCHEZ</option>
                <option value="LUIS FERNANDO ROMAN">LUIS FERNANDO ROMAN</option>
                <option value="SANDRA ROMANO">SANDRA ROMANO</option>
                <option value="ROLAND TREJOS">ROLAND TREJOS</option>
                <option value="VICTOR MARTINEZ">VICTOR MARTINEZ</option>
                <option value="JAVIER BENAVIDES">JAVIER BENAVIDES</option>
                <option value="DIANA LOPEZ">DIANA LOPEZ</option>
                <option value="LEONARDO ARCE">LEONARDO ARCE</option>
                <option value="JIMENA CALLE">JIMENA CALLE</option>
                <option value="KATALINA ESPINOSA">KATALINA ESPINOSA</option>
                <option value="DIANA MURCIA">DIANA MURCIA</option>
            </select>
            
            </select>
        </div>
        
        
          <div class="col-lg-3">
                <label for="future4" class="col-xs-4 control-label requerido">Profesional que ordena</label>
                <select name="future4" id="future4" class="form-control select2bs4" style="width: 100%;" required>
                <option value="">---seleccione---</option>
                <option value="SANTIAGO SANCHEZ">SANTIAGO SANCHEZ</option>
                <option value="SANTIAGO VALENCIA">SANTIAGO VALENCIA</option>
                <option value="CATALINA PROAÑO">CATALINA PROAÑO</option>
                <option value="ZULLY NORIEGA">ZULLY NORIEGA</option>
                <option value="LUIS FERNANDO ROMAN">LUIS FERNANDO ROMAN</option>
                <option value="SANDRA ROMANO">SANDRA ROMANO</option>
                <option value="ROLAND TREJOS">ROLAND TREJOS</option>
                <option value="VICTOR MARTINEZ">VICTOR MARTINEZ</option>
                <option value="JAVIER BENAVIDES">JAVIER BENAVIDES</option>
                <option value="DIANA LOPEZ">DIANA LOPEZ</option>
                <option value="LEONARDO ARCE">LEONARDO ARCE</option>
                <option value="JIMENA CALLE">JIMENA CALLE</option>
                <option value="KATALINA ESPINOSA">KATALINA ESPINOSA</option>
                <option value="DIANA MURCIA">DIANA MURCIA</option>
                <option value="DIANA MURCIA">ISABELLA DELGADO </option>
                <option value="DIANA MURCIA">NICOLAS CUARTAS </option>
                
            </select>
            
            </select>
        </div>

          
            </div>
            <div class="form-group col-lg-3 clearfix" id="radio_button" style="display: none;">
                <label for="radio_button">Paciente Requiere Copago??</label>
                <div class="icheck-primary d-inline">
                    <label for="radioPrimary1">SI
                    </label>
                    <input type="radio" id="radioPrimary1" name="r1" >

                </div>
                <div class="icheck-primary d-inline">
                    <label for="radioPrimary2">NO
                    </label>
                    <input type="radio" id="radioPrimary2" name="r1" >

                </div>
            </div>
            <input type="hidden" name="diagnosis" id="diagnosis" class="form-control" value="">



    </div>
     <div class="form-group row">
       
            <div class="col-lg-6">
                <label for="future2" class="col-xs-4 control-label requerido">Tipo de Medicamento</label>
                <select name="future2" id="future2" class="form-control select2bs4" style="width: 100%;" required>
                <option value="">---seleccione---</option>
                <option value="N/A">N/A</option>
                <option value="ACIDO HIALURONICO">ACIDO HIALURONICO</option>
                <option value="TOXINA BOTULINICA TIPO A">TOXINA BOTULINICA TIPO A</option>
                <option value="TOXINA BOTULINICA ONABOTULINUM">TOXINA BOTULINICA ONABOTULINUM</option>
                <option value="TOXINA BOTULINICA ABOBOTULINUM">TOXINA BOTULINICA ABOBOTULINUM</option>
                </select>
            </div>

    </div>
    <div class="form-group row">
        <div class="col-lg-12 col-md-12 col-xs-12">
            <label for="reason_consultation" class="col-xs-8 control-label requerido">Procedimiento</label>
            <textarea name="reason_consultation" id="reason_consultation" class="form-control UpperCase" rows="3" placeholder="Ingrese el procedimiento ..." value="{{old('reason_consultation')}}" required></textarea>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-lg-12 col-md-12 col-xs-12">
            <label for="future1" class="col-xs-8 control-label requerido">Observación</label>
            <textarea name="future1" id="future1" class="form-control UpperCase" rows="3" placeholder="Ingrese la observación..." value="{{old('future1')}}" required></textarea>
        </div>
    </div>
    

    <input type="hidden" name="user_id" id="user_id" class="form-control" value="{{Session()->get('usuario_id')}}" >





