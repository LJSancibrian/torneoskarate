$(document).on('click', '[data-generar-tablero]', function() {
    var competicion_torneo_id = $(this).attr('data-generar-tablero');
    var competicion_tipo = $(this).attr('data-generar-tipo');

    var fd = new FormData();
    fd.append("competicion_torneo_id", competicion_torneo_id);
    fd.append("competicion_tipo", competicion_tipo);
    fd.append("csrf_token", $('[name="csrf_token"]').val());
    $.ajax({
        url: base_url + 'Competiciones/generar_tablero_competicion_tipo',
        method: "POST",
        contentType: false,
        processData: false,
        data: fd
    }).done(function(response) {
        var response = JSON.parse(response);
        $('[name="csrf_token"]').val(response.csrf)
        if (response.error > 0) {
            var errorhtml = ''
            if (response.hasOwnProperty('error_validation')) {
                $.each(response.error_validation, function(i, value) {
                    errorhtml += value + '<br>'
                })
            }
            if (response.hasOwnProperty('error_msn')) {
                errorhtml += response.error_msn
            }
            swal.fire({
                icon: 'error',
                title: 'ERROR',
                html: errorhtml,
                willClose: function() {
                    if (response.hasOwnProperty('redirect')) {
                        if (response.redirect == 'refresh') {
                            location.reload()
                        } else {
                            window.location.href = response.redirect
                        }
                    }
                }
            });
            return;
        } else {
            // se copulta el tablero
            //$('#tablero-competicion').slideUp()
            var tipo = response.tipo
            var competicion_tipo = response.competicion_tipo;
            // KATA
            if (competicion_tipo == 'puntos' && tipo == 'KATA') {
                var tabla = ` <div class="table-responsive">
                <table class="table table-striped table-bordered text-center" id="tablakata">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th colspan="2" class="text-left">Deportista</th>
                            <th class="text-left">Equipo</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    </table>
                </div>
                <button type="button" data-guardar-puntos="${competicion_torneo_id}" class="btn btn-sm btn-default m-3">Guardar orden</button>`;
                $('#tablero-competicion').html(tabla);
                var totalinscritos = response.inscritos.length;
                if (totalinscritos > 0) {
                    $.each(response.inscritos, function(i, elem) {
                        var tr = `<tr>
                            <td>${i + 1}</td>
                            <td colspan="2" class="text-left text-nowrap" data-inscripcion="${elem.inscripcion_id}">${elem.first_name + ' ' + elem.last_name}</td>
                            <td class="text-left text-nowrap">${elem.nombre}</td>
                        </tr>`
                        $('#tablakata').append(tr);
                    })
                }
                $('#tablero-competicion tbody').sortable({});
                $('#tablero-competicion').slideDown();
            }

            if (competicion_tipo == 'liguilla') {
                var html = `<h4 class="card-title d-flex justify-content-between align-items-center">Fase de grupos <span id="totalcomabtes"></span></h4>
                <div id="fasegrupos"></div>
                <hr>
                <h4>Eliminatorias</h4>
                <div id="faseeliminatorias">
                    <div class="brackets"></div>
                </div>
                <button type="button" data-guardar-liguilla="${competicion_torneo_id}" class="btn btn-sm btn-default m-3">Guardar grupos</button>`;
                $('#tablero-competicion').html(html);
                var totalinscritos = response.inscritos.length;
                var grupos = [];
                if (totalinscritos < 5) {
                    // se genera una tabla y se añaden a ella
                    grupos.push(1)
                } else if (totalinscritos == 12) {
                    var ngrupos = totalinscritos / 3;
                    ngrupos = Math.ceil(ngrupos)
                        // letra del grupo = (index + 9).toString(36).toUpperCase()
                    for (let index = 0; index < ngrupos; index++) {
                        //var letter = (index + 10).toString(36).toUpperCase()
                        var letter = index + 1
                        grupos.push(letter)
                    }
                } else {
                    // son 9 o mas 10,
                    // calculo para tablas es el numero de inscritos dividido entre 4 (participantes en cada grupo) y redondeado a mayores 
                    var ngrupos = totalinscritos / 4;
                    ngrupos = Math.ceil(ngrupos)
                        // letra del grupo = (index + 9).toString(36).toUpperCase()
                    for (let index = 0; index < ngrupos; index++) {
                        //var letter = (index + 10).toString(36).toUpperCase()
                        var letter = index + 1
                        grupos.push(letter)
                    }
                }

                $('[id^="grupokumite_"]').remove();
                for (let index = 0; index < grupos.length; index++) {
                    var html = `<div class="border-bottom d-flex flex-row justify-content-start my-3" id="grupokumite_${grupos[index]}" grupo="${grupos[index]}">
                    <table class="table table-striped table-bordered text-center w-auto" id="tablakumite_${grupos[index]}">
                    <thead>
                        <tr>
                            <th colspan="4" class="bg-white text-primary font-weigth-bold">GRUPO ${grupos[index]}</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th colspan="2" class="text-left">Deportista</th>
                            <th class="text-left">Equipo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="no-sort"><td colspan="4"></td></tr>
                    </tbody>
                </table></div>`;
                    $('#fasegrupos').append(html);
                }
                // recorrer las inscripciones y colocar una en cada grupo de forma
                $.each(response.inscritos, function(i, elem) {
                    var deportista = elem.first_name + ' ' + elem.last_name;
                    var posicion = i + 1;
                    var tr = `<tr data-user="${elem.user_id}" data-inscripcion_id="${elem.inscripcion_id}"><td>${posicion}</td><td colspan="2" class="text-nowrap">${deportista}</td><td>${elem.nombre}</td></tr>`;
                    if (i < grupos.length) {
                        var letter = grupos[i];
                    } else {
                        var grupo = i % grupos.length;
                        var letter = grupos[grupo];
                    }
                    selectortabla = '#tablakumite_' + letter + ' tbody';
                    $(selectortabla).append(tr);
                })
                tabassortable();
                dibujar_cruces_grupos();
                dibulareliminatorias()
                actualizar_datos_combates();
            }

            if (competicion_tipo == 'eliminatoria') {
                var html = `<h4 class="card-title d-flex justify-content-between align-items-center">Eliminatorias <span id="totalcomabtes"></span></h4>
                <div id="faseeliminatorias">
                    <div class="brackets"></div>
                </div>
                <button type="button" data-guardar-eliminatoria="${competicion_torneo_id}" class="btn btn-sm btn-default m-3">Guardar eliminatorias</button>`;
                $('#tablero-competicion').html(html);
                dibular_competicion_eliminatoria(response.inscritos)
                actualizar_datos_combates();
            }

            if (competicion_tipo == 'liga') {
                var html = `<h4 class="card-title d-flex justify-content-between align-items-center">Fase de grupos <span id="totalcomabtes"></span></h4>
                <div id="fasegrupos"></div>
                <hr>
                <h4>Eliminatorias</h4>
                <div id="faseeliminatorias">
                    <div class="brackets"></div>
                </div>
                <button type="button" data-guardar-liguilla="${competicion_torneo_id}" class="btn btn-sm btn-default m-3">Guardar grupos</button>`;
                $('#tablero-competicion').html(html);
                var totalinscritos = response.inscritos.length;
                var grupos = [];

                // se genera una tabla 
                grupos.push(1)
               
                $('[id^="grupokumite_"]').remove();
                for (let index = 0; index < grupos.length; index++) {
                    var html = `<div class="border-bottom d-flex flex-row justify-content-start my-3" id="grupokumite_${grupos[index]}" grupo="${grupos[index]}">
                    <table class="table table-striped table-bordered text-center w-auto" id="tablakumite_${grupos[index]}">
                    <thead>
                        <tr>
                            <th colspan="4" class="bg-white text-primary font-weigth-bold">GRUPO ${grupos[index]}</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th colspan="2" class="text-left">Deportista</th>
                            <th class="text-left">Equipo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="no-sort"><td colspan="4"></td></tr>
                    </tbody>
                </table></div>`;
                    $('#fasegrupos').append(html);
                }
                // recorrer las inscripciones y colocar una en cada grupo de forma
                $.each(response.inscritos, function(i, elem) {
                    var deportista = elem.first_name + ' ' + elem.last_name;
                    var posicion = i + 1;
                    var tr = `<tr data-user="${elem.user_id}" data-inscripcion_id="${elem.inscripcion_id}"><td>${posicion}</td><td colspan="2" class="text-nowrap">${deportista}</td><td>${elem.nombre}</td></tr>`;
                    if (i < grupos.length) {
                        var letter = grupos[i];
                    } else {
                        var grupo = i % grupos.length;
                        var letter = grupos[grupo];
                    }
                    selectortabla = '#tablakumite_' + letter + ' tbody';
                    $(selectortabla).append(tr);
                })
                tabassortable();
                dibujar_cruces_grupos();
                dibulareliminatorias()
                actualizar_datos_combates();
            }


        }
    }).always(function(jqXHR, textStatus) {
        if (textStatus != "success") {
            swal.fire({
                icon: 'error',
                title: 'Ha ocurrido un error AJAX',
                html: jqXHR.statusText,
                timer: 5000,
                willClose: function() {}
            })
        }
    });
})

