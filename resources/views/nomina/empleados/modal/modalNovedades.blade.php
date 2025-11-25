<div class="modal fade" tabindex="-1" id="modal-novedad" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="row">
                <div class="col-lg-12">
                    @include('includes.form-error')
                    @include('includes.form-mensaje')
                    <span id="form_result_novedad"></span>
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title-novedad"></h3>
                            <div class="card-tools pull-right">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                        <form id="form-novedad" class="form-horizontal" method="POST">
                            @csrf
                            <div class="card-body">
                                <!-- Información del Empleado -->
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <h5 id="empleado-info-novedad"></h5>
                                    </div>
                                </div>

                                <!-- Tipo de Novedad -->
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label for="tipo_novedad" class="control-label">Tipo de Novedad <span class="text-danger">*</span></label>
                                        <select name="tipo_novedad" id="tipo_novedad" class="form-control" required>
                                            <option value="">-- Seleccione --</option>
                                            <option value="incapacidad">Incapacidad</option>
                                            <option value="licencia">Licencia</option>
                                            <option value="vacaciones">Vacaciones</option>
                                            <option value="suspension">Suspensión</option>
                                            <option value="permiso">Permiso</option>
                                            <option value="otro">Otro</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="estado_novedad" class="control-label">Estado</label>
                                        <select name="estado_novedad" id="estado_novedad" class="form-control">
                                            <option value="activo">Activo</option>
                                            <option value="finalizado">Finalizado</option>
                                            <option value="cancelado">Cancelado</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Fechas -->
                                <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label for="fecha_inicio_novedad" class="control-label">Fecha Inicio <span class="text-danger">*</span></label>
                                        <input type="date" name="fecha_inicio_novedad" id="fecha_inicio_novedad" class="form-control" required>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="fecha_fin_novedad" class="control-label">Fecha Fin</label>
                                        <input type="date" name="fecha_fin_novedad" id="fecha_fin_novedad" class="form-control">
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="dias_novedad" class="control-label">Días</label>
                                        <input type="number" name="dias_novedad" id="dias_novedad" class="form-control" readonly>
                                    </div>
                                </div>

                                <!-- Valor -->
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="valor_novedad" class="control-label">Valor (si aplica)</label>
                                        <input type="number" name="valor_novedad" id="valor_novedad" class="form-control">
                                    </div>
                                </div>

                                <!-- Observación -->
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="observacion_novedad" class="control-label">Observación</label>
                                        <textarea name="observacion_novedad" id="observacion_novedad" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>

                                <!-- Documento Soporte -->
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="documento_soporte_novedad" class="control-label">Documento de Soporte (Ruta)</label>
                                        <input type="text" name="documento_soporte_novedad" id="documento_soporte_novedad" class="form-control">
                                    </div>
                                </div>

                                <input type="hidden" name="empleado_id_novedad" id="empleado_id_novedad">
                                <input type="hidden" name="hidden_id_novedad" id="hidden_id_novedad">
                                <input type="hidden" name="action_novedad" id="action_novedad" value="Add">
                            </div>

                            <!-- Tabla de Novedades del Empleado -->
                            <div class="card-body">
                                <h5>Historial de Novedades</h5>
                                <div class="table-responsive">
                                    <table id="tabla-novedades-empleado" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Acciones</th>
                                                <th>Tipo</th>
                                                <th>Fecha Inicio</th>
                                                <th>Fecha Fin</th>
                                                <th>Días</th>
                                                <th>Valor</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- /.card-body -->
                            <div class="card-footer">
                                <div class="col-lg-12">
                                    <button type="button" name="action_button_novedad" id="action_button_novedad" class="btn btn-warning btn-block addnovedad-empleado">Guardar Novedad</button>
                                </div>
                            </div>
                            <!-- /.card-footer -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
