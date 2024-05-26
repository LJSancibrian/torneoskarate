<?php if ($this->user->group->id > 3) { ?>
	<div class="card">
		<div class="card-header">
			<ul class="nav nav-pills nav-secondary nav-pills-no-bd" id="pills-tab-without-border" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" id="pills-sorteos-tab" href="<?php echo base_url(); ?>Torneos/competiciones/<?php echo $torneo->slug; ?>" role="tab">Competiciones</a>
				</li>
			</ul>
		</div>
	</div>
<?php } ?>


<div class="card">
	<div class="card-header d-flex justify-content-between">
		<div class="card-title fw-mediumbold">Tablero competición</div>
		<button class="btn btn-icon btn-primary btn-round btn-sm" data-ver-clasificacion data-toggle="tooltip" title="Ver clasificacion">
			<i class="fas fa-list"></i>
		</button>

		<a href="<?php echo base_url(); ?>Competiciones/pdfdoc/<?php echo $competicion->competicion_torneo_id; ?>" target="_blankl" class="btn btn-icon btn-primary btn-round btn-xs" title="Guardar imagen tablero" target="_blank">
			<i class="fas fa-file-pdf"></i>
		</a>
	</div>
	<div class="card-body p-1 bg-white" id="tablero-competicion">


		
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
                        <table class="table table-striped table-bordered text-center" id="tablakata_<?=$grupo?>" data-competicion="<?php echo $competicion->competicion_torneo_id; ?>">
							<thead>
								<tr>
									<th class="bg-white text-primary">#</th>
									<th class="bg-white text-primary text-left columnfixed" colspan="2">Deportista</th>
									<?php for ($i=1; $i <= $rondaspuntos; $i++) { ?>
										<th class="bg-white text-primary" colspan="2">Ronda <?=$i?></th>
									<?php } ?>
									<th class="bg-white text-primary">Total</th>
									<th class="bg-white text-primary">Media</th>
								</tr>
								<tr>
									<th class="">#</th>
									<th colspan="2" class="text-left columnfixed">Deportista</th>
									<?php for ($i=1; $i <= $rondaspuntos; $i++) { ?>
										<th>J1</th>
										<th>J2</th>
									<?php } ?>
									<th>Total</th>
									<th>Media</th>
								</tr>
							</thead>
            	            <tbody>
                <?php } ?>
				<tr data-user_id="<?php echo $value->user_id; ?>">
					<td class=""><?php echo $value->orden; ?></td>
					<td colspan="2" class="text-left text-nowrap columnfixed"><button type="button" class="btn btn-default p-1 rounded btn-block" data-inscripcion="<?php echo $value->inscripcion_id; ?>"><?php echo $value->first_name; ?> <?php echo $value->last_name; ?></button></td>

					<?php for ($i=1; $i <= $rondaspuntos; $i++) { ?>								
						<td data-ronda="<?=$i?>" data-j="1"></td>
						<td data-ronda="<?=$i?>" data-j="2"></td>
						<td data-media="<?=$i?>" class="bg-success text-white d-none">0</td>
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
			<table class="table table-striped table-bordered text-center" id="tablakata" data-competicion="<?php echo $competicion->competicion_torneo_id; ?>">
				<thead>
					<tr>
						<th class="bg-white text-primary">#</th>
						<th class="bg-white text-primary text-left columnfixed" colspan="2">Deportista</th>
						<?php for ($i=1; $i <= $rondaspuntos; $i++) { ?>
							<th class="bg-white text-primary" colspan="2">Ronda <?=$i?></th>
						<?php } ?>
						<th class="bg-white text-primary">Total</th>
						<th class="bg-white text-primary">Media</th>
					</tr>
					<tr>
						<th class="">#</th>
						<th colspan="2" class="text-left columnfixed">Deportista</th>
						<?php for ($i=1; $i <= $rondaspuntos; $i++) { ?>
							<th>J1</th>
							<th>J2</th>
						<?php } ?>
						<th>Total</th>
						<th>Media</th>
					</tr>
				</thead>
				<tbody>
					
						<tr data-user_id="<?php echo $value->user_id; ?>">
							<td class=""><?php echo $value->orden; ?></td>
							<td colspan="2" class="text-left text-nowrap columnfixed"><button type="button" class="btn btn-default p-1 rounded btn-block" data-inscripcion="<?php echo $value->inscripcion_id; ?>"><?php echo $value->first_name; ?> <?php echo $value->last_name; ?></button></td>

							<?php for ($i=1; $i <= $rondaspuntos; $i++) { ?>
								
								<td data-ronda="<?=$i?>" data-j="1"></td>
								<td data-ronda="<?=$i?>" data-j="2"></td>
								<td data-media="<?=$i?>" class="bg-success text-white d-none">0</td>
							<?php } ?>

							<td data-total></td>
							<td data-media-total class="bg-primary text-white">0</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
			</div>
			*/?>
		
	</div>

	<div class="card-header d-flex justify-content-between">
		<div class="card-title fw-mediumbold">RONDA FINAL</div>
		<button class="btn btn-primary btn-round btn-sm" onclick="openFullscreenWindow()"><i class="fas fa-desktop"></i> Abrir ventana en pantalla secundaria</button>
	</div>

	<div class="card-body p-1 bg-white" id="tablero-competicion-2">
		<div class="row">
			<div class="col-md-4">
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
									<td data-ronda="<?= $rondaspuntos + 1 ?>" data-j="1"></td>
									<td data-ronda="<?= $rondaspuntos + 1 ?>" data-j="2"></td>
									<td data-ronda="<?= $rondaspuntos + 1 ?>" data-j="3"></td>
									<td data-total></td>
									<td data-media-total class="bg-primary text-white">0</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-md-8">
				<div class="table-responsive">
					<table class="table table-striped w-100" id="clasificaionkatafinal">
						<thead>
							<tr>
								<th>#</th>
								<th class="columnfixed">Deportista</th>
								<th>Equipo</th>
								<th>Puntos</th>
								<th>Max 1</th>
								<th>Max.2</th>
								<th>Max 3</th>
								<th>Media</th>
							</tr>
						</thead>
						<tbody id="clasificacion_final_competicion"></tbody>
					</table>
				</div>
			</div>
		</div>

	</div>

	<div class="modal fade" id="clasificaciongrupo" tabindex="-1" role="dialog" aria-labelledby="clasificaciongrupoLabel">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Clasificación</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
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
					<?php for ($i=1; $i <= $grupo; $i++) { ?>
						
					
						<div class="table-responsive" style="overflow: auto;min-height: 200px;">
						<h4>Grupo <?=$i?></h4>
							<table class="table table-striped w-100" id="clasificaicon_grupo_<?=$i?>">
								<thead style="position: sticky; top: 0; z-index: 1;">
									<tr>
										<th>#</th>
										<th class="columnfixed">Deportista</th>
										<th>Equipo</th>
										<th>Puntos</th>
										<th>Media</th>
										<th>Max 1</th>
										<th>Max.2</th>
										<th>Max 3</th>
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

	<div class="card-header d-flex justify-content-between">
		<div class="card-title fw-mediumbold w-100"><a data-finalizar-competicion href="<?php echo base_url(); ?>Competiciones/FinalizarCompeticion/<?php echo $competicion->competicion_torneo_id; ?>" class="btn btn-primary text-white rounded">Finalizar competición</a></div>
	</div>


</div>

<?php echo form_open();
echo form_close(); ?>