
$(document).on('click', '[data-inscripcion]', function () {
    $('[contenteditable]').removeAttr('contenteditable');
    if ($(this).hasClass('btn-border')) {
        $(this).removeClass('btn-border')
        localStorage.removeItem('enkata')
    } else {
        $('[data-inscripcion].btn-border').removeClass('btn-border')
        var tr = $(this).closest('tr');
        $(tr).find('td[data-ronda]').prop('contenteditable', true);
        $(this).addClass('btn-border')
        localStorage.setItem('enkata', $(this).html())
    }
})

$(document).on('click', '[data-ver-clasificacion]', function(){
    cargar_puntos();
    $('#clasificaciongrupo').modal('show')
})
$(document).on('focusout', '[data-ronda]', function () {
    var table_ID = $(this).closest('table').attr('id');
    if ($(this).text() == '') {
        return;
    }
    var puntos = parseFloat($(this).text());
    var ronda = $(this).attr('data-ronda');
    var juez = $(this).attr('data-j');
    var tr = $(this).closest('tr');
    var user_id = $(tr).attr('data-user_id');
    var competicion_torneo_id = $('[data-competicion]').attr('data-competicion');
    var fd = new FormData();
    fd.append("competicion_torneo_id", competicion_torneo_id);
    fd.append("user_id", user_id);
    fd.append("ronda", ronda);
    fd.append("juez", juez);
    fd.append("puntos", puntos);
    fd.append("csrf_token", $('[name="csrf_token"]').val());
    $.ajax({
        url: base_url + 'Competiciones/guardar_puntos_kata',
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
            /*var totalronda = 0;
            var jueces = 0;
            // para la ronda
            $.each($(tr).find('[data-ronda="' + ronda + '"]'), function (i, elem) {
                if ($(this).text() != '') {
                    jueces++;
                    totalronda = totalronda + parseFloat($(this).text())
                }
            })
            var media = (totalronda / jueces).toFixed(2)
            $(tr).find('[data-media="' + ronda + '"]').html(media)
            // para el total
            var totalronda = 0;
            var jueces = 0;
            $.each($(tr).find('[data-ronda]'), function (i, elem) {
                if ($(this).text() != '') {
                    jueces++;
                    totalronda = totalronda + parseFloat($(this).text())
                }
            })
            var media = (totalronda / jueces).toFixed(2)
            $(tr).find('[data-total]').html(totalronda)
            $(tr).find('[data-media-total]').html(media)
            clasificacion(table_ID);*/
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

$(document).on('click', '#guardar-finalistas', function () {
    var num_finalistas = $('#num_final').val();
    var competicion_torneo_id = $('[data-competicion]').attr('data-competicion');
    var users_id = [];
    console.log(num_finalistas)
    $('#clasificacion_competicion tr').each(function (pos, depor) {
        console.log(pos)
        if (pos < num_finalistas) {
            users_id.push($(depor).attr('clasificado_user'))
        }

    })
    console.log(users_id)
    var fd = new FormData();
    fd.append("competicion_torneo_id", competicion_torneo_id);
    fd.append("csrf_token", $('[name="csrf_token"]').val());
    fd.append("users_id", users_id);
    $.ajax({
        url: base_url + 'Competiciones/clasificadoskata',
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
            swal.fire({
                icon: 'success',
                title: 'Correcto',
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
})

$(document).on('click', '[ver-ronda]', function () {
    var competicion_torneo_id = $('[data-competicion]').attr('data-competicion');
    var ronda = $(this).attr('ver-ronda');
    var url = base_url + 'verronda/' + competicion_torneo_id + '/' + ronda;
    var popup = window.open(url, "popup", "fullscreen");
    if (popup.outerWidth < screen.availWidth || popup.outerHeight < screen.availHeight) {
        popup.moveTo(0, 0);
        popup.resizeTo(screen.availWidth, screen.availHeight);
    }
})



function clasificacion(table_ID) {
    var competicion_torneo_id = $('[data-competicion]').attr('data-competicion');
    var fd = new FormData();
    fd.append("competicion_torneo_id", competicion_torneo_id);
    fd.append("csrf_token", $('[name="csrf_token"]').val());
    var urlfetch = (table_ID == 'tablakata') ? base_url + 'Competiciones/clasificacionkata' : base_url + 'Competiciones/clasificacionfinalkata';
    var bodytable = (table_ID == 'tablakata') ? '#clasificacion_competicion' : '#clasificacion_final_competicion'
    $.ajax({
        url: urlfetch,
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
            $(bodytable).slideUp('500', function () {
                $(bodytable).html('');
                $.each(response.clasificacion, function (i, row) {
                    var tr = `<tr clasificado_user="${row.user_id}" clasificado_inscripcion="${row.inscripcion_id}">
                    <td>${i + 1}</td>
                    <td class="columnfixed">${row.first_name} ${row.last_name}</td>
                    <td>${row.nombre}</td>
                    <td>${row.total}</td>
                    <td>${row.media}</td>
                    <td>${row.puntos_max}</td>
                    <td>${row.puntos_max2}</td>
                    <td>${row.puntos_max3}</td>
                    </tr>`;

                    $(bodytable).append(tr)
                })
                setTimeout(function () {
                    $(bodytable).slideDown();
                }, 300)
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

function openFullscreen() {
    var elem = document.getElementById('clasificaicon');
    if (elem.requestFullscreen) {
        elem.requestFullscreen();
    } else if (elem.webkitRequestFullscreen) { /* Safari */
        elem.webkitRequestFullscreen();
    } else if (elem.msRequestFullscreen) { /* IE11 */
        elem.msRequestFullscreen();
    }
}

/* Close fullscreen */
function closeFullscreen() {
    var elem = document.documentElement;
    if (document.exitFullscreen) {
        document.exitFullscreen();
    } else if (document.webkitExitFullscreen) { /* Safari */
        document.webkitExitFullscreen();
    } else if (document.msExitFullscreen) { /* IE11 */
        document.msExitFullscreen();
    }
}


function cargar_puntos(){
    var competicion_torneo_id = $('[data-competicion]').attr('data-competicion');
    var fd = new FormData();
    fd.append("competicion_torneo_id", competicion_torneo_id);
    fd.append("csrf_token", $('[name="csrf_token"]').val());
    $.ajax({
        url: base_url + 'Competiciones/obtener_puntos_kata_competicion',
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
            var tr = 0;
            var user_id = 0;
            var ronda = 0;
            var juez = 0;
            $.each(response.puntos, function (i, punto) {
                ronda = punto.ronda;
                juez = punto.juez;
                user_id = punto.user_id;
                $('[data-user_id="' + user_id + '"]').find('[data-ronda="' + ronda + '"][data-j="' + juez + '"]').html(punto.puntos)
            })
            // recorrer las medias
            $.each($('td[data-media]'), function (i, casilla) {
                ronda = $(casilla).attr('data-media');
                tr = $(casilla).closest('tr')
                var totalronda = 0;
                var jueces = 0;
                $.each($(tr).find('[data-ronda="' + ronda + '"]'), function (i, elem) {
                    if ($(this).text() != '') {
                        jueces++;
                        totalronda = totalronda + parseFloat($(this).text())
                    }
                })
                if (totalronda > 0) {
                    var media = (totalronda / jueces).toFixed(2)
                    $(tr).find('[data-media="' + ronda + '"]').html(media)
                }

            })
            // recorrer las totales
            $.each($('td[data-total]'), function (i, casilla) {
                tr = $(casilla).closest('tr')
                var totalronda = 0;
                var jueces = 0;
                $.each($(tr).find('[data-ronda]'), function (i, elem) {
                    if ($(this).text() != '') {
                        jueces++;
                        totalronda = totalronda + parseFloat($(this).text())
                    }
                })
                if (totalronda > 0) {
                    var media = (totalronda / jueces).toFixed(2)
                    $(tr).find('[data-total]').html(totalronda)
                    $(tr).find('[data-media-total]').html(media)
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

    clasificacion('tablakata');
    clasificacion('finaltablakata');
}
$(document).ready(function () {
    cargar_puntos();
})

$(document).on('change', '[name="all"]', function(){
    
})