<?php echo form_open_multipart('Archivos/manageFiles'); ?>
<div class="row">
	<div class="col-md-12">
		
		<div class="form-group">
			<input type="file" name="documento" style="visibility: hidden;position: absolute;" text="#filearchivo">
			<div class="input-group my-3">
				<input type="text" class="form-control form-control-sm" disabled placeholder="Selecciona un archivo" id="filearchivo">
				<div class="input-group-append">
					<button type="button" class="browse btn btn-primary btn-sm"><i class="fas fa-search"></i></button>
				</div>
			</div>
		</div>
		
	</div>

	<div class="col-md-6">
		<div class="form-group">
			<label for="titulo"><strong>Nombre del archivo</strong></label>
			<input class="form-control" name="titulo" id="titulo" placeholder="Nombre del archivo" type="text" value="<?php if (isset($archivo)) {echo $archivo->titulo;} ?>" />
		</div>

		<div class="form-group">
			<label for="tipo"><strong>Clasificación / Categoría</strong></label>
			<input class="form-control" name="tipo" id="tipo" placeholder="Tipo de clasificación / categoría" type="text" value="<?php if (isset($archivo)) {echo $archivo->tipo;} ?>" />
		</div>

		<div class="form-group">
			<label for="acceso"><strong>Usuarios con acceso</strong></label>
			<select class="form-control" id="acceso" name="acceso">
				<?php if ($this->user->group->id == 1) { ?><option value="1" <?php echo (isset($archivo) && $archivo->acceso == 1) ? "selected" : ""; ?>>Webmaster</option><?php } ?>
				<?php if ($this->user->group->id == 1) { ?> <option value="2" <?php echo (isset($archivo) && $archivo->acceso ==  2) ? "selected" : ""; ?>>Admnistrador general</option><?php } ?>
				<?php if ($this->user->group->id < 3) { ?> <option value="3" <?php echo (isset($archivo) && $archivo->acceso ==  3) ? "selected" : ""; ?>>Administrador</option><?php } ?>
				<?php if ($this->user->group->id < 3) { ?> <option value="4" <?php echo (isset($archivo) && $archivo->acceso ==  4) ? "selected" : ""; ?>>Auxiliar</option><?php } ?>
				<?php if ($this->user->group->id < 3) { ?> <option value="5" <?php echo (isset($archivo) && $archivo->acceso ==  5) ? "selected" : ""; ?>>Entrenadores</option><?php } ?>
				<?php if ($this->user->group->id < 3) { ?> <option value="6" <?php echo (isset($archivo) && $archivo->acceso ==  6) ? "selected" : ""; ?>>Deportistas</option><?php } ?>
			</select>
		</div>
	</div>

	<div class="col-md-6">
		<div class="form-group">
			<label for="item_rel"><strong>Elemento Relacionado</strong></label>
			<input class="form-control" name="item_rel" id="item_rel" placeholder="Organizador del archivo" type="text" value="<?php if (isset($archivo)) {echo $archivo->item_rel;} else {if (isset($torneo)) {echo 'torneo';}} ?>" readonly />
		</div>

		<div class="form-group">
			<label for="item_id"><strong>Elemento (ID) relacionado</strong></label>
			<input class="form-control" name="item_id" id="item_id" placeholder="Email de contacto" type="text" value="<?php if (isset($archivo)) {echo $archivo->item_id;} else {if (isset($torneo)) {echo $torneo->torneo_id;}} ?>" readonly />
		</div>

		<div class="form-group">
			<label for="estado"><strong>Estado</strong></label>
			<select class="form-control" id="estado" name="estado">
				<option value="no disponible" <?php echo (isset($archivo) && $archivo->estado == 'no disponible') ? "selected" : ""; ?>>No disponbible / sin publicar</option>
				<option value="disponible" <?php echo (isset($archivo) && $archivo->estado ==  'disponible') ? "selected" : ""; ?>>Disponible / publicado</option>
			</select>
		</div>

		<div class="form-group">
            <?php echo form_hidden('archivo_id',(isset($archivo)) ? $archivo->archivo_id : 'nuevo');?>
            <button class="btn btn-primary btn-sm" type="submit" id="submit-archivo-form">Guardar datos</button>
        </div>
	</div>

</div>
<?php echo form_close(); ?>