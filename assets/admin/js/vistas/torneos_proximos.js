var tabla;
var titledoc = 'torneos';
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
    }, { //4
        title: "Límite",
        name: "limite",
        data: "limite",
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
        className: "text-truncate",
        render: function (data, type, row) {
            html = '<div class="form-button-action">';
            html += '<button type="button" data-tooltip title="" class="btn btn-sm btn-outline-primary" data-original-title="Entar al torneo" data-gestion-torneo="' + row.torneo_id + '" data-slug="' + row.slug + '"><i class="fas fa-trophy"></i></button>';
            html += '</div>';
            return html
        }
    },],
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
        data: function (data) {
            data.estado = 1;
            data.proximos = 1;
        }
    },
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

$(document).on('click', '[data-gestion-torneo]', function () {
    torneo_id = $(this).attr('data-gestion-torneo')
    slug = $(this).attr('data-slug')

    /*swal.fire({
        icon: 'info',
        title: '¿Ir a la página de gestión del torneo?',
        showCancelButton: true,
        confirmButtonText: 'Gestionar torneo',
        cancelButtonText: 'Cerrar',
    }).then((result) => {
        if (result.isConfirmed) {*/
            window.location.href = base_url + 'torneos/gestion/' + slug
       /* }
    })*/
});