$('#tablakata tbody').sortable({
    opacity: 0.9,
    connectWith: $('tbody'),
    stop: function() {
        $.each($('#tablero-competicion tbody tr'), function(i, elem) {
            $(elem).find('td').eq(0).html(i + 1)
        })
    }
});


function tabassortable() {
    $('table[id^="tablakumite_"] tbody').sortable({
        opacity: 0.9,
        items: '>*:not(.no-sort)',
        connectWith: $('tbody'),
        start: function(event, ui) {},
        receive: function(event, ui) {
            var item = $(ui.item);
            var sender = $(ui.sender);
            if (item.next().length > 0) {
                sender.append(item.next());
            } else {
                sender.append(item.prev());
            }
        },
        stop: function(event, ui) {
            $.each($('#tablero-competicion table'), function(i, table) {
                $.each($(table).find('tbody tr:not(.no-sort)'), function(n, elem) {
                    $(elem).find('td').eq(0).html(n + 1)
                })
            })
            dibujar_cruces_grupos()
        }
    });
}

function dibujar_cruces_grupos() {
    var n_combate = 0;
    $('table[id^="tablakumite_"]').each(function(index, tabla) {
        var tabla_id = $(tabla).attr('id');
        var competidores = [];
        var inscripciones_id = [];
        var nombres = [];
        var equipos = [];
        var posiciones = [];
        $('#' + tabla_id + ' tbody tr:not(.no-sort)').each(function(row, tr) {
            var user_id = $(tr).attr('data-user')
            var inscripcion_id = $(tr).attr('data-inscripcion_id')
            var nombre = $(tr).find('td').eq(1).html();
            var equipo = $(tr).find('td').eq(2).html();
            var posicion = $(tr).find('td').eq(0).html();
            competidores.push(user_id)
            nombres[user_id] = nombre;
            equipos.push(equipo)
            posiciones[user_id] = posicion
            inscripciones_id[user_id] = inscripcion_id
        })
        var cuadroFinal = generar_enfrentamientos(competidores)
        var tabla_id = $(tabla).attr('id');
        $('#' + tabla_id).next('div').remove();
        $('<div class="text-nowrap ml-3" style="overflow-x: auto;margin-top: 2.5rem;"></div>').insertAfter('#' + tabla_id);
        var div = $('#' + tabla_id).next('div');
        var grupo = tabla_id.replace('tablakumite_', '');
        div.html('');
        for (let r = 0; r < cuadroFinal.length; r++) {
            var html = '<div class="d-inline-block" data-grupo="' + grupo + '" data-ronda="' + (r + 1) + '">';
            if (r == 0) {
                html += '<div class="pr-2">';
            } else {
                html += '<div class="border-left border-primary px-2">';
            }
            var ronda = 'Ronda ' + (r + 1);
            html += '<h4 class="bg-primary text-white p-2 mb-3">' + ronda + '</h4><div class="">'
            for (let c = 0; c < cuadroFinal[r].length; c++) {
                n_combate++;
                var id1 = cuadroFinal[r][c][0];
                var id2 = cuadroFinal[r][c][1];
                if (c == (cuadroFinal[r].length) - 1) {
                    html += '<ul class="list-group border border-primary match" data-combate="' + (c + 1) + '" data-ncombate="' + n_combate + '">';
                } else {
                    html += '<ul class="list-group border border-primary mb-3 match" data-combate="' + (c + 1) + '" data-ncombate="' + n_combate + '">';
                }
                html += '<li class="list-group-item d-flex justify-content-between align-items-center px-2 py-1" data-grupo="' + grupo + '" data-ronda="' + (r + 1) + '" data-combate="' + (c + 1) + '" data-ncombate="' + n_combate + '" data-color="rojo" data-user="' + id1 + '" data-inscripcion_id="' + inscripciones_id[id1] + '"><span class="badge badge-primary badge-pill m-0 mr-3 rounded-0" style="background: red; width:50px;">' + posiciones[id1] + '</span>' + nombres[id1] + '</li>';
                html += '<li class="list-group-item d-flex justify-content-between align-items-center px-2 py-1" data-grupo="' + grupo + '" data-ronda="' + (r + 1) + '" data-combate="' + (c + 1) + '" data-color="azul" data-ncombate="' + n_combate + '" data-user="' + id2 + '" data-inscripcion_id="' + inscripciones_id[id2] + '"><span class="badge badge-primary badge-pill m-0 mr-3 rounded-0" style="background: blue; width:50px;">' + posiciones[id2] + '</span>' + nombres[id2] + '</li>';
                html += '</ul>';
            }
            html += '</div></div></div>';
            div.append(html);
        }
    })
}

