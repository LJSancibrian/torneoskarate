<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<section class="portfolio mt-3" id="portfolio">
    <div class="container-fluid">
        <div class="row ">
            <div class="col-lg-12">

                <ul class="nav nav-pills justify-content-center mt-5" id="torneotabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="info-tab" data-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="true">Info</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="competiciones-tab" data-toggle="tab" href="#competiciones" role="tab" aria-controls="competiciones" aria-selected="false">Competiciones</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="medallero-tab" data-toggle="tab" href="#medallero" role="tab" aria-controls="medallero" aria-selected="false">Medallero</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="info" role="tabpanel" aria-labelledby="info-tab">
                        <section class="about-2 section pt-0 bg-white" id="about">
                            <div class="container">
                                <div class="blog-slider">
                                    <div class="blog-slider__content w-100">
                                        <div class="row">
                                            <div class="title text-center w-100">
                                                <h2>Información</h2>
                                                <div class="border"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <img src="<?php echo $torneo->cartel; ?>" class="img-fluid" alt="">
                                            </div>
                                            <div class="col-md-6">
                                                <p><?php echo $torneo->descripcion; ?></p>
                                                <h4>Importante</h4>
                                                <ul class="checklist ml-3">
                                                    <li> <i class="tf-ion-android-calendar"></i> <?php echo fechaCastellano($torneo->fecha); ?></li>
                                                    <li> <i class="tf-ion-android-locate"></i> <?php echo $torneo->direccion;; ?></li>
                                                    <li> <i class="tf-ion-android-calendar"></i> Inscripciones hasta <?php echo fechaCastellano($torneo->limite); ?></li>
                                                    <li> <i class="tf-ion-android-checkmark-circle"></i> Organiza <?php echo $torneo->organizador; ?></li>
                                                    <li> <i class="tf-ion-android-mail"></i> <?php echo $torneo->email; ?></li>
                                                    <li> <i class="tf-ion-ios-telephone"></i> <?php echo $torneo->telefono; ?></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                    
                    <div class="tab-pane" id="participantes" role="tabpanel" aria-labelledby="participantes-tab">
                        <section class="about-2 section bg-white" id="about">
                            <div class="container">
                               
                                    <?php if (count($clubs) > 0) { ?>
                                       
                                            <?php foreach ($clubs as $key => $club) { ?>
                                                <img src="<?php echo ($club->img != '')?$club->img:base_url().'assets/img/placeholder.jpg'; ?>" alt="<?php echo $club->nombre; ?>" style="height: 200px">
                                            <?php } ?>
                                        
                                    <?php } else { ?>
                                        <div class="title text-center w-100">
                                            <h2>Sin clubs invidatos</h2>
                                            <p>Aún no se han realizado invitaciones a clubs y equipos.</p>
                                            <div class="border"></div>
                                        </div>
                                    <?php } ?>
                                
                            </div>
                        </section>
                    </div>

                    <div class="tab-pane" id="competiciones" role="tabpanel" aria-labelledby="competiciones-tab">
                        <section class="pricing-table section mt-3 pt-0 bg-white" >
                            <div class="container">
                                <div class="row">
                                    <div class="title text-center w-100">
                                        <h2>Competiciones</h2>
                                        <div class="border"></div>
                                    </div>
                                    <?php if ($torneo->tipo != 2) { ?>
                                        <div class="col-12 col-sm-6">
                                            <div class="blog-slider">
                                                <div class="blog-slider__content w-100 p-0 pb-3">
                                                        <div class="price-title">
                                                            <strong class="value p-0">KATA</strong>
                                                            <p></p>
                                                        </div>
                                                        <table class="table table-striped table-bordered">
                                                            <tbody>
                                                            <?php foreach ($competicioneskata as $key => $competicion) { 
                                                                if($competicion->estado < 3){?>
                                                                <tr><td><a href="<?php echo base_url();?>vercompeticion/<?php echo $torneo->slug;?>/<?php echo $competicion->competicion_torneo_id;?>" class="align-items-center d-flex justify-content-between"><?php echo $competicion->categoria;?> <?php echo $competicion->nivel;?> - <?php echo ($competicion->genero == 'M') ? 'masculino' : (($competicion->genero == 'F') ? 'femenino' : 'mixto');?> <i class="tf-ion-android-arrow-forward" style="font-size: 1.5rem;"></i></a></td></tr>
                                                            <?php } }?>
                                                            </tbody>
                                                            </table>
                                                    
                                                </div>
                                            </div>
                                        </div>

                                    <?php } ?>

                                    <?php if ($torneo->tipo != 1) { ?>
                                        <div class="col-12 col-sm-6">
                                            <div class="blog-slider">
                                                <div class="blog-slider__content w-100 p-0 pb-3">
                                                    <div class="price-title">
                                                        <strong class="value p-0">KUMITE</strong>
                                                        <p></p>
                                                    </div>
                                                    <table class="table table-striped table-bordered">
                                                        <tbody>
                                                            <?php foreach ($competicioneskumite as $key => $competicion) { 
                                                                if($competicion->estado < 3){?>
                                                                <tr><td><a href="<?php echo base_url();?>vercompeticion/<?php echo $torneo->slug;?>/<?php echo $competicion->competicion_torneo_id;?>" class="align-items-center d-flex justify-content-between"><?php echo $competicion->categoria;?> <?php echo $competicion->nivel;?> - <?php echo ($competicion->genero == 'M') ? 'masculino' : (($competicion->genero == 'F') ? 'femenino' : 'mixto');?> <i class="tf-ion-android-arrow-forward" style="font-size: 1.5rem;"></i></a></td></tr>
                                                            <?php } }?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>








                                </div>
                            </div>
                        </section>

                    </div>
                     
                    <div class="tab-pane" id="medallero" role="tabpanel" aria-labelledby="medallero-tab">
                        <section class="pricing-table section mt-3 pt-0 bg-white" >
                            <div class="container">
                                <div class="title text-center w-100">
                                    <h2>Medallero</h2>
                                    <div class="border"></div>
                                </div>
                                <div class="blog-slider">
                                    <div class="blog-slider__content w-100 p-0 pb-3">
                                        <div class="row">
                                            <?php if ($torneo->tipo != 2) { ?>
                                                <div class="col-12 col-sm-6">
                                                    <div class="price-title">
                                                        <strong class="value p-0">KATA</strong>
                                                        <p></p>
                                                    </div>
                                                    <?php foreach ($competicioneskata as $key => $competicion) {
                                                        if($competicion->estado < 3){?>
                                                        <div class="mb-3">
                                                            <h5 class="text-uppercase"><?php echo $competicion->categoria;?> <?php echo $competicion->nivel;?> - <?php echo ($competicion->genero == 'M') ? 'masculino' : (($competicion->genero == 'F') ? 'femenino' : 'mixto');?></h5>
                                                            <?php if($competicion->medallas == 1){?>
                                                                <table class="table table-striped table-bordered" id="medallero_<?php echo $competicion->competicion_torneo_id;?>" data-medallero="<?php echo $competicion->competicion_torneo_id;?>">
                                                                    <tbody>
                                                                        <?php foreach ($competicion->clasificacionfinal as $k => $value) { 
                                                                            $pos = '';
                                                                            if( $k + 1 < 4){
                                                                                switch ($k + 1) {
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
                                                                                        $classmedal = 'quiz-medal__circle';
                                                                                        break;
                                                                                } ?>
                                                                            
                                                                            
                                                                            <tr>
                                                                                <td style="width: 60px"><div class="quiz-medal quiz-medal-sm">
                                                                                    <div class="<?php echo $classmedal;?>">
                                                                                        <span>
                                                                                        <?php echo $k + 1;?>
                                                                                        </span>
                                                                                    </div>
                                                                                    <div class="quiz-medal__ribbon quiz-medal__ribbon--left"></div>
                                                                                    <div class="quiz-medal__ribbon quiz-medal__ribbon--right"></div>
                                                                                    </div>
                                                                                </td>
                                                                                <td><strong class="text-uppercase"><?php echo $value->first_name.' '.$value->last_name.'</strong><br><small>'.$value->nombre;?></small></td>
                                                                            </tr>
                                                                            <?php } ?>
                                                                        <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                            <?php }else{?>
                                                                <p>No se ha establecido la entrega de medallas para esta competición</p>
                                                            <?php  } ?>
                                                        </div>
                                                    <?php } }?>
                                                </div>
                                            <?php } ?>

                                            <?php if ($torneo->tipo != 1) { ?>
                                                <div class="col-12 col-sm-6">
                                                    <div class="price-title">
                                                        <strong class="value p-0">KUMITE</strong>
                                                        <p></p>
                                                    </div>
                                                    <?php foreach ($competicioneskumite as $key => $competicion) { 
                                                        if($competicion->estado < 3 ){?>
                                                        <div class="mb-3">
                                                            <h5 class="text-uppercase"><?php echo $competicion->categoria;?> <?php echo $competicion->nivel;?> - <?php echo ($competicion->genero == 'M') ? 'masculino' : (($competicion->genero == 'F') ? 'femenino' : 'mixto');?></h5>
                                                            <?php if($competicion->medallas == 1){?>
                                                            <table class="table table-striped table-bordered" id="medallero_<?php echo $competicion->competicion_torneo_id;?>" data-medallero="<?php echo $competicion->competicion_torneo_id;?>">
                                                                <tbody>
                                                                <?php if(isset($competicion->clasificacionfinal)){
                                                                    foreach ($competicion->clasificacionfinal as $k => $value) { 
                                                                        $pos = '';
                                                                        if( $k + 1 < 4){
                                                                            switch ($k + 1) {
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
                                                                                    $classmedal = 'quiz-medal__circle';
                                                                                    break;
                                                                            } ?>
                                                                        
                                                                        <tr>
                                                                            <td style="width: 60px"><div class="quiz-medal quiz-medal-sm">
                                                                                <div class="<?php echo $classmedal;?>">
                                                                                    <span>
                                                                                    <?php echo $k + 1;?>
                                                                                    </span>
                                                                                </div>
                                                                                <div class="quiz-medal__ribbon quiz-medal__ribbon--left"></div>
                                                                                <div class="quiz-medal__ribbon quiz-medal__ribbon--right"></div>
                                                                                </div>
                                                                            </td>
                                                                            <td><?php echo '<strong class="text-uppercase">'.$value->first_name.' '.$value->last_name.'</strong><br><small>'.$value->nombre.'</small>';?></td>
                                                                        </tr>
                                                                        <?php } ?>
                                                                    <?php } }?>
                                                                </tbody>
                                                            </table>
                                                            <?php }else{?>
                                                                <p>No se ha establecido la entrega de medallas para esta competición</p>
                                                            <?php  } ?>
                                                        </div>
                                                    <?php } }?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>