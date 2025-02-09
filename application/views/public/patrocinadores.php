<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Sponsors Image Slider</title>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/public/plugins/bootstrap/css/bootstrap.min.css">
	<style>
        html {
            cursor: none;
        }
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		body {
			background-color: #fff;
			overflow: hidden;
			display: flex;
			justify-content: center;
			align-items: center;
			height: 100vh;
			width: 100vw;
		}

		.carousel {
            width: 100vw;
            height: 100vh;
        }

        .carousel-item img {
            width: 100vw;
            height: 100vh;
            object-fit: contain;
        }

		.carousel-item {
            transition: transform 1s ease-in-out !important;
			padding: 0 4%;
        }

        /*.carousel-item .d-flex{
            height: 100vh;
            width: 100vw;
            text-align: center;
        }*/

        .responsive-text {
            font-size: clamp(2rem, 15vw, 10rem); /* Ajusta dinámicamente el tamaño del texto */
            white-space: nowrap; /* Evita el salto de línea */
            overflow: hidden; /* Previene desbordamientos */
            line-height: normal;
        }

        .responsive-text-min {
            display: none;
            font-size: clamp(2rem, 8vw, 6rem); /* Ajusta dinámicamente el tamaño del texto */
            white-space: nowrap; /* Evita el salto de línea */
            overflow: hidden; /* Previene desbordamientos */
            line-height: normal;
        }

	</style>
</head>

<body>

    <div id="imageCarousel" class="carousel slide" data-ride="carousel" data-interval="8000">
        <div class="carousel-inner">

            <div class="carousel-item active">
                <div class="d-flex flex-column align-items-center justify-content-center vh-100 text-center w-100">
                    <h2 class="responsive-text-min">Sigue el torneo en</h2>
                    <h1 class="responsive-text">www.karatepielagos.com</h1>
                </div>
            </div>
            <?php
            $directorio = FCPATH . "assets/public/images/sponsors_todos";
            $archivos = array_filter(scandir($directorio), function ($archivo) use ($directorio) {
                return is_file("$directorio/$archivo") && preg_match('/\.(jpg|jpeg|png|gif|svg)$/i', $archivo);
            });

            shuffle($archivos); // Mezclar imágenes aleatoriamente
            $first = false; // Para marcar la primera imagen como activa

            foreach ($archivos as $archivo) {
                $archivo = $directorio . "/" . $archivo;
                $archivo = str_replace(FCPATH, base_url(), $archivo); // Convertir a URL pública
                echo '<div class="carousel-item ' . ($first ? 'active' : '') . '">';
                echo '<img src="' . $archivo . '" class="d-block w-100" alt="Imagen">';
                echo '</div>';
                $first = false; 
            }
            ?>
        </div>

        <!-- Controles -->
        <a class="carousel-control-prev" href="#imageCarousel" role="button" data-slide="prev">
           
        </a>
        <a class="carousel-control-next" href="#imageCarousel" role="button" data-slide="next">
          
        </a>
    </div>

    <script src="<?=base_url()?>assets/js/jquery.min.js"></script>
    <script src="<?php echo base_url();?>assets/public/plugins/bootstrap/js/bootstrap.min.js"></script>

    <script>
		let wakeLock = null;
		async function requestWakeLock() {
			try {
				wakeLock = await navigator.wakeLock.request("screen");
				console.log("Wake Lock activado");
				document.addEventListener("visibilitychange", async () => {
					if (wakeLock !== null && document.visibilityState === "visible") {
						wakeLock = await navigator.wakeLock.request("screen");
					}
				});
			} catch (err) {
				console.error("Error al activar Wake Lock:", err);
			}
		}
        function openFullscreen() {
            let elem = document.documentElement;
            if (!document.fullscreenElement) {
                if (elem.requestFullscreen) {
                    elem.requestFullscreen();
                } else if (elem.mozRequestFullScreen) {
                    elem.mozRequestFullScreen();
                } else if (elem.webkitRequestFullscreen) {
                    elem.webkitRequestFullscreen();
                } else if (elem.msRequestFullscreen) {
                    elem.msRequestFullscreen();
                }
            }
        }
        $(document).ready(function () {
            requestWakeLock();
            $(document).on("click", function () {
                openFullscreen();
				requestWakeLock();
                $("#imageCarousel").carousel("cycle");
            });
            $("#imageCarousel").on("click", function () {
                let $carousel = $(this);
                if ($carousel.is(".paused")) {
                    $carousel.carousel("cycle").removeClass("paused");
                } else {
                    $carousel.carousel("pause").addClass("paused");
                }
            });
        });
    </script>
</body>
</html>