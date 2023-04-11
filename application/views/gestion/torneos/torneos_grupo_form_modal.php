<div class="modal fade" id="modal_crear_grupo" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="bg-primary modal-header">
				<h3 class="modal-title"><span class="fw-mediumbold"></span></h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<?php echo form_open(); ?>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label for="titulo"><strong>Nombre del grupo</strong></label>
							<input class="form-control" name="titulo" id="titulo" placeholder="Nombre del grupo" type="text" value="" />
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label for="descripcion"><strong>Texto promocional</strong></label>
							<textarea name="descripcion" id="descripcion" rows="4" class="form-control"></textarea>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label for="direccion"><strong>Torneos</strong></label>
							<?php foreach ($torneos as $t => $torneo) { ?>
							<label class="form-check-label ml-3 d-block">
								<input class="form-check-input" type="checkbox" id="torneo_id_<?=$t?>" name="torneo_id[]" value="<?=$torneo->torneo_id?>" >
								<span class="form-check-sign"><?=$torneo->titulo?></span>
							</label>
							<?php } ?>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label for="estado"><strong>Estado</strong></label>
							<div class="form-check">
								<label class="form-check-label ml-3">
									<input class="form-check-input" type="checkbox" id="estado" name="estado" value="OK" >
									<span class="form-check-sign">Grupo activo, visible</span>
								</label>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<?php echo form_hidden('grupo_id', (isset($grupo)) ? $grupo->grupo_id : 'nuevo'); ?>
							<button class="btn btn-primary btn-sm" type="button" id="submit-grupo-form">Guardar</button>
						</div>
					</div>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>