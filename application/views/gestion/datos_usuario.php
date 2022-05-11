<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Datos del usuario</h4>
			</div>
			<div class="card-body">
            <?php echo form_open();?>
            <div class="form-row">
                <div class="col-md-4">
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
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="email"><strong>Email</strong></label>
                        <input class="form-control" name="email" id="email" placeholder="tu email de acceso" type="email" value="<?php echo (isset($user)) ? $user->email : '';?>" />
                    </div>
                    <div class="form-group">
                        <label for="phone"><strong>Teléfono</strong></label>
                        <input class="form-control" name="phone" id="phone" placeholder="Teléfono" type="tel" value="<?php echo (isset($user)) ? $user->phone : '';?>" />
                    </div>

                    <?php if(!isset($perfil) || $perfil != TRUE){?>
                    <div class="form-group">
						<label for="rol">Rol</label>
						<select class="form-control" id="rol" name="rol">
							<?php if($this->user->group->id == 1){ ?><option value="1" <?php echo (isset($user) && $user->group->id == 1)?"selected":"";?>>Webmaster</option><?php } ?>
							<?php if($this->user->group->id == 1){ ?> <option value="2" <?php echo (isset($user) && $user->group->id ==  2)?"selected":"";?>>Admnistrador general</option><?php } ?>
							<?php if($this->user->group->id < 3){ ?> <option value="3" <?php echo (isset($user) && $user->group->id ==  3)?"selected":"";?>>Administrador</option><?php } ?>
							<?php if($this->user->group->id < 3){ ?> <option value="4" <?php echo (isset($user) && $user->group->id ==  4)?"selected":"";?>>Auxiliar</option><?php } ?>
						</select>
					</div>
                    <div class="form-check">
                        <label for="active">Estado</label>
						<label class="form-check-label ml-3">
							<input class="form-check-input" type="checkbox" id="active" name="active" value="OK" <?php echo (isset($user) && $user->active == 1) ? 'checked' : '';?>>
							<span class="form-check-sign">Usuario activo</span>
						</label>
					</div>
                    <?php } ?>

                    
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="newpassword"><strong>Nueva contraseña</strong></label>
                        <input class="form-control" name="newpassword" id="newpassword" type="password" <?php //pattern="(?=^.{8,}$)(?=.*\d)(?=.*\W+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" ?> placeholder="Mínimo 8 caracteres" />
                    </div>
                    <div class="form-group">
                        <label for="confirmpassword"><strong>Confirma la contraseña</strong></label>
                        <input class="form-control" name="confirmpassword" id="confirmpassword" type="password"  <?php //pattern="(?=^.{8,}$)(?=.*\d)(?=.*\W+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" ?> placeholder="Mínimo 8 caracteres" />
                    </div>
                    <?php /*<div class="form-group">
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
                    </div>*/?>
                </div>
                <div class="form-group">
                    <?php if(isset($user)){ ?>
                        <?php echo form_hidden('userID', $user->id);?>
                        <button class="btn btn-primary btn-sm" type="button" id="guardar-datos">Guardar datos</button>
                    <?php }else{ ?>
                        <button class="btn btn-primary btn-sm" type="button" id="crear-usuario">Crear usuario</button>
                    <?php } ?>
                    
                </div>
                <?php echo form_close();?>
            </div>
        </div>
		</div>
	</div>
</div>
