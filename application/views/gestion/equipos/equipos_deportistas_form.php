<?php echo form_open();?>
<div class="row">
	<div class="col-md-6">
		<h4>Datos obligatorios</h4>
		<div class="form-group">
			<label for="first_name_d"><strong>Nombre</strong></label>
			<input class="form-control" name="first_name_d" id="first_name_d" placeholder="Nombre" type="text" value="<?php echo (isset($deportista)) ? $depor->nombre : '';?>" />
		</div>
		<div class="form-group">
			<label for="last_name_d"><strong>Apellidos</strong></label>
			<input class="form-control" name="last_name_d" id="last_name_d" placeholder="Apellidos" type="text" value="<?php echo (isset($deportista)) ? $deportista->last_name : '';?>" />
		</div>
		<div class="form-group">
			<label for="dob"><strong>Fecha de nacimiento</strong></label>
			<input class="form-control" name="dob" id="dob" placeholder="Fecha de nacimiento" type="date" value="<?php echo (isset($deportista)) ? $deportista->dob : '';?>" />
		</div>

        <div class="form-group">
			<label for="email"><strong>Género / categoría</strong></label>
			<select class="form-control" name="genero" id="genero">
                <option value=""> Selecciona una opción</option>
                <option value="1" <?php echo (isset($deportista) && $deportista->genero == 1) ? 'selected' : '';?>>Masculino</option>
                <option value="2" <?php echo (isset($deportista) && $deportista->genero == 2) ? 'selected' : '';?>>Femenino</option>
            </select>
		</div>

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
			<label for="active_d">Estado</label>
			<label class="form-check-label ml-3">
				<input class="form-check-input" type="checkbox" id="active_d" name="active_d" value="OK" <?php echo (isset($deportista) && $deportista->active == 1) ? 'checked' : '';?>>
				<span class="form-check-sign">Deportista activo</span>
			</label>
		</div>
	</div>

<div class="col-md-6">
		<h4>Otros datos</h4>
		<div class="form-group">
			<label for="peso"><strong>Peso</strong></label>
			<input class="form-control" name="peso" id="peso" placeholder="Peso" type="number" value="<?php echo (isset($deportista)) ? $deportista->peso : '';?>" />
		</div>

        <div class="form-group">
			<label for="nivel"><strong>Nivel / cinturón</strong></label>
			<select class="form-control" name="nivel" id="nivel">
				<option value=""> Selecciona una opción</option>
                <option value="BLANCO" <?php echo (isset($deportista) && $deportista->nivel == 'BLANCO') ? 'selected' : '';?>>Blanco</option>
                <option value="BLANCO-AMARILLO" <?php echo (isset($deportista) && $deportista->nivel == 'BLANCO-AMARILLO') ? 'selected' : '';?>>Blanco - amarillo</option>
                <option value="AMARILLO" <?php echo (isset($deportista) && $deportista->nivel == 'AMARILLO') ? 'selected' : '';?>>Amarillo</option>
                <option value="AMARILLO-NARANJA" <?php echo (isset($deportista) && $deportista->nivel == 'AMARILLO-NARANJA') ? 'selected' : '';?>>Amarillo - naranja</option>
                <option value="NARANJA" <?php echo (isset($deportista) && $deportista->nivel == 'NARANJA') ? 'selected' : '';?>>Naranja</option>
                <option value="NARANJA-VERDE" <?php echo (isset($deportista) && $deportista->nivel == 'NARANJA-VERDE') ? 'selected' : '';?>>Naranja - verde</option>
                <option value="VERDE" <?php echo (isset($deportista) && $deportista->nivel == 'VERDE') ? 'selected' : '';?>>Verde</option>
                <option value="VERDE-AZUL" <?php echo (isset($deportista) && $deportista->nivel == 'VERDE-AZUL') ? 'selected' : '';?>>Verde - azul</option>
                <option value="AZUL" <?php echo (isset($deportista) && $deportista->nivel == 'AZUL') ? 'selected' : '';?>>Azul</option>
                <option value="AZUL-MARRON" <?php echo (isset($deportista) && $deportista->nivel == 'AZUL-MARRON') ? 'selected' : '';?>>Azul - marrón</option>
                <option value="MARRON" <?php echo (isset($deportista) && $deportista->nivel == 'MARRON') ? 'selected' : '';?>>Marrón</option>
                <option value="MARRON-NEGRO" <?php echo (isset($deportista) && $deportista->nivel == 'MARRON-NEGRO') ? 'selected' : '';?>>Marrón - negro</option>
                <option value="NEGRO" <?php echo (isset($deportista) && $deportista->nivel == 'NEGRO') ? 'selected' : '';?>>Negro</option>
            </select>
		</div>

        <div class="form-group">
			<label for="phone_d"><strong>Teléfono</strong></label>
			<input class="form-control" name="phone_d" id="phone_d" placeholder="Teléfono de contacto" type="tel" value="<?php echo (isset($deportista)) ? $deportista->phone : '';?>" />
		</div>

        <div class="form-group">
			<label for="email_d"><strong>Email</strong></label>
			<input class="form-control" name="email_d" id="email_d" placeholder="Email de contacto" type="email" value="<?php echo (isset($deportista)) ? $deportista->email : '';?>" />
		</div>

        <div class="form-group">
			<label for="dni_d"><strong>DNI, NIF , NIE</strong></label>
			<input class="form-control" name="dni_d" id="dni_d" placeholder="DNI, NIF , NIE" type="text" value="<?php echo (isset($deportista)) ? $deportista->dni : '';?>" />
		</div>

		<div class="form-group">
            <?php $deportista_club_id = (isset($club)) ? $club->club_id : 'nuevo';
			$deportista_id = (isset($deportista)) ? $deportista->id : 'nuevo';
            echo form_hidden('deportista_id', $deportista_id); ?>
			<button class="btn btn-primary btn-sm" type="button" id="submit-deportista-form">Guardar datos</button>
			<?php if(isset($club)){ echo form_hidden('club_id', $club->club_id);}?>
		</div>
	</div>
</div>
<?php echo form_close();?>
