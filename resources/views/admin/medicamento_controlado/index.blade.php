@extends("theme.$theme.layout")
@section('titulo')
Medicamentos Controlados
@endsection

@section('scripts')
<script src="{{asset("assets/pages/scripts/admin/medicamento_controlado/index.js")}}" type="text/javascript"></script>
@endsection

@section('contenido')
<div class="row">
    <div class="col-lg-12">
        @include('includes.form-error')
        @include('includes.form-mensaje')

        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Medicamentos Controlados</h3>
                <div class="card-tools">
                    <a href="{{route('crear_medicamento_controlado')}}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus"></i> Nuevo Medicamento
                    </a>
                    <a href="{{route('medicamento_controlado_movimiento')}}" class="btn btn-info btn-sm">
                        <i class="fas fa-list"></i> Ver Movimientos
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="tabla-medicamentos">
                        <thead>
                            <tr>
                                <th width="10%">ID</th>
                                <th width="35%">Nombre</th>
                                <th width="25%">Descripción</th>
                                <th width="10%" class="text-center">Saldo Actual</th>
                                <th width="10%" class="text-center">Estado</th>
                                <th width="10%" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($medicamentos as $medicamento)
                            <tr>
                                <td>{{$medicamento->id}}</td>
                                <td>{{$medicamento->nombre}}</td>
                                <td>{{$medicamento->descripcion ?? 'N/A'}}</td>
                                <td class="text-center">
                                    <span class="badge badge-info badge-lg">{{$medicamento->saldo_actual}}</span>
                                </td>
                                <td class="text-center">
                                    @if($medicamento->activo)
                                        <span class="badge badge-success">Activo</span>
                                    @else
                                        <span class="badge badge-secondary">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{route('editar_medicamento_controlado', ['id' => $medicamento->id])}}" class="btn btn-warning btn-sm" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm btn-eliminar" data-id="{{$medicamento->id}}" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
