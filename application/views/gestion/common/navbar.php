<div class="main-header">
	<div class="logo-header" data-background-color="<?php echo ($this->user->group->id > 3) ? 'white' : 'blue';?>">
		<a href="<?php echo base_url(); ?>" class="logo">
			 <?php /* <img src="<?php echo base_url(); ?>assets/images/logo-obulcula.png" alt="navbar brand" class="navbar-brand img-fluid" style="filter: brightness(0) invert(1); max-height:30px;"> */ ?>
		</a>
		<?php if($this->user->group->id > 3){?>
			<button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon">
				<i class="icon-menu"></i>
			</span>
		</button>
		<div class="nav-toggle">
			<button class="btn btn-toggle toggle-sidebar">
				<i class="icon-menu"></i>
			</button>
		</div>
		<?php }else{?>

		<button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon">
				<i class="icon-menu"></i>
			</span>
		</button>
		<div class="nav-toggle">
			<button class="btn btn-toggle toggle-sidebar">
				<i class="icon-menu"></i>
			</button>
		</div>
		<?php } ?>
	</div>
	<nav class="navbar navbar-header navbar-expand-lg" data-background-color="<?php echo ($this->user->group->id > 3) ? 'white' : 'blue2';?>">
		<div class="container-fluid">
			<ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
				<li class="nav-item dropdown hidden-caret">
					<a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
						<div class="avatar-sm">
							<?php /* <img src="<?php echo base_url(); ?>assets/admin/assets/img/no-photo.jpg" alt="..." class="avatar-img rounded-circle"> */ ?>
						</div>
					</a>
					<ul class="dropdown-menu dropdown-user animated fadeIn">
						<div class="dropdown-user-scroll scrollbar-outer">
							<li>
								<a class="dropdown-item" href="<?php echo base_url();?>area-privada">Mi Perfil</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo base_url();?>logout">Logout</a>
							</li>
						</div>
					</ul>
				</li>
			</ul>
		</div>
	</nav>
</div>