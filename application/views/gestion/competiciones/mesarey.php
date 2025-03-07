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
	<input type="hidden" id="competicion_torneo_id" value="<?= $competicion->competicion_torneo_id; ?>">
	<div class="card-header d-flex justify-content-between">
		<div class="card-title fw-mediumbold">Tablero competición</div>
		<?php if ($this->ion_auth->in_group([1, 2, 3])) { ?>
			<a href="<?php echo base_url(); ?>Competiciones/pdfdoc/<?php echo $competicion->competicion_torneo_id; ?>" target="_blankl" class="btn btn-icon btn-primary btn-round btn-xs" title="Guardar imagen tablero" target="_blank">
				<i class="fas fa-file-pdf"></i>
			</a>
		<?php } ?>
		<button type="button" id="toggle-marcador-btn" class="btn btn-primary float-right">Abrir marcador en segunda pantalla</button>
	</div>
	<div class="card-body" id="tablero-competicion">
		<ul class="nav nav-pills nav-secondary nav-pills-no-bd mb-3" role="tablist">
			<li class="nav-item submenu">
				<a class="nav-link <?php echo ($tipo == 'grupos') ? 'active show' : ''; ?>" href="<?php echo base_url(); ?>Competiciones/mesa/<?php echo $competicion->competicion_torneo_id; ?>/grupos">Fase de grupos</a>
			</li>
			<li class="nav-item submenu">
				<a class="nav-link <?php echo ($tipo == 'eliminatorias') ? 'active show' : ''; ?>" href="<?php echo base_url(); ?>Competiciones/mesa/<?php echo $competicion->competicion_torneo_id; ?>/eliminatorias" role="tab">Eliminatorias</a>
			</li>
		</ul>

		<div id="content_print">
			
			<?php if ($tipo == 'grupos') {
				foreach ($grupos as $key => $grupo) { ?>
					<div class="mb-5">
						<h3>Grupo <?= $key ?></h3>
						<div class="row">
							<div class="col-md-6">
								<div class="table-responsive">
									<table class="table table-striped table-bordered text-center" id="tablarey<?= $key ?>" data-competicion="<?php echo $competicion->competicion_torneo_id; ?>">
										<thead>
											<tr>
												<th class="bg-white text-primary text-left columnfixed">Deportista</th>
												<th class="bg-white text-primary">V</th>
												<th class="bg-white text-primary">E</th>
												<th class="bg-white text-primary">D</th>
												<th class="bg-white text-primary">Pt.F</th>
												<th class="bg-white text-primary">Com.</th>
												<th class="bg-white text-primary">Desem.</th>
												<th class="bg-white text-primary">Total</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($grupo as $k => $value) { ?>
												<tr data-user_id="<?php echo $value->user_id; ?>" data-user-name="<?php echo $value->first_name; ?> <?php echo $value->last_name; ?>">
													<td class="text-left text-nowrap columnfixed">
														<button type="button" class="btn btn-default p-1 rounded btn-block" data-inscripcion="<?php echo $value->inscripcion_id; ?>"><?php echo $value->first_name; ?> <?php echo $value->last_name; ?></button>
													</td>

													<td <?=$this->ion_auth->in_group([1, 2, 3]) ? 'data-editable':''?> data-field="victorias" data-valor="100">0</td>
													<td <?=$this->ion_auth->in_group([1, 2, 3]) ? 'data-editable':''?> data-field="empates" data-valor="50">0</td>
													<td <?=$this->ion_auth->in_group([1, 2, 3]) ? 'data-editable':''?> data-field="derrotas" data-valor="0">0</td>
													<td <?=$this->ion_auth->in_group([1, 2, 3]) ? 'data-editable':''?> data-field="puntos_favor" data-valor="1">0</td>
													<td <?=$this->ion_auth->in_group([1, 2, 3]) ? 'data-editable':''?> data-field="total_combates">0</td>
													<td <?=$this->ion_auth->in_group([1, 2, 3]) ? 'data-editable':''?> data-field="penalizaciones">0</td>
													<td <?=$this->ion_auth->in_group([1, 2, 3]) ? 'data-editable':''?> data-field="puntos_total">0</td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
								<hr>
								<div class="table-responsive">
									<table class="table table-striped table-bordered text-center w-auto" id="clasificacion_grupo_<?= $key ?>">
										<thead>
											<tr>
												<th class="bg-white text-primary" colspan="9">
													<div class="d-flex justify-content-between">
														<h4>Clasificación </h4>
														<?php if ($this->ion_auth->in_group([1, 2, 3])) { ?>
															<button class="btn btn-primary btn-sm ml-3 mb-2" data-guardar-clasificaicon="<?php echo $competicion->competicion_torneo_id; ?>" data-grupo="<?= $key ?>" data-toggle="tooltip" title="Confirmar clasificación y enviar clasificados a las eliminatorias">
																<i class="fas fa-code-branch"></i> Confirmar clasificados
															</button>
														<?php } ?>

													</div>
												</th>
											</tr>
											<tr>
												<th>#</th>
												<th class="text-left">Deportista</th>
												<th class="text-left">Equipo</th>
												<th class="text-left">Total</th>
												<th class="">V</th>
												<th class="">E</th>
												<th class="">D</th>
												<th class="">Pun. favor</th>
												<th class="">Nº C.</th>
											</tr>
										</thead>
										<tbody data-competicion_torneo_id="<?php echo $competicion->competicion_torneo_id; ?>" data-grupo="<?= $key ?>">

										</tbody>
									</table>
								</div>

							</div>

							<div class="col-md-6">
								<div class="row">
									<div class="col-sm-5">
										<div class="form-group">
											<label for="ao">AO - Azul</label>
											<select class="form-control select2" id="add_ao" name="add_ao" data-placeholder="Deportista Azul">
												<option></option>
												<?php foreach ($grupo as $k => $value) { ?>
													<option value="<?php echo $value->user_id; ?>" data-inscripcion="<?php echo $value->inscripcion_id; ?>"><?php echo $value->first_name; ?> <?php echo $value->last_name; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="col-sm-5">
										<div class="form-group">
											<label for="ao">AKA - Rojo</label>
											<select class="form-control select2" id="add_aka" name="ad_aka" data-placeholder="Deportista Rojo">
												<option></option>
												<?php foreach ($grupo as $k => $value) { ?>
													<option value="<?php echo $value->user_id; ?>" data-inscripcion="<?php echo $value->inscripcion_id; ?>"><?php echo $value->first_name; ?> <?php echo $value->last_name; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="col-sm-2 align-content-end mb-3">
										<button type="button" class="btn btn-sm btn-primary" crear_combate_rey="true" data-competicion_torneo_id="<?php echo $competicion->competicion_torneo_id; ?>" data-grupo="<?= $key ?>">Crear</button>
									</div>
								</div>
								

								<table class="table" id="matches_rey_<?= $key ?>">
									<thead>
										<tr>
											<th>ID</th>
											<th style="text-align:left; font-size:14px;">AO</th>
											<th colspan="2"></th>
											<th style="text-align:right; font-size:14px;">AKA</th>
											<th>T</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				<?php } ?>

				<?php /*
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
												<th class="text-left">Total</th>
												<th class="text-left">Victorias</th>
												<th class="text-left">Empates</th>
												<th class="text-left">Derrotas</th>
												<th class="text-left">Puntos favor</th>
												<th class="text-left">Nº Combates</th>
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
				*/ ?>

			<?php } ?>

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
									<?php foreach ($eliminatoria as $match) { ?>
										<ul class="list-group p-0 match btn btn-link" data-manage-match="<?php echo $match->match_id; ?>" <?= ($match->user_rojo == 0 || $match->user_azul == 0) ? 'style="pointer-events: none"' : '' ?>>

											<li class="list-group-item d-flex justify-content-between align-items-center px-2 py-1" style="background: red;" data-user="<?php echo $match->user_rojo; ?>">
												<span class="text-white text-truncate text-left" style="width:calc(100% - 30px);"><?php echo (isset($match->rojo)) ? $match->rojo->nombre : ''; ?></span>
												<span class="bg-white <?php echo ($match->hantei == 'rojo') ? 'hantei' : ''; ?> <?php echo ($match->senshu == 'rojo') ? 'senshu' : ''; ?>" style="width:25px;"><?php echo (isset($match->rojo)) ? $match->puntos_rojo : 0; ?></span>
											</li>

											<li class="list-group-item d-flex justify-content-between align-items-center px-2 py-1" style="background: blue;" data-user="<?php echo $match->user_azul; ?>">
												<span class="text-white text-truncate text-left"><?php echo (isset($match->azul)) ? $match->azul->nombre : ''; ?></span>
												<span class="bg-white <?php echo ($match->hantei == 'azul') ? 'hantei' : ''; ?> <?php echo ($match->senshu == 'azul') ? 'senshu' : ''; ?>" style="width:25px;"><?php echo (isset($match->azul)) ? $match->puntos_azul : 0; ?></span>
											</li>
										</ul>
									<?php } ?>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
				<?php if ($this->ion_auth->in_group([1, 2, 3])) { ?>
					<div class="card-header d-flex justify-content-between">
						<div class="card-title fw-mediumbold w-100"><a data-finalizar-competicion href="<?php echo base_url(); ?>Competiciones/FinalizarCompeticion/<?php echo $competicion->competicion_torneo_id; ?>" class="btn btn-primary text-white rounded">Finalizar competición</a></div>
					</div>
				<?php } ?>
				<?php // $this->load->view('gestion/competiciones/marcadorauxiliar'); ?>
			<?php } ?>
			<?php $this->load->view('gestion/competiciones/marcadorauxiliarrey'); ?>
		</div>
	</div>
</div>

<?php echo form_open();
echo form_close(); ?>