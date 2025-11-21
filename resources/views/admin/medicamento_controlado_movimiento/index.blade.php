@extends("theme.$theme.layout")
@section('titulo')
Movimientos de Medicamentos Controlados
@endsection

@section('scripts')
<script src="{{asset("assets/pages/scripts/admin/medicamento_controlado_movimiento/index.js")}}" type="text/javascript"></script>
@endsection

@section('contenido')
<div class="row">
    <div class="col-lg-12">
        @include('includes.form-error')
        @include('includes.form-mensaje')

        <!-- Filtros -->
        <div class="card card-secondary collapsed-card">
            <div class="card-header">
                <h3 class="card-title">Filtros de Búsqueda</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form action="{{route('medicamento_controlado_movimiento')}}" method="GET">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Medicamento</label>
                                <select name="medicamento_id" class="form-control">
                                    <option value="">Todos</option>
                                    @foreach($medicamentos as $med)
                                        <option value="{{$med->id}}" {{$medicamento_id == $med->id ? 'selected' : ''}}>
                                            {{$med->nombre}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Fecha Desde</label>
                                <input type="date" name="fecha_desde" class="form-control" value="{{$fecha_desde}}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Fecha Hasta</label>
                                <input type="date" name="fecha_hasta" class="form-control" value="{{$fecha_hasta}}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i> Filtrar
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Listado de movimientos -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Movimientos de Medicamentos</h3>
                <div class="card-tools">
                    <a href="{{route('crear_entrada_medicamento_controlado')}}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus-circle"></i> Nueva Entrada
                    </a>
                    <a href="{{route('crear_salida_medicamento_controlado')}}" class="btn btn-danger btn-sm">
                        <i class="fas fa-minus-circle"></i> Nueva Salida
                    </a>
                    <a href="{{route('medicamento_controlado')}}" class="btn btn-info btn-sm">
                        <i class="fas fa-pills"></i> Medicamentos
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover table-sm" id="tabla-movimientos">
                        <thead>
                            <tr>
                                <th width="8%">Fecha</th>
                                <th width="18%">Medicamento</th>
                                <th width="8%" class="text-center">Tipo</th>
                                <th width="15%">Proveedor</th>
                                <th width="15%">Paciente</th>
                                <th width="8%" class="text-center">Entrada</th>
                                <th width="8%" class="text-center">Salida</th>
                                <th width="8%" class="text-center">Saldo</th>
                                <th width="12%" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($movimientos as $mov)
                            <tr>
                                <td>{{date('d/m/Y', strtotime($mov->fecha))}}</td>
                                <td>{{$mov->medicamentoControlado->nombre}}</td>
                                <td class="text-center">
                                    @if($mov->tipo_movimiento == 'entrada')
                                        <span class="badge badge-success">ENTRADA</span>
                                    @else
                                        <span class="badge badge-danger">SALIDA</span>
                                    @endif
                                </td>
                                <td>{{$mov->proveedor ?? '-'}}</td>
                                <td>
                                    @if($mov->nombre_paciente)
                                        {{$mov->nombre_paciente}}<br>
                                        <small class="text-muted">CC: {{$mov->cedula_paciente}}</small>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($mov->entrada > 0)
                                        <span class="badge badge-success">+{{$mov->entrada}}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($mov->salida > 0)
                                        <span class="badge badge-danger">-{{$mov->salida}}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">
                                    <strong>{{$mov->saldo}}</strong>
                                </td>
                                <td class="text-center">
                                    @if($mov->foto_formula)
                                        <a href="{{asset('storage/' . $mov->foto_formula)}}" target="_blank" class="btn btn-info btn-sm" title="Ver foto fórmula">
                                            <i class="fas fa-image"></i>
                                        </a>
                                    @endif
                                    @if($mov->numero_factura)
                                        <span class="badge badge-info" title="Factura">{{$mov->numero_factura}}</span>
                                    @endif
                                    @if($mov->numero_formula_control)
                                        <span class="badge badge-warning" title="Fórmula Control">{{$mov->numero_formula_control}}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">No hay movimientos registrados</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
