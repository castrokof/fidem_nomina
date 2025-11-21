@extends("theme.$theme.layout")
@section('titulo')
Editar Medicamento Controlado
@endsection

@section('styles')
<link href="{{asset('assets/css/custom/medicamentos-glassmorphism.css')}}" rel="stylesheet" type="text/css"/>
<style>
    .content-wrapper {
        background: linear-gradient(-45deg, #fa709a, #fee140, #30cfd0, #330867);
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
    }
</style>
@endsection

@section('scripts')
<script>
    var actualizarMedicamentoUrl = "{{route('actualizar_medicamento_controlado', ['id' => $data->id])}}";
</script>
<script src="{{asset("assets/pages/scripts/admin/medicamento_controlado/editar.js")}}" type="text/javascript"></script>
@endsection

@section('contenido')
<div class="medicamentos-wrapper">
    <div class="row">
        <div class="col-lg-12">
            @include('includes.form-error')

            <div class="glass-card animate-in">
                <div class="glass-card-header" style="background: var(--warning-gradient);">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h3><i class="fas fa-edit mr-2"></i> Editar Medicamento: {{$data->nombre}}</h3>
                        <a href="{{route('medicamento_controlado')}}" class="glass-btn glass-btn-info mt-2 mt-md-0">
                            <i class="fas fa-list"></i> Listar Medicamentos
                        </a>
                    </div>
                </div>

                <div class="glass-card-body">
                    <form id="form-medicamento" method="POST">
                        @csrf
                        @method('PUT')
                        @include('admin.medicamento_controlado.form')

                        <div class="row mt-4">
                            <div class="col-12 text-center">
                                <a href="{{route('medicamento_controlado')}}" class="glass-btn glass-btn-secondary mr-2">
                                    <i class="fas fa-times mr-2"></i>Cancelar
                                </a>
                                <button type="submit" class="glass-btn glass-btn-warning" id="btn-actualizar">
                                    <i class="fas fa-sync mr-2"></i>Actualizar Medicamento
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
