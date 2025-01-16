var tabla;
var titledoc = 'usuarios';
var titledoc = window.location.pathname.split("/").pop()+'_registrados';
tabla = $("#table_datatable").DataTable({
    processing: true,
    serverSide: true,
    columns: [{ //0
        title: "ID",
        name: "ID",
        data: "id",
    }, { //1 
        title: "Código",
        name: "usercode",
        data: "usercode"
    }, { //2 
        title: "Nombre",
        name: "Nombre",
        data: "first_name"
    }, { //3 
        title: "Apellidos",
        name: "Apellidos",
        data: "last_name"
    }, { //4
        title: "Telefono",
        name: "phone",
        data: "phone",
    }, { //5
        title: "Email",
        name: "email",
        data: "email",
    }, { //6
        title: "Estado",
        name: "active",
        data: "active",
        render: function(data, type, row) {
            if(row.active == 1){
                html = '<span class="badge badge-success">Activo</span>';
            }else{
                html = '<span class="badge badge-danger">Inactivo</span>'; 
            }
            return html
        }
    }, { // 7
        name: "Acción",
        title: "Acción",
        data: "bonoID",
        className: "text-truncate",
        render: function(data, type, row) {
            html = '<div class="form-button-action">';
            html += '<button type="button" data-tooltip title="" class="btn btn-sm btn-outline-primary" data-original-title="Ver usuario" data-ver-usuario="' + row.id + '" data-slug="' + row.usercode + '"><i class="fa fa-user"></i></button>';
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
        url: base_url + 'Usuarios/getUsuarios',
        type: "GET",
        datatype: "json",
        data: function(data) { 
            var rol = $("#table_datatable").data('default');
            if (rol != '') {
                data.rol = rol;
            }
        }
    },
    buttons: [
        {name: 'excel', extend: 'excel', filename: titledoc, sheetName: 'Results', title: titledoc},
        {name: 'csv',   extend: 'csv', filename: titledoc, title: titledoc}
    ],
    createdRow: function(row, data, dataIndex) {
        var i = 0;
        $.each(data, function(index, value) {
            var ti = i + 1;
            var thead = $('tr th:nth-child(' + ti + ')').text()
            $(row).find('td:eq(' + i + ')').attr('thead', thead);
            i++
        })
        $(row).attr('data-userid', data.id);
        $(row).attr('data-nombre', data.first_name + ' ' + data.last_name);
        if(dataIndex > 0){
            $(row).find('[data-tooltip]').tooltip();
        }else{
            $(row).find('[data-tooltip]').tooltip({ boundary: 'window' });
        }
    },
    drawCallback: function(settings) {},
    initComplete: function() {}
});

$(document).on('click', '[data-ver-usuario]', function () {
    user_id = $(this).attr('data-ver-usuario')
    slug = $(this).attr('data-slug')
    var fd = new FormData();
    fd.append("user_id", user_id);
    fd.append("csrf_token", $('[name="csrf_token"]').val());

    $.ajax({
        url: base_url + 'Usuarios/ver_usuario_fetch',
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
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Código usuario</strong><span>'+responsable.usercode+'</span></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Nombre</strong><span>'+responsable.first_nam+'</span></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Apellidos</strong><span>'+responsable.last_name+'</span></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>DNI</strong><span>'+responsable.dni+'</span></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Email</strong><span>'+responsable.email+'</span></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Teléfono</strong><span>'+responsable.phone+'</span></div>';
            var estado = (responsable.active == 1 ) ? 'Activo' : 'Inactivo';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Estado</strong><span>'+estado+'</span></div>';
            html += '</div>';      
            swal.fire({
                icon: 'info',
                title: 'Usuario responsable del equipo',
                html: html,
                showCancelButton: true,
                confirmButtonText: 'Ver usuario',
                cancelButtonText: 'Cerrar',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = base_url + 'usuarios/ver/' + slug
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