function generar_enfrentamientos(competidores) {
    const rondas = (competidores) => {
        let schedule = []
        let league = competidores.slice()

        if (league.length % 2) {
            league.push('None')
        }
        let rounds = league.length
            //for (let j=0; j<(rounds-1)*2; j ++) { // ida y vuelta
        for (let j = 0; j < (rounds - 1) * 1; j++) {
            schedule[j] = []
            for (let i = 0; i < rounds / 2; i++) {
                if (league[i] !== 'None' && league[rounds - 1 - i] !== 'None') {
                    if (j % 2 == 1) {
                        schedule[j].push([league[i], league[rounds - 1 - i]])
                    } else {
                        schedule[j].push([league[rounds - 1 - i], league[i]])
                    }
                }
            }
            league.splice(1, 0, league.pop())
        }
        return schedule
    }
    let cuadroFinal = rondas(competidores)
    return cuadroFinal;
}

function dibujarRondas(tabla_id, cuadroFinal, nombres, equipos, posiciones) {
    $('table[id^="tablakumite_"]').each(function(index, tabla) {
        var tabla_id = $(tabla).attr('id');
        $('#' + tabla_id).next('div').remove();
        $('<div class="text-nowrap ml-3" style="overflow-x: auto;margin-top: 2.5rem;"></div>').insertAfter('#' + tabla_id);
        var div = $('#' + tabla_id).next('div');
        var grupo = tabla_id.replace('tablakumite_', '');
        var n_combate = 0;
        div.html('');
        for (let r = 0; r < cuadroFinal.length; r++) {
            var html = '<div class="d-inline-block" data-grupo="' + grupo + '" data-ronda="' + (r + 1) + '">';
            if (r == 0) {
                html += '<div class="pr-2">';
            } else {
                html += '<div class="border-left border-primary px-2">';
            }
            var ronda = 'Ronda ' + (r + 1);
            html += '<h4 class="bg-primary text-white p-2 mb-3">' + ronda + '</h4><div class="">'
            for (let c = 0; c < cuadroFinal[r].length; c++) {
                n_combate++;
                var id1 = cuadroFinal[r][c][0];
                var id2 = cuadroFinal[r][c][1];
                if (c == (cuadroFinal[r].length) - 1) {
                    html += '<ul class="list-group border border-primary match" data-combate="' + (c + 1) + '" data-ncombate="' + n_combate + '">';
                } else {
                    html += '<ul class="list-group border border-primary mb-3 match" data-combate="' + (c + 1) + '" data-ncombate="' + n_combate + '">';
                }
                html += '<li class="list-group-item d-flex justify-content-between align-items-center px-2 py-1 player" data-grupo="' + grupo + '" data-ronda="' + (r + 1) + '" data-combate="' + (c + 1) + '" data-color="rojo" data-user="' + id1 + '"><span class="badge badge-primary badge-pill m-0 mr-3 rounded-0" style="background: red; width:50px;">' + posiciones[id1] + '</span>' + nombres[id1] + '</li>';
                html += '<li class="list-group-item d-flex justify-content-between align-items-center px-2 py-1 player" data-grupo="' + grupo + '" data-ronda="' + (r + 1) + '" data-combate="' + (c + 1) + '" data-color="azul" data-user="' + id2 + '"><span class="badge badge-primary badge-pill m-0 mr-3 rounded-0" style="background: blue; width:50px;">' + posiciones[id2] + '</span>' + nombres[id2] + '</li>';
                html += '</ul>';
            }
            html += '</div></div></div>';
            div.append(html);
        }
    })
}

