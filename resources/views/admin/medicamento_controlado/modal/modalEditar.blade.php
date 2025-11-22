<div class="modal fade" tabindex="-1" id="modal-editar-medicamento" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background: linear-gradient(-45deg, #ffc107, #ff9800, #ff5722, #e91e63); background-size: 400% 400%; animation: gradientBG 15s ease infinite;">
            <div class="row">
                <div class="col-lg-12">
                    <span id="form_result_editar"></span>
                    <div class="card card-primary" style="transition: all 0.15s ease 0s; height: inherit; width: inherit; background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2);">
                        <div class="card-header with-border" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); border-radius: 8px; padding: 15px; margin-bottom: 20px;">
                            <div class="row align-items-center">
                                <div class="col-md-2 text-center">
                                    <div style="font-size: 2.5rem; font-weight: bold; color: white;">
                                        <i class="fas fa-edit"></i>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div style="font-size: 1.5rem; font-weight: bold; color: white; margin: 0;">FIDEM</div>
                                    <div style="font-size: 0.9rem; opacity: 0.9; color: white; margin: 5px 0 0 0;">CLÍNICA ESPECIALIZADA EN DOLOR</div>
                                </div>
                                <div class="col-md-4">
                                    <h4 style="color: white; font-weight: bold;">EDITAR MEDICAMENTO CONTROLADO</h4>
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
                            <form id="form-editar-medicamento" method="POST">
                                @csrf
                                <input type="hidden" name="id" id="editar_id">

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group-glass">
                                            <label for="editar_nombre" style="color: white; font-weight: bold;">
                                                <i class="fas fa-prescription-bottle mr-2"></i>Nombre del Medicamento *
                                            </label>
                                            <input type="text" name="nombre" id="editar_nombre" class="form-control glass-input" required maxlength="200" placeholder="Ej: Morfina 10mg, Fentanilo 50mcg">
                                        </div>

                                        <div class="form-group-glass">
                                            <label for="editar_descripcion" style="color: white; font-weight: bold;">
                                                <i class="fas fa-info-circle mr-2"></i>Descripción
                                            </label>
                                            <textarea name="descripcion" id="editar_descripcion" class="form-control glass-input" rows="4" placeholder="Descripción opcional del medicamento: presentación, concentración, uso, etc."></textarea>
                                        </div>

                                        <div class="form-group-glass">
                                            <label style="color: white; font-weight: bold;">
                                                <i class="fas fa-toggle-on mr-2"></i>Estado
                                            </label>
                                            <div class="custom-control custom-switch" style="padding-left: 3rem;">
                                                <input type="checkbox" name="activo" id="editar_activo" class="custom-control-input" value="1">
                                                <label class="custom-control-label" for="editar_activo" style="color: white; font-weight: 600; padding-top: 2px;">
                                                    <span id="editar-estado-texto">Activo</span>
                                                </label>
                                            </div>
                                            <small style="color: rgba(255, 255, 255, 0.8);">Los medicamentos inactivos no aparecerán en los registros de entradas/salidas</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12 text-center">
                                        <div class="btn-group-ios">
                                            <button type="button" class="btn-ios btn-ios-secondary" data-dismiss="modal">
                                                <i class="fas fa-times"></i>
                                                <span>Cancelar</span>
                                            </button>
                                            <button type="submit" class="btn-ios btn-ios-warning" id="btn-actualizar">
                                                <i class="fas fa-save"></i>
                                                <span>Actualizar Medicamento</span>
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

    textarea.glass-input {
        resize: vertical;
    }

    .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #28a745;
        border-color: #28a745;
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

    .btn-ios-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    }
</style>

<script>
    // Cambiar texto del switch
    document.getElementById('editar_activo').addEventListener('change', function() {
        document.getElementById('editar-estado-texto').textContent = this.checked ? 'Activo' : 'Inactivo';
    });
</script>
