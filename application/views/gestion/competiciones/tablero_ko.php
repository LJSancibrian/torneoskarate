<div class="row">
    <div class="col-md-12">
        <div class="card" id="tablero-competicion-card">
            <div class="card-header d-flex justify-content-between">
                <div class="card-title fw-mediumbold"><?php echo $competicion->modalidad.' '.$competicion->categoria.' '.$competicion->nivel;?> - <?php echo ($competicion->genero == 'M') ? 'Masculino' : (($competicion->genero == 'F') ? 'Femenino' : 'Mixto');?></div>
                <button class="btn btn-icon btn-primary btn-round btn-xs" data-generar-tablero="<?php echo $competicion->competicion_torneo_id; ?>" data-toggle="tooltip" title="Generar tablero">
                    <i class="fas fa-random"></i>
                </button>
                <a href="<?php echo base_url();?>Competiciones/pdfdoc/<?php echo $competicion->competicion_torneo_id;?>" target="_blankl" class="btn btn-icon btn-primary btn-round btn-xs" title="Guardar imagen tablero" target="_blank">
                    <i class="fas fa-file-pdf"></i>
                </a>
            </div>
            <div class="card-body bg-white" id="tablero-competicion">
                <h4 class="card-title d-flex justify-content-between align-items-center">Eliminatorias <span id="totalcomabtes"></span></h4>
                <div id="faseeliminatorias">
                    <div class="brackets"></div>
                </div>
                <button type="button" data-guardar-orden-kumite="<?php echo $competicion->competicion_torneo_id; ?>" class="btn btn-sm btn-default m-3">Guardar grupos</button>
            </div>
        </div>
    </div>
</div>