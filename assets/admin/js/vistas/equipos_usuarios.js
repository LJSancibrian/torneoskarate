var tabla;
var titledoc = 'usuarios-equipos';
tabla = $("#table_datatable").DataTable({
    processing: true,
    serverSide: true,
    columns: [{ //0
        title: "ID",
        name: "ID",
        data: "id",
    }, { //1 
        title: "Nombre",
        name: "Nombre",
        data: "first_name"
    }, { //2 
        title: "Apellidos",
        name: "Apellidos",
        data: "first_name"
    }, { //3
        title: "Telefono",
        name: "phone",
        data: "phone",
    }, { //4
        title: "Email",
        name: "email",
        data: "email",
    }, { //5
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
    }, { //6
        title: "Equipo",
        name: "nombre",
        data: "nombre",
    }, { // 7
        name: "Acción",
        title: "Acción",
        className: "text-truncate",
        render: function(data, type, row) {
            html = '<div class="form-button-action">';
            html += '<button type="button" data-tooltip title="" class="btn btn-sm btn-outline-primary" data-original-title="Ver entrenador" data-editar-usuario="'+row.id+' data-nombre="'+row.first_name+' '+last_name+'"><i class="fa fa-eye"></i></button>';
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
        url: base_url + 'Equipos/getUsuarios',
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
        {name: 'excel', extend: 'excel', filename: 'usuarios_registrados', sheetName: 'Results', title: 'entrenadores_registrados'},
        {name: 'csv',   extend: 'csv', filename: 'usuarios_registrados', title: 'entrenadores_registrados'}
    ],
    createdRow: function(row, data, dataIndex) {
        if(dataIndex > 0){
            $(row).find('[data-tooltip]').tooltip();
        }else{
            $(row).find('[data-tooltip]').tooltip({ boundary: 'window' });
        }
    },
    drawCallback: function(settings) {},
    initComplete: function() {}
});

$(document).on('click', '[data-editar-usuario]', function() {
    user_id = $(this).attr('data-editar-usuario')
    nombre = $(this).attr('data-nombre')
    swal.fire({
        icon: 'question',
        title: 'Confirmar acción',
        html: 'Ver el perfil de <span style="font-size:24px; font-weight:bold;">' + nombre + '</span>?',
        showCancelButton: true,
        confirmButtonText: 'Si, ir ',
        cancelButtonText: 'No, cerrar',
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = base_url + 'equipos/ver-usuario/' + user_id
        }
    })
});