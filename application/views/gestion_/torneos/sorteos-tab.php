<div class="card mt--3">
    <div class="card-header">
        <div class="form-group">
            <label for="" class="col-12 mb-2">Selecciona la categoría:</label>
            <select name="ver_categoria" id="ver_categoria" class="form-control">
                <option value="">Selecciona una competición</option>
                <?php foreach ($categorias_torneo as $key => $competicion) {
                    echo '<option value="' . $competicion->competicion_torneo_id . '">' . $competicion->modalidad . ' ' . $competicion->categoria . ' ' . $competicion->genero . ' ' . $competicion->peso . '</option>';
                } ?>
            </select>
        </div>
    </div>
    <div class="card-body" id="card-body" style="display: none">
        <div class=" row">
            <div class="col-md-3">
                <table class="table table-striped" id="inscritos">
                    <thead>
                        <tr>
                            <th>Deportista</th>
                            <th>Equipo</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <style>
                <?php /*.rondas {
                    display: flex;
                    justify-content: start;
                    align-items: top;
                    overflow-x: auto;
                    width: 100%;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                    cursor: pointer;
                }
                .ronda-content {
                    min-width: 350px;
                    max-width: 450px;
                    margin-right: 3rem;
                }

                .ronda-content .list-group {
                    border: 1px solid #dee2e6 !important;
                }

                .ronda-content .list-group-item {
                    padding: 0 !important;
                    display: flex !important;
                    justify-content: space-between !important;
                    align-items: center !important;
                }

                .ronda-content .card {
                    border: 1px solid #dee2e6 !important;
                    background-color: #f8f9fa !important;
                    margin-bottom: 5px;
                }

                .ronda-content .card-body {
                    padding: 5px 7px 5px 3rem;
                }

                .ideliminatoria {
                    padding: 5px;
                    text-align: center;
                    position: absolute;
                    top: 50%;
                    left: 1.5rem;
                    font-size: 2rem;
                    -moz-transform: translateX(-50%) translateY(-50%) rotate(-90deg);
                    -webkit-transform: translateX(-50%) translateY(-50%) rotate(-90deg);
                    transform: translateX(-50%) translateY(-50%) rotate(-90deg);
                }

                .ronda-content .list-group-item span.color {
                    color: #ffffff;
                    width: 50px;
                    height: 50px;
                }

                .ronda-content .list-group-item span.color.red {
                    background-color: red;
                }

                .ronda-content .list-group-item span.color.blue {
                    background-color: blue;
                }*/ ?>
                .list-group-item:nth-child(odd) {
                    border-left: 25px solid red!important;
                    color: red
                }
                .list-group-item:nth-child(even) {
                    border-left: 25px solid blue!important;
                    margin-bottom: 1rem;
                    color: blue;
                }
                .list-group-item{
                    padding: 0.25rem;
                    min-height: 3.3rem;
                }
                .selectable{
                    z-index: 3;
                }
            </style> 
            <div class="col-md-9">
                <h4>Deportistas inscritos: <span id="totalinscritos" class="mr-3"></span> Rondas totales: <span id="totalrondas"></span> Eliminatorias primera ronda: <span id="eliminatoriasprimera"></span></h4>
                <button class="btn btn-primary mb-3" data-sortear="true">Sortear emparejamientos</button>
                <div class="rondas d-flex">
                    <ul class="list-group" id="ronda1">

                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>