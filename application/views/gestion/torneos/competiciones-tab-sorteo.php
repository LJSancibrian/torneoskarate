
    <div class="row">
    <div class="col-md-4 col-xl-3">
        <?php if ($torneo->tipo != 2) { ?>
            <div class="card">
                <div class="card-header">
                    <div class="card-title fw-mediumbold">KATA</div>
                </div>
                <div class="list-group">
                    <?php foreach ($c_kata as $key => $value) { ?>
                        <div class="list-group-item list-group-item-action justify-content-between">
                            <span><?php echo $value->categoria . ' ' . $value->genero . ' <span class="text-nowrap">' . $value->peso . '</span>'; ?></span>
                            <button class="btn btn-icon btn-primary btn-round btn-xs" data-competicion="<?php echo $value->competicion_torneo_id; ?>">
                                <i class="fa fa-arrow-right"></i>
                            </button>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <?php if ($torneo->tipo != 1) { ?>
            <div class="card">
                <div class="card-header">
                    <div class="card-title fw-mediumbold">KUMITE</div>
                </div>
                <div class="list-group">
                    <?php foreach ($c_kumite as $key => $value) { ?>
                        <div class="list-group-item list-group-item-action justify-content-between">
                            <span><?php echo $value->categoria . ' ' . $value->genero . ' <span class="text-nowrap">' . $value->peso . '</span>'; ?></span>
                            <button class="btn btn-icon btn-primary btn-round btn-xs" data-competicion="<?php echo $value->competicion_torneo_id; ?>">
                                <i class="fa fa-arrow-right"></i>
                            </button>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>

    <div class="col-md-3">
        <div class="card ">
            <div class="card-header">
                <div class="card-title fw-mediumbold">Deportistas</div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <tbody id="list-deportistas"></tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="col-md-5 col-xl-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="card-title fw-mediumbold">Tablero competición</div>
                <button class="btn btn-icon btn-primary btn-round btn-xs" data-generar-tablero="" data-toggle="tooltip" title="Generar tablero">
                    <i class="fas fa-sitemap"></i>
                </button>
            </div>
            <div class="card-body p-1" id="tablero-competicion">
            </div>
        </div>
    </div>
</div>
    