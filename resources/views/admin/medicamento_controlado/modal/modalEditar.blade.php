<div class="modal fade" tabindex="-1" id="modal-editar-medicamento" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background: #f8f9fa; border-radius: 15px; border: none;">
            <div class="row">
                <div class="col-lg-12">
                    <span id="form_result_editar"></span>
                    <div class="card card-primary" style="transition: all 0.15s ease 0s; height: inherit; width: inherit; background: transparent; border: none; box-shadow: none;">
                        <div class="card-header with-border" style="background: linear-gradient(135deg, #5ba3d0 0%, #2193b0 100%); border-radius: 12px; padding: 15px; margin-bottom: 20px;">
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

                        <div class="card-body" style="background: #ffffff; border-radius: 12px; padding: 25px;">
                            <form id="form-editar-medicamento" method="POST">
                                @csrf
                                <input type="hidden" name="id" id="editar_id">

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="editar_nombre" style="color: #495057; font-weight: bold;">
                                                <i class="fas fa-prescription-bottle mr-2" style="color: #5ba3d0;"></i>Nombre del Medicamento *
                                            </label>
                                            <input type="text" name="nombre" id="editar_nombre" class="form-control" required maxlength="200" placeholder="Ej: Morfina 10mg, Fentanilo 50mcg" style="border: 2px solid #e0e0e0; border-radius: 8px; padding: 10px;">
                                        </div>

                                        <div class="form-group">
                                            <label for="editar_descripcion" style="color: #495057; font-weight: bold;">
                                                <i class="fas fa-info-circle mr-2" style="color: #5ba3d0;"></i>Descripción
                                            </label>
                                            <textarea name="descripcion" id="editar_descripcion" class="form-control" rows="4" placeholder="Descripción opcional del medicamento: presentación, concentración, uso, etc." style="border: 2px solid #e0e0e0; border-radius: 8px; padding: 10px;"></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label style="color: #495057; font-weight: bold;">
                                                <i class="fas fa-toggle-on mr-2" style="color: #5ba3d0;"></i>Estado
                                            </label>
                                            <div class="custom-control custom-switch" style="padding-left: 3rem;">
                                                <input type="checkbox" name="activo" id="editar_activo" class="custom-control-input" value="1">
                                                <label class="custom-control-label" for="editar_activo" style="color: #495057; font-weight: 600; padding-top: 2px;">
                                                    <span id="editar-estado-texto">Activo</span>
                                                </label>
                                            </div>
                                            <small style="color: #6c757d;">Los medicamentos inactivos no aparecerán en los registros de entradas/salidas</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12 text-center">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-right: 10px; border-radius: 8px; padding: 10px 25px;">
                                            <i class="fas fa-times"></i> Cancelar
                                        </button>
                                        <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #5ba3d0 0%, #2193b0 100%); border: none; border-radius: 8px; padding: 10px 25px;">
                                            <i class="fas fa-save"></i> Actualizar Medicamento
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

<style>
    .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #5ba3d0;
        border-color: #5ba3d0;
    }
</style>

<script>
    // Cambiar texto del switch
    document.getElementById('editar_activo').addEventListener('change', function() {
        document.getElementById('editar-estado-texto').textContent = this.checked ? 'Activo' : 'Inactivo';
    });
</script>
