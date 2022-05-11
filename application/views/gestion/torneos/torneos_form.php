<?php echo form_open();?>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
 			<label for="titulo"><strong>Nombre del torneo</strong></label>
 			<input class="form-control" name="titulo" id="titulo" placeholder="Nombre del torneo" type="text" value="<?php if(isset($torneo)){echo $torneo->titulo;}?>" />
 		</div>
    </div>

	<div class="col-md-6">
        <div class="form-group">
 			<label for="descripcion"><strong>Texto promocional</strong></label>
             <textarea name="descripcion" id="descripcion" rows="4" class="form-control"><?php if(isset($torneo)){echo $torneo->descripcion;}?></textarea>
 		</div>
        <div class="form-group">
 			<label for="organizador"><strong>Organizador</strong></label>
 			<input class="form-control" name="organizador" id="organizador" placeholder="Organizador del torneo" type="text" value="<?php if(isset($torneo)){echo $torneo->organizador;}?>" />
 		</div>

 		<div class="form-group">
 			<label for="email"><strong>Email de contacto</strong></label>
 			<input class="form-control" name="email" id="email" placeholder="Email de contacto" type="email" value="<?php if(isset($torneo)){echo $torneo->email;}?>" />
 		</div>

 		<div class="form-group">
 			<label for="telefono"><strong>Teléfono de contacto</strong></label>
 			<input class="form-control" name="telefono" id="telefono" placeholder="Teléfono de contacto" type="tel" value="<?php if(isset($torneo)){echo $torneo->telefono;}?>" />
 		</div>
         </div>
	<div class="col-md-6">
        <div class="form-group">
 			<label for="direccion"><strong>Dirección / localización</strong></label>
 			<input class="form-control" name="direccion" id="direccion" placeholder="Direccion / Localización" type="text" value="<?php if(isset($torneo)){echo $torneo->direccion;}?>" />
 		</div>
         <div class="form-group">
 			<label for="tipo"><strong>Modalidades</strong></label>
 			<select class="form-control" name="tipo" id="tipo">
                 <option value="0" <?php if(isset($torneo) && $torneo->tipo == 0){echo 'selected';}?>>Kata y Kumite</option>
                 <option value="1" <?php if(isset($torneo) && $torneo->tipo == 1){echo 'selected';}?>>Kata</option>
                 <option value="2" <?php if(isset($torneo) && $torneo->tipo == 2){echo 'selected';}?>>Kumite</option>
             </select>
 		</div>

        <div class="form-group">
 			<label for="fecha"><strong>Fecha inicial del torneo</strong></label>
 			<input class="form-control" name="fecha" id="fecha" placeholder="YYYY-MM-DD" type="date" value="<?php if(isset($torneo)){echo $torneo->fecha;}?>" />
 		</div>

 		<div class="form-group">
 			<label for="limite"><strong>Fecha límite de inscripciones</strong></label>
 			<input class="form-control" name="limite" id="limite" placeholder="YYYY-MM-DD" type="date" value="<?php if(isset($torneo)){echo $torneo->limite;}?>" />
 		</div>

 		<div class="form-check">
 			<label for="estado">Estado</label>
 			<label class="form-check-label ml-3">
 				<input class="form-check-input" type="checkbox" id="estado" name="estado" value="OK" <?php if(isset($torneo) && $torneo->estado == 1){echo 'checked';}?>>
 				<span class="form-check-sign">Torneo activo</span>
 			</label>
 		</div>

        <div class="form-group">
            <?php echo form_hidden('torneo_id',(isset($torneo)) ? $torneo->torneo_id : 'nuevo');?>
            <button class="btn btn-primary btn-sm" type="button" id="submit-torneo-form">Guardar datos</button>
        </div>
	</div>
</div>
<?php echo form_close();?>
