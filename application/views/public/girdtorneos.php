<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<section class="posts section">
	<div class="container">

		<?php if(count($grupos) > 0){ ?>
			<div class="col-md-12 text-center mb-3">
				<?php 
				$grupos_array = [] ;
				foreach ($grupos as $key => $grupo) {
					if(!in_array($grupo->grupo_id, $grupos_array)){ 
						$grupos_array[] = $grupo->grupo_id;?>
						<a href="<?php echo base_url(); ?>clasificaciongrupo/<?=$grupo->grupo_id?>" class="btn btn-main m-2">Clasificaciones <?=$grupo->titulogrupo?></a>
					<?php }
				} ?>
			</div>
		<?php } ?>

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