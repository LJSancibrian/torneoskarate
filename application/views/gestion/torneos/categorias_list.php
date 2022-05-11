<div class="row">
    <?php if($torneo->tipo != 2){?>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="border-bottom card-title font-weight-bold mb-4 text-uppercase">Kata</h4>
                <table class="table table-border">
                    <thead>
                        <tr>
                            <th>Categoría</th>
                            <th>Género</th>
                            <th>Peso / nivel</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($competicioneskata as $key => $value) { ?>
                            <tr>
                                <td><?php echo $value->categoria; ?></td>
                                <td><?php echo ($value->genero == 'M') ? "Masculino" : (($value->genero == 'F') ? "Femenino" : "Mixto"); ?></td>
                                <td><?php echo $value->nivel; ?></td>
                                <td>

                                <?php /* condicionar aqui a que si ha pasado, se muestre solo el link a la clasificacion y si no ha pasado, que muestre el botón para el cuadro de emparejamiento, si existe */ ?>
                                    

                                    <button type="button" class="btn btn-sm btn-icon btn-round btn-primary" onclick="window.open('<?php echo base_url();?>Competiciones/clasificacionCompeticionKata/<?php echo $value->competicion_torneo_id;?>', '_blank')" rel="noopener noreferrer" data-toggle="tooltip" title="Ver competición"><i class="fas fa-list-ol"></i></button>
                                </td>
                            </tr>  
                        <?php } ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php if($torneo->tipo != 1){?>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="border-bottom card-title font-weight-bold mb-4 text-uppercase">KUMITE</h4>
                    <table class="table table-border">
                    <thead>
                        <tr>
                            <th>Categoría</th>
                            <th>Género</th>
                            <th>Peso / nivel</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($competicioneskumite as $key => $value) { ?>
                            <tr>
                                <td><?php echo $value->categoria; ?></td>
                                <td><?php echo ($value->genero == 'M') ? "Masculino" : (($value->genero == 'F') ? "Femenino" : "Mixto"); ?></td>
                                <td><?php echo $value->nivel; ?></td>
                                <td>
                                <button type="button" class="btn btn-sm btn-icon btn-round btn-primary" onclick="window.open('<?php echo base_url();?>Competiciones/eliminatoriasCompeticionKumite/<?php echo $value->competicion_torneo_id;?>', '_blank')" rel="noopener noreferrer" data-toggle="tooltip" title="Ver competición"><i class="fas fas fa-project-diagram"></i></button>
                                <button type="button" class="btn btn-sm btn-icon btn-round btn-primary" onclick="window.open('<?php echo base_url();?>Competiciones/clasificacionCompeticionKumite/<?php echo $value->competicion_torneo_id;?>', '_blank')" rel="noopener noreferrer" data-toogle="tooltip" title="Ver competición"><i class="fas fa-list-ol"></i></button>
                            </td>
                            </tr>  
                        <?php } ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    <?php } ?>
</div>