<?php echo form_open();?>
<div class="row">
	<div class="col-md-6">
		<div class="form-group">
			<label for="first_name"><strong>Nombre</strong></label>
			<input class="form-control" name="first_name" id="first_name" placeholder="Nombre" type="text" value="<?php echo (isset($user)) ? $user->first_name : '';?>" />
		</div>
		<div class="form-group">
			<label for="last_name"><strong>Apellidos</strong></label>
			<input class="form-control" name="last_name" id="last_name" placeholder="Apellidos" type="text" value="<?php echo (isset($user)) ? $user->last_name : '';?>" />
		</div>
		<div class="form-group">
			<label for="dni"><strong>DNI, NIF, NIE...</strong></label>
			<input class="form-control" name="dni" id="dni" placeholder="DNI, NIF, NIE..." type="text" value="<?php echo (isset($user)) ? $user->dni : '';?>" />
		</div>
		<div class="form-group">
			<label for="email"><strong>Email</strong></label>
			<input class="form-control" name="email" id="email" placeholder="tu email de acceso" type="email" value="<?php echo (isset($user)) ? $user->email : '';?>" />
		</div>
		<div class="form-group">
			<label for="phone"><strong>Teléfono</strong></label>
			<input class="form-control" name="phone" id="phone" placeholder="Teléfono" type="tel" value="<?php echo (isset($user)) ? $user->phone : '';?>" />
		</div>
	</div>

	<div class="col-md-6">
		<div class="form-check">
			<label for="active">Estado</label>
			<label class="form-check-label ml-3">
				<input class="form-check-input" type="checkbox" id="active" name="active" value="OK" <?php echo (isset($user) && $user->active == 1) ? 'checked' : '';?>>
				<span class="form-check-sign">Usuario activo</span>
			</label>
		</div>
		<div class="form-group">
			<label for="newpassword"><strong>Nueva contraseña</strong></label>
			<input class="form-control" name="newpassword" id="newpassword" type="password" pattern="(?=^.{8,}$)(?=.*\d)(?=.*\W+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" />
		</div>
		<div class="form-group">
			<label for="confirmpassword"><strong>Confirma la contraseña</strong></label>
			<input class="form-control" name="confirmpassword" id="confirmpassword" type="password" pattern="(?=^.{8,}$)(?=.*\d)(?=.*\W+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" />
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
            <?php $user_id = (isset($user)) ? $user->id : 'nuevo';
            echo form_hidden('user_id', $user_id); ?>
			<button class="btn btn-primary btn-sm" type="button" id="submit-usuario-form">Guardar datos</button>
		</div>
	</div>
</div>
<?php echo form_close();?>
