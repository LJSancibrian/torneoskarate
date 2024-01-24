<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<input type="hidden" id="competicion_torneo_id" value="<?= $competicion->competicion_torneo_id; ?>">
<section class="portfolio mt-3" id="portfolio">
	<div class="container-fluid">
		<div class="row ">
			<div class="title text-center w-100">
				<h2 class="text-uppercase"><?php echo $competicion->modalidad; ?></h2>
				<p>Rey de la pista.</p>
				<p>ganador pasa al siguiente combate guardando las penalizaciones. Cada victoria suma 100 puntos. Cada empate suma 50 puntos. Los puntos obtenidos en el combate sambien suman para la puntuación total.</p>
				<div class="border"></div>
			</div>
		</div>
	</div>
</section>

<section class="pricing-table pt-0" competicion_torneo_id="<?php echo $competicion->competicion_torneo_id; ?>">
	<div class="container-fluid">
		<ul class="nav nav-pills justify-content-center" id="torneotabs" role="tablist">
			<li class="nav-item">
				<a class="nav-link" role="tab" id="info-tab" href="<?php echo base_url(); ?>torneo/<?php echo $torneo->slug; ?>#info">Info</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" role="tab" id="competiciones-tab" href="<?php echo base_url(); ?>torneo/<?php echo $torneo->slug; ?>#competiciones">Competiciones</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="medallero-tab" href="<?php echo base_url(); ?>torneo/<?php echo $torneo->slug; ?>#medallero" role="tab">Medallero</a>
			</li>
		</ul>
		<div class="blog-slider">
			<div class="blog-slider__content w-100 p-0">
				<div class="price-title">
					<strong class="value p-0"><?php echo $competicion->categoria . ' ' . $competicion->nivel; ?></strong>
				</div>
			</div>
			<div class="service-2">
			
				<?php if (count($grupos) > 0) {
					foreach ($grupos as $key => $grupo) { ?>
						<div class="service-item p-3 mb-3">
							<div class="tab-content">
								<div class="tab-pane active">
									<h4>Grupo <?= $key ?></h3>
										<div class="table-responsive">
											<table class="table table-striped table-bordered text-center" id="clasificacion_grupo_<?= $key ?>">
												<thead>
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
												<?php foreach ($players[$key] as $p => $player) { ?>
													<tr>
														<td><?= $p + 1?></td>
														<td class=""><?=$player->first_name.' '.$player->last_name?></td>
														<td class=""><?=$player->nombre?></td>
														<td class=""><?=$player->puntos_total?></td>
														<td class=""><?=$player->victorias?></td>
														<td class=""><?=$player->empates?></td>
														<td class=""><?=$player->derrotas?></td>
														<td class=""><?=$player->puntos_favor?></td>
														<td class=""><?=$player->total_combates?></td>
													</tr>
												<?php } ?>
												</tbody>
											</table>
										</div>
								</div>
							</div>
						</div>
					<?php } ?>
				<?php } ?>


				<?php if (count($eliminatorias) > 0) { ?>
					<div class="service-item p-3 mb-3">
						<h4>Eliminatorias</h4>
						<div class="row">
							<?php foreach ($eliminatorias as $key => $eliminatoria) {
								if ($key  == count($eliminatorias)) {
									$ronda = 'FINAL';
								} elseif ($key  == count($eliminatorias) - 1) {
									$ronda = 'SEMI - FINAL';
								} else {
									$ronda = 'Ronda ' . $key;
								} ?>

								<?php switch (count($eliminatorias)) {
									case 1:
										$class = "col-12 round";
										break;
									case 2:
										$class = "col-md-4 round";
										break;
									case 3:
										$class = "col-md-4 round";
										break;
									default:
										$class = "col-md-4 col-lg-3 round";
										break;
								} ?>
								<div class="<?php echo $class; ?>">
									<h4 class="bg-primary text-white p-2 mb-3"><?php echo $ronda; ?></h4>
									<div>
										<?php foreach ($eliminatoria as $match) { ?>
											<ul class="list-group mb-3 p-0 match" data-match_id="<?php echo $match->match_id; ?>">
												<li class="list-group-item d-flex justify-content-between align-items-center px-2 py-1 <?php echo ($match->senshu == 'rojo') ? 'senshu' : (($match->hantei == 'rojo') ? 'hantei' : ''); ?>" style="background: red;" data-user="<?php echo $match->user_rojo; ?>">
													<span class="text-white text-truncate text-left" style="width:calc(100% - 30px);"><?php echo (isset($match->rojo)) ? $match->rojo->nombre : ''; ?></span>
													<span class="font-weight-bold text-white" style="font-size:1.5rem"><?php echo $match->puntos_rojo; ?></span>
												</li>

												<li class="list-group-item d-flex justify-content-between align-items-center px-2 py-1 <?php echo ($match->senshu == 'azul') ? 'senshu' : (($match->hantei == 'azul') ? 'hantei' : ''); ?>" style="background: blue;" data-user="<?php echo $match->user_azul; ?>">
													<span class="text-white text-truncate text-left"><?php echo (isset($match->azul)) ? $match->azul->nombre : ''; ?></span>
													<span class="font-weight-bold text-white" style="font-size:1.5rem"><?php echo $match->puntos_azul; ?></span>
												</li>
											</ul>
										<?php } ?>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</section>

<div class="modal fade" id="clasificacionkata_modal" tabindex="-1" aria-labelledby="clasificacionkata_modal" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<div class="card-title fw-mediumbold">Clasificación</div>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
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
</div>