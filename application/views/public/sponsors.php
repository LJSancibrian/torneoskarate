<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if ($torneo->torneo_id == 19 || $torneo->torneo_id == 20) { ?>
	<style>
		.sponsor-slider {
			overflow: hidden;
			width: 95%;
			max-width: 1000px;
			position: relative;
			margin: 1rem auto 2rem;
		}

		.slider-track {
			display: flex;
			gap: 20px;
			transition: transform 0.5s ease-in-out;
			justify-content: center;
		}

		.slider-track img {
			height: 70px;
			/* Ajusta según tus logos */
		}

		@media (min-width: 768px) {
			.slider-track img {
				height: 90px;
			}
		}
	</style>
	<div class="container sponsor-slider">
		<div class="py-3">
			<h5 class="text-center py2">Con la colaboración de:</h5>
			<div class="slider-track" id="sliderTrack">

				<?php
				$directorio = FCPATH . "assets/public/images/sponsors_" . $torneo->torneo_id; // Carpeta donde están los logos
				$archivos = array_filter(scandir($directorio), function ($archivo) use ($directorio) {
					return is_file("$directorio/$archivo") && preg_match('/\.(jpg|jpeg|png|gif)$/i', $archivo);
				});

				shuffle($archivos); // Barajar logos aleatoriamente

				for ($i = 0; $i < 5; $i++) {
					foreach ($archivos as $archivo) {
						$archivo = $directorio . "/" . $archivo;  // Ruta absoluta
						$archivo = str_replace(FCPATH, base_url(), $archivo); // Convertir a URL pública

						echo "<img src='$archivo' alt='Patrocinador'>";
					}
				}
				?>
			</div>
		</div>
	</div>

	<script>
		document.addEventListener("DOMContentLoaded", function() {
			const sliderTrack = document.getElementById("sliderTrack");

			function slide() {
				let firstImg = sliderTrack.querySelector("img");
				let imgWidth = firstImg.offsetWidth + 20; // +20 por el gap

				sliderTrack.style.transition = "transform 0.5s ease-in-out";
				sliderTrack.style.transform = `translateX(-${imgWidth}px)`;

				setTimeout(() => {
					sliderTrack.appendChild(firstImg); // Mueve la primera imagen al final
					sliderTrack.style.transition = "none";
					sliderTrack.style.transform = "translateX(0)";
				}, 500);
			}

			setInterval(slide, 2000);
		});
	</script>
<?php } ?>