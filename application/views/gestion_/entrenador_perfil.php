<div class="row">
	<div class="col-md-8">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Datos del entrenador</h4>
			</div>
			<div class="card-body">
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

                            <div class="form-group">
                                <button class="btn btn-primary btn-sm" type="button" id="crear-entrenador">Crear entrenador</button>
                            </div>
                        </div>
                    </div>
                <?php echo form_close();?>
            </div>
		</div>
	</div>

    <div class="col-md-4">
		<div class="card card-round">
            <div class="card-body">
                <div class="card-title fw-mediumbold">Escuelas</div>
                    <div class="card-list">
                        <?php foreach ($clubs as $key => $club) { ?>
                                <div class="item-list">
                                    <div class="info-user ml-3">
                                        <h4><?php echo $club->nombre;?></dh4>
                                    </div>
                                    <button class="btn btn-icon btn-primary btn-round btn-xs" data-formulario="#formulario_club" data-club_id="<?php echo $club->club_id;?>">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>				
                        <?php }?>
                        <div class="item-list">
                            <div class="info-user ml-3">
                                <h4>Añadir elemento</dh4>
                            </div>
                            <button class="btn btn-icon btn-primary btn-round btn-xs" data-formulario="#formulario_club" data-club_id="nuevo">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="formulario_club" tabindex="-1" aria-labelledby="formulario_club" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
            <div class="card-title fw-mediumbold">Crear escuela</div>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <?php $this->load->view('gestion/club_form');?>
            </div>
		</div>
	</div>
</div>