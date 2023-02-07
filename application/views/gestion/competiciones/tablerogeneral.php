<div class="row">
    <div class="col-md-3 col-lg-2 d-none">
        <div class="card ">
            <div class="card-header">
                <div class="card-title fw-mediumbold">Deportistas</div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <tbody id="list-deportistas">
                        <?php $i = 1;
                        foreach ($inscripciones as $key => $value) {
                            echo '<tr><td class="text-sm"><p class="mb-0">' . $i . '.- ' . $value->first_name . ' ' . $value->last_name . '</p><span>' . $value->nombre . '</span></td></tr>';
                            $i++;
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card" id="div_opciones">
            <div class="card-header d-flex justify-content-between">
                <div class="card-title fw-mediumbold text-uppercase"><?php echo $competicion->modalidad . ' ' . $competicion->categoria . ' ' . $competicion->nivel; ?> - <?php echo ($competicion->genero == 'M') ? 'Masculino' : (($competicion->genero == 'F') ? 'Femenino' : 'Mixto'); ?></div>
            </div>
            <div class="card-body bg-white">
                <h4>Opciones de sorteo</h4>
                <ul class="list-unstyled">
                    <li class="py-1">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Generar como puntos" data-generar-tablero="<?php echo $competicion->competicion_torneo_id; ?>" data-generar-tipo="puntos"><i class="fas fa-random mr-2"></i> Rondas de puntos</button> Cada deportista realiza dos rondas de katas. Los que obtienen mas puntos realizan una tercera ronda final para decidir la clasificación final.
                    </li>
                    <li class="py-1">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Generar como ligulla" data-generar-tablero="<?php echo $competicion->competicion_torneo_id; ?>" data-generar-tipo="liguilla"><i class="fas fa-random mr-2"></i> Liguilla + eliminatorias</button> Enfrentamientos entre deportistas del mismo grupo. Clasifican a eliminatorias los mejor clasificados de cada grupo.   
                    </li>
                    <li class="py-1">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Generar como eliminatoria" data-generar-tablero="<?php echo $competicion->competicion_torneo_id; ?>" data-generar-tipo="eliminatoria"><i class="fas fa-random mr-2"></i> Eliminatorias directas</button> Entrentamiento directo entre deportistas continuando en la competición los ganadores.
                    </li>

                    <li class="py-1">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Generar como liga todos contra todos" data-generar-tablero="<?php echo $competicion->competicion_torneo_id; ?>" data-generar-tipo="liga"><i class="fas fa-random mr-2"></i> Liga</button> Liga todos contra todos, pasando a la final el primer y segundo clasificado.
                    </li>

                </ul>
            </div>

            <div class="card-body bg-white border-top">
                <div class="row" id="add_inscripcion_form">
                    <label for="" class="col-12 col-md-3 text-right">Añadir inscripción:</label>
                    <div class="col-12 col-md-6">
                        <select name="deportista_id" id="deportista_id" class="form-control select2">
                            <option value=""></option>
                            <?php foreach ($deportistas as $key => $dep) { ?>
                                <option value="<?php echo $dep->id; ?>"><?php echo $dep->first_name . ' ' . $dep->last_name . ' - ' . $dep->nombre; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-3 mb-3">
                        <button class="btn btn-default btn-sm" type="button" id="add_inscripcion">Añadir inscripción</button>
                    </div>
                    <?php echo form_open();
                    echo form_hidden('competicion_nueva_torneo_id', $competicion->competicion_torneo_id);
                    echo form_hidden('torneo_id', $competicion->torneo_id);
                    echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card" id="div_principal">
            <div class="card-header d-flex justify-content-between">
                <div class="card-title fw-mediumbold text-uppercase"><?php echo $competicion->modalidad . ' ' . $competicion->categoria . ' ' . $competicion->nivel; ?> - <?php echo ($competicion->genero == 'M') ? 'Masculino' : (($competicion->genero == 'F') ? 'Femenino' : 'Mixto'); ?></div>

                <a href="<?php echo base_url();?>Competiciones/mesa/<?php echo $competicion->competicion_torneo_id;?>" data-toggle="tooltip"  title="Manejar mesa" class="btn btn-icon btn-primary btn-round btn-sm" data-original-title="Manejar mesa"><i class="fas fa-chalkboard-teacher"></i></a>

                <a href="<?php echo base_url(); ?>Competiciones/pdfdoc/<?php echo $competicion->competicion_torneo_id; ?>" target="_blank" class="btn btn-icon btn-primary btn-round btn-sm" title="Generar PDF" target="_blank">
                    <i class="fas fa-file-pdf"></i>
                </a>
            </div>
            <div class="card-body bg-white" id="tablero-competicion">
                <?php if($competicion->tipo == 'puntos'){?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered text-center" id="tablakata">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th colspan="2" class="text-left">Deportista</th>
                                <th class="text-left">Equipo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($ordenparticipacion['ordenados'] as $key => $inscripcion) { ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td colspan="2" class="text-left text-nowrap" data-inscripcion="<?php echo $inscripcion->inscripcion_id; ?>"><?php echo $inscripcion->first_name; ?> <?php echo $inscripcion->last_name; ?></td>
                                    <td class="text-left text-nowrap"><?php echo $inscripcion->nombre; ?></td>
                                </tr>
                            <?php $i++;
                            }
                            foreach ($ordenparticipacion['noordenados'] as $key => $inscripcion) { ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td colspan="2" class="text-left text-nowrap" data-inscripcion="<?php echo $inscripcion->inscripcion_id; ?>"><?php echo $inscripcion->first_name; ?> <?php echo $inscripcion->last_name; ?></td>
                                    <td class="text-left text-nowrap"><?php echo $inscripcion->nombre; ?></td>
                                </tr>
                            <?php $i++;
                            } ?>
                        </tbody>
                    </table>
                </div>
                <button type="button" data-guardar-puntos="<?php echo $competicion->competicion_torneo_id; ?>" class="btn btn-sm btn-default m-3">Guardar orden</button>
                <?php } elseif($competicion->tipo == 'liguilla'){?>
                    <h4 class="card-title d-flex justify-content-between align-items-center">Fase de grupos <span id="totalcomabtes"></span></h4>
                    <div id="fasegrupos">
                        <?php $g = 0;
                        $i = 0;
                        
                        foreach ($ordenparticipacion['ordenados'] as $key => $inscripcion) { ?>
                            <?php if ($inscripcion->grupo > $g) {
                                // // se crea el div y la tabla
                                if ($i > 0) {
                                    // se cierra el grupo
                                    echo '</tbody></table></div>';
                                }
                                echo '
                                <div class="border-bottom d-flex flex-row justify-content-start my-3" id="grupokumite_' . $inscripcion->grupo . '" grupo="' .$inscripcion->grupo . '">
                                    <table class="table table-striped table-bordered text-center w-auto" id="tablakumite_' .$inscripcion->grupo . '">
                                        <thead>
                                            <tr>
                                                <th colspan="4" class="bg-white text-primary font-weigth-bold">GRUPO ' . $inscripcion->grupo . '</th>
                                            </tr>
                                        </thead>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th colspan="2" class="text-left">Deportista</th>
                                                <th class="text-left">Equipo</th>
                                            </tr>
                                        </thead>
                                        <tbody><tr class="no-sort"><td colspan="4"></td></tr>';
                                $g++;
                            } ?>
                            <tr data-user="<?php echo $inscripcion->user_id; ?>" data-inscripcion_id="<?php echo $inscripcion->inscripcion_id; ?>">
                                <td><?php echo $inscripcion->orden; ?></td>
                                <td colspan="2" class="text-nowrap"><?php echo $inscripcion->first_name; ?> <?php echo $inscripcion->last_name; ?></td>
                                <td><?php echo $inscripcion->nombre; ?></td>
                            </tr>
                        <?php
                            $i++;
                            if (count($ordenparticipacion['ordenados']) == $i) {
                                echo '</tbody></table></div>';
                            }
                        }
                        ?>
                    </div>
                    <hr>
                    <h4>Eliminatorias</h4>
                    <div id="faseeliminatorias">
                        <div class="brackets"></div>
                    </div>
                    <button type="button" data-guardar-liguilla="<?php echo $competicion->competicion_torneo_id; ?>" class="btn btn-sm btn-default m-3">Guardar grupos</button>
                    <?php } elseif($competicion->tipo == 'liguilla'){?>
                    <h4 class="card-title d-flex justify-content-between align-items-center">Fase de grupos <span id="totalcomabtes"></span></h4>
                    <div id="fasegrupos">
                        <?php $g = 0;
                        $i = 0;
                        
                        foreach ($ordenparticipacion['ordenados'] as $key => $inscripcion) { ?>
                            <?php if ($inscripcion->grupo > $g) {
                                // // se crea el div y la tabla
                                if ($i > 0) {
                                    // se cierra el grupo
                                    echo '</tbody></table></div>';
                                }
                                echo '
                                <div class="border-bottom d-flex flex-row justify-content-start my-3" id="grupokumite_' . $inscripcion->grupo . '" grupo="' .$inscripcion->grupo . '">
                                    <table class="table table-striped table-bordered text-center w-auto" id="tablakumite_' .$inscripcion->grupo . '">
                                        <thead>
                                            <tr>
                                                <th colspan="4" class="bg-white text-primary font-weigth-bold">GRUPO ' . $inscripcion->grupo . '</th>
                                            </tr>
                                        </thead>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th colspan="2" class="text-left">Deportista</th>
                                                <th class="text-left">Equipo</th>
                                            </tr>
                                        </thead>
                                        <tbody><tr class="no-sort"><td colspan="4"></td></tr>';
                                $g++;
                            } ?>
                            <tr data-user="<?php echo $inscripcion->user_id; ?>" data-inscripcion_id="<?php echo $inscripcion->inscripcion_id; ?>">
                                <td><?php echo $inscripcion->orden; ?></td>
                                <td colspan="2" class="text-nowrap"><?php echo $inscripcion->first_name; ?> <?php echo $inscripcion->last_name; ?></td>
                                <td><?php echo $inscripcion->nombre; ?></td>
                            </tr>
                        <?php
                            $i++;
                            if (count($ordenparticipacion['ordenados']) == $i) {
                                echo '</tbody></table></div>';
                            }
                        }
                        ?>
                    </div>
                    <hr>
                    <h4>Eliminatorias</h4>
                    <div id="faseeliminatorias">
                        <div class="brackets"></div>
                    </div>
                    <button type="button" data-guardar-liguilla="<?php echo $competicion->competicion_torneo_id; ?>" class="btn btn-sm btn-default m-3">Guardar grupos</button>
                <?php } elseif($competicion->tipo == 'eliminatoria'){?>
                    `<h4 class="card-title d-flex justify-content-between align-items-center">Eliminatorias <span id="totalcomabtes"></span></h4>
                <div id="faseeliminatorias">
                    <?php if(count($ordenparticipacion['ordenados']) > 0){
                        $total = count($ordenparticipacion['ordenados']);
                    }?>
                    <div class="brackets" data-inscritos-eliminatoria="<?php echo $competicion->competicion_torneo_id; ?>"></div>
                </div>
                <button type="button" data-guardar-eliminatoria="<?php echo $competicion->competicion_torneo_id; ?>" class="btn btn-sm btn-default m-3">Guardar eliminatorias</button>
                <?php } ?>
            </div>
        </div>
    </div>
</div>