<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style>

</style>
<section class="portfolio mt-3" id="portfolio">
    <div class="container-fluid">
        <div class="row ">
            <div class="title text-center w-100">
                <h2>Clasificación General</h2>
                <p>Clasificación general valorando la posición de todas las jornadas disputadas de la liga del año en curso</p>
                <div class="border"></div>
            </div>
        </div>
    </div>
</section>
<section class="pricing-table pt-0">
    <div class="container-fluid">
        <div class="blog-slider">
            <div class="blog-slider__content w-100 p-0 pb-3">
                <div class="price-title">
                    <strong class="value p-0"><?php echo $competicion->modalidad . ' ' .$competicion->categoria . ' ' . $competicion->nivel; ?> - <?php echo ($competicion->genero == 'M') ? 'masculino' : (($competicion->genero == 'F') ? 'femenino' : 'mixto'); ?></strong>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered text-center" id="tablavistakata" data-competicion="<?php echo $competicion->competicion_torneo_id; ?>">
                        <thead>
                            <tr>
                                <th class="">#</th>
                                <th class="text-left columnfixed">Deportista</th>
                                <?php $j = 1;
                                $jornada2 = [75,76,77,78];
                                foreach ($jornadas as $key => $value) {
                                    if(!in_array($competicion->competicion_torneo_id, $jornada2)){
                                        echo '<th>Jornada ' . $j . '</th>';
                                    }else{
                                        echo '<th>Jornada 2</th>';
                                    }
                                    $j++;
                                } ?>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $orden = 1;
                            foreach ($clasificacion as $key => $value) {
                                switch ($orden) {
                                    case 1:
                                        $classmedal = 'quiz-medal__circle quiz-medal__circle--gold';
                                        break;
                                    case 2:
                                        $classmedal = 'quiz-medal__circle quiz-medal__circle--silver';
                                        break;
                                    case 3:
                                        $classmedal = 'quiz-medal__circle quiz-medal__circle--bronze';
                                        break;
                                    default:
                                        $classmedal = '';
                                        break;
                                }
                                if($classmedal != ''){
                                    $html = ' <div class="quiz-medal quiz-medal-sm">
                                            <div class="'.$classmedal.'">
                                                <span>'.$orden.'</span>
                                            </div>
                                            <div class="quiz-medal__ribbon quiz-medal__ribbon--left"></div>
                                            <div class="quiz-medal__ribbon quiz-medal__ribbon--right"></div>
                                        </div>';
                                }else{
                                        $html = $orden; 
                                }
                                ?>
                                    <tr>
                                        <td style="width: 60px"><?php echo $html; ?></td>
                                        <td class="text-left text-nowrap">
                                            <strong class="text-uppercase"><?php echo $value->nombre; ?> <?php echo $value->apellidos; ?></strong>
                                            <br>
                                            <?php echo $value->club;?>
                                        </td>
                                        <?php $j = 1;
                                        foreach ($jornadas as $jo => $jornada) {
                                            $jornada = 'jornada_' . $j;
                                            echo '<td>' . $value->$jornada . '</td>';
                                            $j++;
                                        } ?>
                                        <td data-ronda="1" data-j="1">
                                            <?php echo $value->total; ?>
                                        </td>
                                    </tr>
                                <?php $orden++;
                            } ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</section>