<style>
    .modal-fullscreen {
        width: 100vw !important;
        max-width: 100vw !important;
        height: 100vh !important;
        max-height: 100vh !important;
        margin: 0;
    }

    .modal-fullscreen .modal-content {
        height: 100vh !important;
        max-height: 100vh !important;
        display: flex;
        flex-direction: column;
    }

    .modal-fullscreen .modal-body-scroll {
        flex: 1 1 auto;
        overflow-y: auto;
    }

    .btn-expand {
        border: none;
        background: none;
        font-size: 1.2rem;
        cursor: pointer;
    }
</style>


<div class="modal fade" id="modal-seguimientos" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white" id="myLargeModalLabel">Historial de Seguimientos</h5>
         <div class="d-flex align-items-center gap-2">
                    <!-- Botón expandir -->
                    <button type="button" class="btn-expand" id="expandModal" title="Expandir o contraer ventana">
                        ⛶
                    </button>

                    <!-- Botón cerrar -->
                    <button type="button" class="close ml-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
      </div>
      <div class="modal-body modal-body-scroll">
        <div class="timeline" id="timeline-seguimientos">
          <!-- Aquí se insertan dinámicamente los seguimientos -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>



<script>
    document.getElementById('expandModal').addEventListener('click', function () {
        const dialog = document.querySelector('#modal-seguimientos .modal-dialog');
        const content = document.querySelector('#modal-seguimientos .modal-content');
        dialog.classList.toggle('modal-fullscreen');
    });
</script>



