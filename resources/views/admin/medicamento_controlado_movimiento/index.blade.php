@extends("theme.$theme.layout")
@section('titulo')
Movimientos de Medicamentos Controlados
@endsection

@section('styles')
<link href="{{asset('assets/css/custom/medicamentos-glassmorphism.css')}}" rel="stylesheet" type="text/css"/>
<style>
    .content-wrapper {
        background: linear-gradient(-45deg, #667eea, #764ba2, #f093fb, #f5576c);
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
    }
</style>
@endsection

@section('scripts')
<script src="{{asset("assets/pages/scripts/admin/medicamento_controlado_movimiento/index.js")}}" type="text/javascript"></script>
@endsection

@section('contenido')
<div class="medicamentos-wrapper">
    <div class="row">
        <div class="col-lg-12">
            @include('includes.form-error')
            @include('includes.form-mensaje')

            <!-- Filtros -->
            <div class="filter-glass animate-in mb-3">
                <div class="card-header" style="background: rgba(255, 255, 255, 0.1); border-radius: 15px 15px 0 0; padding: 15px 20px; border: none;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" style="color: white; font-weight: 600;">
                            <i class="fas fa-filter mr-2"></i>Filtros de Búsqueda
                        </h5>
                        <button type="button" class="btn btn-sm glass-btn glass-btn-info" data-toggle="collapse" data-target="#filtros-collapse">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                </div>
                <div class="collapse" id="filtros-collapse">
                    <div style="padding: 20px;">
                        <form action="{{route('medicamento_controlado_movimiento')}}" method="GET">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group-glass">
                                        <label><i class="fas fa-pills mr-2"></i>Medicamento</label>
                                        <select name="medicamento_id" class="form-control glass-select">
                                            <option value="">Todos los medicamentos</option>
                                            @foreach($medicamentos as $med)
                                                <option value="{{$med->id}}" {{$medicamento_id == $med->id ? 'selected' : ''}}>
                                                    {{$med->nombre}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group-glass">
                                        <label><i class="fas fa-calendar-alt mr-2"></i>Fecha Desde</label>
                                        <input type="date" name="fecha_desde" class="form-control glass-input" value="{{$fecha_desde}}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group-glass">
                                        <label><i class="fas fa-calendar-check mr-2"></i>Fecha Hasta</label>
                                        <input type="date" name="fecha_hasta" class="form-control glass-input" value="{{$fecha_hasta}}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group-glass">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn glass-btn glass-btn-primary btn-block">
                                            <i class="fas fa-search mr-2"></i>Filtrar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Listado de movimientos -->
            <div class="glass-card animate-in">
                <div class="glass-card-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h3><i class="fas fa-exchange-alt mr-2"></i> Historial de Movimientos</h3>
                        <div class="mt-2 mt-md-0">
                            <a href="{{route('crear_entrada_medicamento_controlado')}}" class="glass-btn glass-btn-success">
                                <i class="fas fa-plus-circle"></i> Nueva Entrada
                            </a>
                            <a href="{{route('crear_salida_medicamento_controlado')}}" class="glass-btn glass-btn-danger ml-2">
                                <i class="fas fa-minus-circle"></i> Nueva Salida
                            </a>
                            <a href="{{route('medicamento_controlado')}}" class="glass-btn glass-btn-info ml-2">
                                <i class="fas fa-pills"></i> Medicamentos
                            </a>
                        </div>
                    </div>
                </div>
                <div class="glass-card-body">
                    <div class="table-responsive">
                        <table class="table glass-table table-hover table-sm" id="tabla-movimientos">
                            <thead>
                                <tr>
                                    <th width="8%"><i class="fas fa-calendar"></i> Fecha</th>
                                    <th width="18%"><i class="fas fa-capsules"></i> Medicamento</th>
                                    <th width="8%" class="text-center"><i class="fas fa-exchange-alt"></i> Tipo</th>
                                    <th width="15%"><i class="fas fa-truck"></i> Proveedor</th>
                                    <th width="15%"><i class="fas fa-user-injured"></i> Paciente</th>
                                    <th width="8%" class="text-center"><i class="fas fa-plus"></i> Entrada</th>
                                    <th width="8%" class="text-center"><i class="fas fa-minus"></i> Salida</th>
                                    <th width="8%" class="text-center"><i class="fas fa-warehouse"></i> Saldo</th>
                                    <th width="12%" class="text-center"><i class="fas fa-info-circle"></i> Detalles</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($movimientos as $mov)
                                <tr>
                                    <td><strong>{{date('d/m/Y', strtotime($mov->fecha))}}</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-prescription-bottle text-primary mr-2"></i>
                                            {{$mov->medicamentoControlado->nombre}}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($mov->tipo_movimiento == 'entrada')
                                            <span class="glass-badge glass-badge-success">
                                                <i class="fas fa-arrow-down"></i> ENTRADA
                                            </span>
                                        @else
                                            <span class="glass-badge glass-badge-danger">
                                                <i class="fas fa-arrow-up"></i> SALIDA
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{$mov->proveedor ?? '-'}}</td>
                                    <td>
                                        @if($mov->nombre_paciente)
                                            <strong>{{$mov->nombre_paciente}}</strong><br>
                                            <small class="text-muted"><i class="fas fa-id-card mr-1"></i>{{$mov->cedula_paciente}}</small>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($mov->entrada > 0)
                                            <span class="glass-badge glass-badge-success">+{{$mov->entrada}}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($mov->salida > 0)
                                            <span class="glass-badge glass-badge-danger">-{{$mov->salida}}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <strong style="font-size: 1.1rem;">{{$mov->saldo}}</strong>
                                    </td>
                                    <td class="text-center">
                                        @if($mov->foto_formula)
                                            <a href="{{asset('storage/' . $mov->foto_formula)}}" target="_blank" class="btn btn-info btn-sm" title="Ver foto fórmula" data-toggle="tooltip">
                                                <i class="fas fa-image"></i>
                                            </a>
                                        @endif
                                        @if($mov->numero_factura)
                                            <span class="badge badge-info" title="Factura: {{$mov->numero_factura}}" data-toggle="tooltip">
                                                <i class="fas fa-file-invoice"></i> {{$mov->numero_factura}}
                                            </span>
                                        @endif
                                        @if($mov->numero_formula_control)
                                            <span class="badge badge-warning" title="Fórmula Control: {{$mov->numero_formula_control}}" data-toggle="tooltip">
                                                <i class="fas fa-file-prescription"></i> {{$mov->numero_formula_control}}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center" style="padding: 40px;">
                                        <i class="fas fa-inbox" style="font-size: 3rem; color: rgba(0, 0, 0, 0.2);"></i>
                                        <p class="mt-3" style="color: rgba(0, 0, 0, 0.5);">No hay movimientos registrados</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
