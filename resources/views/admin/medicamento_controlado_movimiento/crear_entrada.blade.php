@extends("theme.$theme.layout")
@section('titulo')
Registrar Entrada de Medicamento
@endsection

@section('scripts')
<script src="{{asset("assets/pages/scripts/admin/medicamento_controlado_movimiento/crear_entrada.js")}}" type="text/javascript"></script>
@endsection

@section('contenido')
<div class="row">
    <div class="col-lg-12">
        @include('includes.form-error')
        @include('includes.form-mensaje')

        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Registrar Entrada de Medicamento Controlado</h3>
                <div class="card-tools">
                    <a href="{{route('medicamento_controlado_movimiento')}}" class="btn btn-info btn-sm">
                        <i class="fas fa-list"></i> Ver Movimientos
                    </a>
                </div>
            </div>

            <form action="{{route('guardar_medicamento_controlado_movimiento')}}" id="form-entrada" class="form-horizontal" method="POST">
                @csrf
                <input type="hidden" name="tipo_movimiento" value="entrada">

                <div class="card-body">
                    <div class="form-group row">
                        <label for="fecha" class="col-lg-3 control-label requerido">Fecha</label>
                        <div class="col-lg-8">
                            <input type="date" name="fecha" id="fecha" class="form-control" value="{{old('fecha', date('Y-m-d'))}}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="medicamento_controlado_id" class="col-lg-3 control-label requerido">Medicamento</label>
                        <div class="col-lg-8">
                            <select name="medicamento_controlado_id" id="medicamento_controlado_id" class="form-control" required>
                                <option value="">Seleccione un medicamento</option>
                                @foreach($medicamentos as $med)
                                    <option value="{{$med->id}}" data-saldo="{{$med->saldo_actual}}" {{old('medicamento_controlado_id') == $med->id ? 'selected' : ''}}>
                                        {{$med->nombre}} (Saldo actual: {{$med->saldo_actual}})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 control-label">Saldo Actual</label>
                        <div class="col-lg-8">
                            <h4><span class="badge badge-info" id="saldo-actual">0</span></h4>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="proveedor" class="col-lg-3 control-label requerido">Proveedor</label>
                        <div class="col-lg-8">
                            <input type="text" name="proveedor" id="proveedor" class="form-control" value="{{old('proveedor')}}" required maxlength="200" placeholder="Nombre del proveedor">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="numero_factura" class="col-lg-3 control-label">No. Factura</label>
                        <div class="col-lg-8">
                            <input type="text" name="numero_factura" id="numero_factura" class="form-control" value="{{old('numero_factura')}}" maxlength="100" placeholder="Número de factura (opcional)">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="entrada" class="col-lg-3 control-label requerido">Cantidad de Entrada</label>
                        <div class="col-lg-8">
                            <input type="number" name="entrada" id="entrada" class="form-control" value="{{old('entrada')}}" required min="1" placeholder="Cantidad a ingresar">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 control-label">Nuevo Saldo</label>
                        <div class="col-lg-8">
                            <h3><span class="badge badge-success" id="nuevo-saldo">0</span></h3>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="col-lg-6">
                        <button type="reset" class="btn btn-default">
                            <i class="fas fa-eraser"></i> Limpiar
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Registrar Entrada
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
