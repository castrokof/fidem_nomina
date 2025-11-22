@extends("theme.$theme.layout")
@section('titulo')
Medicamentos Controlados
@endsection

@section('styles')
<link href="{{asset('assets/css/custom/medicamentos-glassmorphism.css')}}" rel="stylesheet" type="text/css"/>
<style>
    .content-wrapper {
        background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
    }
</style>
@endsection

@section('scripts')
<script src="{{asset("assets/pages/scripts/admin/medicamento_controlado/index.js")}}" type="text/javascript"></script>
@endsection

@section('contenido')
<div class="medicamentos-wrapper">
    <div class="row animate-in">
        <!-- Estadísticas -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="stats-card">
                <div class="icon">
                    <i class="fas fa-pills"></i>
                </div>
                <div class="value" id="total-medicamentos">{{ count($medicamentos) }}</div>
                <div class="label">Total Medicamentos</div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="stats-card">
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="value" id="medicamentos-activos">{{ $medicamentos->where('activo', 1)->count() }}</div>
                <div class="label">Medicamentos Activos</div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="stats-card">
                <div class="icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="value" id="total-stock">{{ $medicamentos->sum('saldo_actual') }}</div>
                <div class="label">Stock Total</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            @include('includes.form-error')
            @include('includes.form-mensaje')

            <div class="glass-card animate-in">
                <div class="glass-card-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h3><i class="fas fa-pills mr-2"></i> Medicamentos Controlados</h3>
                        <div class="mt-2 mt-md-0">
                            <div class="btn-group-ios">
                                <a href="{{route('crear_medicamento_controlado')}}" class="btn-ios btn-ios-success">
                                    <i class="fas fa-plus-circle"></i>
                                    <span>Nuevo</span>
                                </a>
                                <a href="{{route('medicamento_controlado_movimiento')}}" class="btn-ios btn-ios-info">
                                    <i class="fas fa-list"></i>
                                    <span>Movimientos</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="glass-card-body">
                    <div class="table-responsive">
                        <table class="table glass-table table-hover" id="tabla-medicamentos">
                            <thead>
                                <tr>
                                    <th width="8%">
                                        <i class="fas fa-hashtag"></i> ID
                                    </th>
                                    <th width="32%">
                                        <i class="fas fa-capsules"></i> Nombre
                                    </th>
                                    <th width="25%">
                                        <i class="fas fa-info-circle"></i> Descripción
                                    </th>
                                    <th width="12%" class="text-center">
                                        <i class="fas fa-warehouse"></i> Saldo Actual
                                    </th>
                                    <th width="10%" class="text-center">
                                        <i class="fas fa-toggle-on"></i> Estado
                                    </th>
                                    <th width="13%" class="text-center">
                                        <i class="fas fa-cogs"></i> Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($medicamentos as $medicamento)
                                <tr data-id="{{$medicamento->id}}">
                                    <td><strong>{{$medicamento->id}}</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-prescription-bottle text-primary mr-2"></i>
                                            <strong>{{$medicamento->nombre}}</strong>
                                        </div>
                                    </td>
                                    <td>{{$medicamento->descripcion ?? 'N/A'}}</td>
                                    <td class="text-center">
                                        @if($medicamento->saldo_actual > 50)
                                            <span class="glass-badge glass-badge-success">
                                                <i class="fas fa-box-open"></i> {{$medicamento->saldo_actual}}
                                            </span>
                                        @elseif($medicamento->saldo_actual > 20)
                                            <span class="glass-badge glass-badge-warning">
                                                <i class="fas fa-box-open"></i> {{$medicamento->saldo_actual}}
                                            </span>
                                        @else
                                            <span class="glass-badge glass-badge-danger">
                                                <i class="fas fa-exclamation-triangle"></i> {{$medicamento->saldo_actual}}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($medicamento->activo)
                                            <span class="glass-badge glass-badge-success">
                                                <i class="fas fa-check-circle"></i> Activo
                                            </span>
                                        @else
                                            <span class="glass-badge glass-badge-secondary">
                                                <i class="fas fa-times-circle"></i> Inactivo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group-ios">
                                            <a href="{{route('editar_medicamento_controlado', ['id' => $medicamento->id])}}"
                                               class="btn-ios btn-ios-warning"
                                               title="Editar"
                                               data-toggle="tooltip">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button"
                                                    class="btn-ios btn-ios-danger btn-eliminar"
                                                    data-id="{{$medicamento->id}}"
                                                    title="Eliminar"
                                                    data-toggle="tooltip">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
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
</div>
@endsection
