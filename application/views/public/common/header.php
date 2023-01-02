<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<header class="navigation fixed-top">
	<div class="container">
		<!-- main nav -->
		<nav class="navbar navbar-expand-lg navbar-light">
			<!-- logo -->
			<a class="navbar-brand logo" href="<?php echo base_url(); ?>">
				<img class="logo-default" src="<?php echo base_url(); ?>assets/img/logo-ayuntamiento-pielagos-2018.png" alt="<?php echo site_title(); ?>" />
				<img class="logo-white" src="<?php echo base_url(); ?>assets/img/logo-ayuntamiento-pielagos-2018.png" alt="<?php echo site_title(); ?>" />
			</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navigation">
				<ul class="navbar-nav ml-auto text-center">
					<li class="nav-item ">
						<a class="nav-link" href="<?php echo base_url(); ?>login">Área privada de usuarios</a>
					</li>
					<li class="nav-item dropdown d-none">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Pages
						</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdown">
							<a class="dropdown-item" href="404.html">404 Page</a>
							<a class="dropdown-item" href="blog.html">Blog Page</a>
							<a class="dropdown-item" href="single-post.html">Blog Single Page</a>
						</div>
					</li>
				</ul>
			</div>
		</nav>
		<!-- /main nav -->
	</div>
</header>