function dibulareliminatorias() {
    var rounds = [];
    grupos = $('table[id^="tablakumite_"]').length;
    if (grupos == 1) {
        var ronda = [
            [
                { name: "Primero", id: "g1|1" },
                { name: "Segundo", id: "g1|2" }
            ],
            [
                { name: "Tercero", id: "g1|3" },
                { name: "Cuarto", id: "g1|4" }
            ]
        ];
        rounds.push(ronda);
    }
    if (grupos == 2) {
        var ronda = [
            [
                { name: "Primero 1", id: "g1|1" },
                { name: "Segundo B", id: "g2|2" }
            ],
            [
                { name: "Primero 2", id: "g2|1" },
                { name: "Segundo A", id: "g1|2" }
            ]
        ];
        rounds.push(ronda);
        var ronda = [
            [
                { name: "Ganador 1", id: "r1|1" },
                { name: "Ganador 2", id: "r1|2" }
            ],
            [
                { name: "Perdedor 1", id: "r1|1-" },
                { name: "Perdedor 2", id: "r1|2-" }
            ]
        ];
        rounds.push(ronda);
    }
    if (grupos == 3) {
        var ronda = [
            [
                { name: "Primero 1", id: "g1|1" },
                { name: "Primero 2", id: "g2|1" }
            ],
            [
                { name: "Primero 3", id: "g3|1" },
                { name: "Mejor Segundo", id: "m2|1" }
            ]
        ];
        rounds.push(ronda);
        var ronda = [
            [
                { name: "Ganador 1", id: "r1|1" },
                { name: "Ganador 2", id: "r1|2" }
            ],
            [
                { name: "Perdedor 1", id: "r1|1-" },
                { name: "Perdedor 2", id: "r1|2-" }
            ]
        ];
        rounds.push(ronda);
    }
    if (grupos == 4) {
        var ronda = [
            [
                { name: "Primero 1", id: "g1|1" },
                { name: "Primero 2", id: "g2|1" }
            ],
            [
                { name: "Primero 3", id: "g3|1" },
                { name: "Primero 4", id: "g4|1" }
            ]
        ];
        rounds.push(ronda);
        var ronda = [
            [
                { name: "Ganador 1", id: "r1|1" },
                { name: "Ganador 2", id: "r1|2" }
            ],
            [
                { name: "Perdedor 1", id: "r1|1-" },
                { name: "Perdedor 2", id: "r1|2-" }
            ]
        ];
        rounds.push(ronda);
    }
    if (grupos > 4) {
        primerosgrupo = grupos;
        var n_rondas = 0;

        function buscar_resto(gr, exp) {
            n_rondas = exp;
            if (Math.pow(2, exp) < gr) {
                buscar_resto(gr, exp++);
            } else {
                return Math.pow(2, exp) - gr;
            }
        }

        segundosgrupos = buscar_resto(grupos, 3)
        var clasificados = primerosgrupo + segundosgrupos;
        var matches = clasificados / 2;
        for (let index = 0; index < n_rondas; index++) {
            var ronda = [];
            if (index == 0) {
                var clasificados_en_combate = 0;
                var segundosclasificados = 1;
                var idr = 'g';
                for (let i = 0; i < matches; i++) {
                    var estematch = [];
                    for (let clasif = 1; clasif <= 2; clasif++) {
                        var fighter = {};
                        fighter.name = '';
                        fighter.id = '';
                        estematch.push(fighter);
                    }
                    ronda.push(estematch);
                }
                // asignar los ganadores de grupo a cada combate, 
                for (let i = 0; i < matches; i++) {
                    var match = ronda[i];
                    match[0].name = 'Primero ' + primerosgrupo
                    match[0].id = 'g' + primerosgrupo + '|1'
                    primerosgrupo--;
                }
                // se recorren, para el resto de ganadores
                for (let i = 0; i < matches; i++) {
                    if (primerosgrupo > 0) {
                        var match = ronda[i];
                        match[1].name = 'Primero ' + primerosgrupo
                        match[1].id = 'g' + primerosgrupo + '|1'
                        primerosgrupo--;
                    }
                }
                // se recorre para los segundos
                for (let i = 0; i < matches; i++) {
                    if (segundosgrupos > 0) {
                        var match = ronda[i];
                        if (match[1].name == '') {
                            match[1].name = segundosgrupos + 'º Segundo'
                            match[1].id = 'm2|' + segundosgrupos
                            segundosgrupos--;
                        }
                    }
                }
                /*
                for (let i = 0; i < matches; i++) {
                    var estematch = [];
                    for (let clasif = 1; clasif <= 2; clasif++) {
                        var fighter = {};
                        if (clasificados_en_combate > grupos + 1) {
                            clasificado = 2;
                        } else {
                            clasificado = 1;
                        }
                        grupo = clasificados_en_combate + 1;
                        idr = 'g' + grupo;
                        if (grupo > grupos) {
                            fighter.name = segundosclasificados + 'º Segundo de grupo';
                            idr = 'm2|' + segundosclasificados;
                            segundosclasificados++;
                        } else {
                            fighter.name = 'Ganador Grupo ' + grupo;
                            idr = idr + '|' + clasificado
                        }
                        fighter.id = idr;
                        estematch.push(fighter);
                        clasificados_en_combate++;
                    }
                    ronda.push(estematch);
                }
                */
            } else {
                var idr = 'r' + index;
                var ganadorcombateanterior = 1;
                for (let i = 0; i < matches; i++) {
                    var estematch = [];
                    for (let clasif = 1; clasif <= 2; clasif++) {
                        var fighter = {};
                        fighter.name = 'Ganador ' + ganadorcombateanterior;
                        fighter.id = idr + '|' + ganadorcombateanterior;
                        estematch.push(fighter);
                        ganadorcombateanterior++
                    }
                    ronda.push(estematch);
                    if (index == (n_rondas - 1)) {
                        ganadorcombateanterior--;
                        ganadorcombateanterior--;
                        var lastmatch = [];
                        for (let clasif = 1; clasif <= 2; clasif++) {
                            var fighter = {};
                            fighter.name = 'Perdedor ' + ganadorcombateanterior;
                            fighter.id = idr + '|' + ganadorcombateanterior + '-';
                            lastmatch.push(fighter);
                            ganadorcombateanterior++
                        }
                        ronda.push(lastmatch);
                    }
                }
            }
            matches = matches / 2;
            rounds.push(ronda);
        }

    }
    $(".brackets").html('');
    $(".brackets").gracket({ src: rounds });
}

