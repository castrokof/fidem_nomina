@extends("theme.$theme.layout")
@section('titulo')
Registrar Salida de Medicamento
@endsection

@section('styles')
<link href="{{asset('assets/css/custom/medicamentos-glassmorphism.css')}}" rel="stylesheet" type="text/css"/>
<style>
    .content-wrapper {
        background: linear-gradient(-45deg, #f093fb, #f5576c, #fd5949, #fc6767);
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
    }
</style>
@endsection

@section('scripts')
<script>
    var guardarMovimientoUrl = "{{route('guardar_medicamento_controlado_movimiento')}}";
</script>
<script src="{{asset("assets/pages/scripts/admin/medicamento_controlado_movimiento/crear_salida.js")}}" type="text/javascript"></script>
@endsection

@section('contenido')
<div class="medicamentos-wrapper">
    <div class="row">
        <div class="col-lg-12">
            @include('includes.form-error')

            <!-- Mensaje de éxito AJAX -->
            <div id="mensaje-ajax" class="glass-alert glass-alert-success" style="display: none;">
                <i class="fas fa-check-circle mr-2"></i>
                <span id="mensaje-texto"></span>
            </div>

            <div class="glass-card animate-in">
                <div class="glass-card-header" style="background: var(--danger-gradient);">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h3><i class="fas fa-minus-circle mr-2"></i> Registrar Salida de Medicamento</h3>
                        <a href="{{route('medicamento_controlado_movimiento')}}" class="btn-ios btn-ios-info mt-2 mt-md-0">
                            <i class="fas fa-list"></i>
                            <span>Ver Movimientos</span>
                        </a>
                    </div>
                </div>

                <div class="glass-card-body">
                    <form id="form-salida" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="tipo_movimiento" value="salida">

                        <div class="row">
                            <!-- Columna izquierda: Datos del medicamento y paciente -->
                            <div class="col-lg-6">
                                <div class="form-group-glass">
                                    <label for="fecha">
                                        <i class="fas fa-calendar-alt mr-2"></i>Fecha *
                                    </label>
                                    <input type="date" name="fecha" id="fecha" class="form-control glass-input" value="{{date('Y-m-d')}}" required>
                                </div>

                                <div class="form-group-glass">
                                    <label for="medicamento_controlado_id">
                                        <i class="fas fa-pills mr-2"></i>Medicamento *
                                    </label>
                                    <select name="medicamento_controlado_id" id="medicamento_controlado_id" class="form-control glass-select" required>
                                        <option value="">Seleccione un medicamento</option>
                                        @foreach($medicamentos as $med)
                                            <option value="{{$med->id}}" data-saldo="{{$med->saldo_actual}}">
                                                {{$med->nombre}} (Stock: {{$med->saldo_actual}})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group-glass">
                                    <label><i class="fas fa-warehouse mr-2"></i>Saldo Disponible</label>
                                    <div class="text-center">
                                        <span class="glass-badge glass-badge-info" style="font-size: 1.5rem; padding: 15px 30px;">
                                            <i class="fas fa-box-open"></i> <span id="saldo-actual">0</span>
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group-glass">
                                    <label for="nombre_paciente">
                                        <i class="fas fa-user-injured mr-2"></i>Nombre del Paciente *
                                    </label>
                                    <input type="text" name="nombre_paciente" id="nombre_paciente" class="form-control glass-input" required maxlength="200" placeholder="Nombre completo del paciente">
                                </div>

                                <div class="form-group-glass">
                                    <label for="cedula_paciente">
                                        <i class="fas fa-id-card mr-2"></i>Cédula del Paciente *
                                    </label>
                                    <input type="text" name="cedula_paciente" id="cedula_paciente" class="form-control glass-input" required maxlength="50" placeholder="Número de cédula">
                                </div>

                                <div class="form-group-glass">
                                    <label for="numero_formula_control">
                                        <i class="fas fa-file-prescription mr-2"></i>No. Fórmula de Control
                                    </label>
                                    <input type="text" name="numero_formula_control" id="numero_formula_control" class="form-control glass-input" maxlength="100" placeholder="Número del formulario (opcional)">
                                </div>
                            </div>

                            <!-- Columna derecha: Cantidad y foto -->
                            <div class="col-lg-6">
                                <div class="form-group-glass">
                                    <label for="salida">
                                        <i class="fas fa-minus mr-2"></i>Cantidad a Retirar *
                                    </label>
                                    <input type="number" name="salida" id="salida" class="form-control glass-input" required min="1" placeholder="Cantidad a retirar" style="font-size: 1.2rem; font-weight: bold;">
                                    <small style="color: rgba(255, 255, 255, 0.8);">La cantidad no puede ser mayor al saldo disponible</small>
                                </div>

                                <div class="form-group-glass">
                                    <label><i class="fas fa-calculator mr-2"></i>Nuevo Saldo</label>
                                    <div class="text-center">
                                        <span class="glass-badge glass-badge-warning" style="font-size: 2rem; padding: 20px 40px;" id="nuevo-saldo">0</span>
                                    </div>
                                </div>

                                <div class="form-group-glass">
                                    <label for="foto_formula">
                                        <i class="fas fa-camera mr-2"></i>Foto del Formulario
                                    </label>
                                    <div class="custom-file">
                                        <input type="file" name="foto_formula" id="foto_formula" class="custom-file-input" accept="image/*" capture="environment">
                                        <label class="custom-file-label glass-input" for="foto_formula">
                                            <i class="fas fa-camera mr-2"></i>Tomar foto o seleccionar...
                                        </label>
                                    </div>
                                    <small style="color: rgba(255, 255, 255, 0.8);">JPG, PNG. Máx: 5MB</small>

                                    <!-- Preview con glassmorphism -->
                                    <div id="preview-container" class="image-preview-glass" style="display: none;">
                                        <img id="preview-imagen" src="" alt="Preview" style="max-height: 250px; border-radius: 10px;">
                                        <button type="button" id="btn-eliminar-foto" class="glass-btn glass-btn-danger mt-3">
                                            <i class="fas fa-trash"></i> Eliminar foto
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12 text-center">
                                <div class="btn-group-ios">
                                    <button type="reset" class="btn-ios btn-ios-warning" id="btn-limpiar">
                                        <i class="fas fa-eraser"></i>
                                        <span>Limpiar</span>
                                    </button>
                                    <button type="submit" class="btn-ios btn-ios-danger" id="btn-guardar">
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
@endsection
