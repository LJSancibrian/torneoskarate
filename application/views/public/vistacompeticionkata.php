<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style>

</style>
<section class="portfolio mt-3" id="portfolio">
    <div class="container-fluid">
        <div class="row ">
            <div class="title text-center w-100">
                <h2 class="text-uppercase"><?php echo $competicion->modalidad;?></h2>
                <p>Se valora la puntuación obtenida en cada ronda por cada árbitro asistente.</p>
                <div class="border"></div>
            </div>
        </div>
    </div>
</section>
<section class="pricing-table pt-0">
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
            <div class="blog-slider__content w-100 p-0 pb-3">
                <div class="price-title d-flex justify-content-between">
                    <strong class="value p-0"><?php echo $competicion->categoria . ' ' . $competicion->nivel; ?> - <?php echo ($competicion->genero == 'M') ? 'masculino' : (($competicion->genero == 'F') ? 'femenino' : 'mixto'); ?></strong>
                    <button class="btn btn-info" data-clasificacion="<?php echo $competicion->competicion_torneo_id; ?>">Ver la clasificación</button>
                </div>

                <?php $grupo = 0;
                foreach ($ordenparticipacion['ordenados'] as $key => $value) { ?>
                    <?php if($value->grupo != $grupo){ ?>
                        <?php if($key > 0){ ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } 
                        $grupo = $value->grupo; ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered text-center fixed2 mb-5" id="tablakata_<?=$grupo?>" data-competicion="<?php echo $competicion->competicion_torneo_id; ?>">
                                <thead>
                                    <tr>
                                        <th class="bg-white text-primary"></th>
                                        <th class="bg-white text-primary">GRUPO <?=$grupo?></th>
                                        <?php for ($i=1; $i <= $rondaspuntos; $i++) { 
                                            echo '<th class="bg-white text-primary" colspan="3">Ronda'.$i.'</th>';
                                        }?>
                                        <th class="bg-white text-primary">Total</th>
                                        <th class="bg-white text-primary">Media</th>
                                    </tr>

                                    <tr>
                                        <th class="">#</th>
                                        <th class="text-left columnfixed">Deportista</th>
                                        <?php for ($i=1; $i <= $rondaspuntos; $i++) { ?>
                                            <th>P1</th>
                                            <th>P2</th>
                                            <th>T1</th>
                                        <?php } ?>
                                        <th>Total</th>
                                        <th>Media</th>
                                    </tr>
                                </thead>
                                <tbody>
                    <?php } ?>
                    <tr data-user_id="<?php echo $value->user_id; ?>">
                        <td class=""><?php echo $value->orden; ?></td>
                        <td class="text-left text-nowrap">
                            <?php echo $value->first_name; ?> <?php echo $value->last_name; ?>
                        </td>
                        <?php for ($i=1; $i <= $rondaspuntos; $i++) { ?>
                            <td data-ronda="<?=$i?>" data-j="1"></td>
                            <td data-ronda="<?=$i?>" data-j="2"></td>
                            <td data-media="<?=$i?>" class="bg-success text-white">0</td>
                        <?php } ?>
                        <td data-total></td>
                        <td data-media-total class="bg-primary text-white">0</td>
                    </tr>
                    <?php if($key == count($ordenparticipacion['ordenados']) - 1){ ?>
                                </tbody>
                            </table>
                        </div>
                    <?php }
                } ?>

                <?php /*
                <div class="table-responsive">
                    <table class="table table-striped table-bordered text-center fixed2" id="tablavistakata" data-competicion="<?php echo $competicion->competicion_torneo_id; ?>">
                        <thead>
                            <tr>
                                <th class="bg-white text-primary"></th>
                                <th class="bg-white text-primary"></th>
                                <?php for ($i=1; $i <= $rondaspuntos; $i++) { 
                                    echo '<th class="bg-white text-primary" colspan="3">Ronda'.$i.'</th>';
                                }?>
                                <th class="bg-white text-primary">Total</th>
                                <th class="bg-white text-primary">Media</th>
                            </tr>

                            <tr>
                                <th class="">#</th>
                                <th class="text-left columnfixed">Deportista</th>
                                <?php for ($i=1; $i <= $rondaspuntos; $i++) { ?>
                                    <th>P1</th>
                                    <th>P2</th>
                                    <th>T1</th>
                                <?php } ?>
                                <th>Total</th>
                                <th>Media</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ordenparticipacion['ordenados'] as $key => $value) { ?>
                                <tr data-user_id="<?php echo $value->user_id; ?>">
                                    <td class=""><?php echo $value->orden; ?></td>
                                    <td class="text-left text-nowrap">
                                        <?php echo $value->first_name; ?> <?php echo $value->last_name; ?>
                                    </td>
                                    <?php for ($i=1; $i <= $rondaspuntos; $i++) { ?>
                                        <td data-ronda="<?=$i?>" data-j="1"></td>
                                        <td data-ronda="<?=$i?>" data-j="2"></td>
                                        <td data-media="<?=$i?>" class="bg-success text-white">0</td>
                                    <?php } ?>
                                    <td data-total></td>
                                    <td data-media-total class="bg-primary text-white">0</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                */ ?>

                <?php if(count($finalistas) > 0) { ?>
                    <div class="row">
                        <div class="col-lg-6">
                            <h4 class="mt-5">RONDA FINAL</h4>
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
                        <div class="col-lg-6">
                            <h4 class="mt-5">CLASIFICACIÓN FINAL</h4>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="clasificaionkatafinal">
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
                <?php for ($i=1; $i <= $grupo; $i++) { ?>
                    <div class="table-responsive">
                        <h4>Grupo <?=$i?></h4>
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
                            <tbody id="clasificacion_competicion_<?=$i?>"></tbody>
                        </table>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>