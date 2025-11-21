 <!-- Modal del resumen -->

 <div class="modal fade" tabindex="-1" id ="modal-addseguimientopro"  role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-xl" role="document">

          <div class="modal-content bg-lite">
             <div class="modal-header">
                <h5 class="modal-title-addseguimientopro" id="myLargeModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>

            </div>
        <div class="modal-body">

        <form id="form-generaladdpro" name="form-general" class="form-horizontal">
                @csrf
                @include('fisiatria.form.formAnalistaAddSeguimientoProfesional')
        </form>
        </div>


        </div>
    </div>
</div>
