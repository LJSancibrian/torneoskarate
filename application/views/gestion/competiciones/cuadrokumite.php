<style>
    .senshu:after, .hantei:after {
    width: 0.5rem;
    height: 0.5rem;
    position: absolute;
    border-radius: 0.7rem;
    right: 5px;
    top: 4px;
    font-size: 70%;
}
</style>
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
        <div class="card-title fw-mediumbold" competicion_torneo_id="<?php echo $competicion->competicion_torneo_id; ?>">Clasificación <?php echo $competicion->modalidad . ' ' . $competicion->categoria . ' ' . $competicion->genero . ' ' . $competicion->nivel;?></div>
    </div>
    <div class="card-body p-1 bg-white" id="tablero-competicion">
    <?php foreach ($matches as $grupo) { ?>
                    <div class="service-item p-3 mb-3">
                        <div class="d-flex justify-content-between">
                            <h4>Grupo <?php echo $grupo->grupo; ?></h4>


                            <ul class="nav nav-pills justify-content-center" id="torneotabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link px-3 py-1 active" id="info-tab" data-toggle="tab" href="#comb_<?php echo $grupo->grupo; ?>" role="tab" aria-controls="info" aria-selected="true">Combates</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link px-3 py-1" id="competiciones-tab" data-toggle="tab" href="#clasif_<?php echo $grupo->grupo; ?>" role="tab" aria-controls="competiciones" aria-selected="false">Clasificación</a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane active" id="comb_<?php echo $grupo->grupo; ?>" role="tabpanel" aria-labelledby="comb_<?php echo $grupo->grupo; ?>-tab">
                                <div class="row text-center flex-nowrap" style="overflow-x: auto;white-space: nowrap">
                                    <?php foreach ($grupo->rondas as $ronda) { ?>
                                        <div class="col-md-4 col-lg-3">
                                            <h4 class="bg-primary text-white p-2 mb-3">Ronda <?php echo $ronda->ronda; ?></h4>
                                            <?php foreach ($ronda->matches as $match) { ?>
                                                <ul class="list-group mb-3 p-0 btn btn-link" data-match_id="<?php echo $match->match_id; ?>">
                                                    <li class="list-group-item d-flex justify-content-between align-items-center px-2 py-1 <?php echo ($match->senshu == 'rojo') ? 'senshu' : (($match->hantei == 'rojo') ? 'hantei' : ''); ?>" style="background: red;" data-user="<?php echo $match->user_rojo; ?>">
                                                        <span class="text-white text-truncate text-left" style="width:calc(100% - 30px);"><?php echo $match->rojo->nombre; ?></span>
                                                        <span class="font-weight-bold text-white" style="font-size:1.5rem"><?php echo $match->puntos_rojo; ?></span>
                                                    </li>

                                                    <li class="list-group-item d-flex justify-content-between align-items-center px-2 py-1 <?php echo ($match->senshu == 'azul') ? 'senshu' : (($match->hantei == 'azul') ? 'hantei' : ''); ?>" style="background: blue;" data-user="<?php echo $match->user_azul; ?>">
                                                        <span class="text-white text-truncate text-left"><?php echo $match->azul->nombre; ?></span>
                                                        <span class="font-weight-bold text-white" style="font-size:1.5rem"><?php echo $match->puntos_azul; ?></span>
                                                    </li>
                                                </ul>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="tab-pane" id="clasif_<?php echo $grupo->grupo; ?>" role="tabpanel" aria-labelledby="clasif_<?php echo $grupo->grupo; ?>-tab">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered text-center w-100 fixed2" id="tablakumite_<?php echo $grupo->grupo; ?>">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th colspan="8" class="font-weigth-bold">CLASIFICACIÓN</th>
                                            </tr>
                                        </thead>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th class="text-left columnfixed">Deportista</th>
                                                <th class="text-left">Equipo</th>
                                                <th class="text-left">Victorias</th>
                                                <th class="text-left">Puntos favor</th>
                                                <th class="text-left">Puntos contra</th>
                                                <th class="text-left">Senshu</th>
                                                <th class="text-left">Hantei</th>
                                            </tr>
                                        </thead>
                                        <tbody class="clasificacion_grupo" data-competicion_torneo_id="<?php echo $competicion->competicion_torneo_id; ?>" data-grupo="<?php echo $grupo->grupo; ?>">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                                    <span class="text-white text-truncate text-left" style="width:calc(100% - 30px);"><?php echo (isset($match->rojo)) ? $match->rojo->nombre : '' ; ?></span>
                                                    <span class="font-weight-bold text-white" style="font-size:1.5rem"><?php echo $match->puntos_rojo; ?></span>
                                                </li>

                                                <li class="list-group-item d-flex justify-content-between align-items-center px-2 py-1 <?php echo ($match->senshu == 'azul') ? 'senshu' : (($match->hantei == 'azul') ? 'hantei' : ''); ?>" style="background: blue;" data-user="<?php echo $match->user_azul; ?>">
                                                    <span class="text-white text-truncate text-left"><?php echo (isset($match->azul))?$match->azul->nombre:''; ?></span>
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


<?php echo form_open();
echo form_close(); ?>