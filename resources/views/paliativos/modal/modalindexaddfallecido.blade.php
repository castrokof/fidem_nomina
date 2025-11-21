 <!-- Modal del resumen -->

 <div class="modal fade" tabindex="-1" id ="modal-addfallecidopaliativos"  role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-xl" role="document">

          <div class="modal-content bg-lite">
             <div class="modal-header">
                <h5 class="modal-title-addfallecidopaliativos" id="myLargeModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>

            </div>
        <div class="modal-body">

        <form id="form-generaladd" name="form-general" class="form-horizontal">
                @csrf
                @include('paliativos.form.formAnalistaAddfallecido')
        </form>
        </div>


        </div>
    </div>
</div>
