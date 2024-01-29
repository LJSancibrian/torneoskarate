<div class="card mt--3">
        <div class="card-body">
            <div class="table-responsive">
                <table id="table_competiciones" class="display table table-striped table-sm w-100"></table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_inscripciones" tabindex="-1" aria-labelledby="modal_inscripciones" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="card-title fw-mediumbold"></div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Participantes: <span id="totalinscritos"></span></p>
                    <table class="table table-striped" id="deportistas_competicion" style="width: 100% !important;">
                        <thead style="width: 100% !important;">
                            <tr>
                                <th>Deportista</th>
                                <th>Equipo</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php echo form_open();
    echo form_hidden('torneo_id', $torneo->torneo_id);
    echo form_close(); ?>
</div>