@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus"></i>
                        Nuevo Registro de Salario
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('empleados.salary-history.index', $empleado->id) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h5><i class="icon fas fa-ban"></i> Error!</h5>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Información del empleado -->
                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-user"></i> Empleado</h5>
                        <strong>Nombre:</strong> {{ $empleado->pnombre }} {{ $empleado->snombre }} {{ $empleado->papellido }} {{ $empleado->sapellido }}<br>
                        <strong>Documento:</strong> {{ $empleado->tipo_documento }} {{ $empleado->documento }}<br>
                        <strong>Cargo:</strong> {{ $empleado->position }}<br>
                        <strong>Tipo de Contrato:</strong> {{ $empleado->type_contrat }}
                    </div>

                    @if($empleado->salary || $empleado->salary_ps)
                        <div class="alert alert-warning">
                            <h5><i class="icon fas fa-exclamation-triangle"></i> Salario Actual</h5>
                            @if($empleado->salary)
                                <strong>Salario Fijo:</strong> ${{ number_format($empleado->salary, 0, ',', '.') }}<br>
                            @endif
                            @if($empleado->salary_ps)
                                <strong>Salario Prestación de Servicios:</strong> ${{ number_format($empleado->salary_ps, 0, ',', '.') }}<br>
                            @endif
                            <small>Este salario será reemplazado por el nuevo registro.</small>
                        </div>
                    @endif

                    <form action="{{ route('empleados.salary-history.store', $empleado->id) }}" method="POST" id="salaryForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="salary">Salario Fijo Mensual</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number"
                                               class="form-control @error('salary') is-invalid @enderror"
                                               id="salary"
                                               name="salary"
                                               value="{{ old('salary', $empleado->salary) }}"
                                               placeholder="0"
                                               min="0"
                                               step="1">
                                        @error('salary')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">Para empleados con contrato fijo</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="salary_ps">Salario Prestación de Servicios</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number"
                                               class="form-control @error('salary_ps') is-invalid @enderror"
                                               id="salary_ps"
                                               name="salary_ps"
                                               value="{{ old('salary_ps', $empleado->salary_ps) }}"
                                               placeholder="0"
                                               min="0"
                                               step="1">
                                        @error('salary_ps')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">Para empleados con prestación de servicios</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fecha_inicio">Fecha de Inicio de Vigencia <span class="text-danger">*</span></label>
                            <input type="date"
                                   class="form-control @error('fecha_inicio') is-invalid @enderror"
                                   id="fecha_inicio"
                                   name="fecha_inicio"
                                   value="{{ old('fecha_inicio', now()->format('Y-m-d')) }}"
                                   required>
                            @error('fecha_inicio')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <small class="form-text text-muted">Fecha desde la cual este salario será vigente</small>
                        </div>

                        <div class="form-group">
                            <label for="motivo">Motivo del Cambio</label>
                            <textarea class="form-control @error('motivo') is-invalid @enderror"
                                      id="motivo"
                                      name="motivo"
                                      rows="3"
                                      placeholder="Ej: Aumento por mérito, Ajuste anual, Cambio de cargo, etc.">{{ old('motivo') }}</textarea>
                            @error('motivo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <small class="form-text text-muted">Opcional: Razón del cambio salarial</small>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Nota:</strong> Este registro será marcado como el salario activo. Los registros anteriores serán marcados como históricos automáticamente.
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Registro
                            </button>
                            <a href="{{ route('empleados.salary-history.index', $empleado->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Formatear números mientras se escriben
        $('#salary, #salary_ps').on('input', function() {
            var value = $(this).val();
            if (value) {
                // Remover caracteres no numéricos
                value = value.replace(/[^0-9]/g, '');
                $(this).val(value);
            }
        });

        // Validar que al menos un salario esté presente antes de enviar
        $('#salaryForm').on('submit', function(e) {
            var salary = $('#salary').val();
            var salary_ps = $('#salary_ps').val();

            if (!salary && !salary_ps) {
                e.preventDefault();
                alert('Debe ingresar al menos un tipo de salario (Fijo o Prestación de Servicios)');
                return false;
            }
        });
    });
</script>
@endsection
