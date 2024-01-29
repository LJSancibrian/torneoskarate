<?php echo form_open();
echo form_hidden('torneo_id', $torneo->torneo_id);
echo form_close();?>
<div class="row">
<?php if($torneo->tipo != 2){?>
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <select class="form-control form-control-sm float-right w-auto border-secondary" data-copiar-categorias="KATA">
                    <option>Copiar categorias KATA de torneo</option>
                    <?php foreach ($torneos as $key => $torneo) {
                       echo '<option value="'.$torneo->torneo_id.'">'.$torneo->titulo.'</option>'; 
                    } ?>
                </select>
                <h4 class="border-bottom card-title font-weight-bold mb-4 text-uppercase">Kata </h4>
                <table class="table table-border">
                    <thead>
                        <tr>
                            <th>Categoría</th>
                            <th>Género</th>
                            <th>Peso / nivel</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody data-tipo="KATA">
                        <?php foreach ($competicioneskata as $key => $value) {
                            $tr = '<tr data-competicion_torneo_id="' . $value->competicion_torneo_id . '" data-modalidad="'.$value->modalidad .'">
                                <td><input type="text" name="categoria_kata" class="form-control" placeholder="Texto. Ej: Alevín" value="' . $value->categoria . '" disabled></td>
                                <td>
                                    <select name="genero_kata" class="form-control" disabled>';
                                $selected = ($value->genero == 'M') ? "selected" : "";
                                $tr .= '<option value="M" ' . $selected . '>Masculino</option>';
                                $selected = ($value->genero == 'F') ? "selected" : "";
                                $tr .= '<option value="F" ' . $selected . '>Femenino</option>';
                                $selected = ($value->genero == 'X') ? "selected" : "";
                                $tr .= '<option value="X" ' . $selected . '>Mixto</option>';
                                $tr .= '</td>
                                <td><input type="text" name="nivel_kata" class="form-control" placeholder="Texto. Ej: < 40 Kg, ej: inicial" value="' .$value->nivel . '" disabled></td>
                                <td class="text-truncate">
                                <button type="button" class="btn btn-sm btn-icon btn-round btn-warning" data-edit="rowkata" data-accion="editar"><i class="fas fa-edit"></i>
                                <button type="button" class="btn btn-sm btn-icon btn-round btn-danger" data-del="rowkata"><i class="fas fa-trash"></i>
                                </td>
                                </tr>';
                            echo $tr;
                        } ?>
                        <tr id="addrow">
                            <td><input type="text" name="categoria_kata" class="form-control" placeholder="Texto. Ej: Alevín"></td>
                            <td>
                                <select name="genero_kata" class="form-control">
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                    <option value="X">Mixto</option>
                                </td>
                                <td><input type="text" name="nivel_kata" class="form-control" placeholder="Texto. Ej: < 40 Kg, ej: inicial"></td>
                                <td><button type="button" class="btn btn-icon btn-round btn-primary" data-add-row="kata" id="addrowkata"><i class="fas fa-plus"></i></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php } ?>

<?php if($torneo->tipo != 1){?>
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
            <select class="form-control form-control-sm float-right w-auto border-secondary" data-copiar-categorias="KUMITE">
                    <option>Copiar categorias KUMITE de torneo</option>
                    <?php foreach ($torneos as $key => $torneo) {
                       echo '<option value="'.$torneo->torneo_id.'">'.$torneo->titulo.'</option>'; 
                    } ?>
                </select>
                <h4 class="border-bottom card-title font-weight-bold mb-4 text-uppercase">KUMITE</h4>
                <table class="table table-border">
                <thead>
                    <tr>
                        <th>Categoría</th>
                        <th>Género</th>
                        <th>Peso / nivel</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody data-tipo="KUMITE">
                    <?php foreach ($competicioneskumite as $key => $value) {
                        $tr = '<tr data-competicion_torneo_id="' . $value->competicion_torneo_id . '" data-modalidad="'.$value->modalidad .'">
                            <td><input type="text" name="categoria_kumite" class="form-control" placeholder="Texto. Ej: Alevín" value="' . $value->categoria . '" disabled></td>
                            <td>
                                <select name="genero_kumite" class="form-control" disabled>';
                            $selected = ($value->genero == 'M') ? "selected" : "";
                            $tr .= '<option value="M" ' . $selected . '>Masculino</option>';
                            $selected = ($value->genero == 'F') ? "selected" : "";
                            $tr .= '<option value="F" ' . $selected . '>Femenino</option>';
                            $selected = ($value->genero == 'X') ? "selected" : "";
                            $tr .= '<option value="X" ' . $selected . '>Mixto</option>';
                            $tr .= '</td>
                            <td><input type="text" name="nivel_kumite" class="form-control" placeholder="Texto. Ej: < 40 Kg, ej: inicial" value="' .$value->nivel . '" disabled></td>
                            <td class="text-truncate">
                            <button type="button" class="btn btn-sm btn-icon btn-round btn-warning" data-edit="rowkumite" data-accion="editar"><i class="fas fa-edit"></i>
                            <button type="button" class="btn btn-sm btn-icon btn-round btn-danger" data-del="rowkumite"><i class="fas fa-trash"></i>
                            </td>
                            </tr>';
                        echo $tr;
                    } ?>
                    <tr id="addrow">
                        <td><input type="text" name="categoria_kumite" class="form-control" placeholder="Texto. Ej: Alevín"></td>
                        <td>
                            <select name="genero_kumite" class="form-control">
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                                <option value="X">Mixto</option>
                            </td>
                            <td><input type="text" name="nivel_kumite" class="form-control" placeholder="Texto. Ej: < 40 Kg, ej: inicial"></td>
                            <td><button type="button" class="btn btn-icon btn-round btn-primary" data-add-row="kumite" id="addrowkumite"><i class="fas fa-plus"></i></td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>
    </div>
<?php } ?>
</div>