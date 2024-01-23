<!-- Sidebar -->
<div class="sidebar sidebar-style-2">
	<div class="sidebar-wrapper scrollbar scrollbar-inner">
		<div class="sidebar-content">
			<?php // ADMINISTRADORES
			if($this->user->group->id < 4){?>	
			<div class="user">
				<div class="avatar-sm float-left mr-2">
					<img src="<?php echo base_url(); ?>assets/admin/img/no-photo.jpg" alt="..." class="avatar-img rounded-circle">
				</div>
				<div class="info">
					<a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
						<span>
							<span class="user-level"><?php echo $this->user->first_name. ' ' .$this->user->last_name;?></span>
							<span class="user-level"><?php echo $this->user->rol;?></span>
							<span class="caret"></span>
						</span>
					</a>
					<div class="clearfix"></div>

					<div class="collapse" id="collapseExample">
						<ul class="nav">
							<li>
								<a href="<?php echo base_url();?>mi-perfil">
									<span class="link-collapse">Mi Perfil</span>
								</a>
							</li>
							<li>
								<a href="<?php echo base_url();?>logout">
									<span class="link-collapse">Cerrar sesión</span>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			
			<ul class="nav nav-primary">
				<li class="nav-item">
					<a data-toggle="collapse" href="<?php echo base_url();?>gestion" class="collapsed" aria-expanded="false">
						<i class="fas fa-home"></i>
						<p>Inicio</p>
						<span class="caret"></span>
					</a>
					<div class="collapse" id="dashboard">
						<ul class="nav nav-collapse">
							<li>
								<a href="<?php echo base_url(); ?>assets/admin/demo1/index.html">
									<span class="sub-item">Dashboard 1</span>
								</a>
							</li>
							<li>
								<a href="<?php echo base_url(); ?>assets/admin/demo2/index.html">
									<span class="sub-item">Dashboard 2</span>
								</a>
							</li>
						</ul>
					</div>
				</li>

				<li class="nav-item <?php echo ($this->uri->segment(1) == 'usuarios') ? 'active submenu' : '';?>">
					<a class="nav-link" data-toggle="collapse" href="#collapseUsuarios" aria-expanded="<?php echo ($this->uri->segment(1) == 'usuarios') ? 'true' : 'false';?>">
						<i class="far fa-user"></i>
						<span>Usuarios / Staff </span>
						<span class="caret"></span>
					</a>
					<div class="collapse <?php echo ($this->uri->segment(1) == 'usuarios') ? 'show' : '';?>" id="collapseUsuarios">
						<ul class="nav nav-collapse">
							<li <?php echo ($this->uri->segment(1) == 'usuarios' && $this->uri->segment(2) == 'administradores') ? 'class="active"' : '';?>>
								<a href="<?php echo base_url();?>usuarios/administradores">
									<span class="sub-item">Administradores</span>
								</a>
							</li>
							<li <?php echo ($this->uri->segment(1) == 'usuarios' && $this->uri->segment(2) == 'auxiliares') ? 'class="active"' : '';?>>
								<a href="<?php echo base_url();?>usuarios/auxiliares">
									<span class="sub-item">Auxiliares</span>
								</a>
							</li>
							<li <?php echo ($this->uri->segment(1) == 'usuarios' && $this->uri->segment(2) == 'nuevo') ? 'class="active"' : '';?>>
								<a href="<?php echo base_url();?>usuarios/nuevo">
									<span class="sub-item">Añadir usuario</span>
								</a>
							</li>
						</ul>
					</div>
				</li>
				
				<li class="nav-item <?php echo ($this->uri->segment(1) == 'equipos') ? 'active submenu' : '';?>">
					<a class="nav-link" data-toggle="collapse" href="#collapseequipos" aria-expanded="<?php echo ($this->uri->segment(1) == 'equipos') ? 'true' : 'false';?>">
						<i class="fas fa-users"></i>
						<span>Equipos</span>
						<span class="caret"></span>
					</a>
					<div class="collapse <?php echo ($this->uri->segment(1) == 'equipos') ? 'show' : '';?>" id="collapseequipos">
						<ul class="nav nav-collapse">
							<li <?php echo ($this->uri->segment(1) == 'equipos' && $this->uri->segment(2) == 'Usuarios') ? 'class="active"' : '';?>>
								<a href="<?php echo base_url();?>equipos">
									<span class="sub-item">Equipos</span>
								</a>
							</li>
							<li <?php echo ($this->uri->segment(1) == 'equipos' && $this->uri->segment(2) == 'deportistas') ? 'class="active"' : '';?>>
								<a href="<?php echo base_url();?>equipos/deportistas">
									<span class="sub-item">Deportistas</span>
								</a>
							</li>
							<li <?php echo ($this->uri->segment(1) == 'equipos' && $this->uri->segment(2) == 'entrenadores') ? 'class="active"' : '';?>>
								<a href="<?php echo base_url();?>equipos/entrenadores">
									<span class="sub-item">Entrenadores</span>
								</a>
							</li>
						</ul>
					</div>
				</li>
				<li class="nav-item <?php echo ($this->uri->segment(1) == 'torneos') ? 'active submenu' : '';?>">
					<a class="nav-link" data-toggle="collapse" href="#collapsetorneos" aria-expanded="<?php echo ($this->uri->segment(1) == 'torneos') ? 'true' : 'false';?>">
						<i class="fas fa-users"></i>
						<span>Torneos</span>
						<span class="caret"></span>
					</a>
					<div class="collapse <?php echo ($this->uri->segment(1) == 'torneos') ? 'show' : '';?>" id="collapsetorneos">
						<ul class="nav nav-collapse">
							<li <?php echo ($this->uri->segment(1) == 'torneos' && $this->uri->segment(2) == '') ? 'class="active"' : '';?>>
								<a href="<?php echo base_url();?>torneos">
									<span class="sub-item">Próximos torneos</span>
								</a>
							</li>

							<li <?php echo ($this->uri->segment(1) == 'torneos' && $this->uri->segment(2) == 'pasados') ? 'class="active"' : '';?>>
								<a href="<?php echo base_url();?>torneos/pasados">
									<span class="sub-item">Torneos pasados</span>
								</a>
							</li>
							<?php if ($this->user->group->id < 4) { ?>
								<li <?php echo ($this->uri->segment(1) == 'torneos' && $this->uri->segment(2) == 'grupos') ? 'class="active"' : '';?>>
									<a href="<?php echo base_url();?>torneos/grupos">
										<span class="sub-item">Grupos de torneos</span>
									</a>
								</li>
							<?php } ?>
						</ul>
					</div>
				</li>

					
				<?php /* ?>
				<li class="nav-item <?php echo ($this->uri->segment(1) == 'Competiciones_ko') ? 'active submenu' : '';?>">
					<a href="<?php echo base_url();?>Competiciones_ko">
							<i class="fas fa-trophy"></i>
							<p>Competiciones KO</p>
				</a>
				</li>
				
				<li class="nav-item">
					<a class="nav-link" data-toggle="collapse" href="#collapseTorneos">
						<i class="fas fa-trophy"></i>
						<span>Torneos</span>
						<span class="caret"></span>
					</a>
					<div class="collapse" id="collapseTorneos">
						<ul class="nav nav-collapse">
							<li>
								<a href="<?php echo base_url();?>torneos">
									<span class="sub-item">Torneos</span>
								</a>
							</li>
							<li>
								<a href="<?php echo base_url();?>torneos/nueva">
									<span class="sub-item">Nuevo torneo</span>
								</a>
							</li>
						</ul>
					</div>
				</li>

				<?php */ ?>

				<li class="nav-item">
					<a class="nav-link" data-toggle="collapse" href="#collapseArchivos">
						<i class="far fa-file-pdf"></i>
						<span>Archivos</span>
						<span class="caret"></span>
					</a>
					<div class="collapse" id="collapseArchivos">
						<ul class="nav nav-collapse">
							<li>
								<a href="<?php echo base_url();?>/archivos">
									<span class="sub-item">Archivos</span>
								</a>
							</li>
							<li>
								<a href="<?php echo base_url();?>/archivos/nuevo">
									<span class="sub-item">Nuevo archivo</span>
								</a>
							</li>
							<li>
								<a href="<?php echo base_url();?>/archivos/categorias">
									<span class="sub-item">Categorías</span>
								</a>
							</li>
							<li>
								<a href="<?php echo base_url();?>/archivos/categoria_nueva">
									<span class="sub-item">Nueva categoría</span>
								</a>
							</li>
						</ul>
					</div>
				</li>

				<?php /*
				<li class="nav-item">
					<a class="nav-link" data-toggle="collapse" href="#collapseEventos">
						<i class="far fa-calendar"></i>
						<span>Eventos</span>
						<span class="caret"></span>
					</a>
					<div class="collapse" id="collapseEventos">
						<ul class="nav nav-collapse">
							<li>
								<a href="<?php echo base_url();?>/eventos/calendario">
									<span class="sub-item">Calendario</span>
								</a>
							</li>
							<li>
								<a href="<?php echo base_url();?>/eventos">
									<span class="sub-item">Eventos</span>
								</a>
							</li>
							<li>
								<a href="<?php echo base_url();?>/eventos/nuevo">
									<span class="sub-item">Nuevo evento</span>
								</a>
							</li>
							<li>
								<a href="<?php echo base_url();?>/eventos/categorias">
									<span class="sub-item">Categorías</span>
								</a>
							</li>
							<li>
								<a href="<?php echo base_url();?>/eventos/categoria_nueva">
									<span class="sub-item">Nueva categoría</span>
								</a>
							</li>
						</ul>
					</div>
				</li>
				*/?>

				<?php /*
				<li class="nav-item">
					<a data-toggle="collapse" href="#base">
						<i class="fas fa-layer-group"></i>
						<p>Base</p>
						<span class="caret"></span>
					</a>
					<div class="collapse" id="base">
						<ul class="nav nav-collapse">
							<li>
								<a href="components/avatars.html">
									<span class="sub-item">Avatars</span>
								</a>
							</li>
							<li>
								<a href="components/buttons.html">
									<span class="sub-item">Buttons</span>
								</a>
							</li>
							<li>
								<a href="components/gridsystem.html">
									<span class="sub-item">Grid System</span>
								</a>
							</li>
							<li>
								<a href="components/panels.html">
									<span class="sub-item">Panels</span>
								</a>
							</li>
							<li>
								<a href="components/notifications.html">
									<span class="sub-item">Notifications</span>
								</a>
							</li>
							<li>
								<a href="components/sweetalert.html">
									<span class="sub-item">Sweet Alert</span>
								</a>
							</li>
							<li>
								<a href="components/font-awesome-icons.html">
									<span class="sub-item">Font Awesome Icons</span>
								</a>
							</li>
							<li>
								<a href="components/simple-line-icons.html">
									<span class="sub-item">Simple Line Icons</span>
								</a>
							</li>
							<li>
								<a href="components/flaticons.html">
									<span class="sub-item">Flaticons</span>
								</a>
							</li>
							<li>
								<a href="components/typography.html">
									<span class="sub-item">Typography</span>
								</a>
							</li>
						</ul>
					</div>
				</li>
				<li class="nav-item">
					<a data-toggle="collapse" href="#sidebarLayouts">
						<i class="fas fa-th-list"></i>
						<p>Sidebar Layouts</p>
						<span class="caret"></span>
					</a>
					<div class="collapse" id="sidebarLayouts">
						<ul class="nav nav-collapse">
							<li>
								<a href="sidebar-style-1.html">
									<span class="sub-item">Sidebar Style 1</span>
								</a>
							</li>
							<li>
								<a href="overlay-sidebar.html">
									<span class="sub-item">Overlay Sidebar</span>
								</a>
							</li>
							<li>
								<a href="compact-sidebar.html">
									<span class="sub-item">Compact Sidebar</span>
								</a>
							</li>
							<li>
								<a href="static-sidebar.html">
									<span class="sub-item">Static Sidebar</span>
								</a>
							</li>
							<li>
								<a href="icon-menu.html">
									<span class="sub-item">Icon Menu</span>
								</a>
							</li>
						</ul>
					</div>
				</li>
				<li class="nav-item">
					<a data-toggle="collapse" href="#forms">
						<i class="fas fa-pen-square"></i>
						<p>Forms</p>
						<span class="caret"></span>
					</a>
					<div class="collapse" id="forms">
						<ul class="nav nav-collapse">
							<li>
								<a href="forms/forms.html">
									<span class="sub-item">Basic Form</span>
								</a>
							</li>
						</ul>
					</div>
				</li>
				<li class="nav-item">
					<a data-toggle="collapse" href="#tables">
						<i class="fas fa-table"></i>
						<p>Tables</p>
						<span class="caret"></span>
					</a>
					<div class="collapse" id="tables">
						<ul class="nav nav-collapse">
							<li>
								<a href="tables/tables.html">
									<span class="sub-item">Basic Table</span>
								</a>
							</li>
							<li>
								<a href="tables/datatables.html">
									<span class="sub-item">Datatables</span>
								</a>
							</li>
						</ul>
					</div>
				</li>
				<li class="nav-item">
					<a data-toggle="collapse" href="#maps">
						<i class="fas fa-map-marker-alt"></i>
						<p>Maps</p>
						<span class="caret"></span>
					</a>
					<div class="collapse" id="maps">
						<ul class="nav nav-collapse">
							<li>
								<a href="maps/jqvmap.html">
									<span class="sub-item">JQVMap</span>
								</a>
							</li>
						</ul>
					</div>
				</li>
				<li class="nav-item">
					<a data-toggle="collapse" href="#charts">
						<i class="far fa-chart-bar"></i>
						<p>Charts</p>
						<span class="caret"></span>
					</a>
					<div class="collapse" id="charts">
						<ul class="nav nav-collapse">
							<li>
								<a href="charts/charts.html">
									<span class="sub-item">Chart Js</span>
								</a>
							</li>
							<li>
								<a href="charts/sparkline.html">
									<span class="sub-item">Sparkline</span>
								</a>
							</li>
						</ul>
					</div>
				</li>
				<li class="nav-item">
					<a href="widgets.html">
						<i class="fas fa-desktop"></i>
						<p>Widgets</p>
						<span class="badge badge-success">4</span>
					</a>
				</li>
				<li class="nav-item">
					<a data-toggle="collapse" href="#submenu">
						<i class="fas fa-bars"></i>
						<p>Menu Levels</p>
						<span class="caret"></span>
					</a>
					<div class="collapse" id="submenu">
						<ul class="nav nav-collapse">
							<li>
								<a data-toggle="collapse" href="#subnav1">
									<span class="sub-item">Level 1</span>
									<span class="caret"></span>
								</a>
								<div class="collapse" id="subnav1">
									<ul class="nav nav-collapse subnav">
										<li>
											<a href="#">
												<span class="sub-item">Level 2</span>
											</a>
										</li>
										<li>
											<a href="#">
												<span class="sub-item">Level 2</span>
											</a>
										</li>
									</ul>
								</div>
							</li>
							<li>
								<a data-toggle="collapse" href="#subnav2">
									<span class="sub-item">Level 1</span>
									<span class="caret"></span>
								</a>
								<div class="collapse" id="subnav2">
									<ul class="nav nav-collapse subnav">
										<li>
											<a href="#">
												<span class="sub-item">Level 2</span>
											</a>
										</li>
									</ul>
								</div>
							</li>
							<li>
								<a href="#">
									<span class="sub-item">Level 1</span>
								</a>
							</li>
						</ul>
					</div>
				</li>
				<li class="mx-4 mt-2">
					<a href="http://themekita.com/atlantis-bootstrap-dashboard.html" class="btn btn-primary btn-block"><span class="btn-label mr-2"> <i class="fa fa-heart"></i> </span>Buy Pro</a>
				</li>
				*/ ?>
			</ul>
			<?php }else {

			// AUXILIARES
			if($this->user->group->id > 3){ // NO SON ADMINISTRADORES?>
			<ul class="nav nav-primary">
				<li class="nav-item <?php echo ($this->uri->segment(1) == 'area-privada') ? 'active' : '';?>">
					<a href="<?php echo base_url();?>area-privada">
						<i class="fas fa-home"></i>
						<p>Inicio</p>
					</a>
				</li>

				<li class="nav-item <?php echo ($this->uri->segment(1) == 'mi-perfil') ? 'active' : '';?>">
					<a href="<?php echo base_url();?>mi-perfil">
						<i class="fas fa-user"></i>
						<p>Mi perfil</p>
					</a>
				</li>
				<?php if($this->user->group->id == 4){?>
				<li class="nav-item">
					<a class="nav-link" href="<?php echo base_url();?>torneos">
						<i class="fas fa-trophy"></i>
						<span>Torneos</span>
					</a>
				</li>
				<?php }	?>

				<?php if($this->user->group->id > 4){ // ENTRENADORES Y DEPORTISTAS?>
					<li class="nav-item <?php echo ($this->uri->segment(1) == 'mi-equipo') ? 'active' : '';?>">
						<a href="<?php echo base_url();?>mi-equipo">
							<i class="fas fa-users"></i>
							<p>Mi equipo</p>
						</a>
					</li>
					<?php if($this->user->group->id == 5){ // ENTRENADORES ?>
					<li class="nav-item <?php echo ($this->uri->segment(1) == 'entrenadores-equipo') ? 'active' : '';?>">
						<a href="<?php echo base_url();?>entrenadores-equipo">
							<i class="fas fa-user-friends"></i>
							<p>Entrenadores</p>
						</a>
					</li>

					<li class="nav-item <?php echo ($this->uri->segment(1) == 'deportistas-equipo') ? 'active' : '';?>">
						<a href="<?php echo base_url();?>deportistas-equipo">
							<i class="fas fa-user-ninja"></i>
							<p>Deportistas</p>
						</a>
					</li>

					<li class="nav-item <?php echo ($this->uri->segment(1) == 'proximos-torneos') ? 'active' : '';?>">
						<a href="<?php echo base_url();?>proximos-torneos">
							<i class="fas fa-trophy"></i>
							<p>Próximos torneos</p>
						</a>
					</li>

					<li class="nav-item <?php echo ($this->uri->segment(1) == 'torneos-finalizados') ? 'active' : '';?>">
						<a href="<?php echo base_url();?>torneos-finalizados">
							<i class="fas fa-trophy"></i>
							<p>Torneos finalizados</p>
						</a>
					</li>
					<?php }	?>

					<?php if($this->user->group->id == 6){ // DEPORTISTAS ?>
						<li class="nav-item <?php echo ($this->uri->segment(1) == 'mi-trayectoria') ? 'active' : '';?>">
							<a href="<?php echo base_url();?>mi-trayectoria">
								<i class="far fa-list-alt"></i>
								<p>Mi trayectoria</p>
							</a>
						</li>
						<li class="nav-item <?php echo ($this->uri->segment(1) == 'mis-torneos') ? 'active' : '';?>">
							<a href="<?php echo base_url();?>mis-torneos">
							<i class="fas fa-trophy"></i>
								<p>Mis torneos</p>
							</a>
						</li>
						<li class="nav-item <?php echo ($this->uri->segment(1) == 'mi-medallero') ? 'active' : '';?>">
							<a href="<?php echo base_url();?>mi-medallero">
							<i class="fas fa-medal"></i>
								<p>Medallero</p>
							</a>
						</li>
					<?php }	?>

				<?php }	?>

				<li class="nav-item">
					<a href="<?php echo base_url();?>logout">
						<i class="fas fa-sign-out-alt"></i>
								<p>Cerrar sesión</p>
					</a>
				</li>
			</ul>
			<?php } }?>
		</div>
	</div>
</div>
<!-- End Sidebar -->