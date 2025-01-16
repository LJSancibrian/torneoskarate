<?php echo form_open();?>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
 			<label for="categoria"><strong>Nombre de la competición</strong></label>
 			<input class="form-control" name="categoria" id="categoria" placeholder="Nombre de la competición" type="text" value="<?php if(isset($competicion)){echo $competicion->categoria;}?>" />
 		</div>
        
         <div class="form-group">
 			<label for="modalidad"><strong>Modalidad</strong></label>
 			<select class="form-control" name="modalidad" id="modalidad">
                 <option value="kata" <?php if(isset($competicion) && $competicion->modalidad == 'kata'){echo 'selected';}?>>Kata</option>
                 <option value="kumite" <?php if(isset($competicion) && $competicion->modalidad == 'kumite'){echo 'selected';}?>>Kumite</option>
             </select>
 		</div>

        <div class="form-group">
 			<label for="genero"><strong>Género</strong></label>
 			<select class="form-control" name="genero" id="genero">
                 <option value="M" <?php if(isset($competicion) && $competicion->genero == 'M'){echo 'selected';}?>>Masculino</option>
                 <option value="F" <?php if(isset($competicion) && $competicion->genero == 'F'){echo 'selected';}?>>Femenino</option>
                 <option value="X" <?php if(isset($competicion) && $competicion->genero == 'x'){echo 'selected';}?>>Mixto</option>
             </select>
 		</div>

        <div class="form-group">
 			<label for="nivel"><strong>Peso / nivel</strong></label>
 			<input class="form-control" name="nivel" id="nivel" placeholder="Peso / nivel" type="text" value="<?php if(isset($competicion)){echo $competicion->nivel;}?>" />
 		</div>

         <div class="form-group">
            <?php echo form_hidden('competicion_id',(isset($competicion)) ? $competicion->competicion_id : 'nuevo');?>
            <button class="btn btn-primary btn-sm" type="button" id="submit-competicion-form">Guardar datos</button>
        </div>
    </div>
</div>
<?php echo form_close();?>
