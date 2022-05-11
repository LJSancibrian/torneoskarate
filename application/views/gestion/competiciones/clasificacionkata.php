<?php if($this->user->group->id > 3) { ?>
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
					<li class="nav-item">
						<a class="nav-link <?php echo ($tabactive == 'inscripciones-tab' || $tabactive == 'inscripcion_deportistas-tab') ? 'active' : '';?>" id="pills-inscripciones-tab" href="<?php echo base_url();?>torneos/inscripciones/<?php echo $torneo->slug;?>" role="tab">Inscripciones</a>
					</li>
					<?php /* <li class="nav-item">
						<a class="nav-link <?php echo ($tabactive == 'competiciones-tab') ? 'active' : '';?>" id="pills-sorteos-tab" href="<?php echo base_url();?>torneos/competiciones/<?php echo $torneo->slug;?>" role="tab" >Competiciones</a>
					</li>
					<li class="nav-item">
						<a class="nav-link <?php echo ($tabactive == 'eliminatorias-tab') ? 'active' : '';?>" id="pills-eliminatorias-tab" href="<?php echo base_url();?>torneos/gestion/<?php echo $torneo->slug;?>/eliminatorias" role="tab" >Eliminatorias</a>
					</li> 
                    <li class="nav-item">
						<a class="nav-link <?php echo ($tabactive == 'clasificaciones-tab') ? 'active' : '';?>" id="pills-clasificaciones-tab" href="<?php echo base_url();?>torneos/clasificaciones/<?php echo $torneo->slug;?>" role="tab">Clasificaciones</a>
					</li>*/?>
				</ul>
			</div>
		</div>

<?php } ?>

<div class="card">
    <div class="card-body">
        <h4 class="border-bottom card-title font-weight-bold mb-4 text-uppercase">Clasificación <?php echo $competicion->modalidad . ' ' . $competicion->categoria . ' ' . $competicion->genero . ' ' . $competicion->nivel;?></h4>
        <div class="table-responsive" id="tablero-competicion">
            <table class="table table-striped w-100 dataTable" id="clasificaicon">
                    <thead style="position: sticky; top: 0; z-index: 1;">
                        <tr>
                            <th>#</th>
                            <th class="columnfixed">Deportista</th>
                            <th>Equipo</th>
                            <th>Ronda 1</th>
                            <th>Ronda 2</th>
                            <th>R Final</th>
                            <th>Total</th>
                            <th>Val</th>
                            <th>Media</th>
                        </tr>
                    </thead>
                <tbody>
                    <?php /*
                    $pos = 0;
                    foreach ($final as $key => $finalista) { ?>
                        <tr>
                            <td><?php echo $pos + 1;?></td>
                            <td class="columnfixed"><?php echo $finalista->first_name.' '. $finalista->last_name;?></td>
                            <td><?php echo $finalista->nombre;?></td>
                            <td><?php echo $finalista->total;?></td>
                            <td><?php echo $finalista->media;?></td>
                        </tr>
                    <?php  $pos++; } */?>

                    <?php 
                    $class = 0;
                    $pos = 0;
                    $lasttotal = 0;
                    $lastmedia = 0;
                    foreach ($general as $key => $competidor) { 
                        if($lasttotal != $competidor->total){
                            $lasttotal = $competidor->total;
                            $lastmedia = $competidor->media;
                            $pos = $class + 1;
                        }elseif($lastmedia != $competidor->media){
                            $lasttotal = $competidor->total;
                            $lastmedia = $competidor->media;
                            $pos = $class + 1;                     
                        }else{
                            $lasttotal = $competidor->total;
                            $lastmedia = $competidor->media;
                            $pos = $pos;            
                        }
                        ?>
                            <tr>
                                <td><?php echo $pos;?></td>
                                <td class="columnfixed"><?php echo $competidor->first_name.' '. $competidor->last_name;?></td>
                                <td><?php echo $competidor->nombre;?></td>
                                <td><?php echo (isset($competidor->rondas[1]))?$competidor->rondas[1]->total.' / '.$competidor->rondas[1]->media:'';?></td>
                                <td><?php echo (isset($competidor->rondas[2]))?$competidor->rondas[2]->total.' / '.$competidor->rondas[2]->media:'';?></td>
                                <td class="bg-primary text-white"><?php echo (isset($competidor->rondas[3]))?$competidor->rondas[3]->total.' / '.$competidor->rondas[1]->media:'';?></td>
                                <td><?php echo $competidor->total;?></td>
                                <td><?php echo $competidor->valoraciones;?></td>
                                <td><?php echo $competidor->media;?></td>
                            </tr>
                    <?php  $class++; } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<?php echo form_open();
echo form_close(); ?>