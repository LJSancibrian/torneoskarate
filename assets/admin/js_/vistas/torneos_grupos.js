var tabla;
var titledoc = 'equipos';
tabla = $("#table_datatable").DataTable({
    processing: true,
    serverSide: true,
    order: [
        [0, 'desc']
    ],
    columns: [{ //0
        title: "ID",
        name: "grupo_id",
        data: "grupo_id",
    }, { //1
        title: "Título",
        name: "titulo",
        data: "titulo",
    }, { //2 
        title: "Descripción",
        name: "descripcion",
        data: "descripcion"
	},{ //3
		title: "Torneos",
        name: "titulos_torneos",
        data: "titulos_torneos"
	}, { //4
		title: "Estado",
		name: "estado",
		data: "estado",
		render: function(data, type, row) {
			if (row.estado == 1) {
				html = '<span class="badge badge-warning">Activo</span>';
			} else {
				html = '<span class="badge badge-danger">Inactivo</span>';
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
            html += '<button type="button" data-tooltip title="" class="btn btn-sm btn-outline-primary" data-original-title="Editar datos del grupo" data-editar-grupo="' + row.grupo_id + '"><i class="fa fa-edit"></i></a>';
            html += '<button type="button" data-tooltip title="" class="btn btn-sm btn-outline-primary" data-original-title="Gestionar grupo" data-ver-grupo="' + row.grupo_id + '"><i class="fas fa-eye"></i></button>';
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
        targets: [-1],
        orderable: false
    }],
    ajax: {
        url: base_url + 'Torneos/getTorneosGrupos',
        type: "GET",
        datatype: "json",
        data: function(data) {
            var estado = $("#table_datatable").data('default');
            if (estado != '') {
                data.estado = estado;
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


$(document).on('click', '[data-editar-grupo]', function() {
    var grupo_id = $(this).attr('data-editar-grupo')
    if (grupo_id == 'nuevo') {
        $('#modal_crear_grupo form').trigger('reset');
        $('#modal_crear_grupo .fw-mediumbold').html('Crear nuevo grupo');
        $('[name="grupo_id"]').val('nuevo');
        $('#modal_crear_grupo').modal('show');

    } else {
        var fd = new FormData();
        fd.append("grupo_id", grupo_id);
        fd.append("csrf_token", $('[name="csrf_token"]').val());
        $.ajax({
            url:  base_url + 'Torneos/ver_grupo_fetch',
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
                var grupo = response.data.grupo;
                $('#modal_crear_grupo form').trigger('reset');
                $('[name="grupo_id"]').val(grupo_id);
                $("#titulo").val(grupo.titulo);
                $("#descripcion").val(grupo.descripcion);
                
				var ids = grupo.torneo_ids.split('|');
				$.each(ids, function(index, value) {
					$('[name="torneo_id[]"][value="'+value+'"]').prop('checked', true);
				});

				$('#modal_crear_grupo .fw-mediumbold').html('Editar grupo ' + grupo.slug);
                $('#modal_crear_grupo').modal('show');
                if (grupo.estado == 1) {
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
$('#submit-grupo-form').click(function() {
    var grupo_id = $('[name="grupo_id"]').val()
    if (grupo_id == 'nuevo') {
        var stitle = '¿Crear grupo con los torneos indicados?';
        var sconfirm = 'Si, crear grupo';
        var action = base_url + 'Torneos/nuevo_grupo_form';
    } else {
        var stitle = '¿Guardar los torneos del grupo?';
        var sconfirm = 'Si, guardar datos';
        var action = base_url + 'Torneos/editar_grupo_form';
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
			var form = $('#submit-grupo-form').closest("form");
			form.attr('id', 'submitform')
			var fd = new FormData(document.getElementById("submitform"));
          
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
                                $('#modal_crear_grupo').modal('hide');
								window.location.href = response.redirect
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

$(document).on('click', '[data-ver-grupo]', function() {
    grupo_id = $(this).attr('data-ver-grupo')
    url =  base_url + 'clasificaciongrupo/' + grupo_id
    window.open(url, '_blank');
});