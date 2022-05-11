// PESTAÑA CATEGORIAS
$('[data-add-modalidad]').click(function () {
    var btn = $(this);
    var tr = btn.closest('tr');
    var table = btn.closest('table');
    var t_categoria_id = tr.find('select option:selected').val()
    var categoria_text = tr.find('select option:selected').text()
    var pesokg = tr.find('input').val();
    if (pesokg < 25) {
        swal.fire({
            icon: 'error',
            title: 'ERROR',
            html: 'El peso mímino es de 25',
        });
        return;
    }
    var peso = '< ' + tr.find('input').val();
    var newtr = '<tr data-id="nuevo" data-categoria_id="' + t_categoria_id + '" data-peso="' + pesokg + '"><td>' + categoria_text + '</td><td>' + peso + '</td><td><button type="button" class="btn btn-outlime-danger btn-outline-danger btn-sm rounded-0" data-del-modalidad><i class="fa fa-minus-circle"></i></button></td></tr>'
    table.find('tbody').append(newtr);
})

$(document).on('click', '[data-del-modalidad]', function () {
    var btn = $(this);
    var tr = btn.closest('tr');
    var table = btn.closest('table');
    var competicion_torneo_id = tr.attr('data-id');
    if (competicion_torneo_id == 'nuevo') {
        tr.addClass('collapse');
        tr.remove()
    } else {
        // se envia el ajax
        // si no hay error, se elimina
        swal.fire({
            icon: 'question',
            title: 'Confirmar acción',
            html: '¿Elimiar la categoría? Esta acción cambia los datos almacenados en el sistema',
            showCancelButton: true,
            confirmButtonText: 'Si, eliminar',
            cancelButtonText: 'Cerrar sin cambios',
        }).then((result) => {
            if (result.isConfirmed) {
                var fd = new FormData();
                fd.append("competicion_torneo_id", competicion_torneo_id);
                fd.append("csrf_token", $('[name="csrf_token"]').val());
                $.ajax({
                    url: base_url + 'Torneos/del_competiciones',
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
                        });
                        return;
                    } else {
                        tr.addClass('collapse');
                        tr.remove()
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
        })
    }
})

$('[data-guardar-modalidad]').click(function () {
    var btn = $(this);
    var tabla = btn.prev();
    var modalidad = tabla.data('modalidad')
    var genero = tabla.data('genero')
    if (genero == 'M') {
        var genero_n = 1;
        var generotext = 'masculino'
    } else {
        var genero_n = 2;
        var generotext = 'femenino'
    }

    swal.fire({
        icon: 'question',
        title: 'Confirmar acción',
        html: '¿Guardar las categorias actuales para la competición de ' + modalidad + ' ' + generotext + '?',
        showCancelButton: true,
        confirmButtonText: 'Si, guardar',
        cancelButtonText: 'Cerrar sin cambios',
    }).then((result) => {
        if (result.isConfirmed) {
            var fd = new FormData();
            var torneoID = $('[name="torneo_id"]').val();
            fd.append("torneo_id", torneoID);
            fd.append("csrf_token", $('[name="csrf_token"]').val());
            var item = [];
            var competiciones = [];
            $(tabla).find('tbody tr[data-id="nuevo"]').each(function (index, element) {
                tr_item = $(tabla).find('tbody tr').eq(index)
                item = {
                    torneo_id: torneoID,
                    modalidad: modalidad,
                    genero: genero_n,
                    t_categoria_id: tr_item.data('categoria_id'),
                    peso: tr_item.data('peso')
                }
                competiciones.push(item)
            })
            fd.append("competiciones", JSON.stringify(competiciones));
            $.ajax({
                url: base_url + 'Torneos/add_competiciones',
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
                    });
                    return;
                } else {
                    swal.fire({
                        icon: 'success',
                        title: 'Correcto',
                        html: response.msn,
                        willClose: function () {
                            if (response.hasOwnProperty('redirect')) {
                                window.location.href = response.redirect
                            } else {
                                getCompeticiones(modalidad, genero)
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
        }
    })

})

function getCompeticiones(modalidad, genero) {
    var tbody = $('table[data-modalidad="' + modalidad + '"][data-genero="' + genero + '"] tbody')
    var fd = new FormData();
    var torneoID = $('[name="torneo_id"]').val();
    fd.append("torneo_id", torneoID);
    fd.append("modalidad", modalidad);
    fd.append("genero", genero);
    fd.append("csrf_token", $('[name="csrf_token"]').val());

    $.ajax({
        url: base_url + 'Torneos/get_competiciones_fetch',
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
            });
            return;
        } else {
            tbody.html('')
            $.each(response.competiciones, function (i, elem) {
                var newtr = '<tr data-id="' + elem.competicion_torneo_id + '" data-categoria_id="' + elem.t_categoria_id + '" data-peso="' + elem.pesokg + '"><td>' + elem.categoria_text + '</td><td>' + elem.peso + '</td><td><button type="button" class="btn btn-outlime-danger btn-outline-danger btn-sm rounded-0" data-del-modalidad><i class="fa fa-minus-circle"></i></button></td></tr>';
                tbody.append(newtr);
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
$(document).ready(function () {
    /*$('[data-modalidad]').each(function () {
        var mod = $(this).data('modalidad');
        var ges = $(this).data('genero');
        getCompeticiones(mod, ges)
    })*/
})


/************************************** */

$(document).on('click', '[data-add-row]', function () {
    var boton = $(this);
    var tr = boton.closest('tr');
    var modalidad = boton.attr('data-add-row');
    var mod = modalidad.toLowerCase();
    var categoria = $('[name="categoria_'+mod+'"]').val();
    var genero = $('[name="genero_'+mod+'"]').val();
    var nivel = $('[name="nivel_'+mod+'"]').val();
    
    var fd = new FormData();
    var torneoID = $('[name="torneo_id"]').val();
    fd.append("torneo_id", torneoID);
    fd.append("csrf_token", $('[name="csrf_token"]').val());
    fd.append("categoria", categoria);
    fd.append("genero", genero);
    fd.append("nivel", nivel);
    fd.append("modalidad", modalidad);
    $.ajax({
        url: base_url + 'Torneos/add_categoria',
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
            });
            return;
        } else {
            var newtr = response.html
            tr.before(newtr)
            $('[name="categoria_'+mod+'"]').val('');
            $('[name="genero_'+mod+'"] option').removeAttr('selected');
            $('[name="nivel_'+mod+'"]').val('');
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


$(document).on('click', '[data-edit]', function () {
    var boton = $(this)
    var tr = boton.closest('tr');
    var accion = boton.attr('data-accion')
    var modalidad = tr.attr('data-modalidad');
    var mod = modalidad.toLowerCase();

    if (accion == 'editar') {
        tr.find('[disabled]').removeAttr('disabled');
        boton.attr('data-accion', 'guardar')
        boton.html('<i class="fas fa-save">')
    } else {
        // enviar el form
        var competicion_torneo_id = tr.attr('data-competicion_torneo_id')
        var categoria = tr.find('[name="categoria_'+mod+'"]').val();
        var genero = tr.find('[name="genero_'+mod+'"]').val();
        var nivel = tr.find('[name="nivel_'+mod+'"]').val();
        var modalidad = tr.attr('data-modalidad');
        var fd = new FormData();
        var torneoID = $('[name="torneo_id"]').val();
        fd.append("competicion_torneo_id", competicion_torneo_id);
        fd.append("torneo_id", torneoID);
        fd.append("csrf_token", $('[name="csrf_token"]').val());
        fd.append("categoria", categoria);
        fd.append("genero", genero);
        fd.append("nivel", nivel);
        fd.append("modalidad", modalidad);
        $.ajax({
            url: base_url + 'Torneos/edit_categoria',
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
                });
                return;
            } else {
                tr.find('.form-control').attr('disabled', 'disabled');
                boton.attr('data-accion', 'editar')
                boton.html('<i class="fas fa-edit">')
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
})

$(document).on('click', '[data-del]', function () {

    var boton = $(this)
    var tr = boton.closest('tr');
    var competicion_torneo_id = tr.attr('data-competicion_torneo_id')
    var categoria = tr.find('[name="categoria_kata"]').val();
    var genero = tr.find('[name="genero_kata"] option[selected]').html();
    var nivel = tr.find('[name="nivel_kata"]').val();
    var modalidad = tr.attr('data-modalidad');
    swal.fire({
        icon: 'question',
        title: 'Confirmar acción',
        html: 'Elimiar las competición ' + categoria + ' ' + genero + ' ' + nivel + ' de ' + modalidad +'?',
        showCancelButton: true,
        confirmButtonText: 'Si, eliminar',
        cancelButtonText: 'Cerrar sin cambios',
    }).then((result) => {
        if (result.isConfirmed) {
            var fd = new FormData();
            var torneoID = $('[name="torneo_id"]').val();
            fd.append("competicion_torneo_id", competicion_torneo_id);
            fd.append("torneo_id", torneoID);
            fd.append("csrf_token", $('[name="csrf_token"]').val());
            $.ajax({
                url: base_url + 'Torneos/delete_categoria',
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
                    });
                    return;
                } else {
                    tr.remove();
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
    })
})