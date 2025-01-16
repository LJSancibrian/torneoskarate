<div class="card mt--3">
    <div class="card-header">
        <div class="d-flex align-items-center"></div>
        <div class="card-body">
            <div class="row border-bottom mb-3" id="add_inscripcion_form">
                <div class="col-12 col-sm-6 col-md-3 mb-3">
                    <label for="" class="col-12 mb-2">Deportista:</label>
                    <select name="deportista_id" id="deportista_id" class="form-control select2">
                        <option value=""></option>
                        <?php foreach ($deportistas as $key => $dep) { ?>
                            <option value="<?php echo $dep->id;?>"><?php echo $dep->first_name.' '.$dep->last_name.' - '.$dep->nombre;?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-3 mb-3">
                    <label for="" class="col-12 mb-2">Competición:</label>
                    <select name="competicion_nueva_torneo_id" id="competicion_nueva_torneo_id" class="form-control select2">
                        <option value=""></option>
                        <?php if(isset($m_kata)){foreach ($m_kata as $key => $categoria) {?>
                            <option value="<?php echo $categoria->competicion_torneo_id;?>">
                            <?php echo $categoria->modalidad;?> <?php echo $categoria->categoria;?> <?php echo $categoria->genero;?> <?php echo $categoria->nivel;?>
                            </option>
                        <?php } }?>

                        <?php if(isset($m_kumite)){foreach ($m_kumite as $key => $categoria) {?>
                            <option value="<?php echo $categoria->competicion_torneo_id;?>">
                            <?php echo $categoria->modalidad;?> <?php echo $categoria->categoria;?> <?php echo $categoria->genero;?> <?php echo $categoria->nivel;?>
                            </option>
                        <?php } }?>
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-3 mb-3">
                    <button class="btn btn-default" type="button" id="add_inscripcion">Añadir inscripción</button>
                </div>
            </div>
        
            <div class="row border-bottom mb-3" id="add_inscripcion_form">
            <div class="col-12 ">
                <h3>Copiar inscripciones de una categoria a otra</h3>
            </div>
                <div class="col-12 col-md-4 mb-3">
                    <label for="" class="col-12 mb-2">Competición de origen (copiar desde):</label>
                    <select name="competicion_origen" id="competicion_origen" class="form-control select2">
                        <option value="" disabled>KATA</option>
                        <?php foreach ($competicioneskata as $key => $c) { 
                            $nombre = $c->modalidad . ' ' .$c->categoria . ' ' . (($c->genero == 'M') ? 'Masculino' : (($c->genero == 'F') ? 'Femenino' : ' Mixto'));?>
                            <option value="<?php echo $c->competicion_torneo_id;?>"><?php echo $nombre; ?></option>
                        <?php } ?>
                        <option value="" disabled>KUMITE</option>
                        <?php foreach ($competicioneskumite as $key => $c) { 
                            $nombre = $c->modalidad . ' ' .$c->categoria . ' ' . (($c->genero == 'M') ? 'Masculino' : (($c->genero == 'F') ? 'Femenino' : ' Mixto'));?>
                            <option value="<?php echo $c->competicion_torneo_id;?>"><?php echo $nombre; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-12 col-md-4 mb-3">
                    <label for="" class="col-12 mb-2">Competición de destino (copiar hasta):</label>
                    <select name="competicion_destino" id="competicion_destino" class="form-control select2">
                    <option value="" disabled>KATA</option>
                        <?php foreach ($competicioneskata as $key => $c) { 
                            $nombre = $c->modalidad . ' ' .$c->categoria . ' ' . (($c->genero == 'M') ? 'Masculino' : (($c->genero == 'F') ? 'Femenino' : ' Mixto'));?>
                            <option value="<?php echo $c->competicion_torneo_id;?>"><?php echo $nombre; ?></option>
                        <?php } ?>
                        <option value="" disabled>KUMITE</option>
                        <?php foreach ($competicioneskumite as $key => $c) { 
                            $nombre = $c->modalidad . ' ' .$c->categoria . ' ' . (($c->genero == 'M') ? 'Masculino' : (($c->genero == 'F') ? 'Femenino' : ' Mixto'));?>
                            <option value="<?php echo $c->competicion_torneo_id;?>"><?php echo $nombre; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-12 col-md-4 mb-3">
                    <button class="btn btn-default" type="button" id="copy_inscripciones">Copiar inscripcionesde origen a destino</button>
                </div>
            </div>
       
            <div class="row border-bottom mb-3" id="filtros_inscripciones">
                <label for="" class="col-12 mb-2">Filtrar por:</label>
                <div class="col-12 col-sm-6 col-md-3 mb-3">
                    <select name="f_estado" id="f_estado" class="form-control">
                        <option value="">Por estado</option>
                        <option value="0">Pendiente</option>
                        <option value="1">Aceptada</option>
                        <option value="2">Rechazada</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-3 mb-3">
                    <select name="f_equipo" id="f_equipo" class="form-control">
                        <option value="">Por equipo</option>
                        <?php foreach ($clubs as $key => $club) {?>
                            <option value="<?php echo $club->club_id;?>"><?php echo $club->nombre;?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-3 mb-3">
                    <select name="f_modalidad" id="f_modalidad" class="form-control">
                        <option value="">Por modalidad</option>
                        <option value="KATA">Kata</option>
                        <option value="KUMITE">Kumite</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-3 mb-3">
                    <select name="f_t_categoria_id" id="f_t_categoria_id" class="form-control">
                        <option value="">Por categoria</option>
                        <?php if(isset($m_kata)){foreach ($m_kata as $key => $categoria) {?>
                            <option value="<?php echo $categoria->competicion_torneo_id;?>">
                            <?php echo $categoria->modalidad;?> <?php echo $categoria->categoria;?> <?php echo $categoria->genero;?> <?php echo $categoria->nivel;?>
                            </option>
                        <?php } }?>

                        <?php if(isset($m_kumite)){foreach ($m_kumite as $key => $categoria) {?>
                            <option value="<?php echo $categoria->competicion_torneo_id;?>">
                            <?php echo $categoria->modalidad;?> <?php echo $categoria->categoria;?> <?php echo $categoria->genero;?> <?php echo $categoria->nivel;?>
                            </option>
                        <?php } }?>
                    </select>
                </div>
            </div>
            <div class="table-responsive">
                <table id="table_inscripciones" class="display table table-striped table-sm w-100"></table>
            </div>
        </div>
    </div>
    <?php echo form_open();
    echo form_hidden('torneo_id', $torneo->torneo_id);
    echo form_close(); ?>
</div>