<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<input type="hidden" id="competicion_torneo_id" value="<?= $competicion->competicion_torneo_id; ?>">
<section class="portfolio mt-3" id="portfolio">
	<div class="container-fluid">
		<div class="row ">
			<div class="title text-center w-100">
				<h2 class="text-uppercase"><?php echo $competicion->modalidad; ?></h2>
				<p>Esta categoría no ha sido sorteada</p>
				
				<div class="border"></div>
			</div>
		</div>
	</div>
</section>
