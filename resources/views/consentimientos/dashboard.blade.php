@extends("theme.$theme.layout")

@section('titulo')
    Dashboard de Consentimientos Informados
@endsection

@section("styles")
<style>
    .stats-card {
        transition: transform 0.2s;
    }
    .stats-card:hover {
        transform: translateY(-5px);
    }
    .chart-container {
        position: relative;
        height: 300px;
    }
</style>
@endsection

@section('contenido')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">
                        <i class="fas fa-chart-line"></i> Dashboard de Consentimientos
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
                        <li class="breadcrumb-item active">Dashboard CI</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            <!-- Tarjetas de Estadísticas -->
            <div class="row">
                <!-- Total Consentimientos -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info stats-card">
                        <div class="inner">
                            <h3>{{$totalConsentimientos}}</h3>
                            <p>Total Consentimientos</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file-signature"></i>
                        </div>
                        <a href="{{route('consentimientos.index')}}" class="small-box-footer">
                            Ver todos <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Pendientes -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning stats-card">
                        <div class="inner">
                            <h3>{{$pendientes}}</h3>
                            <p>Pendientes de Firma</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <a href="{{route('consentimientos.index', ['estado' => 'pendiente'])}}" class="small-box-footer">
                            Ver detalles <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Firmados -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success stats-card">
                        <div class="inner">
                            <h3>{{$firmados}}</h3>
                            <p>Completados</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <a href="{{route('consentimientos.index', ['estado' => 'firmado'])}}" class="small-box-footer">
                            Ver detalles <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Este Mes -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary stats-card">
                        <div class="inner">
                            <h3>{{$consentimientosMes}}</h3>
                            <p>Este Mes</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <a href="{{route('consentimientos.create')}}" class="small-box-footer">
                            Crear nuevo <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Fila de métricas adicionales -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fas fa-hourglass-half"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">En Proceso</span>
                            <span class="info-box-number">{{$enProceso}}</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger"><i class="fas fa-times-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Cancelados</span>
                            <span class="info-box-number">{{$cancelados}}</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-percentage"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Tasa Completado</span>
                            <span class="info-box-number">{{$tasaFinalizacion}}%</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fas fa-chart-pie"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Promedio Diario</span>
                            <span class="info-box-number">
                                {{$consentimientosMes > 0 ? round($consentimientosMes / date('d'), 1) : 0}}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficos y Tablas -->
            <div class="row">
                <!-- Top 5 Especialidades -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">
                                <i class="fas fa-stethoscope"></i> Top 5 Especialidades
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Especialidad</th>
                                            <th class="text-right">Cantidad</th>
                                            <th style="width: 100px;">Porcentaje</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($porEspecialidad as $item)
                                        <tr>
                                            <td>{{$item->especialidad->nombre ?? 'Sin especialidad'}}</td>
                                            <td class="text-right">{{$item->total}}</td>
                                            <td>
                                                <div class="progress progress-xs">
                                                    <div class="progress-bar bg-primary" style="width: {{$totalConsentimientos > 0 ? ($item->total / $totalConsentimientos * 100) : 0}}%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">No hay datos disponibles</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top 5 Profesionales -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">
                                <i class="fas fa-user-md"></i> Top 5 Profesionales
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Profesional</th>
                                            <th class="text-right">Cantidad</th>
                                            <th style="width: 100px;">Porcentaje</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($porProfesional as $item)
                                        <tr>
                                            <td>{{$item->profesional_nombre}}</td>
                                            <td class="text-right">{{$item->total}}</td>
                                            <td>
                                                <div class="progress progress-xs">
                                                    <div class="progress-bar bg-success" style="width: {{$totalConsentimientos > 0 ? ($item->total / $totalConsentimientos * 100) : 0}}%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">No hay datos disponibles</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tendencia por Mes -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line"></i> Tendencia (Últimos 6 Meses)
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Mes</th>
                                            <th class="text-right">Consentimientos</th>
                                            <th style="width: 300px;">Gráfico</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $maxTotal = $porMes->max('total') ?? 1;
                                        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                                        @endphp
                                        @forelse($porMes as $item)
                                        <tr>
                                            <td>{{$meses[$item->mes]}} {{$item->anio}}</td>
                                            <td class="text-right"><strong>{{$item->total}}</strong></td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar bg-info" role="progressbar"
                                                         style="width: {{($item->total / $maxTotal * 100)}}%"
                                                         aria-valuenow="{{$item->total}}"
                                                         aria-valuemin="0"
                                                         aria-valuemax="{{$maxTotal}}">
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">No hay datos disponibles</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Últimos Consentimientos Creados -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-history"></i> Últimos 10 Consentimientos Creados
                            </h3>
                            <div class="card-tools">
                                <a href="{{route('consentimientos.index')}}" class="btn btn-sm btn-primary">
                                    Ver todos <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Paciente</th>
                                            <th>Procedimiento</th>
                                            <th>Profesional</th>
                                            <th>Fecha</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($ultimosConsentimientos as $c)
                                        <tr>
                                            <td>{{$c->id}}</td>
                                            <td>
                                                <small>{{$c->paciente_nombre}}</small><br>
                                                <small class="text-muted">{{$c->paciente_tipo_doc}}-{{$c->paciente_cedula}}</small>
                                            </td>
                                            <td><small>{{$c->plantilla->nombre ?? 'N/A'}}</small></td>
                                            <td><small>{{$c->profesional_nombre}}</small></td>
                                            <td><small>{{\Carbon\Carbon::parse($c->created_at)->format('d/m/Y H:i')}}</small></td>
                                            <td>
                                                @if($c->estado == 'pendiente')
                                                    <span class="badge badge-warning">
                                                        <i class="fas fa-clock"></i> Pendiente
                                                    </span>
                                                @elseif($c->estado == 'en_proceso')
                                                    <span class="badge badge-info">
                                                        <i class="fas fa-hourglass-half"></i> En Proceso
                                                    </span>
                                                @elseif($c->estado == 'firmado')
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check"></i> Firmado
                                                    </span>
                                                @else
                                                    <span class="badge badge-danger">
                                                        <i class="fas fa-times"></i> Cancelado
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{route('consentimientos.show', $c->id)}}"
                                                   class="btn btn-xs btn-info"
                                                   title="Ver detalles">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">No hay consentimientos recientes</td>
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
    </section>
</div>
@endsection
