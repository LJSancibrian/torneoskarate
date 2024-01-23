var tabla;
var titledoc = 'equipos';
tabla = $("#table_datatable").DataTable({
    processing: true,
    serverSide: true,
    order: [
        [3, 'desc']
    ],
    columns: [{ //0
        title: "CODIGO",
        name: "slug",
        data: "slug",
    }, { //1
        title: "Título",
        name: "titulo",
        data: "titulo",
    }, { //2 
        title: "Modalidades",
        name: "tipo",
        data: "tipo",
        render: function(data, type, row) {
            if (row.tipo == 1) {
                html = '<span class="badge badge-info">Kata</span>';
            } else if (row.tipo == 2) {
                html = '<span class="badge badge-warning">Kumite</span>';
            } else {
                html = '<span class="badge btn-secondary">Kata y Kumite</span>';
            }
            return html
        }
    }, { //3
        title: "Fecha",
        name: "fecha",
        data: "fecha",
    }, { //4
        title: "Límite",
        name: "limite",
        data: "limite",
    }, { //5
        title: "Estado",
        name: "estado",
        data: "estado",
        render: function(data, type, row) {
            if (row.estado == 1) {
                html = '<span class="badge badge-warning">Activo</span>';
            } else {
                html = '<span class="badge badge-danger">Inactivo</span>';
            }
            return html
        }
    }, { // 6
        name: "Acción",
        title: "Acción",
        data: "bonoID",
        className: "text-truncate",
        render: function(data, type, row) {
            html = '<div class="form-button-action">';
            html += '<button type="button" data-tooltip title="" class="btn btn-sm btn-outline-primary" data-original-title="Vista rápida del torneo" data-ver-torneo="' + row.torneo_id + '" data-slug="' + row.slug + '"><i class="fas fa-trophy"></i></button>';
            html += '<button type="button" data-tooltip title="" class="btn btn-sm btn-outline-primary" data-original-title="Editar datos del torneo" data-editar-torneo="' + row.torneo_id + '" data-slug="' + row.slug + '"><i class="fa fa-edit"></i></a>';
            html += '<button type="button" data-tooltip title="" class="btn btn-sm btn-outline-primary" data-original-title="Gestionar torneo" data-gestion-torneo="' + row.torneo_id + '" data-slug="' + row.slug + '"><i class="fas fa-cog"></i></button>';
            html += '</div>';
            return html
        }
    }, ],
    columnDefs: [{
        targets: [0, 1, 2, 3, 4, 5, 6],
        visible: true
    }, {
        targets: ["_all"],
        visible: false
    }, {
        targets: [6],
        orderable: false
    }],
    ajax: {
        url: base_url + 'Torneos/getTorneos',
        type: "GET",
        datatype: "json",
        data: function(data) {
            var estado = $("#table_datatable").data('default');
            if (estado != '') {
                data.estado = estado;
            }
            data.proximos = 1;
        }
    },
    createdRow: function(row, data, dataIndex) {
        if (dataIndex > 0) {
            $(row).find('[data-tooltip]').tooltip();
        } else {
            $(row).find('[data-tooltip]').tooltip({ boundary: 'window' });
        }
    },
    drawCallback: function(settings) {},
    initComplete: function() {}
});

