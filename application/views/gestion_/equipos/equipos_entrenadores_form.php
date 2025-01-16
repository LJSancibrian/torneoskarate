<?php echo form_open(); ?>
<div class="row">
	<div class="col-md-6">
		<div class="form-group">
			<label for="first_name_e"><strong>Nombre</strong></label>
			<input class="form-control" name="first_name_e" id="first_name_e" placeholder="Nombre" type="text" value="" />
		</div>
		<div class="form-group">
			<label for="last_name_e"><strong>Apellidos</strong></label>
			<input class="form-control" name="last_name_e" id="last_name_e" placeholder="Apellidos" type="text" value="" />
		</div>
		<div class="form-group">
			<label for="dni_e"><strong>DNI, NIF, NIE...</strong></label>
			<input class="form-control" name="dni_e" id="dni_e" placeholder="DNI, NIF, NIE..." type="text" value="" />
		</div>
		<div class="form-group">
			<label for="email_e"><strong>Email</strong></label>
			<input class="form-control" name="email_e" id="email_e" placeholder="tu email de acceso" type="email" value="" />
		</div>
		<div class="form-group">
			<label for="phone_e"><strong>Teléfono</strong></label>
			<input class="form-control" name="phone_e" id="phone_e" placeholder="Teléfono" type="tel" value="" />
		</div>
	</div>

	<div class="col-md-6">
		<?php if (isset($clubs)) { ?>
			<div class="form-group">
				<label for="club_id"><strong>Selecciona un equipo</strong></label>
				<select class="form-control" name="club_id" id="club_id">
					<option value="">Selecciona un equipo</option>
					<?php
					foreach ($clubs as $key => $club) {
						echo '<option value="' . $club->club_id . '">' . $club->nombre . '</option>';
					}
					?>
				</select>
			</div>
		<?php } ?>
		<div class="form-check">
			<label for="active_e">Estado</label>
			<label class="form-check-label ml-3">
				<input class="form-check-input" type="checkbox" id="active_e" name="active_e" value="OK">
				<span class="form-check-sign">Usuario activo</span>
			</label>
		</div>
		<div class="form-group">
			<label for="newpassword_e"><strong>Nueva contraseña</strong></label>
			<input class="form-control" name="newpassword_e" id="newpassword_e" type="password" pattern="(?=^.{8,}$)(?=.*\d)(?=.*\W+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" />
		</div>
		<div class="form-group">
			<label for="confirmpassword_e"><strong>Confirma la contraseña</strong></label>
			<input class="form-control" name="confirmpassword_e" id="confirmpassword_e" type="password" pattern="(?=^.{8,}$)(?=.*\d)(?=.*\W+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" />
		</div>
		<div class="form-group">
			<div class="bg-light p-3 border">
				<p class="mb-0"><small>Contraseñas compuestas al menos:</small></p>
				<small>
					<ul class="text-left">
						<li>por 8 caracteres</li>
						<li>una letra mayúscula</li>
						<li>una letra minúscula</li>
						<li>un número </li>
						<li>un uno de los siguientes caracteres: ¡!¿?*-$%</li>
					</ul>
				</small>
			</div>
		</div>

		<div class="form-group">
			<?php echo form_hidden('entrenador_id', 'nuevo'); ?>
			<?php if(isset($club)){ echo form_hidden('club_id', $club->club_id);}?>
			<button class="btn btn-primary btn-sm" type="button" id="submit-coach-form">Guardar datos</button>
		</div>
	</div>
</div>
<?php echo form_close(); ?>