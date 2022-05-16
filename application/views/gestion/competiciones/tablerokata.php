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
        <div class="card" id="div_principal">
            <div class="card-header d-flex justify-content-between">
                <div class="card-title fw-mediumbold text-uppercase"><?php echo $competicion->modalidad.' '.$competicion->categoria.' '.$competicion->nivel;?> - <?php echo ($competicion->genero == 'M') ? 'Masculino' : (($competicion->genero == 'F') ? 'Femenino' : 'Mixto');?></div>
                <button class="btn btn-icon btn-primary btn-round btn-xs" data-generar-tablero="<?php echo $competicion->competicion_torneo_id; ?>" data-toggle="tooltip" title="Generar tablero">
                    <i class="fas fa-random"></i>
                </button>
                <a href="<?php echo base_url();?>Competiciones/pdfdoc/<?php echo $competicion->competicion_torneo_id;?>" target="_blankl" class="btn btn-icon btn-primary btn-round btn-xs" title="Guardar imagen tablero" target="_blank">
                    <i class="fas fa-file-pdf"></i>
                </a>
            </div>
            <div class="card-body bg-white" id="tablero-competicion">
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
                <button type="button" data-guardar-orden-kata="<?php echo $competicion->competicion_torneo_id; ?>" class="btn btn-sm btn-default m-3">Guardar orden</button>
            </div>
        </div>
    </div>
</div>