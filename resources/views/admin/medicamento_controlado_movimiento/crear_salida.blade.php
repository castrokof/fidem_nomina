@extends("theme.$theme.layout")
@section('titulo')
Registrar Salida de Medicamento
@endsection

@section('scripts')
<script src="{{asset("assets/pages/scripts/admin/medicamento_controlado_movimiento/crear_salida.js")}}" type="text/javascript"></script>
@endsection

@section('contenido')
<div class="row">
    <div class="col-lg-12">
        @include('includes.form-error')
        @include('includes.form-mensaje')

        <div class="card card-danger">
            <div class="card-header">
                <h3 class="card-title">Registrar Salida de Medicamento Controlado</h3>
                <div class="card-tools">
                    <a href="{{route('medicamento_controlado_movimiento')}}" class="btn btn-info btn-sm">
                        <i class="fas fa-list"></i> Ver Movimientos
                    </a>
                </div>
            </div>

            <form action="{{route('guardar_medicamento_controlado_movimiento')}}" id="form-salida" class="form-horizontal" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="tipo_movimiento" value="salida">

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
                        <label for="nombre_paciente" class="col-lg-3 control-label requerido">Nombre del Paciente</label>
                        <div class="col-lg-8">
                            <input type="text" name="nombre_paciente" id="nombre_paciente" class="form-control" value="{{old('nombre_paciente')}}" required maxlength="200" placeholder="Nombre completo del paciente">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="cedula_paciente" class="col-lg-3 control-label requerido">Cédula del Paciente</label>
                        <div class="col-lg-8">
                            <input type="text" name="cedula_paciente" id="cedula_paciente" class="form-control" value="{{old('cedula_paciente')}}" required maxlength="50" placeholder="Número de cédula">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="numero_formula_control" class="col-lg-3 control-label">No. Fórmula de Control</label>
                        <div class="col-lg-8">
                            <input type="text" name="numero_formula_control" id="numero_formula_control" class="form-control" value="{{old('numero_formula_control')}}" maxlength="100" placeholder="Número del formulario de control (opcional)">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="salida" class="col-lg-3 control-label requerido">Cantidad de Salida</label>
                        <div class="col-lg-8">
                            <input type="number" name="salida" id="salida" class="form-control" value="{{old('salida')}}" required min="1" placeholder="Cantidad a retirar">
                            <small class="form-text text-muted">La cantidad no puede ser mayor al saldo actual</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 control-label">Nuevo Saldo</label>
                        <div class="col-lg-8">
                            <h3><span class="badge badge-warning" id="nuevo-saldo">0</span></h3>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="foto_formula" class="col-lg-3 control-label">Foto del Formulario</label>
                        <div class="col-lg-8">
                            <div class="custom-file">
                                <input type="file" name="foto_formula" id="foto_formula" class="custom-file-input" accept="image/*" capture="camera">
                                <label class="custom-file-label" for="foto_formula">Seleccionar foto o tomar foto...</label>
                            </div>
                            <small class="form-text text-muted">Formatos: JPG, JPEG, PNG. Tamaño máximo: 5MB</small>

                            <!-- Preview de la imagen -->
                            <div id="preview-container" class="mt-3" style="display: none;">
                                <img id="preview-imagen" src="" alt="Preview" class="img-fluid img-thumbnail" style="max-height: 300px;">
                                <button type="button" id="btn-eliminar-foto" class="btn btn-danger btn-sm mt-2">
                                    <i class="fas fa-trash"></i> Eliminar foto
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="col-lg-6">
                        <button type="reset" class="btn btn-default" id="btn-limpiar">
                            <i class="fas fa-eraser"></i> Limpiar
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-save"></i> Registrar Salida
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
