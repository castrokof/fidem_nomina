@extends("theme.$theme.layout")

@section('titulo')
    Detalle del Consentimiento
@endsection

@section("styles")
<style>
    .firma-container {
        border: 2px solid #007bff;
        border-radius: 10px;
        padding: 10px;
        background-color: #f8f9fa;
        text-align: center;
        margin-top: 10px;
    }
    .firma-img {
        max-width: 100%;
        height: auto;
        border: 1px solid #dee2e6;
        background-color: white;
    }
    .info-section {
        background-color: #f8f9fa;
        padding: 15px;
        border-left: 4px solid #007bff;
        margin-bottom: 20px;
    }
</style>
@endsection

@section('contenido')
<div class="content-wrapper" style="min-height: 543px;">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><i class="fas fa-file-signature"></i> Detalle del Consentimiento</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{route('consentimientos.index')}}">Consentimientos</a></li>
                        <li class="breadcrumb-item active">Detalle</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Consentimiento Informado #{{$consentimiento->id}}</h3>
                    <div class="card-tools">
                        @if($consentimiento->estado == 'firmado')
                            <a href="{{route('consentimientos.pdf', $consentimiento->id)}}" class="btn btn-danger btn-sm" target="_blank">
                                <i class="fas fa-file-pdf"></i> Descargar PDF
                            </a>
                        @endif
                        <a href="{{route('consentimientos.index')}}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Estado -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <h5>Estado del Consentimiento:
                                @if($consentimiento->estado == 'pendiente')
                                    <span class="badge badge-warning badge-lg">
                                        <i class="fas fa-clock"></i> Pendiente de Firma
                                    </span>
                                @elseif($consentimiento->estado == 'firmado')
                                    <span class="badge badge-success badge-lg">
                                        <i class="fas fa-check"></i> Firmado
                                    </span>
                                @elseif($consentimiento->estado == 'anulado')
                                    <span class="badge badge-danger badge-lg">
                                        <i class="fas fa-times"></i> Anulado
                                    </span>
                                @endif
                            </h5>
                        </div>
                    </div>

                    <!-- Información del Paciente -->
                    <div class="info-section">
                        <h5><i class="fas fa-user"></i> Información del Paciente</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Nombre Completo:</strong> {{$consentimiento->paciente->nombres}} {{$consentimiento->paciente->apellidos}}</p>
                                <p><strong>Documento:</strong> {{$consentimiento->paciente->tipo_documento}}-{{$consentimiento->paciente->numero_documento}}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Historia Clínica:</strong> {{$consentimiento->paciente->historia_clinica ?? 'N/A'}}</p>
                                <p><strong>Teléfono:</strong> {{$consentimiento->paciente->telefono ?? 'N/A'}}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Información del Procedimiento -->
                    <div class="info-section">
                        <h5><i class="fas fa-notes-medical"></i> Información del Procedimiento</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Procedimiento:</strong> {{$consentimiento->plantilla->nombre}}</p>
                                <p><strong>Fecha de la Cita:</strong> {{\Carbon\Carbon::parse($consentimiento->fecha_cita)->format('d/m/Y H:i')}}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Profesional:</strong> {{$consentimiento->profesional->nombres}} {{$consentimiento->profesional->apellidos}}</p>
                                <p><strong>Especialidad:</strong> {{$consentimiento->profesional->especialidad->nombre ?? 'N/A'}}</p>
                            </div>
                        </div>
                        @if($consentimiento->observaciones)
                            <p><strong>Observaciones:</strong> {{$consentimiento->observaciones}}</p>
                        @endif
                    </div>

                    <!-- Contenido del Consentimiento -->
                    <div class="info-section">
                        <h5><i class="fas fa-file-alt"></i> Contenido del Consentimiento</h5>
                        <div style="max-height: 400px; overflow-y: auto; padding: 15px; background-color: white; border: 1px solid #dee2e6;">
                            {!! nl2br(e($consentimiento->plantilla->contenido)) !!}
                        </div>
                    </div>

                    @if($consentimiento->estado == 'firmado')
                        <!-- Firmas -->
                        <div class="row">
                            <!-- Firma del Paciente -->
                            @foreach($consentimiento->firmas as $firma)
                                @if($firma->tipo_firma == 'paciente')
                                    <div class="col-md-4">
                                        <h6><i class="fas fa-signature"></i> Firma del Paciente</h6>
                                        <div class="firma-container">
                                            <img src="{{$firma->firma_base64}}" alt="Firma del Paciente" class="firma-img">
                                            <p class="mt-2 mb-0"><small>Firmado el: {{\Carbon\Carbon::parse($firma->fecha_firma)->format('d/m/Y H:i')}}</small></p>
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            <!-- Firma del Acudiente (si existe) -->
                            @foreach($consentimiento->firmas as $firma)
                                @if($firma->tipo_firma == 'acudiente')
                                    <div class="col-md-4">
                                        <h6><i class="fas fa-user-friends"></i> Firma del Acudiente</h6>
                                        <div class="firma-container">
                                            <img src="{{$firma->firma_base64}}" alt="Firma del Acudiente" class="firma-img">
                                            <p class="mt-2 mb-0"><small>{{$firma->nombres}} {{$firma->apellidos}}</small></p>
                                            <p class="mb-0"><small>{{$firma->tipo_documento}}-{{$firma->numero_documento}}</small></p>
                                            <p class="mb-0"><small>Firmado el: {{\Carbon\Carbon::parse($firma->fecha_firma)->format('d/m/Y H:i')}}</small></p>
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            <!-- Firma del Profesional -->
                            @foreach($consentimiento->firmas as $firma)
                                @if($firma->tipo_firma == 'profesional')
                                    <div class="col-md-4">
                                        <h6><i class="fas fa-user-md"></i> Firma del Profesional</h6>
                                        <div class="firma-container">
                                            <img src="{{$firma->firma_base64}}" alt="Firma del Profesional" class="firma-img">
                                            <p class="mt-2 mb-0"><small>{{$firma->nombres}} {{$firma->apellidos}}</small></p>
                                            <p class="mb-0"><small>RM: {{$consentimiento->profesional->registro_medico}}</small></p>
                                            <p class="mb-0"><small>Firmado el: {{\Carbon\Carbon::parse($firma->fecha_firma)->format('d/m/Y H:i')}}</small></p>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <!-- Información del Acudiente (si existe) -->
                        @if($consentimiento->acudientes->count() > 0)
                            <div class="info-section mt-3">
                                <h5><i class="fas fa-user-friends"></i> Información del Acudiente</h5>
                                @foreach($consentimiento->acudientes as $acudiente)
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Nombre:</strong> {{$acudiente->nombres}} {{$acudiente->apellidos}}</p>
                                            <p><strong>Documento:</strong> {{$acudiente->tipo_documento}}-{{$acudiente->numero_documento}}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Parentesco:</strong> {{$acudiente->parentesco}}</p>
                                            <p><strong>Teléfono:</strong> {{$acudiente->telefono ?? 'N/A'}}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @elseif($consentimiento->estado == 'pendiente')
                        <!-- Enlace de firma -->
                        <div class="alert alert-info mt-3">
                            <h5><i class="fas fa-link"></i> Enlace para Firma</h5>
                            <p>Comparte el siguiente enlace con el paciente para que pueda firmar el consentimiento:</p>
                            <div class="input-group">
                                <input type="text" class="form-control" id="enlaceFirma" value="{{route('consentimientos.firmar', $consentimiento->token_firma)}}" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" onclick="copiarEnlace()">
                                        <i class="fas fa-copy"></i> Copiar
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    function copiarEnlace() {
        var input = document.getElementById('enlaceFirma');
        input.select();
        input.setSelectionRange(0, 99999);
        document.execCommand('copy');
        alert('Enlace copiado al portapapeles');
    }
</script>
@endsection
