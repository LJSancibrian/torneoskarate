<div class="card mt--3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="card card-dark bg-secondary-gradient mb-0">
                    <div class="card-body bubble-shadow">
                        <h1><?php echo count($clubs); ?></h1>
                        <h3 class="op-8">Equipos participantes</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-dark bg-secondary2 mb-0">
                    <div class="card-body curves-shadow">
                        <h1><?php echo count($entrenadores); ?></h1>
                        <h3 class="op-8">Entrenadores</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-dark bg-secondary mb-0">
                    <div class="card-body skew-shadow">
                        <h1><?php echo count($inscripciones); ?></h1>
                        <h3 class="op-8">Inscripciones realizadas</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="border-bottom card-title font-weight-bold mb-4 text-uppercase">Información</h4>
                <div class="item-list border-bottom pt-3">
                    <h4><?php echo $torneo->descripcion; ?></h4>
                </div>
                <div class="item-list border-bottom pt-3">
                    <h4><i class="fas mr-3  fa-calendar"></i> <?php echo fechaCastellano($torneo->fecha); ?></h4>
                </div>
                <div class="item-list border-bottom pt-3">
                    <h4><i class="fas mr-3  fa-location-arrow"></i> <?php echo $torneo->direccion;; ?></h4>
                </div>
                <div class="item-list border-bottom pt-3">
                    <h4><i class="fas mr-3  fa-calendar-check"></i> Inscripciones hasta <?php echo fechaCastellano($torneo->limite); ?></h4>
                </div>
                <div class="item-list border-bottom pt-3">
                    <h4><i class="fas mr-3  fa-university"></i> Organiza <?php echo $torneo->organizador; ?></h4>
                </div>
                <div class="item-list border-bottom pt-3">
                    <h4><i class="fas mr-3  fa fa-envelope" aria-hidden="true"></i> <?php echo $torneo->email; ?></h4>
                </div>
                <div class="item-list border-bottom pt-3">
                    <h4><i class="fas mr-3  fa-phone"></i> <?php echo $torneo->telefono; ?></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h4 class="border-bottom card-title font-weight-bold mb-4 text-uppercase">Cartel</h4>
                <img src="<?php echo ($torneo->cartel != '') ? $torneo->cartel : base_url() . 'assets/img/placeholder1.jpg'; ?>" id="preview" class="img-thumbnail">
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
            <h4 class="border-bottom card-title font-weight-bold mb-4 text-uppercase">Documentos importantes</h4>
                <div class="card-list">             
                    <?php foreach ($archivos as $key => $archivo) { ?>
                    <div class="item-list">
                        <p></p>
                        <a href="<?php echo $archivo->url;?>" class="badge badge-primary w-100" target="_blank"><?php echo $archivo->titulo;?> <i class="fas fa-download ml-3"></i>
                    </a>
                    </div>
                   <?php }?>
                </div>
            </div>
        </div>
    </div>
</div>