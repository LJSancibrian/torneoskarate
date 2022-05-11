<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="posts section">
	<div class="container">
		<div class="row">
			<?php for ($i=0; $i < 4; $i++) { 
				$this->load->view('public/secciones/singlepostsection');
			}?>
		</div>
	</div>
</section>
