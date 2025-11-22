@extends("theme.$theme.layout")
@section('titulo')
Medicamentos Controlados
@endsection

@section('styles')
<link href="{{asset('assets/css/custom/medicamentos-glassmorphism.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/$theme/plugins/datatables-bs4/css/dataTables.bootstrap4.css")}}" rel="stylesheet" type="text/css"/>
<style>
    .content-wrapper {
        background: linear-gradient(-45deg, #4facfe, #00f2fe, #5ba3d0, #2193b0, #6dd5ed);
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
    }
</style>
@endsection

@section('scripts')
<script src="{{asset("assets/$theme/plugins/datatables/jquery.dataTables.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/$theme/plugins/datatables-bs4/js/dataTables.bootstrap4.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/$theme/plugins/datatables-responsive/js/dataTables.responsive.min.js")}}" type="text/javascript"></script>
<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
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
                <div class="value" id="total-medicamentos">{{ $stats['total'] }}</div>
                <div class="label">Total Medicamentos</div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="stats-card">
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="value" id="medicamentos-activos">{{ $stats['activos'] }}</div>
                <div class="label">Medicamentos Activos</div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="stats-card">
                <div class="icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="value" id="total-stock">{{ $stats['stock_total'] }}</div>
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
                                <button type="button" class="btn-ios" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);" data-toggle="modal" data-target="#modal-crear-medicamento">
                                    <i class="fas fa-plus-circle"></i>
                                    <span>Nuevo</span>
                                </button>
                                <button type="button" class="btn-ios" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);" data-toggle="modal" data-target="#modal-entrada">
                                    <i class="fas fa-arrow-down"></i>
                                    <span>Entrada</span>
                                </button>
                                <button type="button" class="btn-ios" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);" data-toggle="modal" data-target="#modal-salida">
                                    <i class="fas fa-arrow-up"></i>
                                    <span>Salida</span>
                                </button>
                                <a href="{{route('medicamento_controlado_movimiento')}}" class="btn-ios" style="background: linear-gradient(135deg, #5ba3d0 0%, #2193b0 100%);">
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
                                <!-- DataTables cargará los datos dinámicamente via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.medicamento_controlado.modal.modalCrear')
@include('admin.medicamento_controlado.modal.modalEditar')
@include('admin.medicamento_controlado.modal.modalEntrada')
@include('admin.medicamento_controlado.modal.modalSalida')

@endsection
