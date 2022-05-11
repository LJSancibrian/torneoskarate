<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<section class="single-page-header" style="background-image: url(<?php echo base_url(); ?>assets/img/fondo1.jpg);">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<?php if (isset($page_sub_header)) { ?>
					<h3 class="page-header"><?php echo $page_header; ?></h3>
					<h4 class="page-header"><?php echo $page_sub_header; ?></h4>
				<?php } else { ?>
					<h2 class="page-header"><?php echo $page_header; ?></h2>
				<?php } ?>

			</div>
		</div>
	</div>
</section>