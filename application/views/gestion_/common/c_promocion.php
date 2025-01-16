<div class="container-fluid">
    <h3 class="text-dark mb-4" id="nameempresa"></h3>
    <div class="row mb-3">
        <div class="col-lg-8">
            <div class="card shadow mb-3">
                <div class="card-header py-3">
                    <p class="text-primary m-0 font-weight-bold">Datos de la promoción</p>
                </div>
                <div class="card-body">
                    <form>
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="razon_social"><strong>Nombre</strong></label>
                                    <input class="form-control" type="text" placeholder="Nombre" name="nombre">
                                </div>

                                <div class="form-group">
                                    <label for="direccion"><strong>Descripción</strong></label>
                                    <input class="form-control" type="text" placeholder="Descripción" name="descripcion">
                                </div>
                                <div class="form-group">
                                    <label for="cif"><strong>Tipo</strong></label>
                                    <select class="form-control" name="tipo" id="tipo">
                                        <option value="1" disabled>Premio directo</option>
                                        <option value="2">Sorteo</option>
                                        <option value="3">Momento ganador</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email"><strong>Fecha de inicio</strong></label>
                                    <input class="form-control" type="date" name="startime" id="startime">
                                </div>
                                <div class="form-group">
                                    <label for="telefono"><strong>Fecha de fin</strong></label>
                                    <input class="form-control" type="date" name="endtime" id="endtime">
                                </div>
                                <div class="form-group">
                                    <label for="telefono"><strong>Límite aplicar descuento</strong></label>
                                    <input class="form-control" type="date" name="limittime" id="limittime">
                                </div>
                            </div>
                        </div>
                        <div class="form-group"><button class="btn btn-primary btn-sm" type="button" onclick="updateData($(this).closest('form'))">Guardar datos</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>