function dibular_competicion_eliminatoria(inscripciones) {

    var rounds = [];
    var total_inscripciones = inscripciones.length;
    var n_rondas = Math.ceil(Math.log(total_inscripciones) / Math.log(2));
    var total_deportistas = Math.pow(2, n_rondas);
    var matches = total_deportistas / 2;
    var vacios = total_deportistas - total_inscripciones;
    if (vacios > 0) {
        for (let index = 0; index < vacios; index++) {
            vacio = {};
            inscripciones.push(vacio)
        }
    }
    shuffleArray(inscripciones)
    for (let index = 0; index < n_rondas; index++) {
        var ronda = [];
        if (index == 0) {
            var idr = 'g';
            var posicion = 0;
            for (let i = 0; i < matches; i++) {
                var estematch = [];
                for (let clasif = 1; clasif <= 2; clasif++) {
                    var fighter = {};
                    var name = (inscripciones[posicion].hasOwnProperty('user_id')) ? inscripciones[posicion].first_name + ' ' + inscripciones[posicion].last_name : '';
                    var id = (inscripciones[posicion].hasOwnProperty('user_id')) ? inscripciones[posicion].user_id : 0;
                    var inscripcion_id = (inscripciones[posicion].hasOwnProperty('inscripcion_id')) ? inscripciones[posicion].inscripcion_id : "0";
                    fighter.name = name;
                    fighter.id = id;
                    fighter.inscripcion_id = inscripcion_id;
                    estematch.push(fighter);
                    posicion++;
                }
                ronda.push(estematch);
            }
        } else {
            var idr = 'r' + index;
            var ganadorcombateanterior = 1;
            for (let i = 0; i < matches; i++) {
                var estematch = [];
                for (let clasif = 1; clasif <= 2; clasif++) {
                    var fighter = {};
                    fighter.name = 'Ganador ' + ganadorcombateanterior;
                    fighter.id = idr + '|' + ganadorcombateanterior;
                    estematch.push(fighter);
                    ganadorcombateanterior++
                }
                ronda.push(estematch);

                if (index == (n_rondas - 1)) {
                    ganadorcombateanterior--;
                    ganadorcombateanterior--;
                    var lastmatch = [];
                    for (let clasif = 1; clasif <= 2; clasif++) {
                        var fighter = {};
                        fighter.name = 'Perdedor ' + ganadorcombateanterior;
                        fighter.id = idr + '|' + ganadorcombateanterior + '-';
                        lastmatch.push(fighter);
                        ganadorcombateanterior++
                    }
                    ronda.push(lastmatch);
                }
            }
        }
        matches = matches / 2;
        rounds.push(ronda);
    }
    console.log(rounds)
    $(".brackets").html('');
    $(".brackets").gracket({ src: rounds });
    eliminatoriasortable()

}


