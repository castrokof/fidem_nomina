<div class="form-group row">
    <div class="col-lg-3">
        <label for="surnamee" class="col-xs-4 control-label requerido">Primer nombre</label>
        <input type="text" name="surname" id="surnamee" class="form-control UpperCase" value="{{old('surname')}}" required >
    </div>
    <div class="col-lg-3">
        <label for="ssurnamee" class="col-xs-4 control-label ">Segundo nombre</label>
        <input type="text" name="ssurname" id="ssurnamee" class="form-control UpperCase" value="{{old('ssurname')}}"  >
    </div>
    <div class="col-lg-3">
        <label for="fnamee" class="col-xs-4 control-label requerido">Primer apellido</label>
        <input type="text" name="fname" id="fnamee" class="form-control UpperCase" value="{{old('fname')}}" required >
    </div>
    <div class="col-lg-3">
        <label for="snamee" class="col-xs-4 control-label ">Segundo apellido</label>
        <input type="text" name="sname" id="snamee" class="form-control UpperCase" value="{{old('sname')}}"  >
    </div>
    </div>
    <div class="form-group row">
  
    <div class="col-lg-3">
        <label for="date_birthe" class="col-xs-4 control-label requerido">Fecha nac</label>
        <input type="date" name="date_birth" id="date_birthe" class="form-control" value="{{old('date_birth')}}" required >
        </div>
   

    <div class="col-lg-3">
        <label for="municipalitye" class="col-xs-4 control-label requerido">Municipio</label>
        <select name="municipality" id="municipalitye" class="form-control select2bs4" style="width: 100%;" required>
        <option value="">---seleccione---</option>
        <option value="Cali">Cali</option>
        <option value="Palmira">Palmira</option>
        <option value="Buenaventura">Buenaventura</option>
        <option value="Jamundi">Jamundi</option>
        <option value="Yumbo">Yumbo</option>
        <option value="Candelaria">Candelaria</option>
        <option value="Otro">Otro</option>
    </select>
    </div>

    <div class="col-lg-2" id="municipio_otrase" style="display: none;">
        <label for="other" class="col-xs-4 control-label requerido">Otro</label>
        <input type="text" name="other" id="othere" class="form-control UpperCase" value="{{old('other')}}" >
    </div>
    
 
</div>
    <div class="form-group row">
    <div class="col-lg-3">
    <label for="addresse" class="col-xs-4 control-label requerido">Direccion</label>
    <input type="text" name="address" id="addresse" class="form-control UpperCase" value="{{old('address')}}" minlength="6" required >
    </div>
    <div class="col-lg-3">
        <label for="celulare" class="col-xs-4 control-label requerido">Celular</label>
        <input type="number" name="celular" id="celulare" class="form-control" value="{{old('celular')}}" required>
    </div>
    <div class="col-lg-3">
        <label for="phonee" class="col-xs-4 control-label requerido">Telefono</label>
        <input type="number" name="phone" id="phonee" class="form-control" value="{{old('phone')}}" required>
    </div>

    <div class="col-lg-3">
        <label for="emaile" class="col-xs-4 control-label ">E-mail</label>
        <input type="email" name="email" id="emaile" class="form-control" value="{{old('email')}}">
    </div>

    </div>
    <div class="form-group row">
        <div class="col-lg-3">
            <label for="sexoe" class="col-xs-4 control-label requerido">Sexo</label>
            <select name="sex" id="sexe" class="form-control select2bs4" style="width: 100%;" required>
                <option value="">---seleccione---</option>
                <option value="M">MASCULINO</option>
                <option value="F">FEMENINO</option>
            </select>
        </div>
 
            <div class="col-lg-3">
                <label for="date_ine" class="col-xs-4 control-label requerido">Fecha In </label>
                <input type="date" name="date_in" id="date_ine" class="form-control" value="{{old('date_in')}}" required >
            </div>
    
        <div class="col-lg-3">
                <label for="estado_paci" class="col-xs-4 control-label requerido">Estado</label>
                <select name="estado_paci" id="estado_paci1" class="form-control select2bs4" style="width: 100%;" required>
                <option>--seleccione--</option>
                <option value="S">Soporte</option>    
                <option value="1">1</option>    
                <option value="2">2</option>    
                <option value="3">3</option>    
                </select>
            </div>
        
  


    </div>
 

    <input type="hidden" name="user_id" id="user_ide" class="form-control" value="{{Session()->get('usuario_id')}}" >





