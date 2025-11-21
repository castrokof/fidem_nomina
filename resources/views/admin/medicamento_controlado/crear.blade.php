@extends("theme.$theme.layout")
@section('titulo')
Crear Medicamento Controlado
@endsection

@section('contenido')
<div class="row">
    <div class="col-lg-12">
        @include('includes.form-error')
        @include('includes.form-mensaje')

        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Crear Medicamento Controlado</h3>
                <div class="card-tools">
                    <a href="{{route('medicamento_controlado')}}" class="btn btn-info btn-sm">
                        <i class="fas fa-list"></i> Listar Medicamentos
                    </a>
                </div>
            </div>

            <form action="{{route('guardar_medicamento_controlado')}}" id="form-general" class="form-horizontal" method="POST">
                @csrf
                <div class="card-body">
                    @include('admin.medicamento_controlado.form')
                </div>
                <div class="card-footer">
                    <div class="col-lg-6">
                        <button type="reset" class="btn btn-default">
                            <i class="fas fa-eraser"></i> Limpiar
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
