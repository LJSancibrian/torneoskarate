// PESTAÑA COMPETICIONES
var table_competiciones;
table_competiciones = $("#table_competiciones").DataTable({
    processing: true,
    serverSide: true,
    order: [],
    ordering: true,
    columns: [{ //0
        title: "Modalidad",
        name: "Modalidad",
        data: "modalidad",
    }, { //2
        title: "Categoría",
        name: "categoria",
        data: "categoria",
    }, { //3
        title: "Género",
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
    }, { //3 
        title: "Peso / Nivel",
        name: "nivel",
        data: "nivel",
        render: function(data, type, row) {
            html = '<span class="badge badge-danger">' + row.nivel + '</span>';
            return html
        }
    }, { //4
        title: "Estado",
        name: "estado",
        data: "estado",
        render: function(data, type, row) {
            if (row.estado == 0) {
                html = '<span class="badge badge-info">Pendiente</span>';
            } else if (row.estado == 1) {
                html = '<span class="badge badge-success">Sorteado</span>';
            } else if (row.estado == 2) {
                html = '<span class="badge badge-secondary">Completado</span>';
            } else {
                html = '<span class="badge badge-danger">Cancelado</span>';
            }
            return html
        }
    }, { //5
        title: "<i class='fas fa-users'></i>",
        name: "n_inscripciones",
        data: "n_inscripciones",
        render: function(data, type, row) {
            html = '<div class="form-button-action ">';
            html += '<button type="button" data-tooltip title="" class="btn btn-sm btn-outline-primary" data-original-title="Ver inscripciones" data-ver-inscripciones="' + row.competicion_torneo_id + '">' + row.n_inscripciones + '</button>';
            html += '</div>';
            return html
        }
    }, { // 6
        name: "Acción",
        title: "Acción",
        data: "",
        className: "text-truncate",
        render: function(data, type, row) {
            html = '<div class="form-button-action">';
            if (row.estado == 0 || row.estado == 1) {
                html += '<button type="button" data-tooltip title="" class="btn btn-sm btn-outline-primary" data-original-title="Sorteo" data-gestionar="' + row.competicion_torneo_id + '"><i class="fas fa-random"></i></a>';
            }
            if (row.estado == 1 || row.estado == 2) {
                html += '<button type="button" data-tooltip title="" class="btn btn-sm btn-outline-primary" data-original-title="Manejar mesa" data-mesa="' + row.competicion_torneo_id + '" data-modalidad="' + row.modalidad + '"><i class="fas fa-chalkboard-teacher"></i></a>';
            };
            //if (row.modalidad == 'kata') {
            html += '<button type="button" data-tooltip title="" class="btn btn-sm btn-outline-primary" data-original-title="Clasificación"  data-clasificacion="' + row.competicion_torneo_id + '" data-modalidad="' + row.modalidad + '"><i class="fas fa-medal"></i></a>';
            //}
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
        url: base_url + 'Torneos/getCompeticiones',
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

$(document).on('click', '[data-ver-inscripciones]', function() {
    var modalidad = $(this).closest('tr').find('td').eq(0).text()
    var categoria = $(this).closest('tr').find('td').eq(2).text()
    var genero = $(this).closest('tr').find('td').eq(1).text()
    var peso = $(this).closest('tr').find('td').eq(3).text()

    var modaltitle = modalidad + ' ' + categoria + ' ' + genero + ' ' + peso;
    var competicion_torneo_id = $(this).attr('data-ver-inscripciones');
    var fd = new FormData();
    fd.append("competicion_torneo_id", competicion_torneo_id);
    fd.append("csrf_token", $('[name="csrf_token"]').val());
    $.ajax({
        url: base_url + 'Torneos/get_inscripciones_competicion',
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
            $('#modal_inscripciones .card-title').text(modaltitle.toUpperCase())
            var tabla = $('#deportistas_competicion');
            var tbody = $('#deportistas_competicion tbody');
            if ($.fn.DataTable.isDataTable('#deportistas_competicion')) {
                tabla.DataTable().clear()
                tabla.DataTable().destroy()
            }
            tbody.empty();
            var totalinscritos = response.inscritos.length;
            if (totalinscritos > 0) {
                $.each(response.inscritos, function(i, elem) {
                    var newtr = '<tr data-inscripcion_id="' + elem.inscripcion_id + '" data-user_id="' + elem.user_id + '"><td><h4 class="my-0 ml-1 seleccionable">' + elem.first_name + ' ' + elem.last_name + '</h4></td><td>' + elem.nombre + '</td></tr>';
                    tbody.append(newtr);
                })
            } else {
                var newtr = '<tr data-inscripcion_id="" data-user_id=""><td class="text-center" colspan="2">No hay inscripciones</td><td class="d-none"></td></tr>';
                tbody.append(newtr);
            }
            tabla.DataTable({
                buttons: [
                    { name: 'excel', extend: 'excel', filename: modaltitle, sheetName: 'Results', title: modaltitle },
                    { name: 'csv', extend: 'csv', filename: modaltitle, title: modaltitle }
                ],
            })

            $('#totalinscritos').html(totalinscritos)
            setTimeout(function() {
                $('#modal_inscripciones').modal('show')
            }, 300)
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

$(document).on('click', '[data-gestionar]', function() {
    swal.fire({
        icon: 'question',
        title: 'Sortear la competición',
        html: '¿Quieres ir a la página de sorteo de esta competición?',
        showCancelButton: true,
        confirmButtonText: 'Si, gestionar',
        cancelButtonText: 'No, cerrar',
    }).then((result) => {
        if (result.isConfirmed) {
            var competicion_torneo_id = $(this).attr('data-gestionar');
            window.open(base_url + 'Competiciones/gestion/' + competicion_torneo_id, '_blank');
            //window.location.href = base_url + 'Competiciones/gestion/' + competicion_torneo_id;
            /*
            window.open(
                base_url + 'Competiciones/gestion/' + competicion_torneo_id,
                '_blank'
            )
            */
        }
    })
})

$(document).on('click', '[data-mesa]', function() {
    swal.fire({
        icon: 'question',
        title: 'Gestionar competición',
        html: '¿Quieres ir a la página de gestion de esta competición?',
        showCancelButton: true,
        confirmButtonText: 'Si, gestionar',
        cancelButtonText: 'No, cerrar',
    }).then((result) => {
        if (result.isConfirmed) {
            var competicion_torneo_id = $(this).attr('data-mesa');
            window.open(
                base_url + 'Competiciones/mesa/' + competicion_torneo_id,
                '_blank'
            )
            //window.location.href = base_url + 'Competiciones/mesa/' + competicion_torneo_id;
            /*
            window.open(
                base_url + 'Competiciones/mesa/' + competicion_torneo_id,
                '_blank'
            )
            window.location.reload()
            */
        }
    })
})

$(document).on('click', '[data-clasificacion]', function() {
    var competicion_torneo_id = $(this).attr('data-clasificacion');
    window.location.href = base_url + 'Competiciones/clasificacionCompeticion/' + competicion_torneo_id;
    /*
    window.open(
        base_url + 'Competiciones/clasificacionCompeticion/' + competicion_torneo_id,
        '_blank'
    )
    */
})

$(document).on('shown.bs.modal', function(e) {
    $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
});