<div class="container-fluid">
    <h3 class="text-dark mb-4" id="namepromocion"><?php echo $promocion->nombre;?></h3>

    <div class="card shadow mb-3">
        <div class="card-header pb-0 border-bottom-0">
            <ul class="nav nav-tabs"  id="promoTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">General</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" id="empresaspromo-tab" data-toggle="tab" href="#empresaspromo" role="tab" aria-controls="empresaspromo" aria-selected="false">Empresas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="premiospromo-tab" data-toggle="tab" href="#premiospromo" role="tab" aria-controls="premiospromo" aria-selected="false">Premios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="participacionespromo-tab" data-toggle="tab" href="#participacionespromo" role="tab" aria-controls="participacionespromo" aria-selected="false">Participaciones</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="descuentosaplicadospromo-tab" data-toggle="tab" href="#descuentosaplicadospromo" role="tab" aria-controls="descuentosaplicadospromo" aria-selected="false">Descuentos aplicados</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="extractocomerciopromo-tab" data-toggle="tab" href="#extractocomerciopromo" role="tab" aria-controls="extractocomerciopromo" aria-selected="false">Extracto Empresas</a>
                </li>
                 <li class="nav-item">
                    <a class="nav-link" id="extractopremiadospromo-tab" data-toggle="tab" href="#extractopremiadospromo" role="tab" aria-controls="extractopremiadospromo" aria-selected="false">Extracto Premiados</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                    <h4 class="card-title">Datos generales</h4>
                    <h6 class="card-subtitle mb-2">Tipo de promoción: <?php echo $this->config->item('tipo_promo')[$promocion->tipo];?></h6>
                    <form>
                        <div class="form-row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label for="razon_social"><strong>Nombre</strong></label>
                                    <input class="form-control" type="text" placeholder="Nombre" name="nombre" value="<?php echo $promocion->nombre;?>">
                                </div>
                                <div class="form-group">
                                    <label for="direccion"><strong>Descripción</strong></label>
                                    <textarea class="form-control" placeholder="Descripción" name="descripcion" rows="3"><?php echo $promocion->descripcion;?></textarea>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="email"><strong>Fecha de inicio</strong></label>
                                    <input type="date" class="form-control" id="startime" name="startime" value="<?php echo $promocion->startime;?>"> 
                                </div>
                                <div class="form-group">
                                    <label for="telefono"><strong>Fecha de fin</strong></label>
                                    <input class="form-control" type="date" name="endtime" id="endtime" value="<?php echo $promocion->endtime;?>">
                                </div>
                                <div class="form-group">
                                    <label for="telefono"><strong>Límite aplicar descuento</strong></label>
                                    <input class="form-control" type="date" name="limittime" id="limittime" value="<?php echo $promocion->limittime;?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary btn-sm" type="button" onclick="updateData($(this).closest('form'))">Guardar datos</button>
                            <button class="btn btn-danger btn-sm" type="button" id="borrar-promocion">Borrar promoción</button>
                        </div>
                    </form>
                </div>

                <div class="tab-pane fade" id="premiospromo" role="tabpanel" aria-labelledby="premiospromo-tab">
                    <?php if($promocion->tipo == 1){?>
                    <?php }elseif($promocion->tipo == 2){?>
                        <h4 class="card-title d-flex justify-content-between">Premios <input class="form-control" id="tabla_sorteo_actual_search" type="text" placeholder="Buscar.." style="max-width: 200px;"></h4>
                        <div class="card-body border-bottom">
                            <table class="table table-sm table-striped datatable" id="tabla_sorteo_actual">
                                <thead>
                                    <tr>
                                        <th>Premio</th>
                                        <th>DNI</th>
                                        <th>Fecha de entrega</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($premiossorteo)){
                                        foreach ($premiossorteo as $key => $value) {
                                            echo '<tr><td>'.$value->cantidad.'</td>';
                                            if($value->fecha_premio == '0000-00-00'){
                                                $dni = '<button type="button" class="btn btn-danger btn-sm" data-add_ganador="'.$value->premiosorteoID.'">Añadir DNI premiado</button>';
                                            }else{
                                                $dni = $value->dni;
                                            }
                                            echo '<td>'.$dni.'</td>';
                                            if($value->fecha_premio == '0000-00-00'){
                                                $value->fecha_premio = "";
                                            }
                                            echo '<td>'.$value->fecha_premio.'</td></tr>';
                                        }
                                    }?>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-body">
                        <div class="mb-3 py-3 border-bottom">
                            Generar <input type="number" name="cuantos_mg" class="form_control form-control-sm" style="max-width: 100px"> premios de <input type="number" name="cuanto_mg" class="form_control form-control-sm" style="max-width: 100px"> € cada uno <button class="btn btn-primary btn-sm ml-3" type="button" id="btn-generar_sorteo">Generar</button>
                        </div>
                        <form>
                            <table class="table table-sm table-striped datatable" id="tabla_sorteo">
                                <thead>
                                    <tr>
                                        <th>Premio</th>
                                        <th>Participación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            <button class="btn btn-primary" tabindex="0" id="guardar_premios_sorteo" type="button"><span>Guardar premios</span></button>
                        </form>
                    </div>
                    <?php }elseif($promocion->tipo == 3){?>
                        <div class="mb-3 py-3 border-bottom d-none">
                            Generar <input type="number" name="cuantos_mg" class="form_control form-control-sm" style="max-width: 100px"> premios de <input type="number" name="cuanto_mg" class="form_control form-control-sm" style="max-width: 100px"> € cada uno <button class="btn btn-primary btn-sm ml-3" type="button" id="btn-generar_mg">Generar</button>
                        </div>
                        <form>
                            <table class="table table-sm table-striped datatable" id="tabla_mg">
                                <thead>
                                    <tr>
                                        <th>Fecha - hora</th>
                                        <th>Premio</th>
                                        <th>Participación</th>
                                        <th>DNI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($mometosganadores)){
                                        foreach ($mometosganadores as $key => $value) {
                                            echo '<tr>';
                                            echo '<td>',$value->time.'</td>';
                                            echo '<td>'.$value->premio.'</td>';
                                            echo '<td>'.$value->id_participacion.'</td>';
                                            echo '<td>'.$value->dni.'</td>';
                                            echo '</tr>';
                                        }
                                    }?>
                                </tbody>
                            </table>
                            <button class="btn btn-primary d-none" tabindex="0" id="guardar_premios_mg" type="button"><span>Guardar premios</span></button>
                        </form>
                    <?php } ?>
                </div>

                <div class="tab-pane fade" id="empresaspromo" role="tabpanel" aria-labelledby="empresaspromo-tab">
                    <h4 class="card-title d-flex justify-content-between">Empresas participantes <input class="form-control" id="tablaempresas_search" type="text" placeholder="buscar.." style="max-width: 200px;"></h4>
                    <h6 class="card-subtitle mb-2"></h6>
                    <table class="table table-sm table-striped dataTable" id="tablaempresas">
                        <thead>
                            <tr>
                                <th><div class="form-check"><input class="form-check-input" type="checkbox" name="selectAll"><label class="form-check-label" for="estado">Todos</label></div></th>
                                <th>Comercio</th>
                                <th>CIF</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($empresas as $key => $value) {?>
                            <tr>
                                <td><input type="checkbox" name="id_empresa[]" value="<?php echo $value->id_empresa;?>" <?php if(in_array($value->id_empresa, $empresas_promocion)){echo 'checked';}?>></td>
                                <td><?php echo $value->razon_social;?></td>
                                <td><?php echo $value->cif;?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-between">
                    <button class="btn btn-primary btn-sm" id="guardar_comercios_marcados" type="button">Guardar comercios marcados como participantes</button>
                    <a href="<?php echo base_url();?>crear-comercio" class="btn btn-primary"><span>Crear nuevo comercio</span></a>
                    </div>
                </div>

                <div class="tab-pane fade" id="participacionespromo" role="tabpanel" aria-labelledby="participacionespromo-tab">
                    <h4 class="card-title d-flex justify-content-between"><span>Participaciones: <span id="total_participaciones"></span></span> <div id="buttonspart"></div></h4>
                    <h6 class="card-subtitle mb-2"></h6>
                    <table class="table table-sm table-striped dataTable" id="tablaparticipaciones">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th><i class="fas fa-image"></i></th>
                                <th>DNI</th>
                                <th>TEÉFONO</th>
                                <th>CIF</th>
                                <th>Fecha - hora</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($participaciones as $key => $value) {?>
                            <tr>
                                <td><?php echo $value->id_participacion;?></td>
                                <td><a href="<?php echo $value->fileurl;?>" target="_blank" class="text-dark"><i class="fas fa-image"></i></a></td>
                                <td><?php echo $value->dni;?></td>
                                <td><?php echo $value->telefono;?></td>
                                <td><?php echo $value->cif;?></td>
                                <td><?php echo $value->fecha_creacion;?></td>
                                <td><?php echo $value->cantidad;?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>    
                </div>

                <div class="tab-pane fade" id="descuentosaplicadospromo" role="tabpanel" aria-labelledby="descuentosaplicadospromo-tab">
                    <h4 class="card-title d-flex justify-content-between"><span>Descuentos aplicados: <span id="total_descuentos"></span></span> <div id="buttonsdto"></div></h4>
                    <h6 class="card-subtitle mb-2"></h6>
                    <table class="table table-sm table-striped dataTable" id="tabladescuentosaplicados">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th><i class="fas fa-image"></i></th>
                                <th>Premiado</th>
                                <th>CIF</th>
                                <th>Fecha - hora</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($descuentosaplicados[1] as $key => $value) {?>
                            <tr>
                                <td><?php echo $value->id_descuento;?></td>
                                <td><a href="<?php echo $value->fileurl;?>" target="_blank" class="text-dark"><i class="fas fa-image"></i></a></td>
                                <td><?php echo $value->dni;?></td>
                                <td><?php echo $value->cif;?></td>
                                <td><?php echo $value->fecha_creacion;?></td>
                                <td><?php echo $value->cantidad;?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>    
                </div>

                <div class="tab-pane fade" id="extractocomerciopromo" role="tabpanel" aria-labelledby="extractocomerciopromo-tab">
                    <h4 class="card-title d-flex justify-content-between"><span>Descuentos aplicados por empresas: <span id="total_descuentos_e"></span></span> <div id="buttonsextem"></div></h4>
                    <h6 class="card-subtitle mb-2"></h6>
                    <table class="table table-sm table-striped dataTable" id="tablasextractosempresas">
                        <thead>
                            <tr>
                                <th>CIF</th>
                                <th>Razón social</th>
                                <th>Total descontado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($extractosemresas as $key => $value) {?>
                            <tr>
                                <td><?php echo $value->cif;?></td>
                                <td><?php echo $value->razon_social;?></td>
                                <td><?php echo $value->cantidad;?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div class="tab-pane fade" id="extractopremiadospromo" role="tabpanel" aria-labelledby="extractopremiadospromo-tab">
                    <h4 class="card-title d-flex justify-content-between"><span>Descuentos aplicados a cada premiado: <span id="total_descuentos_p"></span></span> <div id="buttonsextpr"></div></h4>
                    <h6 class="card-subtitle mb-2"></h6>
                    <table class="table table-sm table-striped dataTable" id="tablasextractospremiados">
                        <thead>
                            <tr>
                                <th>NIF</th>
                                <th>Premio</th>
                                <th>Total descontado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($extractospremiados as $key => $value) {?>
                            <tr>
                                <td><?php echo $value->dni;?></td>
                                <td><?php echo $value->premio;?></td>
                                <td><?php echo $value->cantidad;?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>