@extends("theme.$theme.layout")

@section('titulo')
    Editar Especialidad Médica
@endsection

@section('contenido')
<div class="content-wrapper" style="min-height: 543px;">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><i class="fas fa-stethoscope"></i> Editar Especialidad</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{route('especialidades.index')}}">Especialidades</a></li>
                        <li class="breadcrumb-item active">Editar</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Editar Datos de la Especialidad</h3>
                </div>
                <form action="{{route('especialidades.update', $especialidad->id)}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="nombre">Nombre de la Especialidad <span class="text-danger">*</span></label>
                                    <input type="text" name="nombre" id="nombre" class="form-control" value="{{old('nombre', $especialidad->nombre)}}" required placeholder="Ej: Cardiología">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="descripcion">Descripción</label>
                                    <textarea name="descripcion" id="descripcion" class="form-control" rows="4" placeholder="Descripción opcional de la especialidad">{{old('descripcion', $especialidad->descripcion)}}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input type="checkbox" name="activo" id="activo" class="form-check-input" value="1" {{old('activo', $especialidad->activo) ? 'checked' : ''}}>
                                    <label class="form-check-label" for="activo">
                                        Especialidad Activa
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Actualizar Especialidad
                        </button>
                        <a href="{{route('especialidades.index')}}" class="btn btn-default">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>

            @if($especialidad->profesionales->count() > 0 || $especialidad->plantillas->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-info-circle"></i> Información Relacionada</h3>
                </div>
                <div class="card-body">
                    @if($especialidad->profesionales->count() > 0)
                    <div class="mb-3">
                        <h5>Profesionales asociados ({{$especialidad->profesionales->count()}})</h5>
                        <ul>
                            @foreach($especialidad->profesionales as $profesional)
                                <li>{{ $profesional->nombres }} {{ $profesional->apellidos }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if($especialidad->plantillas->count() > 0)
                    <div>
                        <h5>Plantillas CI asociadas ({{$especialidad->plantillas->count()}})</h5>
                        <ul>
                            @foreach($especialidad->plantillas as $plantilla)
                                <li>{{ $plantilla->nombre }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </section>
</div>
@endsection
