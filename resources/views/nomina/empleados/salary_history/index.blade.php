@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-money-bill-wave"></i>
                        Historial de Salarios - {{ $empleado->pnombre }} {{ $empleado->papellido }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('empleados.salary-history.create', $empleado->id) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Nuevo Registro de Salario
                        </a>
                        <a href="{{ route('empleados.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Información del empleado -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-user"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Empleado</span>
                                    <span class="info-box-number">{{ $empleado->pnombre }} {{ $empleado->snombre }} {{ $empleado->papellido }} {{ $empleado->sapellido }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-dollar-sign"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Salario Fijo Actual</span>
                                    <span class="info-box-number">${{ $empleado->salary ? number_format($empleado->salary, 0, ',', '.') : '0' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-dollar-sign"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Salario PS Actual</span>
                                    <span class="info-box-number">${{ $empleado->salary_ps ? number_format($empleado->salary_ps, 0, ',', '.') : '0' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de historial -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="historialTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="5%">Estado</th>
                                    <th width="15%">Fecha Inicio</th>
                                    <th width="15%">Fecha Fin</th>
                                    <th width="15%">Salario Fijo</th>
                                    <th width="15%">Salario PS</th>
                                    <th width="20%">Motivo</th>
                                    <th width="10%">Registrado Por</th>
                                    <th width="5%">Fecha Registro</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($empleado->salaryHistory as $registro)
                                    <tr class="{{ $registro->activo ? 'table-success' : '' }}">
                                        <td class="text-center">
                                            @if($registro->activo)
                                                <span class="badge badge-success">Actual</span>
                                            @else
                                                <span class="badge badge-secondary">Histórico</span>
                                            @endif
                                        </td>
                                        <td>{{ $registro->fecha_inicio->format('d/m/Y') }}</td>
                                        <td>{{ $registro->fecha_fin ? $registro->fecha_fin->format('d/m/Y') : '-' }}</td>
                                        <td class="text-right">
                                            @if($registro->salary)
                                                ${{ number_format($registro->salary, 0, ',', '.') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if($registro->salary_ps)
                                                ${{ number_format($registro->salary_ps, 0, ',', '.') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $registro->motivo ?? '-' }}</td>
                                        <td>{{ $registro->createdBy->usuario ?? '-' }}</td>
                                        <td>{{ $registro->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <div class="alert alert-info mb-0">
                                                <i class="fas fa-info-circle"></i>
                                                No hay registros de historial de salarios para este empleado.
                                                <br>
                                                <a href="{{ route('empleados.salary-history.create', $empleado->id) }}" class="btn btn-sm btn-primary mt-2">
                                                    <i class="fas fa-plus"></i> Crear Primer Registro
                                                </a>
                                            </div>
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

@section('scripts')
<script>
    $(document).ready(function() {
        $('#historialTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            },
            "order": [[1, "desc"]],
            "pageLength": 25,
            "responsive": true
        });
    });
</script>
@endsection
