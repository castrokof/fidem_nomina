@extends("theme.$theme.layout")
@section('titulo')
Registrar Entrada de Medicamento
@endsection

@section('styles')
<link href="{{asset('assets/css/custom/medicamentos-glassmorphism.css')}}" rel="stylesheet" type="text/css"/>
<style>
    .content-wrapper {
        background: linear-gradient(-45deg, #0fd850, #0bad52, #00f2fe, #4facfe);
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
    }
</style>
@endsection

@section('scripts')
<script>
    var guardarMovimientoUrl = "{{route('guardar_medicamento_controlado_movimiento')}}";
</script>
<script src="{{asset("assets/pages/scripts/admin/medicamento_controlado_movimiento/crear_entrada.js")}}" type="text/javascript"></script>
@endsection

@section('contenido')
<div class="medicamentos-wrapper">
    <div class="row">
        <div class="col-lg-12">
            @include('includes.form-error')

            <div class="glass-card animate-in">
                <div class="glass-card-header" style="background: var(--success-gradient);">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h3><i class="fas fa-plus-circle mr-2"></i> Registrar Entrada de Medicamento</h3>
                        <a href="{{route('medicamento_controlado_movimiento')}}" class="btn-ios btn-ios-info mt-2 mt-md-0">
                            <i class="fas fa-list"></i>
                            <span>Ver Movimientos</span>
                        </a>
                    </div>
                </div>

                <div class="glass-card-body">
                    <form id="form-entrada">
                        @csrf
                        <input type="hidden" name="tipo_movimiento" value="entrada">

                        <div class="row">
                            <!-- Columna izquierda -->
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
                                    <label><i class="fas fa-warehouse mr-2"></i>Saldo Actual</label>
                                    <div class="text-center">
                                        <span class="glass-badge glass-badge-info" style="font-size: 1.5rem; padding: 15px 30px;">
                                            <i class="fas fa-box-open"></i> <span id="saldo-actual">0</span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Columna derecha -->
                            <div class="col-lg-6">
                                <div class="form-group-glass">
                                    <label for="proveedor">
                                        <i class="fas fa-truck mr-2"></i>Proveedor *
                                    </label>
                                    <input type="text" name="proveedor" id="proveedor" class="form-control glass-input" required maxlength="200" placeholder="Nombre del proveedor">
                                </div>

                                <div class="form-group-glass">
                                    <label for="numero_factura">
                                        <i class="fas fa-file-invoice mr-2"></i>No. Factura
                                    </label>
                                    <input type="text" name="numero_factura" id="numero_factura" class="form-control glass-input" maxlength="100" placeholder="Número de factura (opcional)">
                                </div>

                                <div class="form-group-glass">
                                    <label for="entrada">
                                        <i class="fas fa-plus mr-2"></i>Cantidad a Ingresar *
                                    </label>
                                    <input type="number" name="entrada" id="entrada" class="form-control glass-input" required min="1" placeholder="Cantidad a ingresar" style="font-size: 1.2rem; font-weight: bold;">
                                </div>

                                <div class="form-group-glass">
                                    <label><i class="fas fa-calculator mr-2"></i>Nuevo Saldo</label>
                                    <div class="text-center">
                                        <span class="glass-badge glass-badge-success" style="font-size: 2rem; padding: 20px 40px;" id="nuevo-saldo">0</span>
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
                                    <button type="submit" class="btn-ios btn-ios-success" id="btn-guardar">
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
@endsection
