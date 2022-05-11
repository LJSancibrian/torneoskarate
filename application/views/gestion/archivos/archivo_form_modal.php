<div class="modal fade" id="modal_crear_archivo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="bg-primary modal-header">
                <h3 class="modal-title"><span class="fw-mediumbold"></span></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
               <?php $this->load->view('gestion/archivos/archivo_form');?>
            </div>
        </div>
    </div>
</div>