function dibujarLiga(inscripciones)
{
    console.log(inscripciones);

    let teams = [
        'Tigers',
        'Foofels',
        'Drampamdom',
        'Lakebaka'
    ]
      
      const roundRobin = (teams) => {
        let schedule = []
        let league = teams.slice()
        
        if (league.length % 2) {
          league.push('None')
        }
        
        let rounds = league.length
        
        for (let j=0; j<(rounds-1)*2; j ++) {
          schedule[j] = []
          for (let i=0; i<rounds/2; i++) {
            if (league[i] !== 'None' && league[rounds-1-i] !== 'None') {
              if (j % 2 == 1) {
                schedule[j].push([league[i], league[rounds-1-i]])
              } else {
                schedule[j].push([league[rounds-1-i], league[i]])
              }
            }
          }
          league.splice(1, 0, league.pop())
        }
        return schedule
      }
      
      let leagueSchedule = roundRobin(teams)
      
      for (let p=0; p<leagueSchedule.length; p++) {
        console.log(leagueSchedule[p])
      }
      
}
function eliminatoriasortable() {
    $('.g_round:first >.g_game').sortable({
        opacity: 0.9,
        items: '>*:not(.no-sort)',
        connectWith: $('.g_round:first >.g_game'),
        start: function(event, ui) {},
        receive: function(event, ui) {
            var item = $(ui.item);
            var sender = $(ui.sender);
            if (item.next().length > 0) {
                sender.append(item.next());
            } else {
                sender.append(item.prev());
            }
        },
        stop: function(event, ui) {}
    });
}

function shuffleArray(array) {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
}

function actualizar_datos_combates() {
    var matches2 = $('.match').length;
    var matchesel = $('.g_game').length;
    $('#totalcomabtes').html('Nº de combates: ' + (matches2 + matchesel))
}

$(document).on('click', '[data-guardar-puntos]', function() {
    var competicion_torneo_id = $(this).attr('data-guardar-puntos');
    var elementos = $(this).attr('data-inscripcion');
    var orderninscripciones = [];
    $.each($('[data-inscripcion]'), function(i, elem) {
        if ($(elem).attr('data-inscripcion') != '') {
            orderninscripciones.push($(elem).attr('data-inscripcion'))
        }
    })
    if (orderninscripciones.length > 0) {
        swal.fire({
            icon: 'question',
            title: 'Guardar orden de competición',
            html: '¿Quieres guardar el orden de los competidores como esta ahora?',
            showCancelButton: true,
            confirmButtonText: 'Si, guardar',
            cancelButtonText: 'No, cerrar',
        }).then((result) => {
            if (result.isConfirmed) {
                var fd = new FormData();
                fd.append("competicion_torneo_id", competicion_torneo_id);
                fd.append("orden_inscripciones", orderninscripciones);
                fd.append("csrf_token", $('[name="csrf_token"]').val());
                $.ajax({
                    url: base_url + 'Competiciones/guardar_orden_competicion',
                    method: "POST",
                    contentType: false,
                    processData: false,
                    data: fd
                }).done(function(response) {
                    var response = JSON.parse(response);
                    $('[name="csrf_token"]').val(response.csrf)
                    if (response.error > 0) {
                        var errorhtml = ''
                        if (response.hasOwnProperty('error_validation')) {
                            $.each(response.error_validation, function(i, value) {
                                errorhtml += value + '<br>'
                            })
                        }
                        if (response.hasOwnProperty('error_msn')) {
                            errorhtml += response.error_msn
                        }
                        swal.fire({
                            icon: 'error',
                            title: 'ERROR',
                            html: errorhtml,
                            willClose: function() {
                                if (response.hasOwnProperty('redirect')) {
                                    if (response.redirect == 'refresh') {
                                        location.reload()
                                    } else {
                                        window.location.href = response.redirect
                                    }
                                }
                            }
                        });
                        return;
                    } else {
                        swal.fire({
                            icon: 'success',
                            title: 'Correcto',
                            html: response.msn,
                            willClose: function() {
                                if (response.hasOwnProperty('redirect')) {
                                    window.location.href = response.redirect
                                }
                            }
                        })
                    }
                }).always(function(jqXHR, textStatus) {
                    if (textStatus != "success") {
                        swal.fire({
                            icon: 'error',
                            title: 'Ha ocurrido un error AJAX',
                            html: jqXHR.statusText,
                            timer: 5000,
                            willClose: function() {}
                        })
                    }
                });
            }
        })
    } else {
        swal.fire({
            icon: 'error',
            title: 'No hay participantes en el tablero de competición',
            timer: 3000,
            willClose: function() {}
        })
    }
})

