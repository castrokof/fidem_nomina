<div class="modal fade" tabindex="-1" id="modal-entrada" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content" style="background: linear-gradient(-45deg, #0fd850, #0bad52, #00f2fe, #4facfe); background-size: 400% 400%; animation: gradientBG 15s ease infinite;">
            <div class="row">
                <div class="col-lg-12">
                    <span id="form_result_entrada"></span>
                    <div class="card card-primary" style="transition: all 0.15s ease 0s; height: inherit; width: inherit; background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2);">
                        <div class="card-header with-border" style="background: linear-gradient(135deg, #0fd850 0%, #0bad52 100%); border-radius: 8px; padding: 15px; margin-bottom: 20px;">
                            <div class="row align-items-center">
                                <div class="col-md-2 text-center">
                                    <div style="font-size: 2.5rem; font-weight: bold; color: white;">
                                        <i class="fas fa-plus-circle"></i>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div style="font-size: 1.5rem; font-weight: bold; color: white; margin: 0;">FIDEM</div>
                                    <div style="font-size: 0.9rem; opacity: 0.9; color: white; margin: 5px 0 0 0;">CLÍNICA ESPECIALIZADA EN DOLOR</div>
                                </div>
                                <div class="col-md-4">
                                    <h4 style="color: white; font-weight: bold;">REGISTRAR ENTRADA DE MEDICAMENTO</h4>
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
                            <form id="form-entrada">
                                @csrf
                                <input type="hidden" name="tipo_movimiento" value="entrada">

                                <div class="row">
                                    <!-- Columna izquierda -->
                                    <div class="col-lg-6">
                                        <div class="form-group-glass">
                                            <label for="entrada_fecha" style="color: white; font-weight: bold;">
                                                <i class="fas fa-calendar-alt mr-2"></i>Fecha *
                                            </label>
                                            <input type="date" name="fecha" id="entrada_fecha" class="form-control glass-input" value="{{date('Y-m-d')}}" required>
                                        </div>

                                        <div class="form-group-glass">
                                            <label for="entrada_medicamento_id" style="color: white; font-weight: bold;">
                                                <i class="fas fa-pills mr-2"></i>Medicamento *
                                            </label>
                                            <select name="medicamento_controlado_id" id="entrada_medicamento_id" class="form-control glass-select" required>
                                                <option value="">Seleccione un medicamento</option>
                                                @foreach($medicamentos as $med)
                                                    <option value="{{$med->id}}" data-saldo="{{$med->saldo_actual}}">
                                                        {{$med->nombre}} (Stock: {{$med->saldo_actual}})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group-glass">
                                            <label style="color: white; font-weight: bold;"><i class="fas fa-warehouse mr-2"></i>Saldo Actual</label>
                                            <div class="text-center">
                                                <span class="glass-badge glass-badge-info" style="font-size: 1.5rem; padding: 15px 30px;">
                                                    <i class="fas fa-box-open"></i> <span id="entrada-saldo-actual">0</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Columna derecha -->
                                    <div class="col-lg-6">
                                        <div class="form-group-glass">
                                            <label for="entrada_proveedor" style="color: white; font-weight: bold;">
                                                <i class="fas fa-truck mr-2"></i>Proveedor *
                                            </label>
                                            <input type="text" name="proveedor" id="entrada_proveedor" class="form-control glass-input" required maxlength="200" placeholder="Nombre del proveedor">
                                        </div>

                                        <div class="form-group-glass">
                                            <label for="entrada_numero_factura" style="color: white; font-weight: bold;">
                                                <i class="fas fa-file-invoice mr-2"></i>No. Factura
                                            </label>
                                            <input type="text" name="numero_factura" id="entrada_numero_factura" class="form-control glass-input" maxlength="100" placeholder="Número de factura (opcional)">
                                        </div>

                                        <div class="form-group-glass">
                                            <label for="entrada_cantidad" style="color: white; font-weight: bold;">
                                                <i class="fas fa-plus mr-2"></i>Cantidad a Ingresar *
                                            </label>
                                            <input type="number" name="entrada" id="entrada_cantidad" class="form-control glass-input" required min="1" placeholder="Cantidad a ingresar" style="font-size: 1.2rem; font-weight: bold;">
                                        </div>

                                        <div class="form-group-glass">
                                            <label style="color: white; font-weight: bold;"><i class="fas fa-calculator mr-2"></i>Nuevo Saldo</label>
                                            <div class="text-center">
                                                <span class="glass-badge glass-badge-success" style="font-size: 2rem; padding: 20px 40px;" id="entrada-nuevo-saldo">0</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12 text-center">
                                        <div class="btn-group-ios">
                                            <button type="button" class="btn-ios btn-ios-warning" id="btn-limpiar-entrada" onclick="limpiarFormEntrada()">
                                                <i class="fas fa-eraser"></i>
                                                <span>Limpiar</span>
                                            </button>
                                            <button type="submit" class="btn-ios btn-ios-success" id="btn-guardar-entrada">
                                                <i class="fas fa-save"></i>
                                                <span>Registrar Entrada</span>
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

    .glass-badge-success {
        background: rgba(40, 167, 69, 0.3);
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
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .btn-ios-success {
        background: linear-gradient(135deg, #0fd850 0%, #0bad52 100%);
    }
</style>
