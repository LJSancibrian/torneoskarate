$(document).on('click', '[data-match_id]', function () {
    var match_id = $(this).data('match_id');
    var fd = new FormData();
    fd.append("match_id", match_id);
    fd.append("csrf_token", $('[name="csrf_token"]').val());
    $.ajax({
        url: base_url + 'Competiciones/getMatch',
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
            var combate = response.match
            if (combate.estado == 'pendiente' || combate.estado == 'en curso') {
                $('#user_rojo').html(combate.rojo.nombre)
                $('#user_azul').html(combate.azul.nombre)
                $('[data-acciones-rojo]').attr('data-user_id', combate.user_rojo)
                $('[data-acciones-azul]').attr('data-user_id', combate.user_azul)
                $('#marcadorauxiliar [data-match_id]').attr('data-match_id', combate.match_id)
                $('#marcadorauxiliar').modal('show');
            } else {
                swal.fire({
                    icon: 'error',
                    title: 'ERROR',
                    html: 'El combate ya ha finalizado',
                    willClose: function () {

                    }
                });
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
})

$(document).on('click', '[data-accion]', function () {
    var accion = $(this).attr('data-accion');
    var user_id = $(this).closest('[data-user_id]').attr('data-user_id');
    var match_id = $('#marcadorauxiliar [data-match_id]').attr('data-match_id');
    
    if ($('[data-user_id="' + user_id + '"]').attr('data-acciones-rojo') !== undefined) {
        var user_color = 'rojo';
        var bg_color = 'red';
    } else {
        var user_color = 'azul';
        var bg_color = 'blue';
    }
    
    updateLocalMatches(match_id, user_id, accion, user_color);
       
    /*var nombre_competido = $('#user_' + user_color).text();
    var fd = new FormData();
    fd.append("match_id", match_id);
    fd.append("user_id", user_id);
    fd.append("accion", accion);
    fd.append("user_color", 'user_' + user_color);
    fd.append("csrf_token", $('[name="csrf_token"]').val());
    $.ajax({
        url: base_url + 'Competiciones/addActionMatch',
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
            match = response.match;
            $('#puntostotalesrojo').html(match.puntos_rojo)
            $('#puntostotalesazul').html(match.puntos_azul)
            if (match.senshu == 'rojo') {
                $('#puntostotalesrojo').addClass('senshu')
            }
            if (match.senshu == 'azul') {
                $('#puntostotalesazul').addClass('senshu')
            }
        }
    }).always(function (jqXHR, textStatus) {
        var localmatches = JSON.parse(localStorage.getItem("localmatches") || "[]");
        var accionmatch = {
            match_id: match_id,
            user_id: user_id,
            accion: accion,
            user_color: user_color,
        };
        localmatches.push(accionmatch);
        localStorage.setItem("localmatches", JSON.stringify(localmatches));
        console.log(localStorage.getItem("localmatches"));

        accionmatch.match;
        var puntos_rojo = parseFloat($('#puntostotalesrojo').html());
        var puntos_azul = parseFloat($('#puntostotalesazul').html());
        switch (accionmatch.accion) {
            case 'rest':
                if (accionmatch.user_color == 'user_rojo') {
                    puntos_rojo = puntos_rojo - 1;
                } else {
                    puntos_azul = puntos_azul - 1;
                }
                break;
            case 'yuko':
                if (accionmatch.user_color == 'user_rojo') {
                    puntos_rojo = puntos_rojo + 1;
                } else {
                    puntos_azul = puntos_azul + 1;
                }
                break;

            case 'wazari':
                if (accionmatch.user_color == 'user_rojo') {
                    puntos_rojo = puntos_rojo + 2;
                } else {
                    puntos_azul = puntos_azul + 2;
                }
                break;

            case 'ippon':
                if (accionmatch.user_color == 'user_rojo') {
                    puntos_rojo = puntos_rojo + 3;
                } else {
                    puntos_azul = puntos_azul + 3;
                }
                break;

            case 'senshu':
                if (accionmatch.user_color == 'user_rojo') {
                    $('[name="senshu_rojo"]').attr('checked', 'checked')
                    $('[name="senshu_azul"]').removeAttr('checked')
                } else {
                    $('[name="senshu_azul"]').attr('checked', 'checked')
                    $('[name="senshu_rojo"]').removeAttr('checked')
                }
                break;

            case 'hantei':

                break;
            case 'chuko':
                break;
            default:

                break;
        }

        $('#puntostotalesrojo').html($('#puntostotalesrojo'))
        $('#puntostotalesazul').html(match.puntos_azul)
        if (match.senshu == 'rojo') {
            $('#puntostotalesrojo').addClass('senshu')
        }
        if (match.senshu == 'azul') {
            $('#puntostotalesazul').addClass('senshu')
        }
        return;
        /*if (textStatus != "success") {
            swal.fire({
                icon: 'error',
                title: 'Ha ocurrido un error AJAX',
                html: jqXHR.statusText,
                timer: 5000,
                willClose: function () { }
            })
        }
    });
    */
})

// MARCADOR
$(document).on('click', '#start-countdown', function () {
    if ($('#ammount').val() != '' && $('#ammount').val() > 0 && $('#tatami').val() != '' && $('#tatami').val() > 0 && $('#measure').val() != 0) {
        // cambiar estado a preparado
        var match_id = $('#marcadorauxiliar [data-match_id]').attr('data-match_id');
        var fd = new FormData();
        fd.append("match_id", match_id);
        fd.append("estado", 'preparado');
        fd.append("tatami", $('#tatami').val());
        fd.append("csrf_token", $('[name="csrf_token"]').val());
        $.ajax({
            url: base_url + 'Competiciones/updateEstadoMatch',
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
                startClock()
                pauseClock()
                $('[data-acciones-rojo], [data-acciones-azul]').animate({ opacity: 1, 'z-index': '1' })
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
    } else {
        swal.fire({
            icon: 'error',
            title: 'ERROR',
            html: 'Indica una unidad de medida de tiempo y la cantidad de la misma',
        });
    }
})

$(document).on('click', '#close_marcador', function () {
    var match_id = $('#marcadorauxiliar [data-match_id]').attr('data-match_id');
    swal.fire({
        icon: 'question',
        title: 'Realizar acción',
        html: 'Dar el combate como finalizado o descartar el marcador y borrar los datos del combate',
        showCancelButton: true,
        confirmButtonText: 'Finalizar el combate',
        cancelButtonText: 'Descartar el combate',
    }).then((result) => {
        var fd = new FormData();
        fd.append("match_id", match_id);
        fd.append("csrf_token", $('[name="csrf_token"]').val());

        if (result.isConfirmed) {
            fd.append("estado", 'finalizado');
            var urlAction = base_url + 'Competiciones/updateEstadoMatch';
            var title = 'Combate finalizado';
            var html = 'Los datos del combate han sido guardados';
        } else {
            fd.append("estado", 'pendiente');
            var urlAction = base_url + 'Competiciones/descartarMatch';
            var title = 'Combate descartado';
            var html = 'Los datos del combate han sido borrados';
            deleteLocalMatch(match_id)
        }

        $.ajax({
            url: urlAction,
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
                    icon: 'info',
                    title: title,
                    html: html,
                    confirmButtonText: 'Cerrar',
                    willClose: function () {
                        location.reload()
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
    });
})


const s = $('#timer').find('.seconds')
const m = $('#timer').find('.minutes')
const h = $('#timer').find('.hours')


$(document).ready(function () {
    //https://www.jqueryscript.net/demo/stopwatch-countdown-app/
    var seconds = 0
    var minutes = 0
    var hours = 0
    $('#stop-timer').on('click', function () {
        $('#stop-timer').hide()
        pauseClock()
    })
    $('#reset-timer').on('click', function () {
        swal.fire({
            icon: 'question',
            title: '¿Reiniciar el combate?',
            html: 'Se borrarán los datos del combate y empezará desde 0.',
            showCancelButton: true,
            confirmButtonText: 'Reiniciar el combate',
            cancelButtonText: 'Continuar',
        }).then((result) => {
            var match_id = $('#marcadorauxiliar [data-match_id]').attr('data-match_id');
            deleteLocalMatch(match_id)
            var fd = new FormData();
            fd.append("match_id", match_id);
            fd.append("csrf_token", $('[name="csrf_token"]').val());
            $.ajax({
                url: base_url + 'Competiciones/descartarMatch',
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

                    restartClock()
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
    })

    $('#resume-timer').on('click', function () {
        $('#resume-timer').hide()
        $('#stop-timer').show()
        countdown()
    })
    var hasStarted = false
    var hasEnded = false
    if (hours == 0 && minutes == 0 && seconds == 0 && hasStarted == true) {
        hasEnded = true
    }
})

function startClock() {
    hasStarted = false
    hasEnded = false
    seconds = 0
    minutes = 0
    hours = 0
    switch ($('#measure').val()) {
        case 's':
            if ($('#ammount').val() > 3599) {
                let hou = Math.floor($('#ammount').val() / 3600)
                hours = hou
                let min = Math.floor(($('#ammount').val() - (hou * 3600)) / 60)
                minutes = min;
                let sec = ($('#ammount').val() - (hou * 3600)) - (min * 60)
                seconds = sec
            }
            else if ($('#ammount').val() > 59) {
                let min = Math.floor($('#ammount').val() / 60)
                minutes = min
                let sec = $('#ammount').val() - (min * 60)
                seconds = sec
            }
            else {
                seconds = $('#ammount').val()
            }
            break
        case 'm':
            if ($('#ammount').val() > 59) {
                let hou = Math.floor($('#ammount').val() / 60)
                hours = hou
                let min = $('#ammount').val() - (hou * 60)
                minutes = min
            }
            else {
                minutes = $('#ammount').val()
            }
            break
        case 'h':
            hours = $('#ammount').val()
            break
        default:
            break
    }

    if (seconds <= 10 && minutes == 0 && hours == 0) {
        $('#timer').addClass('bg-danger')
    }
    refreshClock()
    $('.input-wrapper').slideUp(350)
    setTimeout(function () {
        $('#timer').fadeIn(350)
        $('#buttons-wrapper').fadeIn(350)
        $('#stop-timer').hide()
    }, 350)
    countdown()
}

function countdown() {
    hasStarted = true
    interval = setInterval(() => {
        if (hasEnded == false) {
            if (seconds <= 11 && minutes == 0 && hours == 0) {
                $('#timer').addClass('bg-danger')
            }
            if (seconds == 0 && minutes == 0 || (hours > 0 && minutes == 0 && seconds == 0)) {
                hours--
                minutes = 59
                seconds = 60
                refreshClock()
            }
            if (seconds > 0) {
                seconds--
                refreshClock()
            }
            else if (seconds == 0) {
                minutes--
                seconds = 59
                refreshClock()
            }
        }
        else {
            $('#stop-timer').fadeOut(100)
            $('#marcadorauxiliar .modal-footer').addClass('justify-content-between').removeClass('justify-content-center');
            $('#reset-timer').fadeIn(100)
            $('#close_marcador').fadeIn(100)
            $('[data-acciones-rojo], [data-acciones-azul]').animate({ opacity: 1, 'z-index': '1' })
            if ($('.senshu').length == 0) {
                $('[data-accion="hantei"]').fadeIn(100)
            }

            // restartClock()
        }
    }, 1000)
}

function refreshClock() {
    $(s).text(pad(seconds))
    $(m).text(pad(minutes))
    if (hours < 0) {
        $(s).text('00')
        $(m).text('00')
        $(h).text('00')
    } else {
        $(h).text(pad(hours))
    }
    if (hours == 0 && minutes == 0 && seconds == 0 && hasStarted == true) {
        hasEnded = true
        $(s).text('00')
        $(m).text('00')
        $(h).text('00')
        var audio = new Audio(base_url + 'assets/sounds/endmatch.mp3');
        audio.play();
        swal.fire({
            icon: 'warning',
            title: 'Tiempo finalizado',
            confirmButtonText: 'Continuar',
        })
    }
}

function clear(intervalID) {
    clearInterval(intervalID)
    console.log('cleared the interval called ' + intervalID)
}

function pad(d) {
    return (d < 10) ? '0' + d.toString() : d.toString()
}

function restartClock() {
    if (typeof interval === 'undefined') {
        interval = 0;
    }
    clear(interval)
    hasStarted = false
    hasEnded = false
    seconds = 0
    minutes = 0
    hours = 0
    $(s).text('30')
    $(m).text('01')
    $(h).text('00')
    $('#timer').removeClass('bg-danger')
    $('#stop-timer').fadeOut(100)
    $('#resume-timer').fadeIn(100)
    $('#puntostotalesrojo').html('0')
    $('#puntostotalesazul').html('0')
    $('[type="checkbox"]').prop( "checked", false );
    setTimeout(function () {
        
    }, 350)
}

function pauseClock() {
    if (typeof interval === 'undefined') {
        interval = 0;
    }
    clear(interval)
    $('#resume-timer').show()
    $('#reset-timer').fadeIn(100)
    $('#close_marcador').fadeIn(100)
    $('#marcadorauxiliar .modal-footer').addClass('justify-content-between').removeClass('justify-content-center');
    $('[data-acciones-rojo], [data-acciones-azul]').animate({ opacity: 1, 'z-index': '1' })
}

function updateLocalMatches(match_id, user_id, accion, user_color)
{
    var localmatches = JSON.parse(localStorage.getItem("localmatches") || "[]");
    var accionmatch = {
        match_id: match_id,
        user_id: user_id,
        accion: accion,
        user_color: user_color,
    };
    localmatches.push(accionmatch);
    localStorage.setItem("localmatches", JSON.stringify(localmatches));
    console.log(localStorage.getItem("localmatches"));
    var puntos_rojo = parseFloat($('#puntostotalesrojo').html());
    var puntos_azul = parseFloat($('#puntostotalesazul').html());
    switch (accionmatch.accion) {
        case 'rest':
            if (accionmatch.user_color == 'rojo') {
                puntos_rojo = puntos_rojo - 1;
            } else {
                puntos_azul = puntos_azul - 1;
            }
            break;
        case 'yuko':
            if (accionmatch.user_color == 'rojo') {
                puntos_rojo = puntos_rojo + 1;
            } else {
                puntos_azul = puntos_azul + 1;
            }
            break;

        case 'wazari':
            if (accionmatch.user_color == 'rojo') {
                puntos_rojo = puntos_rojo + 2;
            } else {
                puntos_azul = puntos_azul + 2;
            }
            break;

        case 'ippon':
            if (accionmatch.user_color == 'rojo') {
                puntos_rojo = puntos_rojo + 3;
            } else {
                puntos_azul = puntos_azul + 3;
            }
            break;

        case 'senshu':
            if (accionmatch.user_color == 'rojo') {
                $('[name="senshu_rojo"]').attr('checked', 'checked')
                $('[name="senshu_azul"]').removeAttr('checked')
            } else {
                $('[name="senshu_azul"]').attr('checked', 'checked')
                $('[name="senshu_rojo"]').removeAttr('checked')
            }
            break;

        case 'hantei':

            break;
        case 'chuko':
            break;
        default:

            break;
    }
    $('#puntostotalesrojo').html(puntos_rojo)
    $('#puntostotalesazul').html(puntos_azul)
    return;
}

function deleteLocalMatch(match_id)
{
    var localmatches = JSON.parse(localStorage.getItem("localmatches") || "[]");
    var newlocalmatches = [];
    localmatches.forEach(function(acction, index) {
        if(acction.match_id != match_id){
            newlocalmatches.push(acction);
        }
    });
    localStorage.setItem("localmatches", JSON.stringify(newlocalmatches));
}