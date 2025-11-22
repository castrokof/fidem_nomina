<div class="modal fade" tabindex="-1" id="modal-entrada" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content" style="background: #f8f9fa; border-radius: 15px; border: none;">
            <div class="row">
                <div class="col-lg-12">
                    <span id="form_result_entrada"></span>
                    <div class="card card-primary" style="transition: all 0.15s ease 0s; height: inherit; width: inherit; background: transparent; border: none; box-shadow: none;">
                        <div class="card-header with-border" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 12px; padding: 15px; margin-bottom: 20px;">
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

                        <div class="card-body" style="background: #ffffff; border-radius: 12px; padding: 25px;">
                            <form id="form-entrada">
                                @csrf
                                <input type="hidden" name="tipo_movimiento" value="entrada">

                                <div class="row">
                                    <!-- Columna izquierda -->
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="entrada_fecha" style="color: #495057; font-weight: bold;">
                                                <i class="fas fa-calendar-alt mr-2" style="color: #4facfe;"></i>Fecha *
                                            </label>
                                            <input type="date" name="fecha" id="entrada_fecha" class="form-control" value="{{date('Y-m-d')}}" required style="border: 2px solid #e0e0e0; border-radius: 8px; padding: 10px;">
                                        </div>

                                        <div class="form-group">
                                            <label for="entrada_medicamento_id" style="color: #495057; font-weight: bold;">
                                                <i class="fas fa-pills mr-2" style="color: #4facfe;"></i>Medicamento *
                                            </label>
                                            <select name="medicamento_controlado_id" id="entrada_medicamento_id" class="form-control" required style="border: 2px solid #e0e0e0; border-radius: 8px; padding: 10px;">
                                                <option value="">Cargando medicamentos...</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label style="color: #495057; font-weight: bold;"><i class="fas fa-warehouse mr-2" style="color: #4facfe;"></i>Saldo Actual</label>
                                            <div class="text-center">
                                                <span style="display: inline-block; padding: 15px 30px; border-radius: 12px; font-size: 1.5rem; font-weight: bold; background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); color: #1976d2; border: 2px solid #90caf9;">
                                                    <i class="fas fa-box-open"></i> <span id="entrada-saldo-actual">0</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Columna derecha -->
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="entrada_proveedor" style="color: #495057; font-weight: bold;">
                                                <i class="fas fa-truck mr-2" style="color: #4facfe;"></i>Proveedor *
                                            </label>
                                            <input type="text" name="proveedor" id="entrada_proveedor" class="form-control" required maxlength="200" placeholder="Nombre del proveedor" style="border: 2px solid #e0e0e0; border-radius: 8px; padding: 10px;">
                                        </div>

                                        <div class="form-group">
                                            <label for="entrada_numero_factura" style="color: #495057; font-weight: bold;">
                                                <i class="fas fa-file-invoice mr-2" style="color: #4facfe;"></i>No. Factura
                                            </label>
                                            <input type="text" name="numero_factura" id="entrada_numero_factura" class="form-control" maxlength="100" placeholder="Número de factura (opcional)" style="border: 2px solid #e0e0e0; border-radius: 8px; padding: 10px;">
                                        </div>

                                        <div class="form-group">
                                            <label for="entrada_cantidad" style="color: #495057; font-weight: bold;">
                                                <i class="fas fa-plus mr-2" style="color: #4facfe;"></i>Cantidad a Ingresar *
                                            </label>
                                            <input type="number" name="entrada" id="entrada_cantidad" class="form-control" required min="1" placeholder="Cantidad a ingresar" style="border: 2px solid #e0e0e0; border-radius: 8px; padding: 10px; font-size: 1.2rem; font-weight: bold;">
                                        </div>

                                        <div class="form-group">
                                            <label style="color: #495057; font-weight: bold;"><i class="fas fa-calculator mr-2" style="color: #4facfe;"></i>Nuevo Saldo</label>
                                            <div class="text-center">
                                                <span style="display: inline-block; padding: 20px 40px; border-radius: 12px; font-size: 2rem; font-weight: bold; background: linear-gradient(135deg, #c8e6c9 0%, #a5d6a7 100%); color: #2e7d32; border: 2px solid #81c784;" id="entrada-nuevo-saldo">0</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12 text-center">
                                        <button type="button" class="btn btn-secondary" onclick="limpiarFormEntrada()" style="margin-right: 10px; border-radius: 8px; padding: 10px 25px;">
                                            <i class="fas fa-eraser"></i> Limpiar
                                        </button>
                                        <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border: none; border-radius: 8px; padding: 10px 25px;">
                                            <i class="fas fa-save"></i> Registrar Entrada
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
