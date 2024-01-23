var tabla;
var titledoc = 'equipos';
tabla = $("#table_datatable").DataTable({
    processing: true,
    serverSide: true,
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
        render: function (data, type, row) {
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
    }, { //5
        title: "Estado",
        name: "estado",
        data: "estado",
        render: function (data, type, row) {
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
        render: function (data, type, row) {
            html = '<div class="form-button-action">';
            html += '<button type="button" data-tooltip title="" class="btn btn-sm btn-outline-primary" data-original-title="Vista rápida del torneo" data-ver-torneo="' + row.torneo_id + '" data-slug="' + row.slug + '"><i class="fas fa-trophy"></i></button>';
            html += '<button type="button" data-tooltip title="" class="btn btn-sm btn-outline-primary" data-original-title="Gestionar torneo" data-gestion-torneo="' + row.torneo_id + '" data-slug="' + row.slug + '"><i class="fas fa-cog"></i></button>';
            html += '</div>';
            return html
        }
    },],
    columnDefs: [{
        targets: [0, 1, 2, 3, 4, 5],
        visible: true
    }, {
        targets: ["_all"],
        visible: false
    }, {
        targets: [5],
        orderable: false
    }],
    ajax: {
        url: base_url + 'Torneos/getTorneos',
        type: "GET",
        datatype: "json",
        data: function (data) {
            data.estado = 1;
            data.proximos = 1;
        }
    },
    buttons: [],
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

$(document).on('click', '[data-ver-torneo]', function () {
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
    }).done(function (response) {
        if (response === '' || typeof response == 'undefined') {
            window.location.reload();
        }
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

$(document).on('click', '[data-gestion-torneo]', function () {
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
            window.location.href = base_url + 'Torneos/competiciones/' + slug
        }
    })
});