function getMatchesCompeticion() {
    var competicion_torneo_id = $('[competicion_torneo_id]').attr('competicion_torneo_id');
    var fd = new FormData();
    fd.append("competicion_torneo_id", competicion_torneo_id);
    fd.append("csrf_token", $('[name="csrf_token"]').val());
    $.ajax({
        url: base_url + 'Competiciones/getMatchesCompeticion',
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
            $.each(response.data, function(index, elem){
                var match  = $('[data-match_id="'+elem.match_id+'"]');
                var user_rojo = match.find('[data-user]')[0];
                var user_azul = match.find('[data-user]')[1];
                $(user_rojo).attr("data-user", elem.user_rojo)
                $(user_azul).attr("data-user", elem.user_azul)
                if (elem.hasOwnProperty('rojo')) {
                    $(user_rojo).children(":first").html(elem.rojo.nombre)
                    $(user_rojo).children(":last").html(elem.puntos_rojo)
                }
                if (elem.hasOwnProperty('azul')) {
                    $(user_azul).children(":first").html(elem.azul.nombre)
                    $(user_azul).children(":last").html(elem.puntos_azul)
                }

                if(elem.senshu == 'rojo'){
                    $(user_rojo).addClass('senshu')
                    $(user_azul).removeClass('senshu')
                }
                if(elem.senshu == 'azul'){
                    $(user_azul).addClass('senshu')
                    $(user_rojo).removeClass('senshu')
                }
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

function clasificacionCompeticion() {
    $('tbody.clasificacion_grupo[data-competicion_torneo_id]').each(function (index, elem) {
        var competicion_torneo_id = $(elem).attr('data-competicion_torneo_id');
        var grupo = $(elem).attr('data-grupo');
        updateClasificacionGrupoUsers(competicion_torneo_id, grupo)
    })
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
            var tbody = $('tbody.clasificacion_grupo[data-competicion_torneo_id="' + competicion_torneo_id + '"][data-grupo="' + grupo + '"]')
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
                tr += '<td>' + user.ganados + '</td>';
                tr += '<td>' + user.puntos + '</td>';
                tr += '<td>' + user.puntos_contra + '</td>';
                tr += '<td>' + user.senshu + '</td>';
                tr += '<td>' + user.hantei + '</td>';
                tr += '</th>';
                tbody.append(tr);
            })
            tbody.slideDown();
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
$(document).ready(function(){
    setInterval(function () { getMatchesCompeticion();clasificacionCompeticion();}, 3000)
});
