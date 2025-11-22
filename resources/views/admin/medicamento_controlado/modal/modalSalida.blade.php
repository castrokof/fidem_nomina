<div class="modal fade" tabindex="-1" id="modal-salida" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content" style="background: linear-gradient(-45deg, #f093fb, #f5576c, #fd5949, #fc6767); background-size: 400% 400%; animation: gradientBG 15s ease infinite;">
            <div class="row">
                <div class="col-lg-12">
                    <span id="form_result_salida"></span>
                    <div class="card card-primary" style="transition: all 0.15s ease 0s; height: inherit; width: inherit; background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2);">
                        <div class="card-header with-border" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 8px; padding: 15px; margin-bottom: 20px;">
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

                        <div class="card-body" style="background: rgba(255, 255, 255, 0.05);">
                            <form id="form-salida" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="tipo_movimiento" value="salida">

                                <div class="row">
                                    <!-- Columna izquierda: Datos del medicamento y paciente -->
                                    <div class="col-lg-6">
                                        <div class="form-group-glass">
                                            <label for="salida_fecha" style="color: white; font-weight: bold;">
                                                <i class="fas fa-calendar-alt mr-2"></i>Fecha *
                                            </label>
                                            <input type="date" name="fecha" id="salida_fecha" class="form-control glass-input" value="{{date('Y-m-d')}}" required>
                                        </div>

                                        <div class="form-group-glass">
                                            <label for="salida_medicamento_id" style="color: white; font-weight: bold;">
                                                <i class="fas fa-pills mr-2"></i>Medicamento *
                                            </label>
                                            <select name="medicamento_controlado_id" id="salida_medicamento_id" class="form-control glass-select" required>
                                                <option value="">Seleccione un medicamento</option>
                                                @foreach($medicamentos as $med)
                                                    <option value="{{$med->id}}" data-saldo="{{$med->saldo_actual}}">
                                                        {{$med->nombre}} (Stock: {{$med->saldo_actual}})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group-glass">
                                            <label style="color: white; font-weight: bold;"><i class="fas fa-warehouse mr-2"></i>Saldo Disponible</label>
                                            <div class="text-center">
                                                <span class="glass-badge glass-badge-info" style="font-size: 1.5rem; padding: 15px 30px;">
                                                    <i class="fas fa-box-open"></i> <span id="salida-saldo-actual">0</span>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="form-group-glass">
                                            <label for="salida_nombre_paciente" style="color: white; font-weight: bold;">
                                                <i class="fas fa-user-injured mr-2"></i>Nombre del Paciente *
                                            </label>
                                            <input type="text" name="nombre_paciente" id="salida_nombre_paciente" class="form-control glass-input" required maxlength="200" placeholder="Nombre completo del paciente">
                                        </div>

                                        <div class="form-group-glass">
                                            <label for="salida_cedula_paciente" style="color: white; font-weight: bold;">
                                                <i class="fas fa-id-card mr-2"></i>Cédula del Paciente *
                                            </label>
                                            <input type="text" name="cedula_paciente" id="salida_cedula_paciente" class="form-control glass-input" required maxlength="50" placeholder="Número de cédula">
                                        </div>

                                        <div class="form-group-glass">
                                            <label for="salida_numero_formula_control" style="color: white; font-weight: bold;">
                                                <i class="fas fa-file-prescription mr-2"></i>No. Fórmula de Control
                                            </label>
                                            <input type="text" name="numero_formula_control" id="salida_numero_formula_control" class="form-control glass-input" maxlength="100" placeholder="Número del formulario (opcional)">
                                        </div>
                                    </div>

                                    <!-- Columna derecha: Cantidad y foto -->
                                    <div class="col-lg-6">
                                        <div class="form-group-glass">
                                            <label for="salida_cantidad" style="color: white; font-weight: bold;">
                                                <i class="fas fa-minus mr-2"></i>Cantidad a Retirar *
                                            </label>
                                            <input type="number" name="salida" id="salida_cantidad" class="form-control glass-input" required min="1" placeholder="Cantidad a retirar" style="font-size: 1.2rem; font-weight: bold;">
                                            <small style="color: rgba(255, 255, 255, 0.8);">La cantidad no puede ser mayor al saldo disponible</small>
                                        </div>

                                        <div class="form-group-glass">
                                            <label style="color: white; font-weight: bold;"><i class="fas fa-calculator mr-2"></i>Nuevo Saldo</label>
                                            <div class="text-center">
                                                <span class="glass-badge glass-badge-warning" style="font-size: 2rem; padding: 20px 40px;" id="salida-nuevo-saldo">0</span>
                                            </div>
                                        </div>

                                        <div class="form-group-glass">
                                            <label for="salida_foto_formula" style="color: white; font-weight: bold;">
                                                <i class="fas fa-camera mr-2"></i>Foto del Formulario
                                            </label>
                                            <div class="custom-file">
                                                <input type="file" name="foto_formula" id="salida_foto_formula" class="custom-file-input" accept="image/*" capture="environment">
                                                <label class="custom-file-label glass-input" for="salida_foto_formula">
                                                    <i class="fas fa-camera mr-2"></i>Tomar foto o seleccionar...
                                                </label>
                                            </div>
                                            <small style="color: rgba(255, 255, 255, 0.8);">JPG, PNG. Máx: 5MB</small>

                                            <!-- Preview con glassmorphism -->
                                            <div id="salida-preview-container" class="image-preview-glass" style="display: none; margin-top: 15px; padding: 20px; background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border-radius: 10px; border: 1px solid rgba(255, 255, 255, 0.3);">
                                                <img id="salida-preview-imagen" src="" alt="Preview" style="max-width: 100%; max-height: 250px; border-radius: 10px;">
                                                <button type="button" id="btn-eliminar-foto-salida" class="btn-ios btn-ios-danger mt-3" style="width: 100%;">
                                                    <i class="fas fa-trash"></i> Eliminar foto
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12 text-center">
                                        <div class="btn-group-ios">
                                            <button type="button" class="btn-ios btn-ios-warning" id="btn-limpiar-salida" onclick="limpiarFormSalida()">
                                                <i class="fas fa-eraser"></i>
                                                <span>Limpiar</span>
                                            </button>
                                            <button type="submit" class="btn-ios btn-ios-danger" id="btn-guardar-salida">
                                                <i class="fas fa-save"></i>
                                                <span>Registrar Salida</span>
                                            </button>
                                        </div>
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

<style>
    @keyframes gradientBG {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .form-group-glass {
        margin-bottom: 1.5rem;
    }

    .glass-input, .glass-select {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 10px;
        color: white;
        padding: 10px 15px;
        transition: all 0.3s ease;
    }

    .glass-input::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }

    .glass-input:focus, .glass-select:focus {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.5);
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
        color: white;
        outline: none;
    }

    .glass-select option {
        background: #2c3e50;
        color: white;
    }

    .glass-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 50px;
        font-weight: bold;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
    }

    .glass-badge-info {
        background: rgba(23, 162, 184, 0.3);
    }

    .glass-badge-warning {
        background: rgba(255, 193, 7, 0.3);
    }

    .btn-group-ios {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .btn-ios {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .btn-ios:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    .btn-ios-warning {
        background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
    }

    .btn-ios-danger {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .custom-file-label::after {
        content: "Buscar";
        background: rgba(255, 255, 255, 0.3);
        border-left: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
    }
</style>
