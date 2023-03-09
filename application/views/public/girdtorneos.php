<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<section class="posts section">
	<div class="container">
		<div class="col-md-12 text-center d-none">
			<h2>CLASIFICACIONES LIGA MUNICIPAL 2022</h2>
			<a href="<?php echo base_url(); ?>ligamunicipal2022" class="btn btn-main">Clasificaciones generales</a>
		</div>

		<?php if (count($proximos_torneos) > 0) { ?>
			<h3 class="text-center mb-3">Próximos torneos</h3>
			<div class="row">
				<?php foreach ($proximos_torneos as $key => $torneo) {
					$datos['torneo'] = $torneo;
					$this->load->view('public/secciones/carousel_torneos', $datos);
				} ?>
			</div>
		<?php } ?>

		<?php if (count($torneos_pasados) > 0) { ?>
			<div class="mt-5">
				<h3 class="text-center mb-3">Torneos ya finalizados</h3>
				<div class="row">
					<?php foreach ($torneos_pasados as $key => $torneo) { ?>
						<div class="col-md-4">
							<?php $datos['torneo'] = $torneo;
							$this->load->view('public/secciones/carousel_torneos_pasados', $datos);
							?>
						</div>
					<?php } ?>
				</div>
			</div>
		<?php } ?>
	</div>
</section>