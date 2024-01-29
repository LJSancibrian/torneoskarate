
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
    //var popup = window.open(url, "popup", "fullscreen");
    abrirpantalla(url)
    //window.open("<?php echo base_url();?>dietario/cambio_efectivo_tarjeta/"+id_dietario+"/"+"/"+id_centro, "_blank", "toolbar=no,scrollbars=no,resizable=no,top="+posicion_y+",left="+posicion_x+",width="+ancho+",height="+alto);        

    // Verificar si la segunda pantalla está disponible
    /*if (ventana && ventana.screen && ventana.screen.availWidth) {
        // Obtener las dimensiones de la segunda pantalla
        var screenWidth = ventana.screen.availWidth;
        var screenHeight = ventana.screen.availHeight;

        // Mover la ventana a la segunda pantalla
        ventana.moveTo(screenWidth, 0);
        ventana.resizeTo(screenWidth, screenHeight);
    }*/
    /*}else{

        if (popup.outerWidth < screen.availWidth || popup.outerHeight < screen.availHeight) {
            popup.moveTo(0, 0);
            popup.resizeTo(screen.availWidth, screen.availHeight);
        }*/
    /*}*/
})

let fullscreenWindow;

function openFullscreenWindow_() {
  // Verificar si la ventana ya está abierta
  if (fullscreenWindow && !fullscreenWindow.closed) {
    // Si la ventana está abierta, cerrarla
    fullscreenWindow.close();
  } else {
    var posicion_x;
    var posicion_y;
    var ancho=screen.width + 50;
    var alto=screen.height + 50;
    posicion_x=20;
    posicion_y=20;

    if (window.screen && window.screen.width > window.innerWidth) {
        const secondScreenWidth = window.screen.width;
        const secondScreenHeight = window.screen.height;
        window.moveTo(secondScreenWidth, 0);
        const offsetX = secondScreenWidth - window.innerWidth;
        const offsetY = 0;
        console.log(`Coordenadas del pixel (0,0) en la segunda pantalla: (${offsetX},${offsetY})`);
        console.log(`Dimensiones de la segunda pantalla: ${secondScreenWidth}x${secondScreenHeight}`);
        posicion_x = offsetX
    } else {
        console.log("No se detectó segunda pantalla");
    }


    var competicion_torneo_id = $('[data-competicion]').attr('data-competicion');
    var ronda = $(this).attr('ver-ronda');
    var url = base_url + 'verronda/' + competicion_torneo_id + '/' + ronda;

    const windowFeatures = 'toolbar=no,scrollbars=no,resizable=no,top='+posicion_y+',left='+posicion_x+',width='+ancho+',height='+alto;

    fullscreenWindow = window.open(url, "_blank", windowFeatures);
    var elemento = document.getElementById('miElemento');

    // Verificar si el navegador admite el modo de pantalla completa
    if (fullscreenWindow.requestFullscreen) {
        fullscreenWindow.requestFullscreen();
    } else if (fullscreenWindow.mozRequestFullScreen) { // Para navegadores Firefox
        fullscreenWindow.mozRequestFullScreen();
    } else if (fullscreenWindow.webkitRequestFullscreen) { // Para navegadores Chrome, Safari y Opera
        fullscreenWindow.webkitRequestFullscreen();
    } else if (fullscreenWindow.msRequestFullscreen) { // Para navegadores Microsoft Edge e Internet Explorer
        fullscreenWindow.msRequestFullscreen();
    }

  }

  if (window.screen && window.screen.width > window.innerWidth) {
    // Hay una segunda pantalla conectada
    console.log("Segunda pantalla detectada");
  } else {
    // Solo hay una pantalla o no se puede detectar
    console.log("No se detectó segunda pantalla");
  }

}

function abrirpantalla(url) {
    //04/03/20
    var posicion_x;
    var posicion_y;
    var ancho=600;
    var alto=300;
    posicion_x=(screen.width/2)-(ancho/2);
    posicion_y=(screen.height/2)-(alto/2);
    var ventana = window.open(url, "_blank", "toolbar=no,scrollbars=no,resizable=no,top="+posicion_y+",left="+posicion_x+",width="+ancho+",height="+alto);  
    
    if (ventana && ventana.screen && ventana.screen.availWidth) {
        // Obtener las dimensiones de la segunda pantalla
        var screenWidth = ventana.screen.availWidth;
        var screenHeight = ventana.screen.availHeight;

        // Mover la ventana a la segunda pantalla
        ventana.moveTo(screenWidth, 0);
        ventana.resizeTo(screenWidth, screenHeight);
    }
}



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

                    var tr2 = `<tr clasificado_user="${row.user_id}" clasificado_inscripcion="${row.inscripcion_id}">
                    <td>${i + 1}</td>
                    <td class="columnfixed">${row.first_name} ${row.last_name}</td>
                    <td>${row.nombre}</td>
                    <td>${row.total}</td>
                    <td>${row.media}</td>
                    </tr>`;

                    var trrow = (table_ID == 'tablakata') ? tr : tr2;


                    $(bodytable).append(trrow)
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



function openFullscreenWindow() {
        // Verificar si el navegador soporta la propiedad screen
        if (window.screen) {
            // Obtener el número total de pantallas
            const numeroPantallas = window.screen.availWidth / window.screen.width;
            console.log(numeroPantallas)
            // Crear un array para almacenar las dimensiones de cada pantalla
            const dimensionesPantallas = [];
    
            // Iterar a través de cada pantalla
            for (let i = 0; i < numeroPantallas; i++) {
                const pantalla = {
                    width: window.screen.width,
                    height: window.screen.height
                };
                dimensionesPantallas.push(pantalla);
            }
            console.log(dimensionesPantallas)
            return dimensionesPantallas;
        } else {
            // Si el navegador no soporta la propiedad screen
            return null;
        }
    
}