$(document).on('click', '[data-ver-torneo]', function() {
    torneo_id = $(this).attr('data-ver-torneo')
    slug = $(this).attr('data-slug')
    var fd = new FormData();
    fd.append("torneo_id", torneo_id);
    fd.append("csrf_token", $('[name="csrf_token"]').val());

    $.ajax({
        url: base_url + 'Torneos/ver_torneo_fetch',
        method: "POST",
        contentType: false,
        processData: false,
        data: fd
    }).done(function(response) {
        if (response === '' || typeof response == 'undefined') {
            window.location.reload();
        }
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
            });
            return;
        } else {
            var html = '';
            var torneo = response.data.torneo;

            html += '<div class="card-list">';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Título</strong><span>' + torneo.titulo + '</span></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Código del torneo</strong><span>' + torneo.slug + '</span></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Descripción</strong><span>' + torneo.descripcion + '</span></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Dirección</strong><span>' + torneo.direccion + '</span></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Organizador</strong><span>' + torneo.organizador + '</span></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Email de contacto</strong><span>' + torneo.email + '</span></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Teléfono de contacto</strong><span>' + torneo.telefono + '</span></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Fecha</strong><span>' + torneo.fecha + '</span></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Inscripcion hasta</strong><span>' + torneo.limite + '</span></div>';
            var estado = (torneo.estado == 1) ? 'Activo' : 'Inactivo';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Estado del torneo</strong><span>' + estado + '</span></div>';
            html += '</div>';
            swal.fire({
                icon: 'info',
                title: 'Información del torneo',
                html: html,
                showCancelButton: true,
                confirmButtonText: 'Gestionar torneo',
                cancelButtonText: 'Cerrar',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = base_url + 'torneos/gestion/' + slug
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
});

$(document).on('click', '[data-editar-torneo]', function() {
    var torneo_id = $(this).attr('data-editar-torneo')
    if (torneo_id == 'nuevo') {
        $('#modal_crear_torneo form').trigger('reset');
        $('#modal_crear_torneo .fw-mediumbold').html('Crear nuevo torneo');
        $('[name="torneo_id"]').val('nuevo');
        $('#modal_crear_torneo').modal('show');

    } else {
        slug = $(this).attr('data-slug')
        var fd = new FormData();
        fd.append("torneo_id", torneo_id);
        fd.append("csrf_token", $('[name="csrf_token"]').val());

        $.ajax({
            url: base_url + 'Torneos/ver_torneo_fetch',
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
                });
                return;
            } else {
                var torneo = response.data.torneo;
                $('#modal_crear_torneo form').trigger('reset');
                $('[name="torneo_id"]').val(torneo_id);
                $("#titulo").val(torneo.titulo);
                $("#descripcion").val(torneo.descripcion);
                $("#direccion").val(torneo.direccion);
                $("#organizador").val(torneo.organizador);
                $("#tipo").val(torneo.tipo);
                $("#fecha").val(torneo.fecha);
                $("#limite").val(torneo.limite);
                $("#email").val(torneo.email);
                $("#telefono").val(torneo.telefono);
                $('#modal_crear_torneo .fw-mediumbold').html('Editar torneo ' + torneo.slug);
                $('#modal_crear_torneo').modal('show');
                if (torneo.estado == 1) {
                    $('#estado').attr('checked', 'checked');
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
    }
});

// enviar el formulairo
$('#submit-torneo-form').click(function() {
    var torneo_id = $('[name="torneo_id"]').val()
    if (torneo_id == 'nuevo') {
        var stitle = '¿Crear torneo con los datos indicados?';
        var sconfirm = 'Si, crear torneo';
        var action = base_url + 'Torneos/nuevo_torneo_form';
    } else {
        var stitle = '¿Guardar los datos del torneo?';
        var sconfirm = 'Si, guardar datos';
        var action = base_url + 'Torneos/editar_torneo_form';
    }
    swal.fire({
        icon: 'question',
        title: 'Confirmar acción',
        html: stitle,
        showCancelButton: true,
        confirmButtonText: sconfirm,
        cancelButtonText: 'Cerrar sin cambios',
    }).then((result) => {
        if (result.isConfirmed) {
            var fd = new FormData();
            fd.append("torneo_id", torneo_id);
            fd.append("titulo", $("#titulo").val());
            fd.append("descripcion", $("#descripcion").val());
            fd.append("direccion", $("#direccion").val());
            fd.append("organizador", $("#organizador").val());
            fd.append("tipo", $("#tipo").val());
            fd.append("fecha", $("#fecha").val());
            fd.append("limite", $("#limite").val());
            fd.append("email", $("#email").val());
            fd.append("telefono", $("#telefono").val());
            fd.append("csrf_token", $('[name="csrf_token"]').val());
            if ($('#estado').is(':checked')) {
                fd.append("estado", $("#estado").val());
            }
            $.ajax({
                url: action,
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
                            } else {
                                tabla.draw();
                                $('#modal_crear_torneo').modal('hide');
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
    });
});

$(document).on('click', '[data-gestion-torneo]', function() {
    torneo_id = $(this).attr('data-gestion-torneo')
    slug = $(this).attr('data-slug')

    swal.fire({
        icon: 'info',
        title: '¿Ir a la página de gestión del torneo?',
        showCancelButton: true,
        confirmButtonText: 'Gestionar torneo',
        cancelButtonText: 'Cerrar',
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = base_url + 'torneos/gestion/' + slug
        }
    })
});