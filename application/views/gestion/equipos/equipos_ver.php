<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<ul class="nav nav-pills nav-secondary nav-pills-no-bd" id="pills-tab-without-border" role="tablist">
					<li class="nav-item">
						<a class="nav-link" id="pills-equipo-tab-nobd" data-toggle="pill" href="#pills-equipo-nobd" role="tab" aria-controls="pills-equipo-nobd" aria-selected="true">El equipo</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="pills-usuario-tab-nobd" data-toggle="pill" href="#pills-usuario-nobd" role="tab" aria-controls="pills-usuario-nobd" aria-selected="false">Responsable</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="pills-deportistas-tab-nobd" data-toggle="pill" href="#pills-deportistas-nobd" role="tab" aria-controls="pills-deportistas-nobd" aria-selected="false">Deportistas</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="pills-coaches-tab-nobd" data-toggle="pill" href="#pills-coaches-nobd" role="tab" aria-controls="pills-coaches-nobd" aria-selected="false">Entrenadores</a>
					</li>
				</ul>
			</div>
			<div class="card-body">
				<div class="tab-content mt-2 mb-3" id="pills-without-border-tabContent">
					<div class="tab-pane fade" id="pills-equipo-nobd" role="tabpanel" aria-labelledby="pills-equipo-tab-nobd">
						<h4 class="border-bottom card-title font-weight-bold mb-4 text-uppercase">Información y datos del equipo</h4>
						<?php $this->load->view('gestion/equipos/equipos_form');?>
					</div>
					<div class="tab-pane fade" id="pills-usuario-nobd" role="tabpanel" aria-labelledby="pills-usuario-tab-nobd">
						<h4 class="border-bottom card-title font-weight-bold mb-4 text-uppercase">Responsable del equipo</h4>
						<?php $this->load->view('gestion/equipos/equipos_usuario_form');?>
					</div>
					<div class="tab-pane fade" id="pills-deportistas-nobd" role="tabpanel" aria-labelledby="pills-deportistas-tab-nobd">
						<div class="d-flex align-items-center">
							<h4 class="border-bottom card-title font-weight-bold mb-4 text-uppercase">Deportistas registrados</h4>
							<div class="dropdown ml-auto mb-4">
								<button type="button" id="btn-add-deportistas" data-tooltip="1" title="Crear nuevo equipo" data-original-title="Añadir deportistas" class="btn btn-sm btn-primary ml-auto" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-plus mr-3"></i> Añadir deportistas</button>
								<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
									<a class="dropdown-item" href="#" data-carga-deportista="individual">Individual</a>
									<a class="dropdown-item" href="#" data-carga-deportista="archivo">Carga masiva con archivo .csv</a>
								</div>
							</div>
							<?php $this->load->view('gestion/equipos/equipos_deportistas_add_modal');?>
						</div>
						<?php $this->load->view('gestion/equipos/equipos_deportistas_lista');?>
					</div>
					<div class="tab-pane fade" id="pills-coaches-nobd" role="tabpanel" aria-labelledby="pills-coaches-tab-nobd">
						<div class="d-flex align-items-center">
							<h4 class="border-bottom card-title font-weight-bold mb-4 text-uppercase">Entrenadores del equipo</h4>
                            <button type="button" id="btn-add-entrenadores" data-tooltip="1" title="Crear nuevo entrenador" data-original-title="Añadir entrenador" class="btn btn-sm btn-primary ml-auto" data-carga-entrenador="individual"><i class="fa fa-plus mr-3"></i> Añadir entrenador</button>

							<?php $this->load->view('gestion/equipos/equipos_entrenadores_add_modal');?>
						</div>
						<?php $this->load->view('gestion/equipos/equipos_entrenadores_lista');?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