$(document).on('click', '[data-guardar-liguilla]', function() {
    swal.fire({
        icon: 'question',
        title: 'Guardar orden de competición',
        html: '¿Quieres guardar los grupos y combates en el orden actual?',
        showCancelButton: true,
        confirmButtonText: 'Si, guardar',
        cancelButtonText: 'No, cerrar',
    }).then((result) => {
        if (result.isConfirmed) {
            var competicion_torneo_id = $(this).attr('data-guardar-liguilla');
            var inscripciones_id = [];
            var matches = [];
            $.each($('tr[data-user]'), function(i, elem) {
                var incripcion = {};
                var grupo = $(elem).closest('[grupo]').attr('grupo')
                    //grupo = grupo.toLowerCase().charCodeAt(0) - 97 + 1;
                incripcion.grupo = grupo
                var inscripcion_id = $(elem).attr('data-inscripcion_id');
                incripcion.inscripcion_id = inscripcion_id;
                inscripciones_id.push(incripcion)
            })
            $.each($('ul[data-ncombate]'), function(i, elem) {
                var match = {};
                var ncombate = $(elem).attr('data-ncombate');
                var grupo = $(elem).closest('[grupo]').attr('grupo')
                    //grupo = grupo.toLowerCase().charCodeAt(0) - 97 + 1;
                var ronda = $(elem).closest('[data-ronda]').attr('data-ronda');
                var rojo = $(elem).find('[data-color="rojo"]');
                var azul = $(elem).find('[data-color="azul"]');
                var user_rojo = $(rojo).attr('data-user');
                var inscripcion_rojo = $(rojo).attr('data-inscripcion_id')
                var user_azul = $(azul).attr('data-user');
                var inscripcion_azul = $(azul).attr('data-inscripcion_id')
                match.ncombate = ncombate;
                match.grupo = grupo;
                match.ronda = ronda;
                match.user_rojo = user_rojo;
                match.inscripcion_rojo = inscripcion_rojo;
                match.user_azul = user_azul;
                match.inscripcion_azul = inscripcion_azul;
                match.parent_rojo = 0;
                match.parent_azul = 0;
                matches.push(match)
            })


            $.each($('.brackets .g_round'), function(i, elem_ronda) {
                var ronda = i + 1;
                $(elem_ronda).children('.g_game').each(function(m, elem) {
                    var match = {};
                    var ncombate = matches.length + 1;
                    var grupo = 0
                    var user_rojo = 0;
                    var inscripcion_rojo = 0
                    var user_azul = 0;
                    var inscripcion_azul = 0
                        //if($(elem).children().length > 1){
                    match.ncombate = ncombate;
                    match.grupo = grupo;
                    match.ronda = ronda;
                    match.user_rojo = user_rojo;
                    match.inscripcion_rojo = inscripcion_rojo;
                    match.user_azul = user_azul;
                    match.inscripcion_azul = inscripcion_azul;
                    match.parent_rojo = $(elem).find(">:first-child").attr('data-user');
                    match.parent_azul = $(elem).find(">:last-child").attr('data-user');
                    matches.push(match)
                        //}
                })
            })
            var fd = new FormData();
            fd.append("competicion_torneo_id", competicion_torneo_id);
            fd.append("inscripciones_id", JSON.stringify(inscripciones_id));
            fd.append("matches", JSON.stringify(matches));
            fd.append("tipo", "liguilla");
            fd.append("csrf_token", $('[name="csrf_token"]').val());
            $.ajax({
                url: base_url + 'Competiciones/guardar_grupos_competicion',
                method: "POST",
                contentType: false,
                processData: false,
                data: fd
            }).done(function(response) {
                var response = JSON.parse(response);
                $('[name="csrf_token"]').val(response.csrf)
                if (response.error > 0) {
                    var errorhtml = ''
                    if (response.hasOwnProperty('error_validation')) {
                        $.each(response.error_validation, function(i, value) {
                            errorhtml += value + '<br>'
                        })
                    }
                    if (response.hasOwnProperty('error_msn')) {
                        errorhtml += response.error_msn
                    }
                    swal.fire({
                        icon: 'error',
                        title: 'ERROR',
                        html: errorhtml,
                        willClose: function() {
                            if (response.hasOwnProperty('redirect')) {
                                if (response.redirect == 'refresh') {
                                    location.reload()
                                } else {
                                    window.location.href = response.redirect
                                }
                            }
                        }
                    });
                    return;
                } else {
                    swal.fire({
                        icon: 'success',
                        title: 'Correcto',
                        html: response.msn,
                        willClose: function() {
                            if (response.hasOwnProperty('redirect')) {
                                window.location.href = response.redirect
                            }
                        }
                    })
                }
            }).always(function(jqXHR, textStatus) {
                if (textStatus != "success") {
                    swal.fire({
                        icon: 'error',
                        title: 'Ha ocurrido un error AJAX',
                        html: jqXHR.statusText,
                        timer: 5000,
                        willClose: function() {}
                    })
                }
            });
        }
    })
})

