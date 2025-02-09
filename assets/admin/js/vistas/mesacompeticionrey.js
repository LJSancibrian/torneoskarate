$(document).on('click', '[data-inscripcion]', function () {
    $('[contenteditable]').removeAttr('contenteditable');
    if ($(this).hasClass('btn-border')) {
        $(this).removeClass('btn-border')
    } else {
        $('[data-inscripcion].btn-border').removeClass('btn-border')
        var tr = $(this).closest('tr');
        $(tr).find('td[data-editable]').prop('contenteditable', true);
        $(this).addClass('btn-border')
        localStorage.setItem('enkata', $(this).html())
    }
})

$(document).on('focus', '[data-editable]', function () {
    var range = document.createRange();
    range.selectNodeContents(this);

    var selection = window.getSelection();
    selection.removeAllRanges();
    selection.addRange(range);
});

$(document).on('focusout', '[data-editable]', function () {
    if ($(this).text() == '') {
        return;
    }
    var tr = $(this).closest('tr');
    var user_id = $(tr).attr('data-user_id');
    var competicion_torneo_id = $('#competicion_torneo_id').val();
    var field = $(this).attr('data-field');
    var valor = parseFloat($(this).text());

    var fd = new FormData();
    fd.append("competicion_torneo_id", competicion_torneo_id);
    fd.append("user_id", user_id);
    fd.append("field", field);
    fd.append("valor", valor);
    fd.append("csrf_token", $('[name="csrf_token"]').val());
    $.ajax({
        url: base_url + 'Competiciones/guardar_puntos_rey',
        method: "POST",
        contentType: false,
        processData: false,
        data: fd
    }).done(function (response) {
        var response = JSON.parse(response);
        $('[name="csrf_token"]').val(response.csrf)
        if (response.error > 0) {
            var errorhtml = ''
            if (response.hasOwnProperty('error_validation')) {
                $.each(response.error_validation, function (i, value) {
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
                willClose: function () {
                    if (response.hasOwnProperty('redirect')) {
                        if (response.redirect == 'refresh') {
                            location.reload()
                        } else {
                            window.location.href = response.redirect
                        }
                    }
                }
            });
            select.val(oldvalue);
            return;
        } else {
            cargar_puntos();
        }
    }).always(function (jqXHR, textStatus) {
        if (textStatus != "success") {
            swal.fire({
                icon: 'error',
                title: 'Ha ocurrido un error AJAX',
                html: jqXHR.statusText,
                timer: 5000,
                willClose: function () { }
            })
        }
    });
})

$(document).on('keypress', 'td[contenteditable]', function (e) {
    if (isNaN(String.fromCharCode(e.which))) e.preventDefault();
});

function cargar_puntos() {
    var competicion_torneo_id = $('#competicion_torneo_id').val();
    var fd = new FormData();
    fd.append("competicion_torneo_id", competicion_torneo_id);
    fd.append("csrf_token", $('[name="csrf_token"]').val());
    $.ajax({
        url: base_url + 'Competiciones/obtener_puntos_rey_competicion',
        method: "POST",
        contentType: false,
        processData: false,
        data: fd
    }).done(function (response) {
        var response = JSON.parse(response);
        $('[name="csrf_token"]').val(response.csrf)
        if (response.error > 0) {
            var errorhtml = ''
            if (response.hasOwnProperty('error_validation')) {
                $.each(response.error_validation, function (i, value) {
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
                willClose: function () {
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
            $.each(response.deportistas, function (i, deportista) {
                var tr = $('tr[data-user_id="' + deportista.user_id + '"]');
                tr.find('[data-field="penalizaciones"]').html(deportista.penalizaciones)
                tr.find('[data-field="victorias"]').html(deportista.victorias)
                tr.find('[data-field="empates"]').html(deportista.empates)
                tr.find('[data-field="derrotas"]').html(deportista.derrotas)
                tr.find('[data-field="puntos_favor"]').html(deportista.puntos_favor)
                tr.find('[data-field="total_combates"]').html(deportista.total_combates)
                tr.find('[data-field="puntos_total"]').html(deportista.puntos_total)
            })

            $.each($('tbody[data-competicion_torneo_id][data-grupo]'), function (g, grupo) {
                console.log(g)
                updateClasificacionGrupo(competicion_torneo_id, g + 1)
            })
        }
    }).always(function (jqXHR, textStatus) {
        if (textStatus != "success") {
            swal.fire({
                icon: 'error',
                title: 'Ha ocurrido un error AJAX',
                html: jqXHR.statusText,
                timer: 5000,
                willClose: function () { }
            })
        }
    });
}

function updateClasificacionGrupo(competicion_torneo_id, grupo) {
    var fd = new FormData();
    fd.append("competicion_torneo_id", competicion_torneo_id);
    fd.append("grupo", grupo);
    fd.append("csrf_token", $('[name="csrf_token"]').val());
    $.ajax({
        url: base_url + 'Competiciones/clasificacionGrupoRey',
        method: "POST",
        contentType: false,
        processData: false,
        data: fd
    }).done(function (response) {
        var response = JSON.parse(response);
        $('[name="csrf_token"]').val(response.csrf)
        if (response.error > 0) {
            var errorhtml = ''
            if (response.hasOwnProperty('error_validation')) {
                $.each(response.error_validation, function (i, value) {
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
                willClose: function () {
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
            var tbody = $('tbody[data-competicion_torneo_id="' + competicion_torneo_id + '"][data-grupo="' + grupo + '"]')
            tbody.slideUp();
            tbody.html('');
            $.each(response.users, function (i, user) {
                var posicion = i + 1;
                var deportista = user.first_name + ' ' + user.last_name;
                var club = user.nombre;

                var tr = '<tr>';
                tr += '<td>' + posicion + '</td>';
                tr += '<td>' + deportista + '</td>';
                tr += '<td>' + club + '</td>';
                tr += '<td>' + user.puntos_total + '</td>';
                tr += '<td>' + user.victorias + '</td>';
                tr += '<td>' + user.empates + '</td>';
                tr += '<td>' + user.derrotas + '</td>';
                tr += '<td>' + user.puntos_favor + '</td>';
                tr += '<td>' + user.total_combates + '</td>';
                tr += '</th>';
                tbody.append(tr);
            })
            tbody.slideDown();

            var tbodyg = $('#tablakumite_' + grupo + ' tbody[data-competicion_torneo_id="' + competicion_torneo_id + '"][data-grupo="' + grupo + '"]')
            tbodyg.slideUp();
            tbodyg.html('');
            $.each(response.users, function (i, user) {
                var posicion = i + 1;
                var deportista = user.first_name + ' ' + user.last_name;
                var club = user.nombre;

                var tr = '<tr>';
                tr += '<td>' + posicion + '</td>';
                tr += '<td colspan="2">' + deportista + '</td>';
                tr += '<td>' + club + '</td>';
                tr += '</th>';

                tbodyg.append(tr);
            })
            tbodyg.slideDown();
        }
    }).always(function (jqXHR, textStatus) {
        if (textStatus != "success") {
            swal.fire({
                icon: 'error',
                title: 'Ha ocurrido un error AJAX',
                html: jqXHR.statusText,
                timer: 5000,
                willClose: function () { }
            })
        }
    });

}


function dibujar_cruces_grupos() {
    var competicion_torneo_id = $('#faseeliminatorias').attr('competicion_torneo_id');
    var fd = new FormData();
    fd.append("competicion_torneo_id", competicion_torneo_id);
    fd.append("csrf_token", $('[name="csrf_token"]').val());
    $.ajax({
        url: base_url + 'Competiciones/eliminatoriasCompeticion',
        method: "POST",
        contentType: false,
        processData: false,
        data: fd
    }).done(function (response) {
        var response = JSON.parse(response);
        $('[name="csrf_token"]').val(response.csrf)
        if (response.data.length > 0) {
            $(".brackets").html('')
            $(".brackets").slideDown(300)
            $(".brackets").gracket({ src: response.data });

        } else {
            $(".brackets").slideDown(300)
        }

    }).always(function (jqXHR, textStatus) {
        if (textStatus != "success") {
            swal.fire({
                icon: 'error',
                title: 'Ha ocurrido un error AJAX',
                html: jqXHR.statusText,
                timer: 5000,
                willClose: function () { }
            })
        }
    });
    TestData = [
        /* [
             [
                 { "name": "Erik Zettersten", "id": "erik-zettersten", "seed": 1, "displaySeed": "D1", "score": 47 }, 
                 { "name": "Andrew Miller", "id": "andrew-miller", "seed": 2 }
             ],
             [{ "name": "James Coutry", "id": "james-coutry", "seed": 3 }, { "name": "Sam Merrill", "id": "sam-merrill", "seed": 4 }],
             [{ "name": "Anothy Hopkins", "id": "anthony-hopkins", "seed": 5 }, { "name": "Everett Zettersten", "id": "everett-zettersten", "seed": 6 }],
             [{ "name": "John Scott", "id": "john-scott", "seed": 7 }, { "name": "Teddy Koufus", "id": "teddy-koufus", "seed": 8 }],
             [{ "name": "Arnold Palmer", "id": "arnold-palmer", "seed": 9 }, { "name": "Ryan Anderson", "id": "ryan-anderson", "seed": 10 }],
             [{ "name": "Jesse James", "id": "jesse-james", "seed": 1 }, { "name": "Scott Anderson", "id": "scott-anderson", "seed": 12 }],
             [{ "name": "Josh Groben", "id": "josh-groben", "seed": 13 }, { "name": "Sammy Zettersten", "id": "sammy-zettersten", "seed": 14 }],
             [{ "name": "Jake Coutry", "id": "jake-coutry", "seed": 15 }, { "name": "Spencer Zettersten", "id": "spencer-zettersten", "seed": 16 }]
         ],
         [
             [{ "name": "Erik Zettersten", "id": "erik-zettersten", "seed": 1 }, { "name": "James Coutry", "id": "james-coutry", "seed": 3 }],
             [{ "name": "Anothy Hopkins", "id": "anthony-hopkins", "seed": 5 }, { "name": "Teddy Koufus", "id": "teddy-koufus", "seed": 8 }],
             [{ "name": "Ryan Anderson", "id": "ryan-anderson", "seed": 10 }, { "name": "Scott Anderson", "id": "scott-anderson", "seed": 12 }],
             [{ "name": "Sammy Zettersten", "id": "sammy-zettersten", "seed": 14 }, { "name": "Jake Coutry", "id": "jake-coutry", "seed": 15 }]
         ],*/
        [
            [{ "name": "Erik Zettersten", "id": "erik-zettersten", "seed": 1 }, { "name": "Anothy Hopkins", "id": "anthony-hopkins", "seed": 5 }],
            [{ "name": "Ryan Anderson", "id": "ryan-anderson", "seed": 10 }, { "name": "Sammy Zettersten", "id": "sammy-zettersten", "seed": 14 }]
        ],
        [
            [{ "name": "Erik Zettersten", "id": "erik-zettersten", "seed": 1 }, { "name": "Ryan Anderson", "id": "ryan-anderson", "seed": 10 }]
        ],
        [
            [{ "name": "Erik Zettersten", "id": "erik-zettersten", "seed": 1 }]
        ]
    ];

    // initializer


}

$(document).on('click', '[data-guardar-clasificaicon]', function () {
    var competicion_torneo_id = $(this).attr('data-guardar-clasificaicon');
    var grupo = $(this).attr('data-grupo');
    var fd = new FormData();
    fd.append("competicion_torneo_id", competicion_torneo_id);
    fd.append("grupo", grupo);
    fd.append("csrf_token", $('[name="csrf_token"]').val());
    $.ajax({
        url: base_url + 'Competiciones/guardarClasificacionGrupo',
        method: "POST",
        contentType: false,
        processData: false,
        data: fd
    }).done(function (response) {
        var response = JSON.parse(response);
        $('[name="csrf_token"]').val(response.csrf)
        if (response.error > 0) {
            var errorhtml = ''
            if (response.hasOwnProperty('error_validation')) {
                $.each(response.error_validation, function (i, value) {
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
                willClose: function () {
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
                title: 'OK',
                html: response.msn,
                willClose: function () {
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
        }

    }).always(function (jqXHR, textStatus) {
        if (textStatus != "success") {
            swal.fire({
                icon: 'error',
                title: 'Ha ocurrido un error AJAX',
                html: jqXHR.statusText,
                timer: 5000,
                willClose: function () { }
            })
        }
    });
});


$(document).ready(function () {
    cargar_puntos();
    $('[crear_combate_rey="true"]').each(function (i, btn_crear) {
        var competicion_torneo_id = $(btn_crear).attr('data-competicion_torneo_id');
        var grupo = $(btn_crear).attr('data-grupo');
        updateMatchesListGrupo(competicion_torneo_id, grupo);
    })
    
})

$(document).on('click', '#exportar_grupos', function () {
    var competicion_torneo_id = $(this).attr('data-competicion_torneo_id');
    window.open(base_url + 'Competiciones/pdfdoc/' + competicion_torneo_id, '_blank');
})

$(document).on('click', '[data-finalizar-competicion]', function (event) {
    event.preventDefault();
    var url = $(this).attr('href');
    swal.fire({
        icon: 'info',
        title: '¿Finalizar la competición?<br>Con la competición finalizada, ya no se podrán cambiar los datos.',
        showCancelButton: true,
        confirmButtonText: 'Finalizar',
        cancelButtonText: 'Cerrar',
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url
        }
    })
})


$(document).on('click', '[crear_combate_rey="true"]', function () {
    var btn = $(this);
    var ao = $('#add_ao').val();
    var aoText = $('#add_ao').select2('data')[0].text;
    var aoInscripcion = $('#add_ao option:selected').attr('data-inscripcion');
    var aka = $('#add_aka').val();
    var akaText = $('#add_aka').select2('data')[0].text;
    var akaInscripcion = $('#add_aka option:selected').attr('data-inscripcion');

    var competicion_torneo_id = $(this).attr('data-competicion_torneo_id');
    var grupo = $(this).attr('data-grupo');


    swal.fire({
        html: '¿Crear combate entre <br>' + aoText + ' (AO)<br>' + akaText + ' (AKA)?',
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Crear combate',
        cancelButtonText: 'Cerrar',
    }).then((result) => {
        if (result.isConfirmed) {
            var fd = new FormData();
            fd.append("competicion_torneo_id", competicion_torneo_id);
            fd.append("grupo", grupo);
            fd.append("user_rojo", aka);
            fd.append("inscripcion_rojo", akaInscripcion);
            fd.append("user_azul", ao);
            fd.append("inscripcion_azul", aoInscripcion);
            fd.append("csrf_token", $('[name="csrf_token"]').val());
            $.ajax({
                url: base_url + 'Competiciones/addMatch',
                method: "POST",
                contentType: false,
                processData: false,
                data: fd
            }).done(function (response) {
                var response = JSON.parse(response);
                $('[name="csrf_token"]').val(response.csrf)
                if (response.error > 0) {
                    var errorhtml = ''
                    if (response.hasOwnProperty('error_validation')) {
                        $.each(response.error_validation, function (i, value) {
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
                        willClose: function () {
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
                    // actualizar la lista de combates
                    var match = response.match
                    swal.fire({
                        icon: 'success',
                        title: 'OK',
                        html: errorhtml,
                        willClose: function () {
                            updateMatchesListGrupo(competicion_torneo_id, grupo);
                        }
                    });
                }
            })
        }
    });
});

function updateMatchesListGrupo(competicion_torneo_id, grupo) {
    var fd = new FormData();
    fd.append("competicion_torneo_id", competicion_torneo_id);
    fd.append("grupo", grupo);
    fd.append("csrf_token", $('[name="csrf_token"]').val());
    $.ajax({
        url: base_url + 'Competiciones/getMatchesRey',
        method: "POST",
        contentType: false,
        processData: false,
        data: fd
    }).done(function (response) {
        var response = JSON.parse(response);
        $('[name="csrf_token"]').val(response.csrf)
        if (response.error > 0) {
            var errorhtml = ''
            if (response.hasOwnProperty('error_validation')) {
                $.each(response.error_validation, function (i, value) {
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
                willClose: function () {
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
            var tbody = $('#matches_rey_' + grupo).find('tbody');
            tbody.slideUp();
            tbody.html('');

            $.each(response.matches, function (i, match) {
                console.log(match)
                var match_id = match.match_id

                var deportista_azul = match.first_name_azul + ' '+ match.last_name_azul;
                var club_azul = match.club_azul;
                var puntos_azul = match.puntos_azul

                var deportista_rojo = match.first_name_rojo + ' '+ match.last_name_rojo;
                var club_rojo = match.club_rojo;
                var puntos_rojo = match.puntos_rojo


                var senshu_azul = '';
                var senshu_rojo = '';
                if (match.senshu == 'azul') {
                    senshu_azul = 'background-color: yellow;';
                } else if (match.senshu == 'rojo') {
                    senshu_rojo = 'background-color: yellow;';
                } else if (match.hantei == 'azul') {
                    senshu_azul = 'background-color: black;';
                } else if (match.hantei == 'rojo') {
                    senshu_rojo = 'background-color: black;';
                }

                var row = `
                    <tr>
                        <td><button type="button" class="btn btn-primary px-2 py-1" style="font-size: 12px;" data-manage-match="${match_id}">${match_id}</button></td>
                        <td style="height:40px; vertical-align:bottom; text-align: left; padding:2px 4px; color:blue; font-weight:bold;position:relative;">
                            ${deportista_azul}<br>${club_azul}
                            ${generateBoxes('azul', match.penalizaciones_azul)}
                        </td>
                        <td style="border: 1px solid blue; background-color: blue; color: #ffffff; height:40px; width: 40px;  text-align: center; font-size: 18px; position: relative;">
                            <div style="height:10px; width:10px;border-radius: 5px; display:inline-block; position:absolute; top: 0; left: 0; ${senshu_azul}"></div>
                            ${puntos_azul}
                        </td>
                        <td style="border: 1px solid red; background-color: red; color: #ffffff; height:40px; width: 40px; text-align: right; text-align: center; font-size: 18px; position: relative;">
                            ${puntos_rojo}
                            <div style="height:10px; width:10px; border-radius: 5px; display:inline-block; position:absolute; top: 0; rigth: 0; ${senshu_rojo}"></div>
                        </td>
                        <td style="height:40px; vertical-align:bottom; text-align: right; padding:2px 4px;color:red; font-weight:bold; position:relative;">
                            ${deportista_rojo}<br>${club_rojo}
                            ${generateBoxes('rojo', match.penalizaciones_rojo)}
                        </td>
                        <td class="text-center">
                            <span class="badge badge-info">${match.tatami}</span>
                        </td>  
                    </tr>
                    <tr style="border: 0px !important">
                        <td colspan="4" style="padding:2px;border: 0px !important"></td>
                    </tr>
                `;

                tbody.append(row);
            });

            // Volver a mostrar con animación
            tbody.slideDown();

            // Función para generar los cuadritos dinámicamente
            function generateBoxes(color, count, marcados) {
                var classcss = (color == 'azul') ? 'right: 0' : 'left: 0';
                let boxes = `<div style="position: absolute; top: 0; ${classcss}">`;
                for (let i = 0; i < count; i++) {
                    var marcado = '';
                    if(marcado <= i){
                        marcado = 'background-color: red;';
                    }
                    boxes += `<div style="height:5.5px; width:5px; display:block; margin: 3px; ${marcado}"></div>`;
                }
                boxes += `</div>`;
                return boxes;
            }

        }
    }).always(function (jqXHR, textStatus) {
        if (textStatus != "success") {
            swal.fire({
                icon: 'error',
                title: 'Ha ocurrido un error AJAX',
                html: jqXHR.statusText,
                timer: 5000,
                willClose: function () { }
            })
        }
    });

}