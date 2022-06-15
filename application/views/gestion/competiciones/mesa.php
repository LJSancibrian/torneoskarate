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
    <?php if ($competicion->tipo == 'puntos') { ?>
        <div class="card-body p-1 bg-white" id="tablero-competicion">
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-center" id="tablakata" data-competicion="<?php echo $competicion->competicion_torneo_id; ?>">
                    <thead>
                        <tr>
                            <th class="bg-white text-primary">#</th>
                            <th class="bg-white text-primary text-left columnfixed" colspan="2">Deportista</th>
                            <th class="bg-white text-primary" colspan="3">Ronda 1</th>
                            <th class="bg-white text-primary" colspan="3">Ronda 2</th>
                            <?php /*<th class="bg-white text-primary" colspan="4">Ronda 3</th>*/ ?>
                            <th class="bg-white text-primary">Total</th>
                            <th class="bg-white text-primary">Media</th>
                        </tr>
                        <tr>
                            <th class="">#</th>
                            <th colspan="2" class="text-left columnfixed">Deportista</th>
                            <th>J1</th>
                            <th>J2</th>
                            <?php /*<th>J3</th>
                            <th>J4</th>
                            <th>J5</th> */ ?>
                            <th>M1</th>
                            <th>J1</th>
                            <th>J2</th>
                            <?php /*<th>J3</th>
                            <th>J4</th>
                            <th>J5</th> */ ?>
                            <th>M2</th>
                            <?php /*<th>J1</th>
                            <th>J2</th>
                            <th>J3</th>
                            <th>J4</th>
                            <th>J5</th>
                            <th>M3</th> */ ?>
                            <th>Total</th>
                            <th>Media</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ordenparticipacion['ordenados'] as $key => $value) { ?>
                            <tr data-user_id="<?php echo $value->user_id; ?>">
                                <td class=""><?php echo $value->orden; ?></td>
                                <td colspan="2" class="text-left text-nowrap columnfixed"><button type="button" class="btn btn-default p-1 rounded btn-block" data-inscripcion="<?php echo $value->inscripcion_id; ?>"><?php echo $value->first_name; ?> <?php echo $value->last_name; ?></button></td>
                                <td data-ronda="1" data-j="1"></td>
                                <td data-ronda="1" data-j="2"></td>
                                <?php /*<td data-ronda="1" data-j="3"></td>
                                <td data-ronda="1" data-j="4"></td>
                                <td data-ronda="1" data-j="5"></td>*/ ?>
                                <td data-media="1" class="bg-success text-white">0</td>
                                <td data-ronda="2" data-j="1"></td>
                                <td data-ronda="2" data-j="2"></td>
                                <?php /*<td data-ronda="2" data-j="3"></td>
                                <td data-ronda="2" data-j="4"></td>
                                <td data-ronda="2" data-j="5"></td>*/ ?>
                                <td data-media="2" class="bg-success text-white">0</td>
                                <?php /* <td data-ronda="3" data-j="1"></td>
                                <td data-ronda="3" data-j="2"></td>
                                <td data-ronda="3" data-j="3"></td>
                                <td data-ronda="3" data-j="4"></td>
                                <td data-ronda="3" data-j="5"></td>
                                <th data-media="3" class="bg-success text-white">0</td>*/ ?>
                                <td data-total></td>
                                <td data-media-total class="bg-primary text-white">0</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-header d-flex justify-content-between">
            <div class="card-title fw-mediumbold">RONDA FINAL</div>
            <button class="btn btn-icon btn-primary btn-round btn-sm" ver-competicion="<?php echo $competicion->competicion_torneo_id; ?>" ver-ronda="3">
                <i class="fas fa-desktop"></i>
            </button>
        </div>

        <div class="card-body p-1 bg-white" id="tablero-competicion-2">
            <div class="d-flex flex-column flex-md-row">
                <div class="mb-3 mr-md-3">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered text-center" style="max-width:500px;" id="tablakatafinal" data-competicion="<?php echo $competicion->competicion_torneo_id; ?>">
                            <thead>
                                <tr>
                                    <th class="bg-white text-primary">#</th>
                                    <th class="bg-white text-primary text-left columnfixed" colspan="2">Deportista</th>
                                    <th class="bg-white text-primary" colspan="3">Ronda Final</th>
                                    <th class="bg-white text-primary">Total</th>
                                    <th class="bg-white text-primary">Media</th>
                                </tr>
                                <tr>
                                    <th class="">#</th>
                                    <th colspan="2" class="text-left columnfixed">Deportista</th>
                                    <th>J1</th>
                                    <th>J2</th>
                                    <th>J3</th>
                                    <th>Total</th>
                                    <th>Media</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($finalistas as $key => $value) { ?>
                                    <tr data-user_id="<?php echo $value->user_id; ?>">
                                        <td class=""><?php echo $value->orden; ?></td>
                                        <td colspan="2" class="text-left text-nowrap columnfixed"><button type="button" class="btn btn-default p-1 rounded btn-block" data-inscripcion="<?php echo $value->inscripcion_id; ?>"><?php echo $value->first_name; ?> <?php echo $value->last_name; ?></button></td>
                                        <td data-ronda="3" data-j="1"></td>
                                        <td data-ronda="3" data-j="2"></td>
                                        <td data-ronda="3" data-j="3"></td>
                                        <td data-total></td>
                                        <td data-media-total class="bg-primary text-white">0</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="">
                    <div class="table-responsive">
                        <table class="table table-striped w-100" id="clasificaionkatafinal">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="columnfixed">Deportista</th>
                                    <th>Equipo</th>
                                    <th>Puntos</th>
                                    <th>Media</th>
                                </tr>
                            </thead>
                            <tbody id="clasificacion_final_competicion"></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <div class="modal fade" id="clasificaciongrupo" tabindex="-1" role="dialog" aria-labelledby="clasificaciongrupoLabel">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Clasificación</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="num_final-addon">Indicar el número de finalistas</span>
                                </div>
                                <input type="number" class="form-control" id="num_final" aria-describedby="num_final-addon">
                                <div class="input-group-append">
                                    <button class="btn btn-secondary" type="button" id="guardar-finalistas">Guardar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive" style="overflow: auto;height: calc( 100vh - 250px);">
                            <table class="table table-striped w-100" id="clasificaicon">
                                <thead style="position: sticky; top: 0; z-index: 1;">
                                    <tr>
                                        <th>#</th>
                                        <th class="columnfixed">Deportista</th>
                                        <th>Equipo</th>
                                        <th>Puntos</th>
                                        <th>Media</th>
                                    </tr>
                                </thead>
                                <tbody id="clasificacion_competicion"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-header d-flex justify-content-between">
            <div class="card-title fw-mediumbold w-100"><a href="<?php echo base_url(); ?>Competiciones/FinalizarCompeticion/<?php echo $competicion->competicion_torneo_id; ?>" class="btn btn-primary text-white rounded">Finalizar competición</a></div>
        </div>
    <?php } ?>

    <?php if ($competicion->tipo == 'liguilla') { ?>
        <div class="card-body" id="tablero-competicion">

            <ul class="nav nav-pills nav-secondary nav-pills-no-bd mb-3" role="tablist">
                <li class="nav-item submenu">
                    <a class="nav-link <?php echo ($tipo == 'grupos') ? 'active show' : ''; ?>" href="<?php echo base_url(); ?>Competiciones/mesa/<?php echo $competicion->competicion_torneo_id; ?>/grupos">Fase de grupos</a>
                </li>
                <li class="nav-item submenu">
                    <a class="nav-link <?php echo ($tipo == 'eliminatorias') ? 'active show' : ''; ?>" href="<?php echo base_url(); ?>Competiciones/mesa/<?php echo $competicion->competicion_torneo_id; ?>/eliminatorias" role="tab">Eliminatorias</a>
                </li>
            </ul>
            <div id="content_print">
                <?php if ($tipo == 'grupos') {
                    foreach ($matches as $grupo) { ?>
                        <div class="card shadow-none border">
                            <div class="card-header d-flex justify-content-start flex-wrap">
                                <h4 class="card-title">Grupo <?php echo $grupo->grupo; ?></h4>
                                <button class="btn btn-primary btn-sm ml-3 mb-2" data-ver-clasificacion data-toggle="tooltip" title="Ver clasificacion" data-grupo="<?php echo $grupo->grupo; ?>" data-competicion_torneo_id="<?php echo $competicion->competicion_torneo_id; ?>">
                                    <i class="fas fa-list"></i> Clasificación
                                </button>
                                <button class="btn btn-primary btn-sm ml-3 mb-2" data-guardar-clasificaicon="<?php echo $competicion->competicion_torneo_id; ?>" data-grupo="<?php echo $grupo->grupo; ?>" data-toggle="tooltip" title="Confirmar clasificación y enviar clasificados a las eliminatorias">
                                    <i class="fas fa-code-branch"></i> Confirmar clasificados
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="row text-center flex-nowrap" style=" overflow-x: auto;white-space: nowrap">
                                    <?php foreach ($grupo->rondas as $ronda) { ?>
                                        <div class="col-md-4 col-lg-3">
                                            <h4 class="bg-primary text-white p-2 mb-3">Ronda <?php echo $ronda->ronda; ?></h4>
                                            <div>
                                                <?php foreach ($ronda->matches as $match) { ?>
                                                    <ul class="list-group mb-3 p-0 btn btn-link" data-match_id="<?php echo $match->match_id; ?>">
                                                        <li class="list-group-item d-flex justify-content-between align-items-center px-2 py-1" style="background: red;" data-user="<?php echo $match->user_rojo; ?>">
                                                            <span class="text-white text-truncate text-left" style="width:calc(100% - 30px);"><?php echo $match->rojo->nombre; ?></span>
                                                            <span class="bg-white <?php echo ($match->hantei == 'rojo') ? 'hantei' : ''; ?> <?php echo ($match->senshu == 'rojo') ? 'senshu' : ''; ?>" style="width:25px;"><?php echo $match->puntos_rojo; ?></span>
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center px-2 py-1" style="background: blue;" data-user="<?php echo $match->user_azul; ?>">
                                                            <span class="text-white text-truncate text-left"><?php echo $match->azul->nombre; ?></span>
                                                            <span class="bg-white <?php echo ($match->hantei == 'azul') ? 'hantei' : ''; ?> <?php echo ($match->senshu == 'azul') ? 'senshu' : ''; ?>" style="width:25px;"><?php echo $match->puntos_azul; ?></span>
                                                        </li>
                                                    </ul>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="col">
                                        <table class="table table-striped table-bordered text-center w-100" id="tablakumite_<?php echo intToLetter($grupo->grupo); ?>">
                                            <thead class="bg-primary text-white">
                                                <tr>
                                                    <th colspan="4" class="font-weigth-bold">CLASIFICACIÓN</th>
                                                </tr>
                                            </thead>
                                            <tbody class="user" data-competicion_torneo_id="<?php echo $competicion->competicion_torneo_id; ?>" data-grupo="<?php echo $grupo->grupo; ?>">
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="4" class="font-weigth-bold"><button type="button" class="btn btn-block btn-sm btn-primary" data-guardar-clasificaicon="<?php echo $competicion->competicion_torneo_id; ?>" data-grupo="<?php echo $grupo->grupo; ?>">Guardar clasificación para eliminatorias</button></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php }
                } ?>

                <?php if ($tipo == 'eliminatorias') { ?>
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
                                            <ul class="list-group p-0 match btn btn-link" data-match_id="<?php echo $match->match_id; ?>" <?php if ($match->user_rojo == 0 || $match->user_azul == 0) {
                                                                                                                                                echo 'style="pointer-events: none"';
                                                                                                                                            } ?>>
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
                    <div class="card-header d-flex justify-content-between">
                        <div class="card-title fw-mediumbold w-100"><a href="<?php echo base_url(); ?>Competiciones/FinalizarCompeticion/<?php echo $competicion->competicion_torneo_id; ?>" class="btn btn-primary text-white rounded">Finalizar competición</a></div>
                    </div>
                <?php } ?>

                <?php $this->load->view('gestion/competiciones/marcadorauxiliar'); ?>

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
            </div>
        </div>

    <?php } ?>

    <?php if ($competicion->tipo == 'eliminatoria') { ?>
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
            <div class="card-title fw-mediumbold w-100"><a href="<?php echo base_url(); ?>Competiciones/FinalizarCompeticion/<?php echo $competicion->competicion_torneo_id; ?>" class="btn btn-primary text-white rounded">Finalizar competición</a></div>
        </div>

    <?php } ?>

</div>

<?php echo form_open();
echo form_close(); ?>