<div class="card mt--3">
    <div class="card-body">
        
        <div class="row">
			<?php foreach ($torneos as $key => $torneo) { ?>
				<div class="col-md-6">
					<a href="<?=base_url()?>torneos/competiciones/<?=$torneo->slug ?>" class="btn btn-block border <?=($torneo->fecha == date('Y-m-d'))?'btn-primary':''?>">
						<h4><?=$torneo->titulo?></h4>
						<p class="mb-0"><?= fechaES($torneo->fecha, 1)?></p>
					</a>
				</div>
			<?php } ?>
		</div>
    </div>
</div>