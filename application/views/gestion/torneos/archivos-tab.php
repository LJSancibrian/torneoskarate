<div class="card mt--3">
    <div class="card-body">
        <h4 class="border-bottom card-title font-weight-bold mb-4 text-uppercase">Archivos y documentos</h4>
        <div class="row">
            <div class="col-md-3">
                <h3>Cartel del torneo</h3>
                <img src="<?php echo ($torneo->cartel != '') ? $torneo->cartel : base_url().'assets/img/placeholder1.jpg';?>" id="preview" class="img-thumbnail">
                <div id="msg"></div>
                <?php echo form_open_multipart('Archivos/cartel_torneo', ['id'=>"image-form"]);?>
                    <input type="file" name="archivo" accept="image/*" style="visibility: hidden;position: absolute;" preview="#preview" text="#file">
                    <div class="input-group my-3">
                        <input type="text" class="form-control form-control-sm" disabled placeholder="Selecciona una imagen" id="file">
                        <div class="input-group-append">
                            <button type="button" class="browse btn btn-primary btn-sm"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                    <?php echo form_hidden('torneo_id', $torneo->torneo_id);?>
                    <button type="sumbit" class="btn btn-primary btn-block">Guardar</button>
                <?php echo form_close();?>
            </div>
            <div class="col-md-9">
                <h3 class="d-flex justify-content-between">Otros archivos y documentos 
                
                <button name="crear_archivo" type="button" data-tooltip="1" data-editar-archivo="nuevo" title="Crear nuevo archivo" data-original-title="Crear nuevo archivo" class="btn btn-sm btn-primary ml-auto"><i class="fa fa-plus mr-3"></i> Nuevo archivo</button></h3>


                <div class="table-responsive">
                    <table class="table table-sortable w-100" id="tabla_archivos" data-default="<?php echo $torneo->torneo_id;?>"></table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('gestion/archivos/archivo_form_modal');?>