$(document).on('click', '[data-guardar-eliminatoria]', function() {
    swal.fire({
        icon: 'question',
        title: 'Guardar el sorteo de la eliminatoria',
        html: '¿Quieres guardar los combates en el orden actual?',
        showCancelButton: true,
        confirmButtonText: 'Si, guardar',
        cancelButtonText: 'No, cerrar',
    }).then((result) => {
        if (result.isConfirmed) {
            var competicion_torneo_id = $(this).attr('data-guardar-eliminatoria');
            var inscripciones_id = [];
            var matches = [];

            $.each($('.brackets .g_round'), function(i, elem_ronda) {
                    var ronda = i + 1;
                    $(elem_ronda).children('.g_game').each(function(m, elem) {
                        var match = {};
                        var ncombate = matches.length + 1;
                        var grupo = 0
                        var user_rojo = 0;
                        var inscripcion_rojo = 0
                        var user_azul = 0;
                        var inscripcion_azul = 0
                        if (ronda > 1) {
                            match.ncombate = ncombate;
                            match.grupo = grupo;
                            match.ronda = ronda;
                            match.user_rojo = user_rojo;
                            match.inscripcion_rojo = inscripcion_rojo;
                            match.user_azul = user_azul;
                            match.inscripcion_azul = inscripcion_azul;
                            match.parent_rojo = $(elem).find(">:first-child").attr('data-user');
                            match.parent_azul = $(elem).find(">:last-child").attr('data-user');
                        } else {
                            match.ncombate = ncombate;
                            match.grupo = grupo;
                            match.ronda = ronda;
                            match.user_rojo = $(elem).find(">:first-child").attr('data-user');
                            match.inscripcion_rojo = $(elem).find(">:first-child").attr('data-inscripcion_id');
                            match.user_azul = $(elem).find(">:last-child").attr('data-user');
                            match.inscripcion_azul = $(elem).find(">:last-child").attr('data-inscripcion_id');
                            match.parent_rojo = 0;
                            match.parent_azul = 0;
                            inscripciones_id.push($(elem).find(">:first-child").attr('data-inscripcion_id'));
                            inscripciones_id.push($(elem).find(">:last-child").attr('data-inscripcion_id'));
                        }

                        matches.push(match)
                    })
                })
                /* console.log(inscripciones_id);

                 return;*/


            var fd = new FormData();
            fd.append("competicion_torneo_id", competicion_torneo_id);
            fd.append("inscripciones_id", JSON.stringify(inscripciones_id));
            fd.append("matches", JSON.stringify(matches));
            fd.append("tipo", "eliminatoria");
            fd.append("csrf_token", $('[name="csrf_token"]').val());
            $.ajax({
                url: base_url + 'Competiciones/guardar_grupos_competicion',
                method: "POST",
                contentType: false,
                processData: false,
                data: fd
            }).done(function(response) {
                var response = JSON.parse(response);
                $('[name="csrf_token"]').val(response.csrf)
                if (response.error > 0) {
                    var errorhtml = ''
                    if (response.hasOwnProperty('error_validation')) {
                        $.each(response.error_validation, function(i, value) {
                            errorhtml += value + '<br>'
                        })
                    }
                    if (response.hasOwnProperty('error_msn')) {
                        errorhtml += response.error_msn
                    }
                    swal.fire({
                        icon: 'error',
                        title: 'ERROR',
                        html: errorhtml,
                        willClose: function() {
                            if (response.hasOwnProperty('redirect')) {
                                if (response.redirect == 'refresh') {
                                    location.reload()
                                } else {
                                    window.location.href = response.redirect
                                }
                            }
                        }
                    });
                    return;
                } else {
                    swal.fire({
                        icon: 'success',
                        title: 'Correcto',
                        html: response.msn,
                        willClose: function() {
                            if (response.hasOwnProperty('redirect')) {
                                window.location.href = response.redirect
                            }
                        }
                    })
                }
            }).always(function(jqXHR, textStatus) {
                if (textStatus != "success") {
                    swal.fire({
                        icon: 'error',
                        title: 'Ha ocurrido un error AJAX',
                        html: jqXHR.statusText,
                        timer: 5000,
                        willClose: function() {}
                    })
                }
            });
        }
    })
})

$(document).on('click', '#exportar_grupos', function() {
    var competicion_torneo_id = $(this).attr('data-competicion_torneo_id');
    window.open(base_url + 'Competiciones/pdfdoc/' + competicion_torneo_id, '_blank');
})

$(document).ready(function() {
    tabassortable();
    dibujar_cruces_grupos();
    dibulareliminatorias();
    actualizar_datos_combates();
    if ($('[data-inscritos-eliminatoria]').length) {
        var competicion_torneo_id = $('[data-inscritos-eliminatoria]').attr('data-inscritos-eliminatoria');
        rounds = $.get(base_url + 'Competiciones/eliminatoriaMatches/' + competicion_torneo_id)

        $.ajax({
            url: base_url + 'Competiciones/eliminatoriaMatches/' + competicion_torneo_id,
            method: "GET",
            contentType: false,
            processData: false,
        }).done(function(response) {
            var response = JSON.parse(response);
            console.log(response)
            $(".brackets").html('');
            $(".brackets").gracket({ src: response });
            eliminatoriasortable()
            actualizar_datos_combates();
        })
    }
})

$(document).on('click', '#add_inscripcion', function() {
    // enviar
    var fd = new FormData();
    fd.append("torneo_id", $('[name="torneo_id"]').val());
    fd.append("competicion_nueva_torneo_id", $('[name="competicion_nueva_torneo_id"]').val());
    fd.append("competicion_previa_torneo_id", 0);
    fd.append("estado", 1);
    fd.append("user_id", $('[name="deportista_id"]').val());
    fd.append("csrf_token", $('[name="csrf_token"]').val());
    $.ajax({
        url: base_url + 'Torneos/manage_inscripciones',
        method: "POST",
        contentType: false,
        processData: false,
        data: fd
    }).done(function(response) {
        var response = JSON.parse(response);
        $('[name="csrf_token"]').val(response.csrf)
        if (response.error > 0) {
            var errorhtml = ''
            if (response.hasOwnProperty('error_validation')) {
                $.each(response.error_validation, function(i, value) {
                    errorhtml += value + '<br>'
                })
            }
            if (response.hasOwnProperty('error_msn')) {
                errorhtml += response.error_msn
            }
            swal.fire({
                icon: 'error',
                title: 'ERROR',
                html: errorhtml,
                willClose: function() {
                    if (response.hasOwnProperty('redirect')) {
                        if (response.redirect == 'refresh') {
                            location.reload()
                        } else {
                            window.location.href = response.redirect
                        }
                    }
                }
            });
            select.val(competicion_previa_torneo_id);
            return;
        } else {
            window.location.reload()
        }
    }).always(function(jqXHR, textStatus) {
        if (textStatus != "success") {
            swal.fire({
                icon: 'error',
                title: 'Ha ocurrido un error AJAX',
                html: jqXHR.statusText,
                timer: 5000,
                willClose: function() {}
            })
        }
    });
})

$(document).ready(function() {
    $(".select2").select2();
});