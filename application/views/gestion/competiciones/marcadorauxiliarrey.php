<style>
    input[type="checkbox"].form-checkbox {
        appearance: none;
        /* Elimina el estilo predeterminado del checkbox */
        -webkit-appearance: none;
        -moz-appearance: none;
        width: 16px;
        height: 16px;
        border: 2px solid #ffffff;
        border-radius: 4px;
        margin: .5rem;
        background: transparent;
        cursor: pointer;
        position: relative;
    }

    input[type="checkbox"].form-checkbox:checked {
        background-color:rgb(255, 255, 255);
        border-color:rgb(255, 255, 255);
    }

    input[type="checkbox"].form-checkbox:checked::after {
        content: "\f00c";
        font-family: "Font Awesome 5 Solid";
        font-size: 20px;
        color: #020202;
        position: absolute;
        top: 50%;
        left: 55%;
        transform: translate(-50%, -50%);
    }

    #timer {
        position: absolute;
        bottom: 0;
        left: calc(50% - 1rem);
        bottom: -3rem;
        transform: translate(-50%, 10px);
        background: #000000;
        padding: 1rem;
        width: 48%;
        text-align: center;
    }
</style>

<div class="modal fade" id="marcadorauxiliar" tabindex="-1" role="dialog" aria-labelledby="marcadorauxiliarLabel" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark text-white">

            <div class="modal-body" data-match-id="">
                <div class="d-flex">
                    <div class="w-100 w-lg-50 p-3" data-acciones="azul" style="background: blue">
                        <h3 class="text-center border-bottom border-white p-3" id="user_azul" data-user="">Nombre Apellidos</h3>
                        <div class="row">
                            <div class="col-5">
                                <button type="button" class="btn btn-primary btn-block mb-1" style="border: 1px solid #ffffff !important;" data-accion="ippon">IPPON</button>
                                <button type="button" class="btn btn-secondary btn-block mb-1" style="border: 1px solid #ffffff !important;" data-accion="wazari">WAZA-ARI</button>
                                <button type="button" class="btn btn-warning btn-block mb-1" style="border: 1px solid #ffffff !important;" data-accion="yuko">YUKO</button>

                                <button type="button" class="btn btn-sm btn-light btn-block mb-2 mt-5" style="border: 1px solid #ffffff !important;" data-accion="rest">-1</button>
                            </div>
                            <div class="align-items-center col-7 d-flex justify-content-center">
                                <h1 id="puntostotalesazul" style="font-size: 7rem;">0</h1>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="border-bottom border-white"></div>
                            </div>
                            <div class="col-6">
                                <div class="align-items-center d-flex">
                                    <input class="form-checkbox" type="checkbox" name="senshu_azul" id="senshu_azul" value="1" data-accion="senshu">
                                    <label for="senshu_azul" class="text-white">SENSHU</label>
                                </div>
                                <div class="align-items-center d-flex">
                                    <input class="form-checkbox" type="checkbox" id="hantei_azul" name="hantei_azul" value="1" data-accion="hantei">
                                    <label for="hantei_azul" class="text-white">HANTEI</label>
                                </div>
                                <div class="d-block p-2 text-white border-top border-white">
                                    <div class="d-flex justify-content-between">
                                        <div class="penalizaciones d-flex">
                                            <div class="align-items-center d-flex flex-column justify-content-end">
                                                <label for="c1_azul" class="text-white">C1</label>
                                                <input class="form-checkbox" name="c1_azul" id="c1_azul" type="checkbox" value="1" data-accion="c1">
                                            </div>
                                            <div class="align-items-center d-flex flex-column justify-content-end">
                                                <label for="c2_azul" class="text-white">C2</label>
                                                <input class="form-checkbox" name="c2_azul" id="c2_azul" type="checkbox" value="2" data-accion="c2">
                                            </div>
                                            <div class="align-items-center d-flex flex-column justify-content-end">
                                                <label for="c3_azul" class="text-white">C3</label>
                                                <input class="form-checkbox" name="c3_azul" id="c3_azul" type="checkbox" value="3" data-accion="c3">
                                            </div>
                                            <div class="align-items-center d-flex flex-column justify-content-end">
                                                <label for="c4_azul" class="text-white">HC</label>
                                                <input class="form-checkbox" name="c4_azul" id="c4_azul" type="checkbox" value="4" data-accion="c4">
                                            </div>
                                            <div class="align-items-center d-flex flex-column justify-content-end">
                                                <label for="c5_azul" class="text-white">H</label>
                                                <input class="form-checkbox" name="c5_azul" id="c5_azul" type="checkbox" value="5" data-accion="c5">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-100 w-lg-50 p-3" data-acciones="rojo" style="background: red">
                        <h3 class="text-center border-bottom border-white p-3" id="user_rojo" data-user="">Nombre Apellidos</h3>
                        <div class="row">
                            <div class="align-items-center col-7 d-flex justify-content-center">
                                <h1 id="puntostotalesrojo" style="font-size: 7rem;">0</h1>
                            </div>
                            <div class="col-5">
                                <button type="button" class="btn btn-primary btn-block mb-1" style="border: 1px solid #ffffff !important;" data-accion="ippon">IPPON</button>
                                <button type="button" class="btn btn-secondary btn-block mb-1" style="border: 1px solid #ffffff !important;" data-accion="wazari">WAZA-ARI</button>
                                <button type="button" class="btn btn-warning btn-block mb-1" style="border: 1px solid #ffffff !important;" data-accion="yuko">YUKO</button>

                                <button type="button" class="btn btn-sm btn-light btn-block mb-2 mt-5" style="border: 1px solid #ffffff !important;" data-accion="rest">-1</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="border-bottom border-white"></div>
                            </div>
                            <div class="col-6 offset-6">
                                <div class="align-items-center d-flex justify-content-end">
                                    <label for="senshu_rojo" class="text-white">SENSHU</label>
                                    <input class="form-checkbox" type="checkbox" name="senshu_rojo" id="senshu_rojo" value="1" data-accion="senshu">

                                </div>
                                <div class="align-items-center d-flex justify-content-end">
                                    <label for="hantei_rojo" class="text-white">HANTEI</label>
                                    <input class="form-checkbox" type="checkbox" id="hantei_rojo" name="hantei_rojo" value="1" data-accion="hantei">
                                </div>


                                <div class="penalizaciones d-flex">

                                    <div class="align-items-center d-flex flex-column justify-content-end">
                                        <label for="c5_rojo" class="text-white">H</label>
                                        <input class="form-checkbox" name="c5_rojo" id="c5_rojo" type="checkbox" value="5" data-accion="c5">
                                    </div>
                                    <div class="align-items-center d-flex flex-column justify-content-end">
                                        <label for="c4_rojo" class="text-white">HC</label>
                                        <input class="form-checkbox" name="c4_rojo" id="c4_rojo" type="checkbox" value="4" data-accion="c4">
                                    </div>
                                    <div class="align-items-center d-flex flex-column justify-content-end">
                                        <label for="c3_rojo" class="text-white">C3</label>
                                        <input class="form-checkbox" name="c3_rojo" id="c3_rojo" type="checkbox" value="3" data-accion="c3">
                                    </div>
                                    <div class="align-items-center d-flex flex-column justify-content-end">
                                        <label for="c2_rojo" class="text-white">C2</label>
                                        <input class="form-checkbox" name="c2_rojo" id="c2_rojo" type="checkbox" value="2" data-accion="c2">
                                    </div>
                                    <div class="align-items-center d-flex flex-column justify-content-end">
                                        <label for="c1_rojo" class="text-white">C1</label>
                                        <input class="form-checkbox" name="c1_rojo" id="c1_rojo" type="checkbox" value="1" data-accion="c1">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="border countdown rounded rounded-bottom mx-3" id="timer">
                    <div class="d-flex justify-content-between">
                        <div>
                            <button class="btn btn-warning btn-sm" id="add-timer" data-toggle="tooltip" title="Añadir 1 segundo"><i class="fas fa-chevron-up"></i></button>
                        </div>
                        <div class="clock-wrapper px-3 d-flex" style="font-size: 5rem;">
                            <span class="minutes">00</span>
                            <span class="dots">:</span>
                            <span class="seconds">00</span>
                        </div>
                        <div>
                            <button class="btn btn-warning btn-sm" id="del-timer" data-toggle="tooltip" title="Disminuir 1 segundo"><i class="fas fa-chevron-down"></i></button>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <button class="btn btn-secondary btn-block" id="start-btn" data-toggle="tooltip" title="Iniciar / Continuar cronómetro"><i class="fas fa-play"></i></button>
                        <button class="btn btn-secondary btn-block mt-0 d-none" id="pause-btn" data-toggle="tooltip" title="Pausar cronómetro"><i class="fas fa-pause"></i></button>
                    </div>
                </div>
            </div>
            <div class="justify-content-between modal-footer">
                <div class="d-flex align-items-center">
                    <input type="time" class="form-control w-auto" data-toggle="tooltip" title="Duración por defecto" id="default-time" name="default-time" value="01:00">
                    <button class="btn btn-danger ml-2" id="restart-btn" data-toggle="tooltip" title="Reiniciar cronómetro"><i class="fas fa-redo"></i></button>
                </div>
                <div>
                    <button type="button" class="btn btn-info" id="mostrar-en-segunda" data-toggle="tooltip" title="Mostrar en segunda pantalla"><i class="fas fa-tv"></i></button>
                    <button type="button" class="btn btn-secondary" id="guardar-marcador" data-toggle="tooltip" title="Finalizar y guardar"><i class="fas fa-save"></i></button>
                    <button type="button" class="btn btn-danger" id="close_modal" data-toggle="tooltip" title="Cerrar sin cambios" data-dismiss="modal"><i class="fas fa-times"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>