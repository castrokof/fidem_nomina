@extends("theme.$theme.layout")
@section('titulo')
Editar Medicamento Controlado
@endsection

@section('contenido')
<div class="row">
    <div class="col-lg-12">
        @include('includes.form-error')
        @include('includes.form-mensaje')

        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Editar Medicamento Controlado</h3>
                <div class="card-tools">
                    <a href="{{route('medicamento_controlado')}}" class="btn btn-info btn-sm">
                        <i class="fas fa-list"></i> Listar Medicamentos
                    </a>
                </div>
            </div>

            <form action="{{route('actualizar_medicamento_controlado', ['id' => $data->id])}}" id="form-general" class="form-horizontal" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    @include('admin.medicamento_controlado.form')
                </div>
                <div class="card-footer">
                    <div class="col-lg-6">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-sync"></i> Actualizar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
