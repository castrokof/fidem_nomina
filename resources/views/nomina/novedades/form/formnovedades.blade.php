<div class="form-group row">
    <div class="col-lg-3">
        <label for="type_nove" class="col-xs-4 control-label requerido">Tipo de Novedad</label>
        <select name="type_nove" id="type_nove" class="form-control select2bs4" style="width: 100%;">
        </select>
    </div>
    <div class="col-lg-3">
        <label for="road" class="col-xs-4 control-label ">Valor</label>
        <input name="road" id="road" class="form-control" value="{{old('road')}}" readonly>
    </div>
    <div class="col-lg-3">
        <label for="hours" class="col-xs-4 control-label requerido">Horas laboradas</label>
        <input type="number" name="hours" id="hours" class="form-control UpperCase" value="{{old('hours')}}" readonly></input>
    </div>
    <div class="col-lg-3">
        <label for="total_pac" class="col-xs-4 control-label ">Pacientes atendidos</label>
        <input type="number" name="total_pac" id="total_pac" class="form-control" value="{{old('road')}}" readonly>
    </div>
</div>
<div class="form-group row">
     <div class="col-lg-12">
        <label for="nove_observacion" class="col-xs-4 control-label">Observacion</label>
        <textarea name="nove_observacion" id="nove_observacion" class="form-control" rows="2" placeholder="Observaciones..." value="{{old('nove_observacion')}}"></textarea>
    </div>
    <input type="hidden" name="user_id" id="user_id" class="form-control" value="{{Session()->get('usuario_id')}}">
    <input type="hidden" name="nove_id" id="nove_id" class="form-control" value="">
</div>


