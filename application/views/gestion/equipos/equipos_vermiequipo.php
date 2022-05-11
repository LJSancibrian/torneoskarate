<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<ul class="nav nav-pills nav-secondary nav-pills-no-bd" id="pills-tab-without-border" role="tablist">
					<li class="nav-item">
						<a class="nav-link" id="pills-equipo-tab-nobd" data-toggle="pill" href="#pills-equipo-nobd" role="tab" aria-controls="pills-equipo-nobd" aria-selected="true">Información</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="pills-usuario-tab-nobd" data-toggle="pill" href="#pills-usuario-nobd" role="tab" aria-controls="pills-usuario-nobd" aria-selected="false">Datos</a>
					</li>
				</ul>
			</div>
			<div class="card-body">
				<div class="tab-content mt-2 mb-3" id="pills-without-border-tabContent">
					<div class="tab-pane fade" id="pills-equipo-nobd" role="tabpanel" aria-labelledby="pills-equipo-tab-nobd">
						<h4 class="border-bottom card-title font-weight-bold mb-4 text-uppercase">Información</h4>
						<?php $this->load->view('gestion/equipos/equipos_vermiequipo_info_tab');?>
					</div>
					<div class="tab-pane fade" id="pills-usuario-nobd" role="tabpanel" aria-labelledby="pills-usuario-tab-nobd">
						<h4 class="border-bottom card-title font-weight-bold mb-4 text-uppercase">Datos del equipo</h4>
						<?php $this->load->view('gestion/equipos/equipos_form');?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
