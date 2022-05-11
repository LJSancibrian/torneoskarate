var tabla_entrenadores;
tabla_entrenadores = $("#table_datatable").DataTable({
        processing: true,
        serverSide: true,
        columns: [{ //0
            title: "Código",
            name: "usercode",
            data: "usercode",
            render: function (data, type, row) {
                if (row.responsable == 1) {
                    html = '<span class="badge badge-default text-uppercase" data-tooltip data-original-title="Usuario responsable del equipo">'+row.usercode+'</span>';
                } else {
                    html = row.usercode;
                }
                return html
            }
        }, { //1
            title: "Nombre",
            name: "first_name",
            data: "first_name",
        }, { //2 
            title: "Apellidos",
            name: "last_name",
            data: "last_name"
        }, { //3
            title: "Email",
            name: "email",
            data: "email",
        }, { //4
            title: "Teléfono",
            name: "phone",
            data: "phone"
        }, { //5
            title: "Estado",
            name: "active",
            data: "active",
            render: function (data, type, row) {
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
            render: function (data, type, row) {
                if (row.responsable == 1) {
                    html = '<span class="badge badge-default text-uppercase" data-tooltip data-original-title="Usuario responsable del equipo">'+row.nombre+'</span>';
                } else {
                    html = row.nombre;
                }
                return html
            }
        }, { // 6
            name: "Acción",
            title: "Acción",
            data: "bonoID",
            className: "text-truncate",
            render: function (data, type, row) {
                html = '<div class="form-button-action">';
                html += '<button type="button" data-tooltip title="" class="btn btn-sm btn-outline-primary" data-original-title="Ver entrenador" data-ver-coach="' + row.id + '" data-slug="' + row.usercode + '"><i class="fa fa-user"></i></a>';
                html += '<button type="button" data-tooltip title="" class="btn btn-sm btn-outline-primary" data-original-title="Editar entrenador" data-editar-coach="' + row.id + '" data-slug="' + row.usercode + '"><i class="fa fa-edit"></i></a>';
                html += '</div>';
                return html
            }
        },],
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
            url: base_url + 'Equipos/getEntrenadores',
            type: "GET",
            datatype: "json",
            data: function (data) {
                var club_id = $("#table_datatable").data('club-id');
                if (club_id != '') {
                    data.club_id = club_id;
                }
            }
        },
        buttons: [
            {name: 'excel', extend: 'excel', filename: 'entrenadores_registrados', sheetName: 'Results', title: 'entrenadores_registrados'},
            {name: 'csv',   extend: 'csv', filename: 'entrenadores_registrados', title: 'entrenadores_registrados'}
        ],
        createdRow: function (row, data, dataIndex) {
            if (dataIndex > 0) {
                $(row).find('[data-tooltip]').tooltip();
            } else {
                $(row).find('[data-tooltip]').tooltip({ boundary: 'window' });
            }
        },
        drawCallback: function (settings) { },
        initComplete: function () { }
    });
    

    $('#submit-coach-form').click(function () {
        swal.fire({
            icon: 'question',
            title: 'Confirmar acción',
            html: '¿Guardar los datos del entrenador?',
            showCancelButton: true,
            confirmButtonText: 'Si, guardar',
            cancelButtonText: 'Cerrar sin cambios',
        }).then((result) => {
            if (result.isConfirmed) {
                if ($('[name="entrenador_id"]').val() == 'nuevo') {
                    var action = base_url + 'Equipos/nuevo_entrenador_form';
                } else {
                    var action = base_url + 'Equipos/editar_entrenador_form';
                }
                var fd = new FormData();
                fd.append("club_id", $('[name="club_id"]').val());
                fd.append("user_id", $('[name="entrenador_id"]').val());
                fd.append("first_name", $("#first_name_e").val());
                fd.append("last_name", $("#last_name_e").val());
                fd.append("dni", $("#dni_e").val());
                fd.append("phone", $("#phone_e").val());
                fd.append("email", $("#email_e").val());
                fd.append("newpassword", $("#newpassword_e").val());
				fd.append("confirmpassword", $("#confirmpassword_e").val());
                if ($('#active_e').is(':checked')) {
                    fd.append("active", $("#active_e").val());
                }
                fd.append("csrf_token", $('[name="csrf_token"]').val());
                $.ajax({
                    url: action,
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
                                tabla_entrenadores.draw();
                                $('#carga_individual_coach').modal('hide');
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
        });
    })

    $(document).on('click', '[data-ver-coach]', function () {
        deportista_id = $(this).attr('data-ver-coach')
        slug = $(this).attr('data-slug')
        var fd = new FormData();
        fd.append("entrenador_id", deportista_id);
        fd.append("csrf_token", $('[name="csrf_token"]').val());

        $.ajax({
            url: base_url + 'Equipos/ver_entrenador_fetch',
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
                var html = '';
                var club = response.data.club;
                var coach = response.data.coach;

                html += '<div class="card-list">';
                html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Código usuario</strong><span>' + coach.usercode + '</span></div>';
                html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Nombre</strong><span>' + coach.first_name + '</span></div>';
                html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Apellidos</strong><span>' + coach.last_name + '</span></div>';
                
                if (coach.dni != '') {
                    html += '<div class="item-list justify-content-between border-bottom p-2"><strong>DNI</strong><span>' + coach.dni + '</span></div>';
                }
                if (coach.email != '' && !coach.email.includes(".generado")) {
                    html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Email</strong><span>' + coach.email + '</span></div>';
                }
                if (coach.phone != '') {
                    html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Teléfono</strong><span>' + coach.phone + '</span></div>';
                }
                var estado = (coach.active == 1) ? 'Activo' : 'Inactivo';
                html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Estado</strong><span>' + estado + '</span></div>';
                html += '</div>';
                swal.fire({
                    icon: 'info',
                    title: 'Datos del entrenador',
                    html: html,
                    showCancelButton: false,
                    confirmButtonText: 'OK',
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

    $(document).on('click', '[data-editar-coach]', function () {
        entrenador_id = $(this).attr('data-editar-coach')
        slug = $(this).attr('data-slug')
        var fd = new FormData();
        fd.append("entrenador_id", entrenador_id);
        fd.append("csrf_token", $('[name="csrf_token"]').val());

        $.ajax({
            url: base_url + 'Equipos/ver_entrenador_fetch',
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
                var html = '';
                var club = response.data.club;
                var coach = response.data.coach;
                $('[name="entrenador_id"]').val(coach.id);
                $('[name="first_name_e"]').val(coach.first_name);
                $('[name="last_name_e"]').val(coach.last_name);
                $('[name="email_e"]').val(coach.email);
                $('[name="phone_e"]').val(coach.phone);
                $('[name="dni_e"]').val(coach.dni);
                if (coach.active == 1) {
                    $('[name="active_e"]').attr('checked', 'checked');
                } else {
                    $('[name="active_e"]').removeAttr('checked');
                }
                $('#carga_individual_coach .modal-title .fw-mediumbold').html('Editar entrenador')
                $('#carga_individual_coach').modal('show');
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

    $('[data-carga-entrenador]').click(function () {
        $('#carga_individual_coach form').trigger('reset');
        $('[name="entrenador_id"]').val('nuevo');
        $('[name="active_d"]').attr('checked', 'checked');
        $('#carga_individual_coach .modal-title .fw-mediumbold').html('Nuevo entrenador');
        $('#carga_individual_coach').modal('show')
    })

    $('#submit-coach-form').click(function () {
        swal.fire({
            icon: 'question',
            title: 'Confirmar acción',
            html: '¿Guardar los datos del entrenador?',
            showCancelButton: true,
            confirmButtonText: 'Si, guardar',
            cancelButtonText: 'Cerrar sin cambios',
        }).then((result) => {
            if (result.isConfirmed) {
                if ($('[name="entrenador_id"]').val() == 'nuevo') {
                    var action = base_url + 'Equipos/nuevo_entrenador_form';
                } else {
                    var action = base_url + 'Equipos/editar_entrenador_form';
                }
                var fd = new FormData();
                fd.append("club_id", $('[name="club_id"]').val());
                fd.append("user_id", $('[name="entrenador_id"]').val());
                fd.append("first_name", $("#first_name_e").val());
                fd.append("last_name", $("#last_name_e").val());
                fd.append("dni", $("#dni_e").val());
                fd.append("phone", $("#phone_e").val());
                fd.append("email", $("#email_e").val());
                fd.append("newpassword", $("#newpassword_e").val());
				fd.append("confirmpassword", $("#confirmpassword_e").val());
                if ($('#active_e').is(':checked')) {
                    fd.append("active", $("#active_e").val());
                }
                fd.append("csrf_token", $('[name="csrf_token"]').val());
                $.ajax({
                    url: action,
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
                                tabla_entrenadores.draw();
                                $('#carga_individual_coach').modal('hide');
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
        });
    })

    $(document).on('click', '[data-ver-coach]', function () {
        deportista_id = $(this).attr('data-ver-coach')
        slug = $(this).attr('data-slug')
        var fd = new FormData();
        fd.append("entrenador_id", deportista_id);
        fd.append("csrf_token", $('[name="csrf_token"]').val());

        $.ajax({
            url: base_url + 'Equipos/ver_entrenador_fetch',
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
                var html = '';
                var club = response.data.club;
                var coach = response.data.coach;

                html += '<div class="card-list">';
                html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Código usuario</strong><span>' + coach.usercode + '</span></div>';
                html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Nombre</strong><span>' + coach.first_name + '</span></div>';
                html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Apellidos</strong><span>' + coach.last_name + '</span></div>';
                
                if (coach.dni != '') {
                    html += '<div class="item-list justify-content-between border-bottom p-2"><strong>DNI</strong><span>' + coach.dni + '</span></div>';
                }
                if (coach.email != '' && !coach.email.includes(".generado")) {
                    html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Email</strong><span>' + coach.email + '</span></div>';
                }
                if (coach.phone != '') {
                    html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Teléfono</strong><span>' + coach.phone + '</span></div>';
                }
                var estado = (coach.active == 1) ? 'Activo' : 'Inactivo';
                html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Estado</strong><span>' + estado + '</span></div>';
                html += '</div>';
                swal.fire({
                    icon: 'info',
                    title: 'Datos del entrenador',
                    html: html,
                    showCancelButton: false,
                    confirmButtonText: 'OK',
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

    $(document).on('click', '[data-editar-coach]', function () {
        entrenador_id = $(this).attr('data-editar-coach')
        slug = $(this).attr('data-slug')
        var fd = new FormData();
        fd.append("entrenador_id", entrenador_id);
        fd.append("csrf_token", $('[name="csrf_token"]').val());

        $.ajax({
            url: base_url + 'Equipos/ver_entrenador_fetch',
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
                var html = '';
                var club = response.data.club;
                var coach = response.data.coach;
                $('[name="entrenador_id"]').val(coach.id);
                $('[name="first_name_e"]').val(coach.first_name);
                $('[name="last_name_e"]').val(coach.last_name);
                $('[name="email_e"]').val(coach.email);
                $('[name="phone_e"]').val(coach.phone);
                $('[name="dni_e"]').val(coach.dni);
                if (coach.active == 1) {
                    $('[name="active_e"]').attr('checked', 'checked');
                } else {
                    $('[name="active_e"]').removeAttr('checked');
                }
                $('#carga_individual_coach .modal-title .fw-mediumbold').html('Editar entrenador')
                $('#carga_individual_coach').modal('show');
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