<div class="form-group row">
    <div class="col-lg-3">
        <label for="road" class="col-xs-4 control-label ">Cuenta de cobro</label>
        <input name="type_nove" id="type_novec" class="form-control" value="CUENTA DE COBRO">
    </div>
    <div class="col-lg-3">
        <label for="roadc" class="col-xs-4 control-label ">Valor cuenta</label>
        <input name="road" id="roadc" class="form-control" value="{{old('road')}}">
    </div>
 </div>
<div class="form-group row">
     <div class="col-lg-12">
        <label for="nove_observacionc" class="col-xs-4 control-label">Observacion</label>
        <textarea name="nove_observacion" id="nove_observacionc" class="form-control" rows="2" placeholder="Observaciones..." value="{{old('nove_observacion')}}"></textarea>
    </div>
    <input type="hidden" name="user_id" id="user_idc" class="form-control" value="{{Session()->get('usuario_id')}}">
    <input type="hidden" name="nove_id" id="nove_idc" class="form-control" value="">
</div>
