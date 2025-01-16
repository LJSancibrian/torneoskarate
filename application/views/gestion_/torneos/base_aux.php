<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<ul class="justify-content-center nav nav-pills nav-pills-no-bd nav-secondary" id="pills-tab-without-border" role="tablist">
					<li class="nav-item">
						<a class="nav-link <?php echo ($tabactive == 'competiciones-tab') ? 'active' : '';?>" id="pills-sorteos-tab" href="<?php echo base_url();?>Torneos/competiciones/<?php echo $torneo->slug;?>" role="tab" >Competiciones</a>
					</li>
				</ul>
			</div>
		</div>
			
		<?php $this->load->view('gestion/torneos/'.$tabactive);?>
					
	</div>
</div>
