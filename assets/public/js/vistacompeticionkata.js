
/* $('#tablavistakata').DataTable({
   scrollX: true,
   scrollCollapse: true,
   paging: false,
   fixedColumns: {
     left: 2
   },
   dom:
     "<'row'<'col-sm-12'tr>>"
 });*/

$(document).on('click', '[data-clasificacion]', function () {
    var competicion_torneo_id = $('#tablavistakata').attr('data-competicion');
    var fd = new FormData();
    fd.append("competicion_torneo_id", competicion_torneo_id);
    fd.append("csrf_token", $('[name="csrf_token"]').val());
    $.ajax({
        url: base_url + 'Competiciones/clasificacionkata',
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
                $('#clasificacion_competicion').html('');
                $.each(response.clasificacion, function (i, row) {
                    

                    var tr = `<tr>
                        <td>${i + 1}</td>
                        <td class="columnfixed">${row.first_name} ${row.last_name}</td>
                        <td>${row.nombre}</td>
                        <td>${row.total}</td>
                        <td>${row.media}</td>
                        </tr>`;

                    $('#clasificacion_competicion').append(tr)
                })
                setTimeout(function () {
                    
                    $('#clasificacionkata_modal').modal('show');
                }, 300)
        

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

function obtener_puntos_kata_competicion(){
    var competicion_torneo_id = $('#tablavistakata').attr('data-competicion');
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
                console.log(punto)
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
}

function clasificacionFinal() {
    var entatami = localStorage.getItem('enkata')
    if(entatami !== null) {
        $('#entatami').html(entatami)
        $('#entatami').slideDown('500')
    }else{
        $('#entatami').html('')
        $('#entatami').slideUp('500')
    }
    var competicion_torneo_id = $('[data-competicion]').attr('data-competicion');
    var fd = new FormData();
    fd.append("competicion_torneo_id", competicion_torneo_id);
    fd.append("csrf_token", $('[name="csrf_token"]').val());
    var urlfetch = base_url + 'Competiciones/clasificacionfinalkata';
    var bodytable = '#clasificacion_final_competicion'
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
                $(bodytable).html('');
                $.each(response.clasificacion, function (i, row) {
                    var pos = '';
                    if( i + 1 < 4){
                        switch (i + 1) {
                            case 1:
                                var classmedal = 'quiz-medal__circle quiz-medal__circle--gold'
                                break;
                            case 2:
                                var classmedal = 'quiz-medal__circle quiz-medal__circle--silver'
                                break;
                            case 3:
                                var classmedal = 'quiz-medal__circle quiz-medal__circle--bronze'
                                break;
                            default:
                                var classmedal = 'quiz-medal__circle '
                                break;
                        }
                        pos += `<div class="quiz-medal quiz-medal-sm">
                        <div class="${classmedal}">
                          <span>
                            ${i + 1}
                          </span>
                        </div>
                        <div class="quiz-medal__ribbon quiz-medal__ribbon--left"></div>
                        <div class="quiz-medal__ribbon quiz-medal__ribbon--right"></div>
                      </div>`;

                    }else{
                        pos = i + 1;
                    }

                    var tr = `<tr clasificado_user="${row.user_id}" clasificado_inscripcion="${row.inscripcion_id}">
                    <td>${pos}</td>
                    <td>${row.first_name} ${row.last_name}</td>
                    <td>${row.nombre}</td>
                    <td>${row.total}</td>
                    <td>${row.media}</td>
                    </tr>`;
                    $(bodytable).append(tr)
                
                });
                
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
    clasificacionFinal()
    obtener_puntos_kata_competicion();
    setInterval(function () { clasificacionFinal(); obtener_puntos_kata_competicion() }, 5000)
});