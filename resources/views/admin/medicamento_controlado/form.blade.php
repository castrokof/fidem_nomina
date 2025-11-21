<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="form-group-glass">
            <label for="nombre">
                <i class="fas fa-prescription-bottle mr-2"></i>Nombre del Medicamento *
            </label>
            <input type="text" name="nombre" id="nombre" class="form-control glass-input" value="{{old('nombre', $data->nombre ?? '')}}" required maxlength="200" placeholder="Ej: Morfina 10mg, Fentanilo 50mcg">
        </div>

        <div class="form-group-glass">
            <label for="descripcion">
                <i class="fas fa-info-circle mr-2"></i>Descripción
            </label>
            <textarea name="descripcion" id="descripcion" class="form-control glass-input" rows="4" placeholder="Descripción opcional del medicamento: presentación, concentración, uso, etc.">{{old('descripcion', $data->descripcion ?? '')}}</textarea>
        </div>

        <div class="form-group-glass">
            <label>
                <i class="fas fa-toggle-on mr-2"></i>Estado
            </label>
            <div class="custom-control custom-switch" style="padding-left: 3rem;">
                <input type="checkbox" name="activo" id="activo" class="custom-control-input" value="1" {{old('activo', $data->activo ?? 1) ? 'checked' : ''}}>
                <label class="custom-control-label" for="activo" style="color: white; font-weight: 600; padding-top: 2px;">
                    <span id="estado-texto">{{old('activo', $data->activo ?? 1) ? 'Activo' : 'Inactivo'}}</span>
                </label>
            </div>
            <small style="color: rgba(255, 255, 255, 0.8);">Los medicamentos inactivos no aparecerán en los registros de entradas/salidas</small>
        </div>
    </div>
</div>

<script>
    // Cambiar texto del switch
    document.getElementById('activo').addEventListener('change', function() {
        document.getElementById('estado-texto').textContent = this.checked ? 'Activo' : 'Inactivo';
    });
</script>
