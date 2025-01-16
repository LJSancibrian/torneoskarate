<?php if ($this->user->group->id > 3) { ?>
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-pills nav-secondary nav-pills-no-bd" id="pills-tab-without-border" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-sorteos-tab" href="<?php echo base_url(); ?>Torneos/competiciones/<?php echo $torneo->slug; ?>" role="tab">Competiciones</a>
                </li>
            </ul>
        </div>
    </div>
<?php } ?>


<div class="card">
    <div class="card-header d-flex justify-content-between">
        <div class="card-title fw-mediumbold">Tablero competición</div>
        <button class="btn btn-icon btn-primary btn-round btn-sm" data-ver-clasificacion data-toggle="tooltip" title="Ver clasificacion">
            <i class="fas fa-list"></i>
        </button>

        <a href="<?php echo base_url(); ?>Competiciones/pdfdoc/<?php echo $competicion->competicion_torneo_id; ?>" target="_blankl" class="btn btn-icon btn-primary btn-round btn-xs" title="Guardar imagen tablero" target="_blank">
            <i class="fas fa-file-pdf"></i>
        </a>
    </div>

    <div id="faseeliminatoria" competicion_torneo_id="<?php echo $competicion->competicion_torneo_id; ?>">
        <div class="brackets"></div>
        <div class="row text-center flex-nowrap" style=" overflow-x: auto;white-space: nowrap">
            <?php foreach ($eliminatorias as $key => $eliminatoria) {
                if ($key  == count($eliminatorias)) {
                    $ronda = 'FINAL';
                } elseif ($key  == count($eliminatorias) - 1) {
                    $ronda = 'SEMI - FINAL';
                } else {
                    $ronda = 'Ronda ' . $key;
                } ?>
                <div class="col-md-4 col-lg-3 round">
                    <h4 class="bg-primary text-white p-2 mb-3"><?php echo $ronda; ?></h4>
                    <div>
                        <?php foreach ($eliminatoria as $match) { ?>
                            <ul class="list-group p-0 match btn btn-link" data-match_id="<?php echo $match->match_id; ?>">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-2 py-1" style="background: red;" data-user="<?php echo $match->user_rojo; ?>">
                                    <span class="text-white text-truncate text-left" style="width:calc(100% - 30px);"><?php echo (isset($match->rojo)) ? $match->rojo->nombre : ''; ?></span>
                                    <span class="bg-white <?php echo ($match->hantei == 'rojo') ? 'hantei' : ''; ?> <?php echo ($match->senshu == 'rojo') ? 'senshu' : ''; ?>" style="width:25px;"><?php echo (isset($match->rojo)) ? $match->puntos_rojo : 0; ?></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-2 py-1" style="background: blue;" data-user="<?php echo $match->user_azul; ?>">
                                    <span class="text-white text-truncate text-left"><?php echo (isset($match->azul)) ? $match->azul->nombre : ''; ?></span>
                                    <span class="bg-white <?php echo ($match->hantei == 'azul') ? 'hantei' : ''; ?> <?php echo ($match->senshu == 'azul') ? 'senshu' : ''; ?>" style="width:25px;"><?php echo (isset($match->azul)) ? $match->puntos_azul : 0; ?></span>
                                </li>
                            </ul>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="modal fade" id="clasificaciongrupo" tabindex="-1" role="dialog" aria-labelledby="clasificaciongrupoLabel">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Clasificación Grupo <span id="clasificacionGrupoModal"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered text-center w-auto" id="clasificacionModalTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="text-left">Deportista</th>
                                    <th class="text-left">Equipo</th>
                                    <th class="text-left">Victorias</th>
                                    <th class="text-left">Puntos favor</th>
                                    <th class="text-left">Puntos contra</th>
                                    <th class="text-left">Senshu</th>
                                    <th class="text-left">Hantei</th>
                                </tr>
                            </thead>
                            <tbody data-competicion_torneo_id="" data-grupo="">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-header d-flex justify-content-between">
        <div class="card-title fw-mediumbold w-100"><a data-finalizar-competicion href="<?php echo base_url(); ?>Competiciones/FinalizarCompeticion/<?php echo $competicion->competicion_torneo_id; ?>" class="btn btn-primary text-white rounded">Finalizar competición</a></div>
    </div>
    <?php $this->load->view('gestion/competiciones/marcadorauxiliar'); ?>


</div>

<?php echo form_open();
echo form_close(); ?>