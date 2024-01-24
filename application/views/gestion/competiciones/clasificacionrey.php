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
    <div class="card-header d-flex justify-content-between">
        <div class="card-title fw-mediumbold">Clasificación <?php echo $competicion->modalidad . ' ' . $competicion->categoria . ' ' . $competicion->genero . ' ' . $competicion->nivel;?></div>
    </div>
    <div class="card-body p-1 bg-white" id="tablero-competicion">
        <div class="table-responsive">
            <table class="table table-striped w-100 dataTable" id="clasificaicon">
                    <thead style="position: sticky; top: 0; z-index: 1;">
                        <tr>
                        <th>#</th>
                                <th class="text-left">Deportista</th>
                                <th class="text-left">Equipo</th>
                                <th class="text-left">Puntos totales</th>
                                <th class="text-left">Victorias</th>
								<th class="text-left">Empates</th>
                                <th class="text-left">Derrotas</th>
                                <th class="text-left">Puntos favor</th>
                                <th class="text-left">Nº Combates</th>
                        </tr>
                    </thead>
                <tbody>
                    <?php 
                    $pos = 0;
                    foreach ($clasificacion as $key => $finalista) { ?>
                        <tr>
                            <td><?php echo $pos + 1;?></td>
                            <td class="columnfixed"><?php echo $finalista->first_name.' '. $finalista->last_name;?></td>
                            <td><?php echo $finalista->nombre;?></td>
                            <td><?php echo $finalista->puntos_total;?></td>
                            <td><?php echo $finalista->victorias;?></td>
                            <td><?php echo $finalista->empates;?></td>
                            <td><?php echo $finalista->derrotas;?></td>
                            <td><?php echo $finalista->puntos_favor;?></td>
							<td><?php echo $finalista->total_combates;?></td>
                        </tr>
                    <?php  $pos++; } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<?php echo form_open();
echo form_close(); ?>