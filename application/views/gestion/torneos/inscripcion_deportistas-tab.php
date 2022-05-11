<div class="card mt--3">
    <div class="card-header">
        <div class="d-flex align-items-center">
        <h4 class="card-title"><?php echo $torneo->titulo;?></h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="display table table-striped table-sm w-100 datatable">
                    <thead>
                        <tr>
                            <th>Deportista</th>
                            <th>Año naciemiento</th>
                            <th>Peso</th>
                            <?php if($torneo->tipo != 2) { ?>
                                <th>Competición de KATA</th>
                            <?php } ?>
                            <?php if($torneo->tipo != 1) { ?>
                                <th>Competicion de KUMITE</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($deportistas as $d => $dep) { ?>
                            <tr data-dep="<?php echo $dep->id;?>" data-kata-original="0" data-kumite-original="0"  data-kata-actual="0" data-kumite-actual="0" <?php if(array_key_exists($dep->id, $inscripciones) && array_key_exists('blocked', $inscripciones[$dep->id])){ echo 'blocked="'.$inscripciones[$dep->id]['blocked'].'"';}?>>
                            <td><?php echo $dep->first_name.' '.$dep->last_name;?></td>
                            <td><?php echo date('Y', strtotime($dep->dob));?></td>
                            <td><?php echo $dep->peso;?>Kg</td>
                            <?php if($torneo->tipo != 2) { ?>
                                <td>
                                    <select name="competicion_torneo_id" data-modalidad="kata" id="kata_competicion_torneo_id_<?php echo $dep->id;?>" class="form-control form-control-sm my-2">
                                        <option value="0">No participa</option>
                                        <?php foreach ($competicioneskata as $k => $kata) {?>
                                            <option value="<?php echo $kata->competicion_torneo_id;?>" <?php if(array_key_exists($dep->id, $inscripciones) && in_array($kata->competicion_torneo_id, $inscripciones[$dep->id])){ echo 'selected';}?>>
                                                <?php echo $kata->categoria;?> <?php echo ($kata->genero == 'M') ? 'Masculino' : (($kata->genero == 'F') ? 'Femenino' : 'Mixto');?> <?php echo $kata->nivel;?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </td>
                            <?php } ?>
                            <?php if($torneo->tipo != 1) { ?>
                                <td>
                                    <select name="competicion_torneo_id" data-modalidad="kumite" id="kumite_competicion_torneo_id_<?php echo $dep->id;?>" class="form-control form-control-sm my-2">
                                        <option value="0">No participa</option>
                                        <?php foreach ($competicioneskumite as $k => $kumite) {?>
                                            <option value="<?php echo $kumite->competicion_torneo_id;?>" <?php if(array_key_exists($dep->id, $inscripciones) && in_array($kumite->competicion_torneo_id, $inscripciones[$dep->id])){ echo 'selected';}?>>
                                                <?php echo $kumite->categoria;?> <?php echo ($kumite->genero == 'M') ? 'Masculino' : (($kumite->genero == 'F') ? 'Femenino' : 'Mixto');?> <?php echo $kumite->nivel;?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php echo form_open();
    echo form_hidden('torneo_id', $torneo->torneo_id);
    echo form_close(); ?>
</div>