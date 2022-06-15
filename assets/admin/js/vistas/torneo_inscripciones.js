// PESTAÑA INSCRIPCIONES
var table_inscripciones;
table_inscripciones = $("#table_inscripciones").DataTable({
    processing: true,
    serverSide: true,
    columns: [{ //0
        title: "CODIGO",
        name: "usercode",
        data: "usercode",
    }, { //1
        title: "Nombre",
        name: "first_name",
        data: "first_name",
    }, { //2
        title: "Apellidos",
        name: "last_name",
        data: "last_name",
    }, { //3
        title: "Competición",
        name: "genero",
        data: "genero",
        render: function(data, type, row) {
            if (row.genero == 'M') {
                var genero = 'Masculino';
            }
            if (row.genero == 'F') {
                var genero = 'Femenino';
            }
            if (row.genero == 'X') {
                var genero = 'Mixto';
            }
            html = '<span class="badge badge-info">' + genero + '</span>';
            return html
        }
    }, { //4 
        title: "Modalidad",
        name: "modalidad",
        data: "modalidad",
        render: function(data, type, row) {
            html = '<span class="badge badge-info">' + row.modalidad + '</span>';
            return html
        }
    }, { //5
        title: "Categoría",
        name: "categoria_text",
        data: "categoria_text",
    }, { //6
        title: "Equipo",
        name: "nombre",
        data: "nombre",
    }, { //7
        title: "Estado",
        name: "estado",
        data: "estado",
        render: function(data, type, row) {
                if (row.estado == 0) {
                    var clase = 'badge badge-info';
                } else if (row.estado == 1) {
                    var clase = 'badge badge-success';
                } else {
                    var clase = 'badge badge-danger';
                }
                html = '<select class="' + clase + '" data-prev="' + row.estado + '" data-change-estado-inscripcion="' + row.inscripcion_id + '">';
                if (row.estado == 0) {
                    html += '<option value="0" selected>Pendiente</option>';
                } else {
                    html += '<option value="0">Pendiente</option>';
                }

                if (row.estado == 1) {
                    html += '<option value="1" selected>Aceptada</option>';
                } else {
                    html += '<option value="1">Aceptada</option>';
                }

                if (row.estado == 2) {
                    html += '<option value="2" selected>Rechazada</option>';
                } else {
                    html += '<option value="2">Rechazada</option>';
                }
                html += '</select>'
                return html
            }
            /*}, { // 8
                name: "Acción",
                title: "Acción",
                data: "bonoID",
                className: "text-truncate",
                render: function (data, type, row) {
                    html = '<div class="form-button-action">';
                    html += '<button type="button" data-tooltip title="" class="btn btn-sm btn-outline-primary" data-original-title="Vista rápida del torneo" data-ver-torneo="' + row.torneo_id + '" data-slug="' + row.slug + '"><i class="fas fa-trophy"></i></button>';
                    html += '<button type="button" data-tooltip title="" class="btn btn-sm btn-outline-primary" data-original-title="Ver usuario responsable" data-editar-torneo="' + row.torneo_id + '" data-slug="' + row.slug + '"><i class="fa fa-edit"></i></a>';
                    html += '</div>';
                    return html
                }*/
    }, ],
    columnDefs: [{
        targets: [0, 1, 2, 3, 4, 5, 6, 7],
        visible: true
    }, {
        targets: ["_all"],
        visible: false
    }, {
        /*targets: [6],
        orderable: false*/
    }],
    ajax: {
        url: base_url + 'Torneos/getInscripciones',
        type: "GET",
        datatype: "json",
        data: function(data) {
            var torneo_id = $('[name="torneo_id"]').val();
            if (torneo_id != '') {
                data.torneo_id = torneo_id;
            }
            var estado = $('[name="f_estado"]').val();
            console.log(estado);
            if (estado != '') {
                data.estado = estado;
            }
            var equipo = $('[name="f_equipo"]').val();
            if (equipo != '') {
                data.equipo = equipo;
            }
            var modalidad = $('[name="f_modalidad"]').val();
            if (modalidad != '') {
                data.modalidad = modalidad;
            }
            var t_categoria_id = $('[name="f_t_categoria_id"]').val();
            if (t_categoria_id != '') {
                data.t_categoria_id = t_categoria_id;
            }
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
$(document).on('change', '[data-change-estado-inscripcion]', function() {
    var select = $(this)
    var inscripcion_id = select.attr('data-change-estado-inscripcion');
    var oldclass = select.attr('class')
    var oldvalue = select.attr('data-prev');
    var estado = select.val();
    var fd = new FormData();
    fd.append("inscripcion_id", inscripcion_id);
    fd.append("estado", estado);
    fd.append("csrf_token", $('[name="csrf_token"]').val());
    $.ajax({
        url: base_url + 'Torneos/manage_estado_inscripciones',
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
                willClose: function() {
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
            if (estado == 0) {
                var clase = 'badge badge-info';
            } else if (estado == 1) {
                var clase = 'badge badge-success';
            } else {
                var clase = 'badge badge-danger';
            }

            select.attr('class', clase);
            select.attr('data-prev', estado)
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
})
$(document).on('change', '#filtros_inscripciones select', function() {
    table_inscripciones.draw()
})

$(document).on('click', '#add_inscripcion', function() {
    // enviar
    var fd = new FormData();
    fd.append("torneo_id", $('[name="torneo_id"]').val());
    fd.append("competicion_nueva_torneo_id", $('[name="competicion_nueva_torneo_id"]').val());
    fd.append("competicion_previa_torneo_id", 0);
    fd.append("estado", 1);
    fd.append("user_id", $('[name="deportista_id"]').val());
    fd.append("csrf_token", $('[name="csrf_token"]').val());
    $.ajax({
        url: base_url + 'Torneos/manage_inscripciones',
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
                willClose: function() {
                    if (response.hasOwnProperty('redirect')) {
                        if (response.redirect == 'refresh') {
                            location.reload()
                        } else {
                            window.location.href = response.redirect
                        }
                    }
                }
            });
            select.val(competicion_previa_torneo_id);
            return;
        } else {
            window.location.reload()
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
})

$(document).ready(function() {
    $(".select2").select2();
});