<div class="modal fade" id="marcadorauxiliar" tabindex="-1" role="dialog" aria-labelledby="marcadorauxiliarLabel">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body" data-match-id="">
                <div class="d-flex flex-column flex-sm-row justify-content-between text-white">
                    

                    <div class="w-100 w-lg-50 p-3" data-match-azul style="background: blue">
                        <h3 class="text-center p-3 border border-white" id="user_azul"></h3>
                        <div class="row">
                            
                            <div class="col-4 d-flex flex-column justify-content-between" data-acciones-azul>
                            
                                <button type="button" class="btn btn-icon" data-plus="azul">
                                    <i class="fa fa-plus"></i>
                                </button>
                                <button type="button" class="btn btn-icon btn-danger" data-minus="azul">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                            <div class="align-items-center col-8 d-flex justify-content-center">
                                <h1 id="puntostotalesazul" style="font-size: 12vh;">0</h1>
                            </div>
                            <div class="col-12 text-left mt-3">
                                <div class="border border-white form-check p-0 p-2 text-white">
                                    <label class="form-check-label mb-0">
                                        <input class="form-check-input" type="checkbox" name="senshu" value="azul">
                                        <span class="form-check-sign  text-white">SENSHU</span>
                                    </label>
                                    <label class="form-check-label mb-0">
                                        <input class="form-check-input" type="checkbox" name="hantei" value="azul">
                                        <span class="form-check-sign  text-white">HANTEI</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-100 w-lg-50 p-3" data-match-rojo style="background: red">
                        <h3 class="text-center p-3 border border-white" id="user_rojo"></h3>
                        <div class="row">
                            <div class="align-items-center col-8 d-flex justify-content-center">
                                <h1 id="puntostotalesrojo" style="font-size: 12vh;">0</h1>
                            </div>

                            <div class="align-items-end col-4 d-flex flex-column justify-content-between" data-acciones-rojo>
                                <button type="button" class="btn btn-icon" data-plus="rojo">
                                    <i class="fa fa-plus"></i>
                                </button>
                                <button type="button" class="btn btn-icon btn-danger" data-minus="rojo">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                            
                            <div class="col-12 mt-3">
                                <div class="border border-white text-right form-check p-0 p-2 text-white">
                                    <label class="form-check-label mb-0">
                                        <input class="form-check-input" type="checkbox" name="senshu" value="rojo">
                                        <span class="form-check-sign text-white">SENSHU</span>
                                    </label>
                                    <label class="form-check-label mb-0">
                                        <input class="form-check-input" type="checkbox" name="hantei" value="azul">
                                        <span class="form-check-sign text-white">HANTEI</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <button class="btn btn-success" id="guardar-marcador">Guardar</button>
                    <button class="btn btn-outline-danger" data-dismiss="modal" id="cerrar-marcador">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>