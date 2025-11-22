<div class="modal fade" tabindex="-1" id="modal-salida" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content" style="background: #f8f9fa; border-radius: 15px; border: none;">
            <div class="row">
                <div class="col-lg-12">
                    <span id="form_result_salida"></span>
                    <div class="card card-primary" style="transition: all 0.15s ease 0s; height: inherit; width: inherit; background: transparent; border: none; box-shadow: none;">
                        <div class="card-header with-border" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); border-radius: 12px; padding: 15px; margin-bottom: 20px;">
                            <div class="row align-items-center">
                                <div class="col-md-2 text-center">
                                    <div style="font-size: 2.5rem; font-weight: bold; color: white;">
                                        <i class="fas fa-minus-circle"></i>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div style="font-size: 1.5rem; font-weight: bold; color: white; margin: 0;">FIDEM</div>
                                    <div style="font-size: 0.9rem; opacity: 0.9; color: white; margin: 5px 0 0 0;">CLÍNICA ESPECIALIZADA EN DOLOR</div>
                                </div>
                                <div class="col-md-4">
                                    <h4 style="color: white; font-weight: bold;">REGISTRAR SALIDA DE MEDICAMENTO</h4>
                                </div>
                            </div>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                    <i class="fas fa-expand" style="color: white;"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus" style="color: white;"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-dismiss="modal">
                                    <i class="fas fa-times" style="color: white;"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body" style="background: #ffffff; border-radius: 12px; padding: 25px;">
                            <form id="form-salida" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="tipo_movimiento" value="salida">

                                <div class="row">
                                    <!-- Columna izquierda: Datos del medicamento y paciente -->
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="salida_fecha" style="color: #495057; font-weight: bold;">
                                                <i class="fas fa-calendar-alt mr-2" style="color: #43e97b;"></i>Fecha *
                                            </label>
                                            <input type="date" name="fecha" id="salida_fecha" class="form-control" value="{{date('Y-m-d')}}" required style="border: 2px solid #e0e0e0; border-radius: 8px; padding: 10px;">
                                        </div>

                                        <div class="form-group">
                                            <label for="salida_medicamento_id" style="color: #495057; font-weight: bold;">
                                                <i class="fas fa-pills mr-2" style="color: #43e97b;"></i>Medicamento *
                                            </label>
                                            <select name="medicamento_controlado_id" id="salida_medicamento_id" class="form-control" required style="border: 2px solid #e0e0e0; border-radius: 8px; padding: 10px;">
                                                <option value="">Seleccione un medicamento</option>
                                                @if(isset($medicamentos) && $medicamentos->count() > 0)
                                                    @foreach($medicamentos as $med)
                                                        <option value="{{$med->id}}" data-saldo="{{$med->saldo_actual}}">
                                                            {{$med->nombre}} (Stock: {{$med->saldo_actual}})
                                                        </option>
                                                    @endforeach
                                                @else
                                                    <option value="" disabled>No hay medicamentos activos disponibles</option>
                                                @endif
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label style="color: #495057; font-weight: bold;"><i class="fas fa-warehouse mr-2" style="color: #43e97b;"></i>Saldo Disponible</label>
                                            <div class="text-center">
                                                <span style="display: inline-block; padding: 15px 30px; border-radius: 12px; font-size: 1.5rem; font-weight: bold; background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); color: #1976d2; border: 2px solid #90caf9;">
                                                    <i class="fas fa-box-open"></i> <span id="salida-saldo-actual">0</span>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="salida_nombre_paciente" style="color: #495057; font-weight: bold;">
                                                <i class="fas fa-user-injured mr-2" style="color: #43e97b;"></i>Nombre del Paciente *
                                            </label>
                                            <input type="text" name="nombre_paciente" id="salida_nombre_paciente" class="form-control" required maxlength="200" placeholder="Nombre completo del paciente" style="border: 2px solid #e0e0e0; border-radius: 8px; padding: 10px;">
                                        </div>

                                        <div class="form-group">
                                            <label for="salida_cedula_paciente" style="color: #495057; font-weight: bold;">
                                                <i class="fas fa-id-card mr-2" style="color: #43e97b;"></i>Cédula del Paciente *
                                            </label>
                                            <input type="text" name="cedula_paciente" id="salida_cedula_paciente" class="form-control" required maxlength="50" placeholder="Número de cédula" style="border: 2px solid #e0e0e0; border-radius: 8px; padding: 10px;">
                                        </div>

                                        <div class="form-group">
                                            <label for="salida_numero_formula_control" style="color: #495057; font-weight: bold;">
                                                <i class="fas fa-file-prescription mr-2" style="color: #43e97b;"></i>No. Fórmula de Control
                                            </label>
                                            <input type="text" name="numero_formula_control" id="salida_numero_formula_control" class="form-control" maxlength="100" placeholder="Número del formulario (opcional)" style="border: 2px solid #e0e0e0; border-radius: 8px; padding: 10px;">
                                        </div>
                                    </div>

                                    <!-- Columna derecha: Cantidad y foto -->
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="salida_cantidad" style="color: #495057; font-weight: bold;">
                                                <i class="fas fa-minus mr-2" style="color: #43e97b;"></i>Cantidad a Retirar *
                                            </label>
                                            <input type="number" name="salida" id="salida_cantidad" class="form-control" required min="1" placeholder="Cantidad a retirar" style="border: 2px solid #e0e0e0; border-radius: 8px; padding: 10px; font-size: 1.2rem; font-weight: bold;">
                                            <small style="color: #6c757d;">La cantidad no puede ser mayor al saldo disponible</small>
                                        </div>

                                        <div class="form-group">
                                            <label style="color: #495057; font-weight: bold;"><i class="fas fa-calculator mr-2" style="color: #43e97b;"></i>Nuevo Saldo</label>
                                            <div class="text-center">
                                                <span style="display: inline-block; padding: 20px 40px; border-radius: 12px; font-size: 2rem; font-weight: bold; background: linear-gradient(135deg, #fff9c4 0%, #fff59d 100%); color: #f57f17; border: 2px solid #ffeb3b;" id="salida-nuevo-saldo">0</span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="salida_foto_formula" style="color: #495057; font-weight: bold;">
                                                <i class="fas fa-camera mr-2" style="color: #43e97b;"></i>Foto del Formulario
                                            </label>
                                            <div class="custom-file">
                                                <input type="file" name="foto_formula" id="salida_foto_formula" class="custom-file-input" accept="image/*" capture="environment">
                                                <label class="custom-file-label" for="salida_foto_formula" style="border: 2px solid #e0e0e0; border-radius: 8px;">
                                                    <i class="fas fa-camera mr-2"></i>Tomar foto o seleccionar...
                                                </label>
                                            </div>
                                            <small style="color: #6c757d;">JPG, PNG. Máx: 5MB</small>

                                            <!-- Preview -->
                                            <div id="salida-preview-container" style="display: none; margin-top: 15px; padding: 15px; background: #f8f9fa; border-radius: 10px; border: 2px solid #e0e0e0;">
                                                <img id="salida-preview-imagen" src="" alt="Preview" style="max-width: 100%; max-height: 250px; border-radius: 10px;">
                                                <button type="button" id="btn-eliminar-foto-salida" class="btn btn-danger mt-3" style="width: 100%; border-radius: 8px;">
                                                    <i class="fas fa-trash"></i> Eliminar foto
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12 text-center">
                                        <button type="button" class="btn btn-secondary" onclick="limpiarFormSalida()" style="margin-right: 10px; border-radius: 8px; padding: 10px 25px;">
                                            <i class="fas fa-eraser"></i> Limpiar
                                        </button>
                                        <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); border: none; border-radius: 8px; padding: 10px 25px;">
                                            <i class="fas fa-save"></i> Registrar Salida
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
