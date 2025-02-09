$(document).on('click', '[data-match_id]', function() {
    var match = $(this)
    var match_id = match.attr('data-match_id');

    var fd = new FormData();
    fd.append("match_id", match_id);
    fd.append("csrf_token", $('[name="csrf_token"]').val());
    $.ajax({
        url: base_url + 'Competiciones/getMatch',
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
            //return;
            var thismath = response.match;
            $('#marcadorauxiliar').find('[data-match-id]').attr('data-match-id', thismath.match_id)
            var nombre_rojo = (thismath.hasOwnProperty('rojo')) ? thismath.rojo.nombre : '';
            var nombre_azul = (thismath.hasOwnProperty('azul')) ? thismath.azul.nombre : '';
            $('#user_rojo').html(nombre_rojo)
            $('#user_azul').html(nombre_azul)
            $('#puntostotalesrojo').html(thismath.puntos_rojo)
            $('#puntostotalesazul').html(thismath.puntos_azul)
            $('#marcadorauxiliar [type="radio"]').prop("checked", false);
            var senshu = '';
            if (thismath.senshu != '') {
                $('[name="senshu"][value="' + thismath.senshu + '"]').prop('checked', true)
            }
            if (thismath.hantei != '') {
                $('[name="hantei"][value="' + thismath.hantei + '"]').prop('checked', true)
            }
            if (thismath.hasOwnProperty('rojo')) {
                $('[data-match-rojo]').show();
            } else {
                $('[data-match-rojo]').hide();
            }
            if (thismath.hasOwnProperty('azul')) {
                $('[data-match-azul]').show();
            } else {
                $('[data-match-azul]').hide();
            }
            $('#marcadorauxiliar').modal('show');
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



    // si es ul 
    var user_rojo = match.find('[data-user]')[0];
    var user_azul = match.find('[data-user]')[1];
    var user_rojo_name = $(user_rojo).children(":first").html()
    var user_azul_name = $(user_azul).children(":first").html()
    var user_rojo_puntos = $(user_rojo).children(":last").html()
    var user_azul_puntos = $(user_azul).children(":last").html()
        // si es div
    $('#marcadorauxiliar').find('[data-match-id]').attr('data-match-id', match_id)
    $('#user_rojo').html(user_rojo_name)
    $('#user_azul').html(user_azul_name)
    $('#puntostotalesrojo').html(user_rojo_puntos)
    $('#puntostotalesazul').html(user_azul_puntos)
    $('#marcadorauxiliar [type="tadio"]').prop("checked", false);
    var senshu = '';
    if ($(user_rojo).children(":last").hasClass('senshu')) {
        var senshu = 'rojo';
    }
    if ($(user_azul).children(":last").hasClass('senshu')) {
        var senshu = 'azul';
    }
    if (senshu != '') {
        $('[name="senshu"][value="' + senshu + '"]').prop('checked', true)
    }
    var hantei = '';
    if ($(user_rojo).children(":last").hasClass('hantei')) {
        var hantei = 'rojo';
    }
    if ($(user_azul).children(":last").hasClass('hantei')) {
        var hantei = 'azul';
    }
    if (hantei != '') {
        $('[name="hantei"][value="' + hantei + '"]').prop('checked', true)
    }

    $('#marcadorauxiliar').modal('show');
});

$(document).on('click', '[data-plus]', function() {
    var color = $(this).attr('data-plus')
    var puntos = $('#puntostotales' + color).html()
    var puntosnuevos = (parseFloat(puntos) + 1 < 0) ? 0 : parseFloat(puntos) + 1
    $('#puntostotales' + color).html(puntosnuevos)
})

$(document).on('click', '[data-minus]', function() {
    var color = $(this).attr('data-minus')
    var puntos = $('#puntostotales' + color).html()
    var puntosnuevos = (parseFloat(puntos) - 1 < 0) ? 0 : parseFloat(puntos) - 1
    $('#puntostotales' + color).html(puntosnuevos)
})

$(document).on('click', '[name="senshu"]', function() {
    var estado = $(this).prop('checked');
    $('[name="senshu"]').prop('checked', false);
    $(this).prop('checked', estado);
});

$(document).on('click', '[name="hantei"]', function() {
    var estado = $(this).prop('checked');
    $('[name="hantei"]').prop('checked', false);
    $(this).prop('checked', estado);
});

$(".check-female").click(function() {
    $("#female").prop("checked", true);
});

$(document).on('click', '#guardar-marcador_old', function() {
    var match_id = $('#marcadorauxiliar').find('[data-match-id]').attr('data-match-id');
    var ul = $('[data-match_id="' + match_id + '"]');
    var user_rojo = ul.find('[data-user]')[0];
    user_rojo = $(user_rojo).attr('data-user');
    var user_azul = ul.find('[data-user]')[1];
    user_azul = $(user_azul).attr('data-user');
    var puntos_rojo = $('#puntostotalesrojo').html()
    var puntos_azul = $('#puntostotalesazul').html()
    var senshu = $('[name="senshu"]:checked').val()
    var hantei = $('[name="hantei"]:checked').val()
    var winner = 0;
    if (puntos_rojo - puntos_azul < 0) {
        winner = user_azul
    }else if (puntos_rojo - puntos_azul > 0) {
        winner = user_rojo
    } else if (puntos_rojo - puntos_azul == 0) {
        if (senshu == 'rojo') {
            winner = user_rojo
        } else {
            if (senshu == 'azul') {
                winner = user_azul
            } else {
                if (hantei == 'azul') {
                    winner = user_azul
                } else if (hantei == 'rojo'){
                    winner = user_rojo
                } else {
                    /*swal.fire({
                        icon: 'error',
                        title: 'ERROR',
                        html: 'En caso de empate, es necesario indicar un ganador. Marcar ganador por HANTEI',
                        willClose: function() {
                          
                        }
                    });
                    return;*/
                }
            }
        }
    }
    

    var fd = new FormData();
    fd.append("match_id", match_id);
    fd.append("puntos_rojo", puntos_rojo);
    fd.append("puntos_azul", puntos_azul);
    fd.append("senshu", senshu);
    fd.append("hantei", hantei);
    fd.append("winner", winner);
    fd.append("estado", 'completado');
    fd.append("csrf_token", $('[name="csrf_token"]').val());

    $.ajax({
        url: base_url + 'Competiciones/updateMatch',
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
            // actualizar el combate
            var match = response.match
                /*var ul = $('[data-match_id="' + match.match_id + '"]');
                ul.find('[data-user="' + match.user_rojo + '"]').children(":last").html(match.puntos_rojo).removeClass('bg-white').addClass('text-white')
                ul.find('[data-user="' + match.user_azul + '"]').children(":last").html(match.puntos_azul).removeClass('bg-white').addClass('text-white')
                //ul.attr('style', 'pointer-events: none')*/
            swal.fire({
                icon: 'success',
                title: 'OK',
                html: errorhtml,
                willClose: function() {
                    $('#marcadorauxiliar').modal('hide');
                    updateClasificacionGrupo(match.competicion_torneo_id, match.grupo)
                    location.reload()
                    if (response.hasOwnProperty('redirect')) {
                        if (response.redirect == 'refresh') {
                            location.reload()
                        } else {
                            window.location.href = response.redirect
                        }
                    }
                }
            });
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

$(document).on('click', '[data-ver-clasificacion]', function() {
    var competicion_torneo_id = $(this).attr('data-competicion_torneo_id');
    var grupo = $(this).attr('data-grupo');
    $('#clasificacionModalTable tbody').attr('data-competicion_torneo_id', competicion_torneo_id);
    $('#clasificacionModalTable tbody').attr('data-grupo', grupo);
    updateClasificacionGrupo(competicion_torneo_id, grupo)
    $('#clasificaciongrupo').modal('show')
})

function updateClasificacionGrupo(competicion_torneo_id, grupo) {
    var fd = new FormData();
    fd.append("competicion_torneo_id", competicion_torneo_id);
    fd.append("grupo", grupo);
    fd.append("csrf_token", $('[name="csrf_token"]').val());
    $.ajax({
        url: base_url + 'Competiciones/clasificacionGrupo',
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
            var tbody = $('#clasificacionModalTable tbody[data-competicion_torneo_id="' + competicion_torneo_id + '"][data-grupo="' + grupo + '"]')
            tbody.slideUp();
            tbody.html('');
            $.each(response.users, function(i, user) {
                var posicion = i + 1;
                var deportista = user.first_name + ' ' + user.last_name;
                var club = user.nombre;

                var tr = '<tr>';
                tr += '<td>' + posicion + '</td>';
                tr += '<td>' + deportista + '</td>';
                tr += '<td>' + club + '</td>';
                tr += '<td>' + user.ganados + '</td>';
                tr += '<td>' + user.puntos + '</td>';
                tr += '<td>' + user.puntos_contra + '</td>';
                tr += '<td>' + user.senshu + '</td>';
                tr += '<td>' + user.hantei + '</td>';
                tr += '</th>';

                tbody.append(tr);
            })
            tbody.slideDown();

            var tbodyg = $('#tablakumite_' + grupo + ' tbody[data-competicion_torneo_id="' + competicion_torneo_id + '"][data-grupo="' + grupo + '"]')
            tbodyg.slideUp();
            tbodyg.html('');
            $.each(response.users, function(i, user) {
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

function updateClasificacionGrupoUsers(competicion_torneo_id, grupo) {
    var fd = new FormData();
    fd.append("competicion_torneo_id", competicion_torneo_id);
    fd.append("grupo", grupo);
    fd.append("csrf_token", $('[name="csrf_token"]').val());
    $.ajax({
        url: base_url + 'Competiciones/clasificacionGrupo',
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
            var tbody = $('tbody.user[data-competicion_torneo_id="' + competicion_torneo_id + '"][data-grupo="' + grupo + '"]')
            tbody.slideUp();
            tbody.html('');
            $.each(response.users, function(i, user) {
                var posicion = i + 1;
                var deportista = user.first_name + ' ' + user.last_name;
                var tr = '<tr data-user_ud="' + user.user_id + '">';
                tr += '<td>' + posicion + '</td>';
                tr += '<td>' + deportista + '</td>';
                tr += '</th>';
                tbody.append(tr);
            })
            tbody.slideDown();
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
    }).done(function(response) {
        var response = JSON.parse(response);
        $('[name="csrf_token"]').val(response.csrf)
        if (response.data.length > 0) {
            $(".brackets").html('')
            $(".brackets").slideDown(300)
            $(".brackets").gracket({ src: response.data });

        } else {
            $(".brackets").slideDown(300)
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

function guardarClasificaciones() {

}
$(document).on('click', '[data-guardar-clasificaicon]', function() {
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
                title: 'OK',
                html: response.msn,
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
    $('tbody.user[data-competicion_torneo_id]').each(function(index, elem) {
        var competicion_torneo_id = $(elem).attr('data-competicion_torneo_id');
        var grupo = $(elem).attr('data-grupo');
        updateClasificacionGrupoUsers(competicion_torneo_id, grupo)
    })
    if ($('#faseeliminatorias').length) {
        dibujar_cruces_grupos()
    }
})

$(document).on('click', '#exportar_grupos', function() {
    var competicion_torneo_id = $(this).attr('data-competicion_torneo_id');
    window.open(base_url + 'Competiciones/pdfdoc/' + competicion_torneo_id, '_blank');
})

$(document).on('click', '[data-finalizar-competicion]', function(event) {
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