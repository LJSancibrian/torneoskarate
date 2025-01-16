// PESTAÑA COMPETICIONES
var table_competiciones;
table_competiciones = $("#table_datatable").DataTable({
    processing: true,
    serverSide: true,
    order: [],
    ordering: true,
    columns: [{ //0
        title: "Modalidad",
        name: "Modalidad",
        data: "modalidad",
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
    }, { //2
        title: "Categoría",
        name: "categoria",
        data: "categoria",
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
    }, { // 5
        name: "Acción",
        title: "Acción",
        data: "bonoID",
        className: "text-truncate",
        render: function(data, type, row) {
            html = '<div class="form-button-action">';
            html += '<button type="button" data-tooltip title="" class="btn btn-sm btn-outline-primary" data-original-title="Ver inscripciones" data-ver-inscripciones="' + row.competicion_torneo_id + '"><i class="fas fa-users"></i></button>';
            html += '<button type="button" data-tooltip title="" class="btn btn-sm btn-outline-primary" data-original-title="Sorteo" data-gestionar="' + row.competicion_torneo_id + '"><i class="fas fa-random"></i></a>';
            html += '<button type="button" data-tooltip title="" class="btn btn-sm btn-outline-primary" data-original-title="Manejar mesa" data-mesa="' + row.competicion_torneo_id + '" data-modalidad="' + row.modalidad + '"><i class="fas fa-chalkboard-teacher"></i></a>';
            //if (row.modalidad == 'kata') {
            html += '<button type="button" data-tooltip title="" class="btn btn-sm btn-outline-primary" data-original-title="Clasificación"  data-clasificacion="' + row.competicion_torneo_id + '" data-modalidad="' + row.modalidad + '"><i class="fas fa-medal"></i></a>';
            //}
            html += '</div>';
            return html
        }
    }, ],
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
        url: base_url + 'Torneos/getCompeticiones',
        type: "GET",
        datatype: "json",
        data: function(data) {
            data.torneo_id = 0;

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
        html: '¿Quieres ir a la página de sorteo de esta competición? Se abrirá en una nueva pestaña.',
        showCancelButton: true,
        confirmButtonText: 'Si, gestionar',
        cancelButtonText: 'No, cerrar',
    }).then((result) => {
        if (result.isConfirmed) {
            var competicion_torneo_id = $(this).attr('data-gestionar');
            window.open(
                base_url + 'Competiciones_ko/gestion/' + competicion_torneo_id,
                '_blank'
            )
        }
    })
})

$(document).on('click', '[data-mesa]', function() {
    swal.fire({
        icon: 'question',
        title: 'Gestionar competición',
        html: '¿Quieres ir a la página de gestion de esta competición? Se abrirá en una nueva pestaña.',
        showCancelButton: true,
        confirmButtonText: 'Si, gestionar',
        cancelButtonText: 'No, cerrar',
    }).then((result) => {
        if (result.isConfirmed) {
            var competicion_torneo_id = $(this).attr('data-mesa');

            if ($(this).attr('data-modalidad') == 'KATA' || $(this).attr('data-modalidad') == 'kata') {
                window.open(
                    base_url + 'Competiciones/mesakata/' + competicion_torneo_id,
                    '_blank'
                )
            } else {
                window.open(
                    base_url + 'Competiciones/mesakumite/' + competicion_torneo_id,
                    '_blank'
                )
            }

        }
    })
})

$(document).on('click', '[data-clasificacion]', function() {
    var competicion_torneo_id = $(this).attr('data-clasificacion');
    if ($(this).attr('data-modalidad') == 'KATA' || $(this).attr('data-modalidad') == 'kata') {
        window.open(
            base_url + 'Competiciones/clasificacionCompeticionKata/' + competicion_torneo_id,
            '_blank'
        )
    }
    if ($(this).attr('data-modalidad') == 'KUMITE' || $(this).attr('data-modalidad') == 'kumite') {
        window.open(
            base_url + 'Competiciones/clasificacionCompeticionKumite/' + competicion_torneo_id,
            '_blank'
        )
    }

})

$(document).on('click', '[data-editar-competicion]', function() {
    var competicion_id = $(this).attr('data-editar-competicion')
    if (competicion_id == 'nuevo') {
        $('#modal_crear_competicion form').trigger('reset');
        $('#modal_crear_competicion .fw-mediumbold').html('Crear nueva competición');
        $('[name="competicion_id"]').val('nuevo');
        $('#modal_crear_competicion').modal('show');

    } else {
        slug = $(this).attr('data-slug')
        var fd = new FormData();
        fd.append("competicion_id", competicion_id);
        fd.append("csrf_token", $('[name="csrf_token"]').val());

        $.ajax({
            url: base_url + 'competicions/ver_competicion_fetch',
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
                var competicion = response.data.competicion;
                $('#modal_crear_competicion form').trigger('reset');
                $('[name="competicion_id"]').val(competicion_id);
                $("#titulo").val(competicion.titulo);
                $("#descripcion").val(competicion.descripcion);
                $("#direccion").val(competicion.direccion);
                $("#organizador").val(competicion.organizador);
                $("#tipo").val(competicion.tipo);
                $("#fecha").val(competicion.fecha);
                $("#limite").val(competicion.limite);
                $("#email").val(competicion.email);
                $("#telefono").val(competicion.telefono);
                $('#modal_crear_competicion .fw-mediumbold').html('Editar competicion ' + competicion.slug);
                $('#modal_crear_competicion').modal('show');
                if (competicion.estado == 1) {
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
$('#submit-competicion-form').click(function() {
    var competicion_id = $('[name="competicion_id"]').val()
    if (competicion_id == 'nuevo') {
        var stitle = '¿Crear competición con los datos indicados?';
        var sconfirm = 'Si, crear competición';
        var action = base_url + 'Competicionesespeciales/nuevo_competicion_form';
    } else {
        var stitle = '¿Guardar los datos de lacompetición?';
        var sconfirm = 'Si, guardar datos';
        var action = base_url + 'Competicionesespeciales/editar_competicion_form';
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
            fd.append("competicion_id", competicion_id);
            fd.append("categoria", $("#categoria").val());
            fd.append("modalidad", $("#modalidad").val());
            fd.append("genero", $("#genero").val());
            fd.append("nivel", $("#nivel").val());
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
                            if (response.hasOwnProperty('redirect')) {
                                window.location.href = response.redirect
                            } else {
                                table_competiciones.draw();
                                $('#modal_crear_competicion').modal('hide');
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


$(document).on('shown.bs.modal', function(e) {
    $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
});