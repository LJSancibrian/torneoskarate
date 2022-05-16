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
        <div class="card" id="tablero-competicion-card">
            <div class="card-header d-flex justify-content-between">
                <div class="card-title fw-mediumbold"><?php echo $competicion->modalidad.' '.$competicion->categoria.' '.$competicion->nivel;?> - <?php echo ($competicion->genero == 'M') ? 'Masculino' : (($competicion->genero == 'F') ? 'Femenino' : 'Mixto');?></div>
                <button class="btn btn-icon btn-primary btn-round btn-xs" data-generar-tablero="<?php echo $competicion->competicion_torneo_id; ?>" data-toggle="tooltip" title="Generar tablero">
                    <i class="fas fa-random"></i>
                </button>
                <a href="<?php echo base_url();?>Competiciones/pdfdoc/<?php echo $competicion->competicion_torneo_id;?>" target="_blankl" class="btn btn-icon btn-primary btn-round btn-xs" title="Guardar imagen tablero" target="_blank">
                    <i class="fas fa-file-pdf"></i>
                </a>
            </div>
            <div class="card-body bg-white" id="tablero-competicion">
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
                            <div class="border-bottom d-flex flex-row justify-content-start my-3" id="grupokumite_' . chr(64 + $inscripcion->grupo) . '" grupo="' . chr(64 + $inscripcion->grupo) . '">
                                <table class="table table-striped table-bordered text-center w-auto" id="tablakumite_' . chr(64 + $inscripcion->grupo) . '">
                                    <thead>
                                        <tr>
                                            <th colspan="4" class="bg-white text-primary font-weigth-bold">GRUPO ' . chr(64 + $inscripcion->grupo) . '</th>
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
                <button type="button" data-guardar-orden-kumite="<?php echo $competicion->competicion_torneo_id; ?>" class="btn btn-sm btn-default m-3">Guardar grupos</button>
            </div>
        </div>
    </div>
</div>

<div class="custom-template">
    <div class="title d-flex justify-content-between">Horarios <button class="btn btn-info btn-sm btn-rounded" data-toggle-custom-template><i class="fas fa-arrow-right"></i></button></div>
    <div class="custom-content">
        <div class="m-3">
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