<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<section class="portfolio mt-3" id="portfolio">
    <div class="container-fluid">
        <div class="row ">
                <div class="title text-center w-100 mt-5">
                                        <h2>Competiciones</h2>
                                        <div class="border"></div>
                                    </div>

            <?php if (count($kata) > 0) { ?>
                <div class="col-12 col-sm-6">
                    <div class="blog-slider">
                        <div class="blog-slider__content w-100 p-0 pb-3">
                            <div class="price-title">
                                <strong class="value p-0">KATA</strong>
                                <p></p>
                            </div>
                            <table class="table table-striped table-bordered">
                                <tbody>
                                    <?php foreach ($kata as $key => $competicion) {
                                        if ($competicion->estado < 3) { ?>
                                            <tr>
                                                <td><a href="<?php echo base_url(); ?>clasificacioncompeticiongrupo/<?php echo $competicion->competicion_torneo_id; ?>" class="align-items-center d-flex justify-content-between"><?php echo $competicion->categoria; ?> <?php echo $competicion->nivel; ?> - <?php echo ($competicion->genero == 'M') ? 'masculino' : (($competicion->genero == 'F') ? 'femenino' : 'mixto'); ?> <i class="tf-ion-podium" style="font-size: 1.5rem;"></i></a></td>
                                            </tr>
                                    <?php }
                                    } ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>

            <?php } ?>

            <?php if (count($kumite) > 0) { ?>
                <div class="col-12 col-sm-6">
                    <div class="blog-slider">
                        <div class="blog-slider__content w-100 p-0 pb-3">
                            <div class="price-title">
                                <strong class="value p-0">KUMITE</strong>
                                <p></p>
                            </div>
                            <table class="table table-striped table-bordered">
                                <tbody>
                                    <?php foreach ($kumite as $key => $competicion) {
                                        if ($competicion->estado < 3) { ?>
                                            <tr>
                                                <td><a href="<?php echo base_url(); ?>clasificacioncompeticiongrupo/<?php echo $competicion->competicion_torneo_id; ?>" class="align-items-center d-flex justify-content-between"><?php echo $competicion->categoria; ?> <?php echo $competicion->nivel; ?> - <?php echo ($competicion->genero == 'M') ? 'masculino' : (($competicion->genero == 'F') ? 'femenino' : 'mixto'); ?> <i class="tf-ion-podium" style="font-size: 1.5rem;"></i></a></td>
                                            </tr>
                                    <?php }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php } ?>

        </div>
    </div>
</section>