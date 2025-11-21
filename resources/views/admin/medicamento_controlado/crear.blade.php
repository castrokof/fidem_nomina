@extends("theme.$theme.layout")
@section('titulo')
Crear Medicamento Controlado
@endsection

@section('styles')
<link href="{{asset('assets/css/custom/medicamentos-glassmorphism.css')}}" rel="stylesheet" type="text/css"/>
<style>
    .content-wrapper {
        background: linear-gradient(-45deg, #0fd850, #f9d423, #23a6d5, #23d5ab);
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
    }
</style>
@endsection

@section('scripts')
<script>
    var guardarMedicamentoUrl = "{{route('guardar_medicamento_controlado')}}";
</script>
<script src="{{asset("assets/pages/scripts/admin/medicamento_controlado/crear.js")}}" type="text/javascript"></script>
@endsection

@section('contenido')
<div class="medicamentos-wrapper">
    <div class="row">
        <div class="col-lg-12">
            @include('includes.form-error')

            <div class="glass-card animate-in">
                <div class="glass-card-header" style="background: var(--success-gradient);">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h3><i class="fas fa-plus-circle mr-2"></i> Crear Medicamento Controlado</h3>
                        <a href="{{route('medicamento_controlado')}}" class="glass-btn glass-btn-info mt-2 mt-md-0">
                            <i class="fas fa-list"></i> Listar Medicamentos
                        </a>
                    </div>
                </div>

                <div class="glass-card-body">
                    <form id="form-medicamento" method="POST">
                        @csrf
                        @include('admin.medicamento_controlado.form')

                        <div class="row mt-4">
                            <div class="col-12 text-center">
                                <button type="reset" class="glass-btn glass-btn-secondary mr-2">
                                    <i class="fas fa-eraser mr-2"></i>Limpiar
                                </button>
                                <button type="submit" class="glass-btn glass-btn-success" id="btn-guardar">
                                    <i class="fas fa-save mr-2"></i>Guardar Medicamento
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
