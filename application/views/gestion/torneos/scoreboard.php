<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sponsors Image Slider</title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/public/plugins/bootstrap/css/bootstrap.min.css">
    <script src="<?php echo base_url(); ?>assets/admin/js/plugin/webfont/webfont.min.js"></script>
	<script>
		WebFont.load({
			google: {
				"families": ["Lato:300,400,700,900"]
			},
			custom: {
				"families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
				urls: ['<?php echo base_url(); ?>assets/admin/css/fonts.min.css']
			},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>
    <style>
        body {
            background-color: white;
            color: black;
            text-align: center;
            font-family: Arial, sans-serif;
            overflow: hidden;
        }

        .marcador-container {
            background-color: black;
            position: relative;
            height: 100vh;
            width: 100vw;
            padding: 20px;
            display: block;
        }

        #competidores{
            position: relative;
            display: flex;
            height: 100%;
            width: 100%;
            justify-content: space-between;
        }

        .competidor {
            height: 100%;
            width: calc( 50% - 10px);
            padding: 20px;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .rojo {
            background-color: red;
            text-align: left;
        }

        .azul {
            background-color: blue;
            text-align: right;
        }
        
        .nombre_competidor {
            text-transform: uppercase;
            font-size: 4rem;
            font-weight: bold;
            color: white;
            text-align: center;
            border-bottom: 6px solid #ffffff;
            padding-bottom: 1rem;
            height: 20vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .puntos_competidor {
            font-size: 22rem;
            font-weight: bold;
            color: white;
            width: 53%;
            text-align: center;
            height: 70vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .azul .puntos_competidor {
            margin-left: 50%;
        }

        .penalizaciones_competidor {
            display: flex;
            justify-content: space-between;
            gap: 5px;
            font-size: 7rem;
            color: #ffffff;
            padding: 0 2rem;
            line-height: 1;
            height: 10vh;
        }


        .sensho_hantei.sensho{
            color: yellow;
        }
        .sensho_hantei.hantei{
            color: black;
        }

        .tiempo {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -7vw) scaleY(1.5);
            background: #000000;
            padding: 1rem;
            width: 46%;
            height: 20vw;
            text-align: center;
            font-size: 16rem;
            font-weight: bold;
            color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        @keyframes flash {
            0% { background-color: #000000; }
            50% { background-color: rgb(228 255 0); }
            100% { background-color: #000000;}
        }

        /*.baraku {
            animation: flash 1s infinite alternate ease-in-out;
        }

        .tiempo.baraku::after {
            content: "Atoshi Baraku";
            color: #000000;
            text-transform: uppercase;
            position: absolute;
            bottom: -20px;
            text-align: center;
            font-size: 5rem;
        }*/


        #imageCarousel{
            display: none;
        }

        /*.carousel {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .carousel img {
            max-width: 80%;
            max-height: 80%;
        }*/

        .carousel {
            width: 100vw;
            height: 100vh;
        }

        .carousel-item img {
            width: 100vw;
            height: 100vh;
            object-fit: contain;
            margin: 0 auto;
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
            font-size: 8vw; 
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
                if(str_contains($archivo, 'svg')){
                    $calss = 'd-block w-100 p-5';
                }else{
                    $calss = 'd-block w-100';
                }
                echo '<div class="carousel-item ' . ($first ? 'active' : '') . '">';
                echo '<img src="' . $archivo . '" class="'.$calss.'" alt="Imagen">';
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

    <!-- 🏆 Marcador (oculto por defecto) -->
    <div class="marcador-container" id="marcador">
        <!-- Competidor Rojo (Izquierda) -->
        <div id="competidores">
            <div class="competidor rojo">
                <h2 id="nombre-rojo" class="nombre_competidor">ROJO</h2>
                <h1 id="score-rojo"  class="puntos_competidor">0</h1>
                <div id="penalizaciones-rojo" class="penalizaciones_competidor">
                    <div class="penalizaciones">
                        <i class="fas fa-times d-none"></i>
                        <i class="fas fa-times d-none"></i>
                        <i class="fas fa-times d-none"></i>
                        <i class="fas fa-times d-none"></i>
                        <i class="fas fa-times d-none"></i>
                    </div>
                    <div class="sensho_hantei">
                        <i class="fas fa-circle d-none"></i>
                    </div>
                </div>
            </div>

            <div class="competidor azul">
                <h2 id="nombre-azul" class="nombre_competidor">AZUL</h2>
                <h1 id="score-azul" class="puntos_competidor">0</h1>
                <div id="penalizaciones-azul" class="penalizaciones_competidor">
                    <div class="sensho_hantei">
                        <i class="fas fa-circle"></i>
                    </div>
                    <div class="penalizaciones">
                        <i class="fas fa-times"></i>
                        <i class="fas fa-times"></i>
                        <i class="fas fa-times"></i>
                        <i class="fas fa-times"></i>
                        <i class="fas fa-times"></i>
                    </div>
                </div>
            </div>
        </div>


        <!-- 📢 Tiempo (centrado arriba) -->
        <div class="tiempo" id="tiempo">00:00</div>


    </div>

    <script src="<?= base_url() ?>assets/js/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/public/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/admin/js/vistas/scoreboard.js"></script>
</body>

</html>