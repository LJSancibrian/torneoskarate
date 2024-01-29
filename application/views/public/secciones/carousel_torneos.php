<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="blog-slider row w-100 mb-4" style="max-width:100%">
        <?php if($torneo->cartel != ''){?>
            <div class="col-sm-6 col-md-5 col-lg-3  py-3">
                <img src="<?php echo $torneo->cartel; ?>" alt="" class="img-fluid">
            </div>
            <div class="col-sm-6 col-md-7 col-lg-9 py-3" >
        <?php } else{ ?>
            <div class="col-12" >
        <?php  } ?>
        
            <span class="blog-slider__code"><?php echo fechaCastellano($torneo->fecha); ?></span>
            <div class="blog-slider__title"><?php echo $torneo->titulo; ?></div>
            <div class="blog-slider__text"><?php echo $torneo->descripcion; ?></div>
            <div class="blog-slider__text">
                Limite de inscripciones: <?php echo fechaCastellano($torneo->limite); ?>
                <p class="my-3 border-top border-bottom">
                    Organizado por: <span class="forn-weight-bold"><?php echo $torneo->organizador; ?></span><br>
                    Email: <span class="forn-weight-bold"><a href="mailto:<?php echo $torneo->email; ?>"><?php echo $torneo->email; ?></a><br>
                        Teléfono: <a href="tel:+34<?php echo $torneo->telefono; ?>"><?php echo $torneo->telefono; ?></a></span><br>
                </p>
                <a href="<?php echo base_url(); ?>torneo/<?php echo $torneo->slug; ?>" class="btn btn-main">Entrar al torneo</a>
            </div>
        </div>
</div>