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
		data: "participacionID"
	}, {
		name: "Nombre",
		title: "Nombre",
		data: "first_name"
	}, {
		name: "Apellidos",
		title: "Apellidos",
		data: "last_name"
	}, {
		name: "DNI",
		title: "DNI",
		data: "dni"
	}, {
		name: "Email",
		title: "Email",
		data: "email"
	}, {
		name: "Teléfono",
		title: "Teléfono",
		data: "phone"
	}, {
		name: "Premio",
		title: "Premio",
		data: "premio"
	}, {
		name: "Código",
		title: "Código",
		data: "codigo",
		className: "text-truncate",
		render: function(data, type, row) {
			if (row.codigo != null) {
				html = `<a class="btn btn-sm btn-link" data-ver-codigo="${row.codigo}"><i class="fas fa-info-circle "></i> ${row.codigo}</a>`
				return html
			} else {
				return '';
			}
		}
	}, {
		name: "Tarjeta",
		title: "Tarjeta",
		data: "bono",
		className: "text-truncate",
		render: function(data, type, row) {
			if (row.bonoID != null) {
				html = `<a class="btn btn-sm btn-link" data-ver-tarjeta="${row.bonoID}"><i class="fas fa-info-circle"></i> ${row.bono}</a>`
				return html
			} else {
				return '';
			}
		}
	}, {
		name: "Fecha",
		title: "Fecha",
		data: "createdAt"
	//}, {
		//name: "Acción",
		//title: "Acción",
		//data: "participacionID",
		//render: function(data, type, row) {
			/*html = `<a class="btn btn-sm btn-link" data-ver-movimientos="${row.bono}" target="_blank"><i class="fas fa-ticket-alt"></i></a>`
			html += `<a class="btn btn-sm btn-link" href="${base_url}assets/bonos/pdf/tarjeta${row.bono}.pdf" target="_blank"><i class="fas fa-qrcode"></i></a>
               <button class="btn btn-sm btn-link" data-borrar="${row.bonoID}"><i class="fas fa-trash"></i></button>`
			return html*/
		//}
	}, ],
	ajax: {
		url: base_url + "Codigos/get_participaciones",
		type: "GET",
		datatype: "json",
		data: function(data) {
			var estado = $('#tabla').attr('data-estado');
			if (estado == 'nopremio' || estado == 'codigo' || estado == 'nobono' || estado == 'bono') {
				data.estado = estado;
			}
			var codigo = $('[name="codigo"]').val();
			var fecha_inicio = $('[name="fecha_inicio"]').val();
			var fecha_fin = $('[name="fecha_fin"]').val();

			if (fecha_inicio != '') {
				data.fecha_inicio = fecha_inicio;
			}
			if (fecha_fin != '') {
				data.fecha_fin = fecha_fin;
			}
			if (codigo != '') {
				data.codigo = codigo;
			}
		}
	},
	language: {
		url: base_url + "assets/admin/js/spanish.lang.json"
	},
	buttons: [{
		text: 'Exportar',
		extend: 'excelHtml5',
		title: 'participaciones',
		attr: {
			"data-tooltip": 'Exportar tabla en excel',
			"data-placement": 'auto',
			"title": 'Exportar tabla en excel'
		},
		exportOptions: {
			columns: ':not(.noexp)'
		}
	}, {
		extend: 'collection',
		text: 'Estado',
		className: 'btn btn-dark',
		autoClose: true,
		buttons: [{
			text: 'TODAS',
			action: function(e, dt, node, config) {
				$('#tabla').attr('data-estado', 'todas')
				tabla.draw();
			}
		}, {
			text: 'NO PREMIADAS',
			action: function(e, dt, node, config) {
				$('#tabla').attr('data-estado', 'nopremio')
				tabla.draw();
			}
		}, {
			text: 'PREMIADAS',
			action: function(e, dt, node, config) {
				$('#tabla').attr('data-estado', 'codigo')
				tabla.draw();
			}
		}, {
			text: 'PREMIADAS SIN TARJETA',
			action: function(e, dt, node, config) {
				$('#tabla').attr('data-estado', 'nobono')
				tabla.draw();
			}
		}, {
			text: 'PREMIADAS CON TARJETA',
			action: function(e, dt, node, config) {
				$('#tabla').attr('data-estado', 'bono')
				tabla.draw();
			}
		}]
	}],
	dom: "<'d-flex flex-wrap justify-content-between'<''l><''B><''f>>" +
		"<'row'<'col-sm-12'tr>>" +
		"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
	createdRow: function(row, data, dataIndex) {
		$(row).attr('data-dni', data.dni).attr('data-ordertpv', data.ordertpv)

	},
	drawCallback: function() {
		// bonificado
		var api = this.api();
		var sumables = tabla.rows().eq(0).filter(function (rowIdx) {
	      return tabla.cell( rowIdx, 8 ).data() != null && tabla.cell( rowIdx, 8 ).data()  != 0 ? true : false;
	  	});
	  	var sum = api.cells(sumables, 6).data().sum()
		$('#pagina_bonificado').html(sum.toFixed(2) + '€');
		$('#pagina_n_premios').html(tabla.rows(sumables).count());
	},
	columnDefs: [{
		orderable: false,
		targets: -1
	}]
});

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
		json.premiototal + '€'
	);
	$('#total_n_premios').html(
		json.npremios
	);
});

$(document).on('click', '[data-ver-tarjeta]', function() {
	var bonoID = $(this).attr('data-ver-tarjeta');
	var fd = new FormData();
	fd.append("bonoID", bonoID);
	fd.append("csrf_fecos", $('[name="csrf_fecos"]').val());

	return fetch(`${base_url}Codigos/getOrderTarjeta/${bonoID}`, {
			method: "POST",
			body: fd
		})
		.then(resp => resp.json())
		.then(response => {
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
			}

			if (response.hasOwnProperty('html')) {
				Swal.fire({
					confirmButtonColor: '#d2635f',
					buttonsStyling: 'background-color: #d2635f; color: #ffffff; font-weight: bolder;',
					confirmButtonText: 'Cerrar',
					html: response.html
				})
			} else {
				Swal.fire({
					title: json.msn,
				})
			}
		});
})

$(document).on('click', '[data-ver-codigo]', function() {
	var codigo = $(this).attr('data-ver-codigo');
	var fd = new FormData();
	fd.append("codigo", codigo);
	fd.append("csrf_fecos", $('[name="csrf_fecos"]').val());

	return fetch(`${base_url}Tarjetas/getCodigoTarjeta/${codigo}`, {
			method: "POST",
			body: fd
		})
		.then(resp => resp.json())
		.then(response => {
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
			}

			if (response.hasOwnProperty('html')) {
				Swal.fire({
					confirmButtonColor: '#d2635f',
					buttonsStyling: 'background-color: #d2635f; color: #ffffff; font-weight: bolder;',
					confirmButtonText: 'Cerrar',
					html: response.html
				})
			} else {
				Swal.fire({
					title: json.msn,
				})
			}
		});
})