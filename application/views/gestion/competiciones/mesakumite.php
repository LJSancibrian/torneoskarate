<style>

</style>

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <div class="card-title fw-mediumbold text-uppercase"><?php echo $competicion->modalidad.' '.$competicion->categoria.' '.$competicion->nivel;?> - <?php echo ($competicion->genero == 'M') ? 'Masculino' : (($competicion->genero == 'F') ? 'Femenino' : 'Mixto');?></div>

        <a <?php /*href="<?php echo base_url();?>Competiciones/pdfdoc/<?php echo $competicion->competicion_torneo_id;?>" */?>class="btn btn-icon btn-primary btn-round btn-xs" title="Generar documento" id="exportar_grupos" data-competicion_torneo_id="<?php echo $competicion->competicion_torneo_id; ?>">
            <i class="fas fa-file"></i>
</a>
    </div>
    <div class="card-body" id="tablero-competicion">

        <ul class="nav nav-pills nav-secondary nav-pills-no-bd mb-3" role="tablist">
            <li class="nav-item submenu">
                <a class="nav-link <?php echo ($tipo == 'grupos') ? 'active show' : ''; ?>" href="<?php echo base_url(); ?>Competiciones/mesakumite/<?php echo $competicion->competicion_torneo_id; ?>/grupos">Fase de grupos</a>
            </li>
            <li class="nav-item submenu">
                <a class="nav-link <?php echo ($tipo == 'eliminatorias') ? 'active show' : ''; ?>" href="<?php echo base_url(); ?>Competiciones/mesakumite/<?php echo $competicion->competicion_torneo_id; ?>/eliminatorias" role="tab">Eliminatorias</a>
            </li>
        </ul>
        <div id="content_print">
        <?php
        if ($tipo == 'grupos') {
            foreach ($matches as $grupo) { ?>
                <div class="card shadow-none border">
                    <div class="card-header d-flex justify-content-start flex-wrap">
                        

                        <h4 class="card-title">Grupo <?php echo $grupo->grupo; ?> <div class="form-check p-0">
                            <label class="form-check-label mb-0">
                                <input type="checkbox" class="form-check-input" name="exportpdf[]" id="export_g_<?php echo $grupo->grupo; ?>" value="<?php echo $grupo->grupo; ?>">
                                <span class="form-check-sign "><i class="fas fa-file"></i></span>
                            </label>
                        </div></h4>

                        <button class="btn btn-primary btn-sm ml-3 mb-2" data-ver-clasificacion data-toggle="tooltip" title="Ver clasificacion" data-grupo="<?php echo $grupo->grupo; ?>" data-competicion_torneo_id="<?php echo $competicion->competicion_torneo_id; ?>">
                            <i class="fas fa-list"></i> Clasificación
                        </button>

                        <button class="btn btn-primary btn-sm ml-3 mb-2" data-guardar-clasificaicon="<?php echo $competicion->competicion_torneo_id; ?>" data-grupo="<?php echo $grupo->grupo; ?>" data-toggle="tooltip" title="Confirmar clasificación y enviar clasificados a las eliminatorias">
                        <i class="fas fa-code-branch"></i> Confirmar clasificados
                        </button>
                        
                    </div>
                    <div class="card-body">
                        <div class="row text-center flex-nowrap" style=" overflow-x: auto;white-space: nowrap">
                            <?php /*<div class="col-md-3 table-responsive">
                                <table class="table table-striped table-bordered text-center w-100" id="tablakumite_<?php echo intToLetter($grupo->grupo); ?>">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th colspan="4" class="font-weigth-bold">CLASIFICACIÓN</th>
                                        </tr>
                                    </thead>
                                    <tbody class="user" data-competicion_torneo_id="<?php echo $competicion->competicion_torneo_id; ?>" data-grupo="<?php echo $grupo->grupo; ?>">
                                    </tbody>
                                    <?php <tfoot>
                                        <tr>
                                            <th colspan="4" class="font-weigth-bold"><button type="button" class="btn btn-block btn-sm btn-primary" data-guardar-clasificaicon="<?php echo $competicion->competicion_torneo_id; ?>" data-grupo="<?php echo $grupo->grupo; ?>">Guardar clasificación para eliminatorias</button></th>
                                        </tr>
                                    </tfoot> ?>
                                </table>
                            </div>
                            */ ?>

                            <?php foreach ($grupo->rondas as $ronda) { ?>
                                <div class="col-md-4 col-lg-3">
                                    <h4 class="bg-primary text-white p-2 mb-3">Ronda <?php echo $ronda->ronda; ?></h4>
                                    <div>
                                        <?php foreach ($ronda->matches as $match) {
                                            //if ($match->estado == 'pendiente') { ?>
                                                <ul class="list-group mb-3 p-0 btn btn-link" data-match_id="<?php echo $match->match_id; ?>">
                                                    <li class="list-group-item d-flex justify-content-between align-items-center px-2 py-1" style="background: red;" data-user="<?php echo $match->user_rojo; ?>">
                                                        <span class="text-white text-truncate text-left" style="width:calc(100% - 30px);"><?php echo $match->rojo->nombre; ?></span>
                                                        <span class="bg-white <?php echo ($match->hantei == 'rojo') ? 'hantei' : '';?> <?php echo ($match->senshu == 'rojo') ? 'senshu' : '';?>" style="width:25px;"><?php echo $match->puntos_rojo; ?></span>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center px-2 py-1" style="background: blue;" data-user="<?php echo $match->user_azul; ?>">
                                                        <span class="text-white text-truncate text-left"><?php echo $match->azul->nombre; ?></span>
                                                        <span class="bg-white <?php echo ($match->hantei == 'azul') ? 'hantei' : '';?> <?php echo ($match->senshu == 'azul') ? 'senshu' : '';?>" style="width:25px;"><?php echo $match->puntos_azul; ?></span>
                                                    </li>
                                                </ul>
                                            <?php /*} else { ?>
                                                <ul class="list-group mb-3 p-0" style="pointer-events: none;" data-match_id="<?php echo $match->match_id; ?>">
                                                    <li class="list-group-item d-flex justify-content-between align-items-center px-2 py-1" style="background: red;" data-user="<?php echo $match->user_rojo; ?>">
                                                        <span class="text-white text-truncate text-left" style="width:calc(100% - 30px);"><?php echo $match->rojo->nombre; ?></span>
                                                        <span class="text-white" style="width:25px;"><?php echo $match->puntos_rojo; ?></span>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center px-2 py-1" style="background: blue;" data-user="<?php echo $match->user_azul; ?>">
                                                        <span class="text-white text-truncate text-left" style="width:calc(100% - 30px);"><?php echo $match->azul->nombre; ?></span>
                                                        <span class="text-white" style="width:25px;"><?php echo $match->puntos_azul; ?></span>
                                                    </li>
                                                </ul>
                                            <?php } */?>
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
                                <?php foreach ($eliminatoria as $match) {
                                    //if ($match->estado == 'pendiente') { ?>
                                        <ul class="list-group p-0 match btn btn-link" data-match_id="<?php echo $match->match_id; ?>" <?php if ($match->user_rojo == 0 || $match->user_azul == 0) {echo 'style="pointer-events: none"';} ?>>
                                            <li class="list-group-item d-flex justify-content-between align-items-center px-2 py-1" style="background: red;" data-user="<?php echo $match->user_rojo; ?>">
                                                <span class="text-white text-truncate text-left" style="width:calc(100% - 30px);"><?php echo (isset($match->rojo)) ? $match->rojo->nombre : ''; ?></span>
                                                <span class="bg-white <?php echo ($match->hantei == 'rojo') ? 'hantei' : '';?> <?php echo ($match->senshu == 'rojo') ? 'senshu' : '';?>" style="width:25px;"><?php echo (isset($match->rojo)) ? $match->puntos_rojo : 0; ?></span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center px-2 py-1" style="background: blue;" data-user="<?php echo $match->user_azul; ?>">
                                                <span class="text-white text-truncate text-left"><?php echo (isset($match->azul)) ? $match->azul->nombre : ''; ?></span>
                                                <span class="bg-white <?php echo ($match->hantei == 'azul') ? 'hantei' : '';?> <?php echo ($match->senshu == 'azul') ? 'senshu' : '';?>" style="width:25px;"><?php echo (isset($match->azul)) ? $match->puntos_azul : 0; ?></span>
                                            </li>
                                        </ul>
                                    <?php /*} else { ?>
                                        <ul class="list-group p-0 match bg-succes rounded"  data-match_id="<?php echo $match->match_id; ?>">
                                            <li class="list-group-item d-flex justify-content-between align-items-center px-2 py-1" style="background: red;" data-user="<?php echo $match->user_rojo; ?>">
                                                <span class="text-white text-truncate text-left" style="width:calc(100% - 30px);"><?php echo $match->rojo->nombre; ?></span>
                                                <span class="text-white" style="width:25px;"><?php echo $match->puntos_rojo; ?></span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center px-2 py-1" style="background: blue;" data-user="<?php echo $match->user_azul; ?>">
                                                <span class="text-white text-truncate text-left" style="width:calc(100% - 30px);"><?php echo $match->azul->nombre; ?></span>
                                                <span class="text-white" style="width:25px;"><?php echo $match->puntos_azul; ?></span>
                                            </li>
                                        </ul>
                                    <?php } */?>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title fw-mediumbold w-100"><a href="<?php echo base_url();?>Competiciones/FinalizarCompeticion/<?php echo $competicion->competicion_torneo_id; ?>" class="btn btn-primary text-white rounded">Finalizar competición</a></div>
                </div>
            </div>
        <?php } ?>
        </div>
    </div>
</div>

<div class="modal fade" id="marcadorauxiliar" tabindex="-1" role="dialog" aria-labelledby="marcadorauxiliarLabel">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body" data-match-id="">
                <div class="d-flex flex-column flex-sm-row justify-content-between text-white">
                    <div class="w-100 w-lg-50 p-3" data-match-rojo style="background: red">
                        <h3 class="text-center p-3 border border-white" id="user_rojo"></h3>
                        <div class="row">
                            <div class="col-4 d-flex flex-column justify-content-between" data-acciones-rojo>
                                <button type="button" class="btn btn-icon" data-plus="rojo">
                                    <i class="fa fa-plus"></i>
                                </button>
                                <button type="button" class="btn btn-icon btn-danger" data-minus="rojo">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                            <div class="align-items-center col-8 d-flex justify-content-center">
                                <h1 id="puntostotalesrojo" style="font-size: 12vh;">0</h1>
                            </div>
                            <div class="col-12 mt-3">
                                <div class="border border-white form-check p-0 p-2 text-white">
                                    <label class="form-radio-label mb-0">
                                        <input class="form-radio-input" type="radio" name="senshu" value="rojo">
                                        <span class="form-radio-sign text-white">SENSHU</span>
                                    </label>
                                    <label class="form-radio-label mb-0">
                                        <input class="form-radio-input" type="radio" name="hantei" value="azul">
                                        <span class="form-radio-sign text-white">HANTEI</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-100 w-lg-50 p-3" data-match-azul style="background: blue">
                        <h3 class="text-center p-3 border border-white" id="user_azul"></h3>
                        <div class="row">
                            <div class="align-items-center col-8 d-flex justify-content-center">
                                <h1 id="puntostotalesazul" style="font-size: 12vh;">0</h1>
                            </div>
                            <div class="align-items-end col-4 d-flex flex-column justify-content-between" data-acciones-azul>
                                <button type="button" class="btn btn-icon" data-plus="azul">
                                    <i class="fa fa-plus"></i>
                                </button>
                                <button type="button" class="btn btn-icon btn-danger" data-minus="azul">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                            <div class="col-12 text-right mt-3">
                                <div class="border border-white form-check p-0 p-2 text-white">
                                    <label class="form-check-label mb-0">
                                        <input class="form-radio-input" type="radio" name="senshu" value="azul">
                                        <span class="form-radio-sign  text-white">SENSHU</span>
                                    </label>
                                    <label class="form-check-label mb-0">
                                        <input class="form-radio-input" type="radio" name="hantei" value="azul">
                                        <span class="form-radio-sign  text-white">HANTEI</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <button class="btn btn-success" id="guardar-marcador">Guardar</button>
                    <button class="btn btn-outline-danger" data-dismiss="modal" id="cerrar-marcador">Cerrar</button>
                </div>
            </div>

        </div>
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


<?php echo form_open();
echo form_close(); ?>