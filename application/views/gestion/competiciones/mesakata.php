<?php if($this->user->group->id > 3) { ?>
    <div class="card">
			<div class="card-header">
				<ul class="nav nav-pills nav-secondary nav-pills-no-bd" id="pills-tab-without-border" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" id="pills-sorteos-tab" href="<?php echo base_url();?>Torneos/competiciones/<?php echo $torneo->slug;?>" role="tab" >Competiciones</a>
					</li>
				</ul>
			</div>
		</div>

<?php } ?>


<div class="card">
    <div class="card-header d-flex justify-content-between">
        <div class="card-title fw-mediumbold">Tablero competición</div>
        <button class="btn btn-icon btn-primary btn-round btn-sm" data-toggle-custom-template data-toggle="tooltip" title="Ver clasificacion">
            <i class="fas fa-list"></i>
        </button>

        <a href="<?php echo base_url();?>Competiciones/pdfdoc/<?php echo $competicion->competicion_torneo_id;?>" class="btn btn-icon btn-primary btn-round btn-xs" title="Generar documento">
            <i class="fas fa-file-export"></i>
        </a>
        <button class="btn btn-icon btn-primary btn-round btn-xs d-none" onclick="imprSelec('tablero-competicion', '<?php echo $competicion->modalidad.'_'.$competicion->categoria.'_'.$competicion->nivel;?>_<?php echo ($competicion->genero == 'M') ? 'Masculino' : (($competicion->genero == 'F') ? 'Femenino' : 'Mixto');?>')" title="Generar tablero">
                    <i class="fas fa-print"></i>
                </button>
    </div>
    <div class="card-body p-1 bg-white" id="tablero-competicion">
        <div class="table-responsive">
            <table class="table table-striped table-bordered text-center" id="tablakata" data-competicion="<?php echo $competicion->competicion_torneo_id; ?>">
                <thead>
                    <tr>
                        <?php if($this->ion_auth->in_group([1,2,3])){?>
                            <th class="bg-white text-primary"><button type="button" class="btn btn-sm btn-secondary"><i class="fas fa-file-export"></i></button></th>
                        <?php } ?>
                        <th class="bg-white text-primary">#</th>
                        <th class="bg-white text-primary text-left columnfixed" colspan="2">Deportista</th>
                        <th class="bg-white text-primary" colspan="3">Ronda 1</th>
                        <th class="bg-white text-primary" colspan="3">Ronda 2</th>
                        <?php /*<th class="bg-white text-primary" colspan="4">Ronda 3</th>*/ ?>
                        <th class="bg-white text-primary">Total</th>
                        <th class="bg-white text-primary">Media</th>
                    </tr>
                    <tr>
                        <?php if($this->ion_auth->in_group([1,2,3])){?>
                            <th class="text-center">
                                <input type="checkbox" name="all" data-toggle="tooltip" title="Marcar / desmarcar todos">
                            </th>
                        <?php } ?>
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
                            <?php if($this->ion_auth->in_group([1,2,3])){?>
                                <td class="text-center">
                                
                                <input type="checkbox" name="exportpdf[]" id="export_g_<?php echo $value->user_id; ?>" value="<?php echo $value->user_id; ?>">
                                
                                </td>
                            <?php } ?>
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

    <div class="card-header d-flex justify-content-between">
        <div class="card-title fw-mediumbold w-100"><a href="<?php echo base_url();?>Competiciones/FinalizarCompeticion/<?php echo $competicion->competicion_torneo_id; ?>" class="btn btn-primary text-white rounded">Finalizar competición</a></div>
    </div>
</div>


<?php echo form_open();
echo form_close(); ?>

<div class="custom-template">
    <div class="title d-flex justify-content-between">Clasificación <button class="btn btn-info btn-sm btn-rounded" data-toggle-custom-template><i class="fas fa-arrow-right"></i></button></div>
    <div class="custom-content">
        <div class="m-3">
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