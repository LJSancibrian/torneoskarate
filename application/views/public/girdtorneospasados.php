<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="posts section">
	<div class="container">

<?php /*
	<div class="quiz-medal quiz-medal-sm">
      <div class="quiz-medal__circle quiz-medal__circle--gold">
        <span>
          1
        </span>
      </div>
      <div class="quiz-medal__ribbon quiz-medal__ribbon--left"></div>
      <div class="quiz-medal__ribbon quiz-medal__ribbon--right"></div>
    </div>
    <div class="quiz-medal quiz-medal-sm">
      <div class="quiz-medal__circle quiz-medal__circle--silver">
        <span>
          2
        </span>
      </div>
      <div class="quiz-medal__ribbon quiz-medal__ribbon--left"></div>
      <div class="quiz-medal__ribbon quiz-medal__ribbon--right"></div>
    </div>
    <div class="quiz-medal quiz-medal-sm">
      <div class="quiz-medal__circle quiz-medal__circle--bronze">
        <span>
          3
        </span>
      </div>
      <div class="quiz-medal__ribbon quiz-medal__ribbon--left"></div>
      <div class="quiz-medal__ribbon quiz-medal__ribbon--right"></div>
    </div>



        <div class="quiz-medal quiz-medal-sm">
          <div class="quiz-medal__circle">
          </div>
          <div class="quiz-medal__ribbon quiz-medal__ribbon--left"></div>
          <div class="quiz-medal__ribbon quiz-medal__ribbon--right"></div>
        </div>
  */?>

		<h3 class="text-center mb-3">Torneos ya finalizados</h3>
		<div class="row">
			<?php foreach ($torneos_pasados as $key => $torneo) {?>
				<div class="col-md-4">
					<?php 
					$datos['torneo'] = $torneo;
					$this->load->view('public/secciones/carousel_torneos_pasados', $datos);
					?>
				</div>
			<?php } ?>
		</div>
	</div>
</section>
