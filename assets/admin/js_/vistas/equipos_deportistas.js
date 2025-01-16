var tabla_deportistas;
tabla_deportistas = $("#table_datatable").DataTable({
    processing: true,
    serverSide: true,
    columns: [{ //0
        title: "Código",
        name: "usercode",
        data: "usercode",
    }, { //1
        title: "Nombre",
        name: "first_name",
        data: "first_name",
    }, { //2 
        title: "Apellidos",
        name: "last_name",
        data: "last_name"
    }, { //3
        title: "Año nacimiento",
        name: "yob",
        data: "yob",
    }, { //4
        title: "Género",
        name: "genero",
        data: "genero",
        render: function(data, type, row) {
            if (row.genero == 1) {
                html = '<span class="badge badge-success">Masculino</span>';
            } else {
                html = '<span class="badge badge-info">Femenino</span>';
            }
            return html
        }
    }, { //5
        title: "Estado",
        name: "active",
        data: "active",
        render: function(data, type, row) {
            if (row.active == 1) {
                html = '<span class="badge badge-success">Activo</span>';
            } else {
                html = '<span class="badge badge-danger">Inactivo</span>';
            }
            return html
        }
    }, { //3
        title: "Equipo",
        name: "nombre",
        data: "nombre",
    }, { // 6
        name: "Acción",
        title: "Acción",
        data: "bonoID",
        className: "text-truncate",
        render: function(data, type, row) {
            html = '<div class="form-button-action">';
            html += '<button type="button" data-tooltip title="" class="btn btn-sm btn-outline-primary" data-original-title="Ver deportista" data-ver-deportista="' + row.id + '" data-slug="' + row.usercode + '"><i class="fa fa-user"></i></a>';
            html += '<button type="button" data-tooltip title="" class="btn btn-sm btn-outline-primary" data-original-title="Editar deportista" data-editar-deportista="' + row.id + '" data-slug="' + row.usercode + '"><i class="fa fa-edit"></i></a>';
            html += '</div>';
            return html
        }
    }, ],
    columnDefs: [{
        targets: [0, 1, 2, 3, 4, 5, 6, 7],
        visible: true
    }, {
        targets: ["_all"],
        visible: false
    }, {
        targets: [6],
        orderable: false
    }],
    ajax: {
        url: base_url + 'Equipos/getDeportistas',
        type: "GET",
        datatype: "json",
        data: function(data) {
            var club_id = $("#table_datatable").data('club-id');
            if (club_id != '') {
                data.club_id = club_id;
            }
        }
    },
    buttons: [
        { name: 'excel', extend: 'excel', filename: 'deportistas_registrados', sheetName: 'Results', title: 'deportistas_registrados' },
        { name: 'csv', extend: 'csv', filename: 'deportistas_registrados', title: 'deportistas_registrados' }
    ],
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

$(document).on('click', '[data-ver-deportista]', function() {
    deportista_id = $(this).attr('data-ver-deportista')
    slug = $(this).attr('data-slug')
    var fd = new FormData();
    fd.append("deportista_id", deportista_id);
    fd.append("csrf_token", $('[name="csrf_token"]').val());

    $.ajax({
        url: base_url + 'Equipos/ver_deportista_fetch',
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
            var html = '';
            var club = response.data.club;
            var deportista = response.data.deportista;

            html += '<div class="card-list">';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Código usuario</strong><span>' + deportista.usercode + '</span></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Nombre</strong><span>' + deportista.first_name + '</span></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Apellidos</strong><span>' + deportista.last_name + '</span></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Fecha de nacimiento</strong><span>' + deportista.dob + '</span></div>';
            var genero = (deportista.genero == 1) ? 'Masculino' : 'Femenino';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Género</strong><span>' + genero + '</span></div>';
            if (deportista.peso > 0) {
                html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Peso</strong><span>' + deportista.peso + '</span></div>';
            }
            if (deportista.nivel != '') {
                html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Nivel de cinturón</strong><span>' + deportista.nivel + '</span></div>';
            }
            if (deportista.dni != '') {
                html += '<div class="item-list justify-content-between border-bottom p-2"><strong>DNI</strong><span>' + deportista.dni + '</span></div>';
            }
            if (deportista.email != '' && !deportista.email.includes(".generado")) {
                html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Email</strong><span>' + deportista.email + '</span></div>';
            }
            if (deportista.phone != '') {
                html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Teléfono</strong><span>' + deportista.phone + '</span></div>';
            }
            var estado = (deportista.active == 1) ? 'Activo' : 'Inactivo';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Estado</strong><span>' + estado + '</span></div>';
            html += '</div>';
            swal.fire({
                icon: 'info',
                title: 'Datos del deportista',
                html: html,
                showCancelButton: true,
                confirmButtonText: 'Ver ficha completa',
                cancelButtonText: 'Cerrar',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = base_url + 'deportistas/ver/' + slug
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

$(document).on('click', '[data-editar-deportista]', function() {
    deportista_id = $(this).attr('data-editar-deportista')
    slug = $(this).attr('data-slug')
    var fd = new FormData();
    fd.append("deportista_id", deportista_id);
    fd.append("csrf_token", $('[name="csrf_token"]').val());

    $.ajax({
        url: base_url + 'Equipos/ver_deportista_fetch',
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
            var html = '';
            var club = response.data.club;
            var deportista = response.data.deportista;
            $('[name="deportista_id"]').val(deportista.id);
            $('[name="first_name_d"]').val(deportista.first_name);
            $('[name="last_name_d"]').val(deportista.last_name);
            $('[name="dob"]').val(deportista.dob);
            $('[name="genero"]').val(deportista.genero);
            $('[name="nivel"] option[value="' + deportista.nivel + '"]').attr('selected', 'selected');
            $('[name="peso"]').val(deportista.peso);
            $('[name="email_d"]').val(deportista.email);
            $('[name="phone_d"]').val(deportista.phone);
            $('[name="dni_d"]').val(deportista.dni);
            if (deportista.active == 1) {
                $('[name="active_d"]').attr('checked', 'checked');
            } else {
                $('[name="active_d"]').removeAttr('checked');
            }
            if ($('select[name="club_id"]').length > 0) {
                $('select[name="club_id"] option').removeAttr('selected', 'selected');
                $('select[name="club_id"] option[value="' + deportista.club_id + '"]').attr('selected', 'selected');
            }
            $('[name="dni_d"]').val(deportista.dni);
            $('#carga_individual .modal-title .fw-mediumbold').html('Editar deportista')
            $('#carga_individual').modal('show');
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

$('#submit-deportista-form').click(function() {
    swal.fire({
        icon: 'question',
        title: 'Confirmar acción',
        html: '¿Guardar los datos del deportista?',
        showCancelButton: true,
        confirmButtonText: 'Si, guardar',
        cancelButtonText: 'Cerrar sin cambios',
    }).then((result) => {
        if (result.isConfirmed) {
            if ($('[name="deportista_id"]').val() == 'nuevo') {
                var action = base_url + 'Equipos/nuevo_deportista_form';
            } else {
                var action = base_url + 'Equipos/editar_deportista_form';
            }
            var fd = new FormData();
            var club_id = $('[name="clubs_id"]').length > 0 ? $('[name="clubs_id"]').val() : $('[name="club_id"]').val();
            fd.append("club_id", club_id);
            fd.append("user_id", $('[name="deportista_id"]').val());
            fd.append("first_name", $("#first_name_d").val());
            fd.append("last_name", $("#last_name_d").val());
            fd.append("dni", $("#dni_d").val());
            fd.append("email", $("#email_d").val());
            fd.append("phone", $("#phone_d").val());
            fd.append("genero", $("#genero").val());
            fd.append("peso", $("#peso").val());
            fd.append("nivel", $("#nivel").val());
            fd.append("dob", $("#dob").val());
            if ($('#active_d').is(':checked')) {
                fd.append("active", $("#active_d").val());
            }
            fd.append("csrf_token", $('[name="csrf_token"]').val());
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
                            tabla_deportistas.draw();
                            $('#carga_individual').modal('hide');
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
})

// boton añadir deportista: opciones de 
$('[data-carga-deportista]').click(function() {
    var tipo = $(this).attr('data-carga-deportista')
    if (tipo == 'individual') {
        $('#carga_individual form').trigger('reset');
        $('[name="deportista_id"]').val('nuevo');
        $('[name="active_d"]').attr('checked', 'checked');
        $('#carga_individual .modal-title .fw-mediumbold').html('Nuevo deportista')
    }
    if ($('select[name="club_id"]').length > 0) {
        $('select[name="club_id"] option').removeAttr('selected');
    }
    $('#carga_' + tipo).modal('show')
})

$('#submit_deportistas_file_form').click(function() {
    swal.fire({
        icon: 'question',
        title: 'Confirmar acción',
        html: '¿Cargar el archivo con los datos de los deportistas?',
        allowOutsideClick: false,
        showCancelButton: true,
        confirmButtonText: 'Si, cargar y guardar',
        cancelButtonText: 'Cerrar sin cambios',
    }).then((result) => {
        if (result.isConfirmed) {
            var sweet_loader = '<div class="sweet_loader" style="animation: fa-spin .5s infinite linear;"><svg viewBox="0 0 140 140" width="140" height="140"><g class="outline"><path d="m 70 28 a 1 1 0 0 0 0 84 a 1 1 0 0 0 0 -84" stroke="rgba(0,0,0,0.1)" stroke-width="5" fill="none" stroke-linecap="round" stroke-linejoin="round"></path></g><g class="circle"><path d="m 70 28 a 1 1 0 0 0 0 84 a 1 1 0 0 0 0 -84" stroke="#71BBFF" stroke-width="7" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-dashoffset="200" stroke-dasharray="300"></path></g></svg></div>';
            swal.fire({
                html: '<h4 class="h1">Procesando los datos</h4><p>Por favor, espere sin recargar la página.</p>',
                onRender: function() {
                    $('.swal2-content').prepend(sweet_loader);
                },
                allowOutsideClick: false,
                showCancelButton: false,
                showConfirmButton: false,
            });

            var file_data = $('#deportistasfile').prop('files')[0];
            var fd = new FormData();
            fd.append("club_id", $('[name="club_id"]').val());
            fd.append("archivo", file_data);
            fd.append("csrf_token", $('[name="csrf_token"]').val());
            $.ajax({
                url: base_url + 'Equipos/deportistas_file_form',
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
                            tabla_deportistas.draw();
                            $('#carga_archivo').modal('hide');
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
})