<div class="form-group row">
    <label for="nombre" class="col-lg-3 control-label requerido">Nombre del Medicamento</label>
    <div class="col-lg-8">
        <input type="text" name="nombre" id="nombre" class="form-control" value="{{old('nombre', $data->nombre ?? '')}}" required maxlength="200" placeholder="Ej: Morfina 10mg">
    </div>
</div>

<div class="form-group row">
    <label for="descripcion" class="col-lg-3 control-label">Descripción</label>
    <div class="col-lg-8">
        <textarea name="descripcion" id="descripcion" class="form-control" rows="3" placeholder="Descripción opcional del medicamento">{{old('descripcion', $data->descripcion ?? '')}}</textarea>
    </div>
</div>

<div class="form-group row">
    <label for="activo" class="col-lg-3 control-label">Estado</label>
    <div class="col-lg-8">
        <div class="form-check">
            <input type="checkbox" name="activo" id="activo" class="form-check-input" value="1" {{old('activo', $data->activo ?? 1) ? 'checked' : ''}}>
            <label class="form-check-label" for="activo">
                Activo
            </label>
        </div>
    </div>
</div>
