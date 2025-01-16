<?php echo form_open_multipart(); ?>
<div class="row">
	<div class="col-md-4">
		<div class="form-group">
			<label for="nombre_club"><strong>Nombre</strong></label>
			<input class="form-control" name="nombre_club" id="nombre_club" placeholder="Nombre" type="text" value="<?php echo (isset($club)) ? $club->nombre : ''; ?>" />
		</div>

		<div class="form-group">
			<img src="<?php echo ($club->img != '') ? $club->img : base_url() . 'assets/img/placeholder.jpg'; ?>" id="preview" class="img-thumbnail">
			<input type="file" name="archivo" accept="image/*" style="visibility: hidden;position: absolute;" preview="#preview" text="#file">
			<div class="input-group my-3">
				<input type="text" class="form-control form-control-sm" disabled placeholder="Selecciona una imagen" id="file">
				<div class="input-group-append">
					<button type="button" class="browse btn btn-primary btn-sm"><i class="fas fa-search"></i></button>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-8">
		<div class="form-group">
			<label for="descripcion_club"><strong>Información de interés</strong></label>
			<textarea name="descripcion_club" id="descripcion_club" rows="6" class="form-control"><?php echo (isset($club)) ? $club->descripcion : ''; ?></textarea>
		</div>

		<div class="form-group">
			<label for="direccion_club"><strong>Dirección</strong></label>
			<input class="form-control" name="direccion_club" id="direccion_club" placeholder="Direccion de la sede" type="text" value="<?php echo (isset($club)) ? $club->direccion : ''; ?>" />
		</div>

		<div class="form-group">
			<label for="email_club"><strong>Email de contacto</strong></label>
			<input class="form-control" name="email" id="email_club" placeholder="Email de contacto" type="email" value="<?php echo (isset($club)) ? $club->email : ''; ?>" />
		</div>

		<div class="form-group">
			<label for="telefono_club"><strong>Teléfono de contacto</strong></label>
			<input class="form-control" name="telefono_club" id="telefono_club" placeholder="Teléfono de contacto" type="tel" value="<?php echo (isset($club)) ? $club->telefono : ''; ?>" />
		</div>

		<div class="form-group">
			<label for="web_club"><strong>WEB / Link de perfil online (url completa)</strong></label>
			<input class="form-control" name="web" id="web_club" placeholder="Página web, página de FB, Instagram..." type="text" value="<?php echo (isset($club)) ? $club->web : ''; ?>" />
		</div>

		<div class="form-check">
			<label for="estado_club">Estado</label>
			<label class="form-check-label ml-3">
				<input class="form-check-input" type="checkbox" id="estado_club" name="estado_club" value="OK" <?php echo (isset($club) && $club->estado == 1) ? 'checked' : ''; ?>>
				<span class="form-check-sign">Equipo activo</span>
			</label>
		</div>

		<div class="form-group">
			<?php echo (isset($user)) ? form_hidden('user_id_club', $user->id) : form_hidden('user_id_club', ''); ?>
			<?php echo (isset($club)) ? form_hidden('club_id', $club->club_id) : form_hidden('club_id', ''); ?>
			<button class="btn btn-primary btn-sm" type="button" id="submit-club-form">Guardar datos</button>
		</div>
	</div>
</div>
<?php echo form_close(); ?>