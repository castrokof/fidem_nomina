
<div class="form-group row">
    <div class="col-lg-2">
        <label for="ips" class="col-xs-4 control-label requerido">Seleccione la IPS</label>
                    <select name="ips" id="ips" class="form-control select2bs4" style="width: 100%;">

                    </select>
    </div>
    <div id="ocultar"
     <div class="col-lg-2">
        <label for="name" class="control-label requerido">Primer dia quincena:</label>
        <input name="date_hour_initial_turn" class="form-control" id="date_hour_initial_turn" value="" required>
    </div>
    <div class="col-lg-2">
        <label for="name" class="control-label requerido">Ultimo día de quincena:</label>
        <input class="form-control" name="date_hour_end_turn" id="date_hour_end_turn" value="" required>
    </div>
    <div class="col-lg-2">
    <label for="working_type" class="col-xs-2 control-label requerido">Jornada</label>
        <input class="form-control" name="working_type" id="working_type" value="" readonly>
     </div>
     <div class="col-lg-2">
        <label for="observacion" class="col-xs-3 control-label ">Quincena</label>
        <input class="form-control" name="quincena" id="quincena" value="" readonly>
    </div>

    <div class="col-lg-2">
    <label for="observacion" class="col-xs-3 control-label ">Observación</label>
    <textarea name="observation" id="observation" class="form-control" rows="1" placeholder="Enter ..." value="{{old('observacion')}}"></textarea>
    </div>
    <input type="hidden" name="user_id" id="user_id" class="form-control" value="{{Session()->get('usuario_id')}}" >
</div>
</div>



