<div class="modal fade" tabindex="-1" id="{{ $modalId }}" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title ?? 'Subir archivo' }}</h3>
                            <div class="card-tools pull-right">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                        <form action="" id="{{$formId}}" name="Form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="loader">
                                <img src="{{ asset("assets/$theme/dist/img/loader6.gif") }}" class="" />
                            </div>
                            <div class="card-body">
                                @include($formInclude)
                            </div>
                            <div class="card-footer">
                                <div class="col-lg-3"></div>
                                <div class="col-lg-6">
                                    <button value="reset" id="reset" type="reset" class="btn btn-default">Limpiar</button>
                                    <button value="{{ $btnValue }}" id="{{ $btnId }}" type="button" class="btn btn-success">Guardar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>