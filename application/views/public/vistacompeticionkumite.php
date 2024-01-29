<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<section class="portfolio mt-3" id="portfolio">
    <div class="container-fluid">
        <div class="row ">
            <div class="title text-center w-100">
                <h2 class="text-uppercase"><?php echo $competicion->modalidad; ?></h2>
                <?php if ($competicion->tipo == 'liguilla') { ?>
                    <p>Fase inicial de grupos y fase eliminatoria con los clasificados de cada grupo.</p>
                <?php } else { ?>
                    <p>Competición eliminatoria. El ganador pasa a la siguiente ronda.</p>
                <?php  } ?>
                <div class="border"></div>
            </div>
        </div>
    </div>
</section>

<section class="pricing-table pt-0" competicion_torneo_id="<?php echo $competicion->competicion_torneo_id; ?>">
    <div class="container-fluid">
        <ul class="nav nav-pills justify-content-center" id="torneotabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link" role="tab" id="info-tab" href="<?php echo base_url(); ?>torneo/<?php echo $torneo->slug; ?>#info">Info</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" role="tab" id="competiciones-tab" href="<?php echo base_url(); ?>torneo/<?php echo $torneo->slug; ?>#competiciones">Competiciones</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="medallero-tab" href="<?php echo base_url(); ?>torneo/<?php echo $torneo->slug; ?>#medallero" role="tab">Medallero</a>
            </li>
        </ul>
        <div class="blog-slider">
            <div class="blog-slider__content w-100 p-0">
                <div class="price-title">
                    <strong class="value p-0"><?php echo $competicion->categoria . ' ' . $competicion->nivel; ?></strong>
                </div>
            </div>
            <div class="service-2">
                <?php foreach ($matches as $grupo) { ?>
                    <div class="service-item p-3 mb-3">
                        <div class="d-flex justify-content-between">
                            <h4>Grupo <?php echo $grupo->grupo; ?></h4>

                            <?php if ($competicion->iniciacion == 0) { ?>
                                <ul class="nav nav-pills justify-content-center" id="torneotabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link px-3 py-1 active" id="info-tab" data-toggle="tab" href="#comb_<?php echo $grupo->grupo; ?>" role="tab" aria-controls="info" aria-selected="true">Combates</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link px-3 py-1" id="competiciones-tab" data-toggle="tab" href="#clasif_<?php echo $grupo->grupo; ?>" role="tab" aria-controls="competiciones" aria-selected="false">Clasificación</a>
                                    </li>
                                </ul>
                            <?php } ?>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane active" id="comb_<?php echo $grupo->grupo; ?>" role="tabpanel" aria-labelledby="comb_<?php echo $grupo->grupo; ?>-tab">
                                <div class="row text-center flex-nowrap" style="overflow-x: auto;white-space: nowrap">
                                    <?php foreach ($grupo->rondas as $ronda) { ?>
                                        <div class="col-md-4 col-lg-3">
                                            <h4 class="bg-primary text-white p-2 mb-3">Ronda <?php echo $ronda->ronda; ?></h4>
                                            <?php foreach ($ronda->matches as $match) { ?>
                                                <ul class="list-group mb-3 p-0 btn btn-link" data-match_id="<?php echo $match->match_id; ?>">
                                                    <li class="list-group-item d-flex justify-content-between align-items-center px-2 py-1 <?php echo ($match->senshu == 'rojo') ? 'senshu' : (($match->hantei == 'rojo') ? 'hantei' : ''); ?>" style="background: red;" data-user="<?php echo $match->user_rojo; ?>">
                                                        <span class="text-white text-truncate text-left" style="width:calc(100% - 30px);"><?php echo $match->rojo->nombre; ?></span>
                                                        <span class="font-weight-bold text-white" style="font-size:1.5rem"><?php echo $match->puntos_rojo; ?></span>
                                                    </li>

                                                    <li class="list-group-item d-flex justify-content-between align-items-center px-2 py-1 <?php echo ($match->senshu == 'azul') ? 'senshu' : (($match->hantei == 'azul') ? 'hantei' : ''); ?>" style="background: blue;" data-user="<?php echo $match->user_azul; ?>">
                                                        <span class="text-white text-truncate text-left"><?php echo $match->azul->nombre; ?></span>
                                                        <span class="font-weight-bold text-white" style="font-size:1.5rem"><?php echo $match->puntos_azul; ?></span>
                                                    </li>
                                                </ul>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <?php if ($competicion->iniciacion == 0) { ?>
                                <div class="tab-pane" id="clasif_<?php echo $grupo->grupo; ?>" role="tabpanel" aria-labelledby="clasif_<?php echo $grupo->grupo; ?>-tab">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered text-center w-100 fixed2" id="tablakumite_<?php echo $grupo->grupo; ?>">
                                            <thead class="bg-primary text-white">
                                                <tr>
                                                    <th colspan="8" class="font-weigth-bold">CLASIFICACIÓN</th>
                                                </tr>
                                            </thead>
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th class="text-left columnfixed">Deportista</th>
                                                    <th class="text-left">Equipo</th>
                                                    <th class="text-left">Victorias</th>
                                                    <th class="text-left">Puntos favor</th>
                                                    <th class="text-left">Puntos contra</th>
                                                    <th class="text-left">Senshu</th>
                                                    <th class="text-left">Hantei</th>
                                                </tr>
                                            </thead>
                                            <tbody class="clasificacion_grupo" data-competicion_torneo_id="<?php echo $competicion->competicion_torneo_id; ?>" data-grupo="<?php echo $grupo->grupo; ?>">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>

                <?php if (count($eliminatorias) > 0) { ?>
                    <div class="service-item p-3 mb-3">
                        <h4>Eliminatorias</h4>
                        <div class="row">
                            <?php foreach ($eliminatorias as $key => $eliminatoria) {
                                if ($key  == count($eliminatorias)) {
                                    $ronda = 'FINAL';
                                } elseif ($key  == count($eliminatorias) - 1) {
                                    $ronda = 'SEMI - FINAL';
                                } else {
                                    $ronda = 'Ronda ' . $key;
                                } ?>

                                <?php switch (count($eliminatorias)) {
                                    case 1:
                                        $class = "col-12 round";
                                        break;
                                    case 2:
                                        $class = "col-md-4 round";
                                        break;
                                    case 3:
                                        $class = "col-md-4 round";
                                        break;
                                    default:
                                        $class = "col-md-4 col-lg-3 round";
                                        break;
                                } ?>
                                <div class="<?php echo $class; ?>">
                                    <h4 class="bg-primary text-white p-2 mb-3"><?php echo $ronda; ?></h4>
                                    <div>
                                        <?php foreach ($eliminatoria as $match) { ?>
                                            <ul class="list-group mb-3 p-0 match" data-match_id="<?php echo $match->match_id; ?>">
                                                <li class="list-group-item d-flex justify-content-between align-items-center px-2 py-1 <?php echo ($match->senshu == 'rojo') ? 'senshu' : (($match->hantei == 'rojo') ? 'hantei' : ''); ?>" style="background: red;" data-user="<?php echo $match->user_rojo; ?>">
                                                    <span class="text-white text-truncate text-left" style="width:calc(100% - 30px);"><?php echo (isset($match->rojo)) ? $match->rojo->nombre : ''; ?></span>
                                                    <span class="font-weight-bold text-white" style="font-size:1.5rem"><?php echo $match->puntos_rojo; ?></span>
                                                </li>

                                                <li class="list-group-item d-flex justify-content-between align-items-center px-2 py-1 <?php echo ($match->senshu == 'azul') ? 'senshu' : (($match->hantei == 'azul') ? 'hantei' : ''); ?>" style="background: blue;" data-user="<?php echo $match->user_azul; ?>">
                                                    <span class="text-white text-truncate text-left"><?php echo (isset($match->azul)) ? $match->azul->nombre : ''; ?></span>
                                                    <span class="font-weight-bold text-white" style="font-size:1.5rem"><?php echo $match->puntos_azul; ?></span>
                                                </li>
                                            </ul>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>

                <?php if ($competicion->iniciacion == 1) { ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered text-center w-100 fixed2" id="tablakumite_iniciacion">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th colspan="8" class="font-weigth-bold">CLASIFICACIÓN</th>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="text-left columnfixed">Deportista</th>
                                    <th class="text-left">Equipo</th>
                                    <th class="text-left">Victorias</th>
                                    <th class="text-left">Empates</th>
                                    <th class="text-left">Derrotas</th>
                                    <th class="text-left">Puntos favor</th>
                                    <th class="text-left">Total</th>
                                </tr>
                            </thead>
                            <tbody class="clasificacion_grupo" data-competicion_torneo_id="<?php echo $competicion->competicion_torneo_id; ?>" data-grupo="iniciacion">
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="clasificacionkata_modal" tabindex="-1" aria-labelledby="clasificacionkata_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="card-title fw-mediumbold">Clasificación</div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped w-100">
                        <thead>
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