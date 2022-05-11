var tabla;
var titledoc = 'equipos';
tabla = $("#table_datatable").DataTable({
    processing: true,
    serverSide: true,
    columns: [{ //0
        title: "ID",
        name: "ID",
        data: "club_id",
    }, { //1
        title: "Código",
        name: "slug",
        data: "slug",
    }, { //2 
        title: "Equipo",
        name: "Nombre",
        data: "nombre"
    }, { //3
        title: "Responsable",
        name: "responsable",
        data: "responsable",
    }, { //4
        title: "Email Equipo",
        name: "email",
        data: "email",
    }, { //5
        title: "Teléfono Equipo",
        name: "telefono",
        data: "telefono",
    }, { //6
        title: "Estado",
        name: "estado",
        data: "estado",
        render: function (data, type, row) {
            if (row.estado == 1) {
                html = '<span class="badge badge-success">Activo</span>';
            } else {
                html = '<span class="badge badge-danger">Inactivo</span>';
            }
            return html
        }
    }, { // 7
        name: "Acción",
        title: "Acción",
        data: "bonoID",
        className: "text-truncate",
        render: function (data, type, row) {
            html = '<div class="form-button-action">';
            html += '<button type="button" data-tooltip title="" class="btn btn-sm btn-outline-primary" data-original-title="Vista rápida del equipo" data-ver-club="' + row.club_id + '" data-slug="' + row.slug + '"><i class="fa fa-users"></i></button>';
            html += '<button type="button" data-tooltip title="" class="btn btn-sm btn-outline-primary" data-original-title="Ver usuario responsable" data-ver-usuario="' + row.user_id + '" data-slug="' + row.slug + '"><i class="fa fa-user"></i></a>';
            html += '<button type="button" data-tooltip title="" class="btn btn-sm btn-outline-primary" data-original-title="Gestionar equipo" data-gestionar-club="' + row.club_id + '" data-slug="' + row.slug + '"><i class="fa fa-cog"></i></a>';
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
        url: base_url + 'Equipos/getEquipos',
        type: "GET",
        datatype: "json",
        data: function (data) {
            var estado = $("#table_datatable").data('default');
            if (estado != '') {
                data.estado = estado;
            }
        }
    },
    buttons: [
        {name: 'excel', extend: 'excel', filename: 'clubs_registrados', sheetName: 'Results', title: 'clubs_registrados'},
        {name: 'csv',   extend: 'csv', filename: 'clubs_registrados', title: 'clubs_registrados'}
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

$(document).on('click', '[data-ver-club]', function () {
    club_id = $(this).attr('data-ver-club')
    slug = $(this).attr('data-slug')
    var fd = new FormData();
    fd.append("club_id", club_id);
    fd.append("csrf_token", $('[name="csrf_token"]').val());

    $.ajax({
        url: base_url + 'Equipos/ver_equipo_fetch',
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
            var responsable = response.data.responsable;

            html += '<div class="card-list">';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Nombre</strong><span>' + club.nombre + '</span></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Código de equipo</strong><span>' + club.slug + '</span></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Responsable</strong><a href="' + base_url + 'equipos/ver-usuario/' + responsable.id + '">' + responsable.first_name + ' ' + responsable.last_name + '</a></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Descripción</strong><span>' + club.descripcion + '</span></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Dirección</strong><span>' + club.direccion + '</span></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Email de contacto</strong><span>' + club.email + '</span></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Teléfono de contacto</strong><span>' + club.telefono + '</span></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Estado del club</strong><span>' + club.estado + '</span></div>';
            html += '</div>';
            swal.fire({
                icon: 'info',
                title: 'Información del equipo',
                html: html,
                showCancelButton: true,
                confirmButtonText: 'Ver equipo',
                cancelButtonText: 'Cerrar',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = base_url + 'equipos/ver/' + slug
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

$(document).on('click', '[data-ver-usuario]', function () {
    user_id = $(this).attr('data-ver-usuario')
    slug = $(this).attr('data-slug')
    var fd = new FormData();
    fd.append("user_id", user_id);
    fd.append("csrf_token", $('[name="csrf_token"]').val());

    $.ajax({
        url: base_url + 'Equipos/ver_usuario_fetch',
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
            var responsable = response.data.responsable;

            html += '<div class="card-list">';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Código usuario</strong><span>' + responsable.usercode + '</span></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Nombre</strong><span>' + responsable.first_name + '</span></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Apellidos</strong><span>' + responsable.last_name + '</span></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>DNI</strong><span>' + responsable.dni + '</span></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Email</strong><span>' + responsable.email + '</span></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Teléfono</strong><span>' + responsable.phone + '</span></div>';
            var estado = (responsable.active == 1) ? 'Activo' : 'Inactivo';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Estado</strong><span>' + estado + '</span></div>';
            html += '</div>';
            swal.fire({
                icon: 'info',
                title: 'Usuario responsable del equipo',
                html: html,
                showCancelButton: true,
                confirmButtonText: 'Ver equipo',
                cancelButtonText: 'Cerrar',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = base_url + 'equipos/ver/' + slug
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

$(document).on('click', '[data-gestionar-club]', function () {
    club_id = $(this).attr('data-gestionar-club')
    slug = $(this).attr('data-slug')
    swal.fire({
        icon: 'info',
        title: '¿Ir a la página de gestión del equipo?',
        showCancelButton: true,
        confirmButtonText: 'Gestionar el equipo',
        cancelButtonText: 'Cerrar',
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = base_url + 'equipos/ver/' + slug
        }
    })   
});
