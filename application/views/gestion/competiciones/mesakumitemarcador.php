<style>
    .senshu:after {
        content: "\f111";
        color: yellow;
        font-family: 'Font Awesome 5 Solid';
        font-size: 1.3rem;
        position: absolute;
    }

    .hantei:after {
        content: "\f111";
        color: green;
        font-family: 'Font Awesome 5 Solid';
        font-size: 1.3rem;
        position: absolute;
    }
</style>

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <div class="card-title fw-mediumbold"><?php echo $competicion->modalidad.' '.$competicion->categoria.' '.$competicion->nivel;?> - <?php echo ($competicion->genero == 'M') ? 'Masculino' : (($competicion->genero == 'F') ? 'Femenino' : 'Mixto');?></div>
        <button class="btn btn-icon btn-primary btn-round btn-sm" data-toggle-custom-template data-toggle="tooltip" title="Ver clasificacion">
            <i class="fas fa-list"></i>
        </button>
        <button class="btn btn-icon btn-primary btn-round btn-xs" onclick="imprSelec('tablero-competicion', '<?php echo $competicion->modalidad.'_'.$competicion->categoria.'_'.$competicion->nivel;?>_<?php echo ($competicion->genero == 'M') ? 'Masculino' : (($competicion->genero == 'F') ? 'Femenino' : 'Mixto');?>')" title="Generar tablero">
                    <i class="fas fa-print"></i>
                </button>
    </div>
    <div class="card-body p-1" id="tablero-competicion">
        <?php
        foreach ($matches as $grupo) { ?>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Grupo <?php echo $grupo->grupo; ?></h4>
                </div>
                <div class="card-body">
                    <div class="row text-center flex-nowrap" style=" overflow-x: auto;white-space: nowrap">
                        <?php foreach ($grupo->rondas as $ronda) { ?>
                            <div class="col-md-4 col-lg-3">
                                <h4 class="bg-primary text-white p-2 mb-3">Ronda <?php echo $ronda->ronda; ?></h4>
                                <div>
                                    <?php foreach ($ronda->matches as $match) {
                                        if ($match->estado == 'pendiente') { ?>
                                            <ul class="list-group border border-primary mb-3 p-0 match btn btn-link" data-match_id="<?php echo $match->match_id; ?>">
                                                <li class="list-group-item d-flex justify-content-between align-items-center px-2 py-1" style="background: red;" data-user="<?php echo $match->user_rojo; ?>">
                                                    <span class="text-white text-truncate text-left" style="width:calc(100% - 30px);"><?php echo $match->rojo->nombre; ?></span>
                                                    <span class="bg-white" style="width:25px;">0</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center px-2 py-1" style="background: blue;" data-user="<?php echo $match->user_azul; ?>">
                                                    <span class="text-white text-truncate text-left"><?php echo $match->azul->nombre; ?></span>
                                                    <span class="bg-white" style="width:25px;">0</span>
                                                </li>
                                            </ul>
                                        <?php } else { ?>
                                            <ul class="list-group border border-primary mb-3 p-0 match bg-succes rounded" style="pointer-events: none; " data-match_id="<?php echo $match->match_id; ?>">
                                                <li class="list-group-item d-flex justify-content-between align-items-center px-2 py-1" style="background: red;" data-user="<?php echo $match->user_rojo; ?>">
                                                    <span class="text-white text-truncate text-left" style="width:calc(100% - 30px);"><?php echo $match->rojo->nombre; ?></span>
                                                    <span class="bg-white" style="width:25px;">0</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center px-2 py-1" style="background: blue;" data-user="<?php echo $match->user_azul; ?>">
                                                    <span class="text-white text-truncate text-left" style="width:calc(100% - 30px);"><?php echo $match->azul->nombre; ?></span>
                                                    <span class="bg-white" style="width:25px;">0</span>
                                                </li>
                                            </ul>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<div class="modal fade" id="marcadorauxiliar" tabindex="-1" role="dialog" aria-labelledby="marcadorauxiliarLabel" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark text-white">
            <div class="modal-body" data-match_id="">
                <div class="d-flex">
                    <div class="w-100 w-lg-50 p-3" style="background: red">
                        <h3 class="text-left p-3" id="user_rojo">Nombre Apellidos</h3>
                        <div class="row">
                            <div class="col-4" data-acciones-rojo>
                                <div class="btn-group-vertical mb-2 w-100">
                                    <button type="button" class="btn btn-sm border-primary btn-outline-primary btn-light" data-accion="ippon">IPPON</button>
                                    <button type="button" class="btn btn-sm border-primary btn-outline-primary btn-light" data-accion="wazari">WAZA-ARI</button>
                                    <button type="button" class="btn btn-sm border-primary btn-light" data-accion="yuko">YUKO</button>
                                </div>
                                <div class="btn-group-vertical mb-2 w-100">
                                    <div class="form-check p-0 text-white">
                                        <label class="form-check-label mb-0">
                                            <input class="form-check-input" type="checkbox" name="senshu_rojo" value="1">
                                            <span class="form-check-sign  text-white">SENSHU</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="btn-group-vertical mb-2 w-100">
                                    <button type="button" class="btn btn-sm btn-light" data-accion="rest">-1</button>
                                </div>
                            </div>
                            <div class="align-items-center col-8 d-flex justify-content-center">
                                <h1 id="puntostotalesrojo" style="font-size: 4rem;">0</h1>
                            </div>
                            <div class="col-12">
                                <div class="border border-white d-inline-block form-check p-0 p-2 text-white">
                                    <span class="mr-3">C1:</span>    
                                    <label class="form-check-label mb-0">
                                        <input class="form-check-input" type="checkbox" value="">
                                        <span class="form-check-sign  text-white">C</span>
                                    </label>

                                    <label class="form-check-label mb-0">
                                        <input class="form-check-input" type="checkbox" value="">
                                        <span class="form-check-sign  text-white">K</span>
                                    </label>

                                    <label class="form-check-label mb-0">
                                        <input class="form-check-input" type="checkbox" value="">
                                        <span class="form-check-sign  text-white">HC</span>
                                    </label>
                                    <label class="form-check-label mb-0">
                                        <input class="form-check-input" type="checkbox" value="">
                                        <span class="form-check-sign text-white">H</span>
                                    </label>
                                </div>

                                <div class="border border-white d-inline-block form-check p-0 p-2 text-white">
                                    <span class="mr-3">C2:</span> 
                                    <label class="form-check-label mb-0">
                                        <input class="form-check-input" type="checkbox" value="">
                                        <span class="form-check-sign  text-white">C</span>
                                    </label>

                                    <label class="form-check-label mb-0">
                                        <input class="form-check-input" type="checkbox" value="">
                                        <span class="form-check-sign  text-white">K</span>
                                    </label>

                                    <label class="form-check-label mb-0">
                                        <input class="form-check-input" type="checkbox" value="">
                                        <span class="form-check-sign  text-white">HC</span>
                                    </label>
                                    <label class="form-check-label mb-0">
                                        <input class="form-check-input" type="checkbox" value="">
                                        <span class="form-check-sign text-white">H</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-100 w-lg-50 p-3" style="background: blue">
                        <h3 class="text-right p-3" id="user_azul">Nombre Apellidos</h3>
                        <div class="row">
                            <div class="align-items-center col-8 d-flex justify-content-center">
                                <h1 id="puntostotalesazul" style="font-size: 4rem;">0</h1>
                            </div>
                            <div class="col-4" data-acciones-azul>
                                <div class="btn-group-vertical mb-2 w-100">
                                    <button type="button" class="btn btn-sm border-primary btn-outline-primary btn-light" data-accion="ippon">IPPON</button>
                                    <button type="button" class="btn btn-sm border-primary btn-outline-primary btn-light" data-accion="wazari">WAZA-ARI</button>
                                    <button type="button" class="btn btn-sm border-primary btn-light" data-accion="yuko">YUKO</button>
                                </div>
                                <div class="btn-group-vertical mb-2 w-100">
                                    <div class="form-check p-0 text-white">
                                        <label class="form-check-label mb-0">
                                            
                                            <input class="form-check-input" type="checkbox" name="senshu_azul" value="1">
                                            <span class="form-check-sign  text-white">SENSHU</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="btn-group-vertical mb-2 w-100">
                                    <button type="button" class="btn btn-sm btn-light" data-accion="rest">-1</button>
                                </div>
                            </div>
                            <div class="col-12 text-right">
                                <div class="border border-white d-inline-block form-check p-0 p-2 text-white">
                                    <span class="mr-3">C1:</span>    
                                    <label class="form-check-label mb-0">
                                        <input class="form-check-input" type="checkbox" value="">
                                        <span class="form-check-sign  text-white">C</span>
                                    </label>

                                    <label class="form-check-label mb-0">
                                        <input class="form-check-input" type="checkbox" value="">
                                        <span class="form-check-sign  text-white">K</span>
                                    </label>

                                    <label class="form-check-label mb-0">
                                        <input class="form-check-input" type="checkbox" value="">
                                        <span class="form-check-sign  text-white">HC</span>
                                    </label>
                                    <label class="form-check-label mb-0">
                                        <input class="form-check-input" type="checkbox" value="">
                                        <span class="form-check-sign text-white">H</span>
                                    </label>
                                </div>

                                <div class="border border-white d-inline-block form-check p-0 p-2 text-white">
                                    <span class="mr-3">C2:</span> 
                                    <label class="form-check-label mb-0">
                                        <input class="form-check-input" type="checkbox" value="">
                                        <span class="form-check-sign  text-white">C</span>
                                    </label>

                                    <label class="form-check-label mb-0">
                                        <input class="form-check-input" type="checkbox" value="">
                                        <span class="form-check-sign  text-white">K</span>
                                    </label>

                                    <label class="form-check-label mb-0">
                                        <input class="form-check-input" type="checkbox" value="">
                                        <span class="form-check-sign  text-white">HC</span>
                                    </label>
                                    <label class="form-check-label mb-0">
                                        <input class="form-check-input" type="checkbox" value="">
                                        <span class="form-check-sign text-white">H</span>
                                    </label>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="justify-content-between modal-footer">
                <button class="btn btn-warning" id="reset-timer">Reiniciar</button>

                <div class="align-items-center d-flex">
                    <button class="btn btn-secondary" id="add-timer"><i class="fas fa-plus"></i></button>
                    <div class="border countdown rounded rounded-bottom mx-3" id="timer">
                        <div class="d-flex justify-content-between">
                            <div class="clock-wrapper px-3" style="font-size: 2rem;">
                                <span class="minutes">00</span>
                                <span class="dots">:</span>
                                <span class="seconds">00</span>
                            </div>
                            <button class="btn btn-secondary" id="resume-timer"><i class="fas fa-play"></i></button>
                            <button class="btn btn-secondary" id="stop-timer" style="display: none;"><i class="fas fa-stop"></i></button>
                        </div>
                    </div>
                    <button class="btn btn-secondary" id="del-timer"><i class="fas fa-minus"></i></button>
                </div>

                <button type="button" class="btn btn-secondary" id="close_marcador">Finalizar</button>
            </div>
        </div>
    </div>
</div>



<?php echo form_open();
echo form_close(); ?>