<div class="modal fade" id="carga_archivo" tabindex="-1" role="dialog" aria-labelledby="carga_archivo" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?php echo form_open_multipart('Equipos/deportistas_file_form', ['id' => 'carga_archivo_form']);?>
            <div class="bg-primary modal-header">
                <h3 class="modal-title"><span class="fw-mediumbold">Carga de deportistas desde archivo .csv</span></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <p>La primera fila no se lee, está reservada para posibles encabezados.</br>
                    El separador de columnas ha de ser punto y coma (;). Comprobar con un editor de texto que esto sea correcto.</pbr>
                    Las columnas con asterisco son obligatorias (*). El resto, pueden estar vacias.</br>
                    El orden de los datos de las columnas es el siguiente: (<a href="<?php echo base_url();?>assets/admin/csvfiles/plantillaimportardeportistas.csv">Descargar plantilla</a>)</p>
                    <ol>
                        <li><span class="font-weight-bold">Nombre *</span></li>
                        <li><span class="font-weight-bold">Apellidos *</span></li>
                        <li><span class="font-weight-bold">Fecha de nacimiento *</span></li>
                        <li><span class="font-weight-bold">Género * (M o F)</span></li>
                        <li>Peso</li>
                        <li>Cinturón en texto, colores intermedios separados con guión</li>
                        <li>Teléfono</li>
                        <li>Email</li>
                        <li>DNI</li>
                    </ol>
                </div>
                <?php if(isset($clubs)){ ?>
                    <div class="form-group">
                        <label for="club_id"><strong>Selecciona un equipo</strong></label>
                        <select class="form-control" name="club_id" id="club_id">
                            <option value="">Selecciona un equipo</option>
                            <?php
                                foreach ($clubs as $key => $club) {
                                 echo '<option value="'.$club->club_id.'">'.$club->nombre.'</option>';
                                }
                            ?>
                        </select>
                    </div>
                <?php } ?>
                <div class="form-group">
                    <label>Archivo .csv con los nuevos deportistas</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="deportistasfile" name="archivo" accept=".csv">
                        <label class="custom-file-label" for="deportistasfile">Selecciona el archivo .csv de tu equipo</label>
                    </div>
                </div>
                
                <div class="form-group">
                    <button type="button" class="btn btn-primary btn-sm" id="submit_deportistas_file_form">Guardar</button>
                    <?php if(isset($club)){ echo form_hidden('club_id', $club->club_id);}?>
                </div>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>

<div class="modal fade" id="carga_individual" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="bg-primary modal-header">
                <h3 class="modal-title"><span class="fw-mediumbold"></span></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
               <?php $this->load->view('gestion/equipos/equipos_deportistas_form');?>
            </div>
        </div>
    </div>
</div>