<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="blog-slider row w-100 mb-3">
    <div class="col py-3">
        <div class="text-center">
            <img src="<?php echo $torneo->cartel;?>" alt="" class="img-fluid">
            <div class="blog-slider__title mt-3"><?php echo $torneo->titulo;?></div>
            <span class="blog-slider__code"><?php echo fechaCastellano($torneo->fecha);?></span>
            <a href="<?php echo base_url();?>torneo/<?php echo $torneo->slug;?>" class="btn btn-main">Ver el torneo</a>
        </div>
    </div>
</div>