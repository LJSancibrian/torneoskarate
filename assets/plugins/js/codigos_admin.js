url = window.location.href;
segment = url.replace(base_url, '');
array = segment.split('/')
title_file = 'Participaciones'
var tabla;
tabla = $("#tabla").DataTable({
    info: true,
    paging: true,
    ordering: true,
    searching: true,
    stateSave: true,
    processing: true,
    serverSide: true,
    scrollX: true,
    autoWidth: false,
    order: [0, "desc"],
    pageLength: 50,
    lengthMenu: [
        [10, 25, 50, 100, -1],
        [10, 25, 50, 100, 'Todos']
    ],
    columns: [{
        name: "ID",
        title: "ID",
        data: "codigopromocionalID"
    }, {
        name: "Código",
        title: "Código",
        data: "codigo"
    }, {
        name: "Valor",
        title: "Valor",
        data: "valor"
    }, {
        name: "Fecha activación",
        title: "Fecha de activación",
        data: "fecha_confirmacion_entrega"
    }, {
        name: "Compra mínima",
        title: "Compra mínima",
        data: "minimo"
    }, {
        name: "Tarjeta regalo",
        title: "Tarjeta regalo",
        data: "bono",
        render: function(data, type, row) {
            var html = '';
            if (row.bono != '' && row.bono != null) {
                html += `<a class="btn btn-sm btn-link" href="${base_url}assets/bonos/pdf/tarjeta${row.bono}.pdf" target="_blank">${row.bono}</a>`;
            }
            return html;
        }
    }, ],
    ajax: {
        url: base_url + "Codigos/get_codigospromocionales",
        type: "GET",
        datatype: "json",
        data: function(data) {
            var fecha_inicio = $('[name="fecha_inicio"]').val();
            var fecha_fin = $('[name="fecha_fin"]').val();

            if (fecha_inicio != '') {
                data.fecha_inicio = fecha_inicio;
            }
            if (fecha_fin != '') {
                data.fecha_fin = fecha_fin;
            }
        }
    },
    language: {
        url: base_url + "assets/admin/js/spanish.lang.json"
    },
    createdRow: function(row, data, dataIndex) {
        $(row).attr('data-dni', data.dni).attr('data-ordertpv', data.ordertpv)

    },
    drawCallback: function() {
        var api = this.api();
        pagina_bonificado = api.column(2).data().sum();
        $('#pagina_bonificado').html(
            pagina_bonificado.toFixed(2) + '€'
        );
    },
    columnDefs: [{
        /*orderable: false,
        targets: -1*/
    }]
});
$(document).ready(function() {
    var titledoc = 'participacion_panel_empresas';
    var buttons = new $.fn.dataTable.Buttons(tabla, {
        buttons: [
            'copyHtml5', {
                extend: 'excelHtml5',
                title: titledoc
            }, {
                extend: 'csvHtml5',
                title: titledoc
            }, {
                extend: 'pdfHtml5',
                title: titledoc
            }
        ]
    }).container().appendTo($('#buttons'));
})
$('#fecha_inicio, #fecha_fin').datetimepicker({
    locale: 'es',
    format: 'YYYY-MM-DD',
    debug: true
});
$(document).on('click', '#searchdesc', function() {
    tabla.draw();
})

tabla.on('xhr', function() {
    var json = tabla.ajax.json();
    $('#total_bonificado').html(
        json.valortotal + '€'
    );
});

$(document).on('click', '#add-new-codes', function() {
    var modal = '<div id="modalTemp" class="modal fade" tabindex="-1" role="dialog">';
    modal += '<div class="modal-dialog modal-dialog-centered" role="document">';
    modal += '<div class="modal-content">';
    modal += '<div class="modal-body">';
    modal += '<p style="font-size: 20px;text-align:center"><span style="font-size:24px; font-weight:bold;">Completa los campos para generar los códigos necesarios</span></p>';
    modal += '<div class="input-group mx-1 my-1">';
    modal += '<div class="input-group-prepend"><span class="input-group-text bg-primary text-white" style="text-align:center; width: 140px; display:block">Cantidad</span></div>';
    modal += '<input type="number" class="form-control" name="cantidad_generar"  id="cantidad_generar"/>';
    modal += '</div>';
    modal += '<div class="input-group mx-1 my-1">';
    modal += '<div class="input-group-prepend"><span class="input-group-text bg-primary text-white" style="text-align:center; width: 140px;display:block;">Valor</span></div>';
    modal += '<input type="number" class="form-control" name="valor_generar"  id="valor_generar"/>';
    modal += '</div>';
    modal += '<div class="input-group mx-1 my-1">';
    modal += '<div class="input-group-prepend"><span class="input-group-text bg-primary text-white" style="text-align:center; width: 140px;display:block;">Compra mínima</span></div>';
    modal += '<input type="number" class="form-control" name="minimo_generar"  id="minimo_generar"/>';
    modal += '</div>';
    modal += '</div>';
    modal += '<div class="modal-footer">';
    modal += '<button type="button" class="btn btn-primary" onclick="generarcodigos();" data-dismiss="modal" data-backdrop="false">Generar</button>';
    modal += '<button type="button" class="btn btn-secondary" data-dismiss="modal">No, cerrar</button>';
    modal += '</div></div></div></div>';
    $("body").append(modal);
    $('#modalTemp').modal('show');
});


function generarcodigos() {
    var cantidad = $('input[name="cantidad_generar"]').val()
    var valor = $('input[name="valor_generar"]').val()
    var minimo = $('input[name="minimo_generar"]').val()

    var fd = new FormData();
    fd.append("cantidad", cantidad);
    fd.append("valor", valor);
    fd.append("minimo", minimo);
    fd.append("csrf_fecos", $('[name="csrf_fecos"]').val());
    swal.fire({
        icon: 'question',
        title: 'Confirmar acción',
        html: '¿Generar códigos con las condiciones indicadas?',
        showCancelButton: true,
        confirmButtonText: 'Si, generar ',
        cancelButtonText: 'Cerrar sin cambios',
        willClose: function() {
            $('#modalTemp').modal('hide');
            $('#modalTemp').remove();
        }
    }).then((result) => {
        $.ajax({
            url: base_url + "Codigos/generarCodigos",
            method: "POST",
            contentType: false,
            processData: false,
            data: fd
        }).done(function(response) {
            var response = JSON.parse(response);
            $('[name="csrf_fecos"]').val(response.csrf)
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
                        $('#modalTemp').modal('hide');
                        $('#modalTemp').remove();
                        tabla.draw();
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
                    willClose: function() {
                        //window.location.reload();
                    }
                })
            }
        });
    });
}
/*

	swal.fire({
		//icon: 'question',
		title: 'Añadir codigos promocional',
		html: '¿Borrar el descuento que se ha indocado?',
		showCancelButton: true,
		confirmButtonText: 'Si, anular',
		cancelButtonText: 'Cerrar sin cambios',
	}).then((result) => {
		if (result.isConfirmed) {

			var fd = new FormData();
			fd.append("descuentoID", parseInt(descuentoID));
			fd.append("csrf_fecos", $('[name="csrf_fecos"]').val());

			$.ajax({
				url: base_url + "Descuentos/borrarDescuento",
				method: "POST",
				contentType: false,
				processData: false,
				data: fd
			}).done(function(response) {
				var response = JSON.parse(response);
				$('[name="csrf_fecos"]').val(response.csrf)
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
							$('#searchdesc').click();
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
						willClose: function() {
							//window.location.reload();
						}
					})
				}
			});
		}
	});
})*/