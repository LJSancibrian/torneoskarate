<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<ul class="nav nav-pills nav-secondary nav-pills-no-bd" id="pills-tab-without-border" role="tablist">
					<li class="nav-item">
						<a class="nav-link <?php echo ($tabactive == 'info-tab') ? 'active' : '';?>" id="pills-info-tab" href="<?php echo base_url();?>torneos/gestion/<?php echo $torneo->slug;?>" role="tab">Información</a>
					</li>
					<?php if($this->ion_auth->in_group([1,2,3])){?>
                    <li class="nav-item">
						<a class="nav-link <?php echo ($tabactive == 'datos-tab') ? 'active' : '';?>" id="pills-datos-tab" href="<?php echo base_url();?>torneos/datos/<?php echo $torneo->slug;?>" role="tab">Datos</a>
					</li>
					<?php } ?>
					<?php if($this->ion_auth->in_group([1,2,3])){?>
					<li class="nav-item">
						<a class="nav-link <?php echo ($tabactive == 'archivos-tab') ? 'active' : '';?>" id="pills-archivos-tab" href="<?php echo base_url();?>torneos/archivos/<?php echo $torneo->slug;?>" role="tab">Archivos</a>
					</li>
					<?php } ?>
					<li class="nav-item">
						<a class="nav-link <?php echo ($tabactive == 'categorias-tab') ? 'active' : '';?>" id="pills-categorias-tab"  href="<?php echo base_url();?>torneos/categorias/<?php echo $torneo->slug;?>" role="tab">Categorias</a>
					</li>

					<?php if($this->ion_auth->in_group([1,2,3,5])){?>
					<li class="nav-item">
						<a class="nav-link <?php echo ($tabactive == 'inscripciones-tab' || $tabactive == 'inscripcion_deportistas-tab') ? 'active' : '';?>" id="pills-inscripciones-tab" href="<?php echo base_url();?>torneos/inscripciones/<?php echo $torneo->slug;?>" role="tab">Inscripciones</a>
					</li>
					<?php } ?>
					<?php if($this->ion_auth->in_group([1,2,3,4])){?>
					<li class="nav-item">
						<a class="nav-link <?php echo ($tabactive == 'competiciones-tab') ? 'active' : '';?>" id="pills-sorteos-tab" href="<?php echo base_url();?>torneos/competiciones/<?php echo $torneo->slug;?>" role="tab" >Competiciones</a>
					</li>
					<?php } ?>
					<?php /* <li class="nav-item">
						<a class="nav-link <?php echo ($tabactive == 'eliminatorias-tab') ? 'active' : '';?>" id="pills-eliminatorias-tab" href="<?php echo base_url();?>torneos/gestion/<?php echo $torneo->slug;?>/eliminatorias" role="tab" >Eliminatorias</a>
					</li> 
                    <li class="nav-item">
						<a class="nav-link <?php echo ($tabactive == 'clasificaciones-tab') ? 'active' : '';?>" id="pills-clasificaciones-tab" href="<?php echo base_url();?>torneos/clasificaciones/<?php echo $torneo->slug;?>" role="tab">Clasificaciones</a>
					</li>*/?>
				</ul>
			</div>
		</div>
			
		<?php $this->load->view('gestion/torneos/'.$tabactive);?>
					
	</div>
</div>
