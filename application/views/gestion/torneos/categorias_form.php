<?php echo form_open();
echo form_hidden('torneo_id', $torneo->torneo_id);
echo form_close();?>
<?php if($torneo->tipo != 2){?>
    <div class="card">
        <div class="card-body">
            <h4 class="border-bottom card-title font-weight-bold mb-4 text-uppercase">Kata</h4>
            <div class="row">
                <div class="col-md-6">
                    <h4 class="font-weight-bold">Kata masculino</h4>
                    <table class="table table-striped w-100" data-modalidad="KATA" data-genero="M">
                        <thead>
                            <tr>
                                <th>Modalidad</th>
                                <th>Peso</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>

                        <tfoot>
                            <tr><th colspan="3">Añadir nuevos datos</th></tr>
                            <tr>
                                <th>
                                    <select class="form-control" name="">
                                        <?php foreach ($m_kata as $key => $cat) {?>
                                            <option value="<?php echo $cat->t_categoria_id;?>"><?php echo $cat->categoria.' '.$cat->year;?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                                <th>
                                    <input type="number" class="form-control" placeholder="menos de" name="peso" min="25" value="25" >
                                </th>
                                <th>
                                    <button type="button" class="btn btn-outlime-danger btn-outline-danger btn-sm rounded-0" id="add_kata_mas" data-add-modalidad><i class="fa fa-plus-circle"></i></button>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                    <button type="button" class="btn btn-primary btn-sm" data-guardar-modalidad>Guardar</button>
                </div>

                <div class="col-md-6">
                    <h4 class="font-weight-bold">Kata femenino</h4>
                    <table class="table table-head-bg-secondary table-striped w-100" data-modalidad="KATA" data-genero="F">
                        <thead>
                            <tr>
                                <th>Modalidad</th>
                                <th>Peso</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>

                        <tfoot>
                            <tr><th colspan="3">Añadir nuevos datos</th></tr>
                            <tr>
                                <th>
                                    <select class="form-control" name="">
                                        <?php foreach ($m_kata as $key => $cat) {?>
                                            <option value="<?php echo $cat->t_categoria_id;?>"><?php echo $cat->categoria.' '.$cat->year;?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                                <th>
                                    <input type="number" class="form-control" placeholder="menos de" name="peso" min="25" value="25" >
                                </th>
                                <th>
                                    <button type="button" class="btn btn-outlime-danger btn-outline-danger btn-sm rounded-0" id="add_kata_fem" data-add-modalidad><i class="fa fa-plus-circle"></i></button>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                    <button type="button" class="btn btn-primary btn-sm" data-guardar-modalidad>Guardar</button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php if($torneo->tipo != 1){?>
    <div class="card">
        <div class="card-body">
            <h4 class="border-bottom card-title font-weight-bold mb-4 text-uppercase">KUMITE</h4>
            <div class="row">
                <div class="col-md-6">
                    <h4 class="font-weight-bold">Kumite masculino</h4>
                    <table class="table table-striped w-100" data-modalidad="KUMITE" data-genero="M">
                        <thead>
                            <tr>
                                <th>Modalidad</th>
                                <th>Peso</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>

                        <tfoot>
                            <tr><th colspan="3">Añadir nuevos datos</th></tr>
                            <tr>
                                <th>
                                    <select class="form-control" name="">
                                        <?php foreach ($m_kumite as $key => $cat) {?>
                                            <option value="<?php echo $cat->t_categoria_id;?>"><?php echo $cat->categoria.' '.$cat->year;?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                                <th>
                                    <input type="number" class="form-control" placeholder="menos de" name="peso" min="25" value="25" >
                                </th>
                                <th>
                                    <button type="button" class="btn btn-outlime-danger btn-outline-danger btn-sm rounded-0" id="add_kumite_mas" data-add-modalidad><i class="fa fa-plus-circle"></i></button>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                    <button type="button" class="btn btn-primary btn-sm" data-guardar-modalidad>Guardar</button>
                </div>

                <div class="col-md-6">
                    <h4 class="font-weight-bold">Kumite femenino</h4>
                    <table class="table table-head-bg-secondary table-striped w-100" data-modalidad="KUMITE" data-genero="F">
                        <thead>
                            <tr>
                                <th>Modalidad</th>
                                <th>Peso</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>

                        <tfoot>
                            <tr><th colspan="3">Añadir nuevos datos</th></tr>
                            <tr>
                                <th>
                                    <select class="form-control" name="">
                                        <?php foreach ($m_kumite as $key => $cat) {?>
                                            <option value="<?php echo $cat->t_categoria_id;?>"><?php echo $cat->categoria.' '.$cat->year;?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                                <th>
                                    <input type="number" class="form-control" placeholder="menos de" name="peso" min="25" value="25" >
                                </th>
                                <th>
                                    <button type="button" class="btn btn-outlime-danger btn-outline-danger btn-sm rounded-0" id="add_kumite_fem" data-add-modalidad><i class="fa fa-plus-circle"></i></button>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                    <button type="button" class="btn btn-primary btn-sm" data-guardar-modalidad>Guardar</button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
