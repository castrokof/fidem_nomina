

        <div class="form-group row">
            <div class="col-lg-3">
                <label for="pnombre" class="col-xs-4 control-label requerido">Primer nombre</label>
                <input type="text" name="pnombre" id="pnombre" class="form-control" value="{{ old('pnombre') }}" required>
            </div>
            <div class="col-lg-3">
                <label for="snombre" class="col-xs-4 control-label ">Segundo nombre</label>
                <input type="text" name="snombre" id="snombre" class="form-control" value="{{ old('snombre') }}">
            </div>
            <div class="col-lg-3">
                <label for="papellido" class="col-xs-4 control-label requerido">Primer apellido</label>
                <input type="text" name="papellido" id="papellido" class="form-control" value="{{ old('papellido') }}"
                    required>
            </div>
            <div class="col-lg-3">
                <label for="sapellido" class="col-xs-4 control-label ">Segundo apellido</label>
                <input type="text" name="sapellido" id="sapellido" class="form-control" value="{{ old('sapellido') }}">
            </div>
        </div>

        <div class="form-group row">

            <div class="col-lg-2">
                <label for="tipo_documento" class="col-xs-4 control-label requerido">Tipo doc</label>

                <select name="tipo_documento" id="tipo_documento" class="form-control select2bs4" required>
                    <option value="">-seleccione-</option>
                    <option value="CE">AS</option>
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
                <label for="documento" class="col-xs-4 control-label requerido">Documento</label>
                <input type="text" name="documento" id="documento" class="form-control" value="{{ old('documento') }}"
                    minlength="6" required>
            </div>
            <div class="col-lg-2">
                <label for="email" class="col-xs-4 control-label requerido">E-mail</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            <div class="col-lg-3">
                <label for="celular" class="col-xs-4 control-label requerido">Celular</label>
                <input type="text" name="celular" id="celular" class="form-control" value="{{ old('celular') }}" required>
            </div>
            <div class="col-lg-2">
                <label for="ips" class="col-xs-4 control-label requerido">Ips</label>
                <select name="ips" id="ips" class="form-control select2bs4" style="width: 100%;" required>
                </select>
            </div>

        </div>
        <input type="hidden" name="user_id" id="user_id" class="form-control" value="{{Session()->get('usuario_id')}}" >
