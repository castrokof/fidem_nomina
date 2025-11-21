
                        <div class="form-group row">
                             <div class="col-lg-3">
                                <label for="name_bank" class="col-xs-4 control-label ">Banco</label>
                                <select name="name_bank" id="name_bank" class="form-control select2bs4"
                                    style="width: 100%;" required>
                                </select>

                            </div>

                            <div class="col-lg-3">
                                <label for="type_account" class="col-xs-4 control-label ">Tipo de
                                    cuenta</label>
                                <select name="type_account" id="type_account" class="form-control select2bs4"
                                    style="width: 100%;" required>
                                </select>

                            </div>
                            <div class="col-lg-3">
                                <label for="account" class="col-xs-4 control-label "># de
                                    Cuenta</label>
                                <input type="number" name="account" id="account" class="form-control"
                                    value="{{ old('account') }}" required>
                            </div>
                            <div class="col-lg-3">
                                <label for="value_transporte" class="col-xs-4 control-label ">Auxilio de Trans.</label>
                                <input type="number" name="value_transporte" id="value_transporte" class="form-control"
                                    value="{{ old('account') }}" required>
                            </div>

                        </div>

                        <div class="form-group row">
                            <div class="col-lg-3">
                                <label for="value_salary_add" class="col-xs-4 control-label ">Rodamiento 1</label>
                                <input type="number" name="value_salary_add" id="value_salary_add" class="form-control"
                                    value="{{ old('account') }}" required>
                            </div>
                            <div class="col-lg-3">
                                <label for="value_hour" class="col-xs-4 control-label ">Valor Hora</label>
                                <input type="number" name="value_hour" id="value_hour" class="form-control"
                                    value="{{ old('account') }}" required>
                            </div>
                            <div class="col-lg-3">
                                <label for="value_patient_attended" class="col-xs-4 control-label ">Valor Paciente Atendido</label>
                                <input type="number" name="value_patient_attended" id="value_patient_attended" class="form-control"
                                    value="{{ old('account') }}" required>
                            </div>
                            <div class="col-lg-3">
                                <label for="value_add_security_social" class="col-xs-4 control-label ">% de Retención</label>
                                <input type="number" name="value_add_security_social" id="value_add_security_social" class="form-control"
                                    value="{{ old('account') }}" required>
                            </div>


                       </div>

