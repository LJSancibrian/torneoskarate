url = window.location.href;
segment = url.replace(base_url, '');
array = segment.split('/')

title_file = 'Descuentos'
if (array.length == 5) {
	if ((array[2] != 'undefined') && (array[3] != 'undefined') && (array[4] != 'undefined')) {
		ajax_url = ajax_url + '/' + array[2] + '/' + array[3] + '/' + array[4]
		title_file = title_file + '_' + array[2] + '_' + array[3] + '/' + array[4]
	}
}
var tabladescuentos;
tabladescuentos = $("#tabladescuentos").DataTable({
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
		data: "descuentoID"
	}, {
		name: "Nº Tarjeta",
		title: "Nº Tarjeta",
		data: "dni"
	}, {
		name: "Empresa",
		title: "Empresa",
		data: "razon_social"
	}, {
		name: "CIF",
		title: "CIF",
		data: "cif"
	}, {
		name: "Fecha - Hora",
		title: "Fecha - Hora",
		data: "createdAt"
	}, {
		name: "Cantidad",
		title: "Cantidad",
		data: "cantidad"
	}, {
		name: "Fecha de amortización",
		title: "Fecha de amortización",
		data: "fecha_amortizacion",
		render: function(data, type, row) {
			if( row.fecha_amortizacion == '0000-00-00 00:00:00'){
				html = `-`;
			}else{
				html = row.fecha_amortizacion.substring(0, 10)
			}
			return html
		}
	}, {
		name: "Acción",
		title: "Acción",
		data: "descuentoID",
		render: function(data, type, row) {
			html = `
               <button class="btn btn-sm btn-link" data-borrar="${row.descuentoID}"><i class="fas fa-trash"></i></button>`
			return html
		}
	}, ],
	ajax: {
		url: base_url + "Descuentos/get_descuentos",
		type: "GET",
		datatype: "json",
		data: function(data) {
            var cif = $('[name="cif"]').val();
            var dni = $('[name="dni"]').val();
            var sector = $('[name="sector"]').val();
            var estado = $('[name="estado"]').val();
            var fecha_inicio = $('[name="fecha_inicio"]').val();
            var fecha_fin = $('[name="fecha_fin"]').val();
            
            if (fecha_inicio != '') {
                data.fecha_inicio = fecha_inicio;
            }
            if (fecha_fin != '') {
                data.fecha_fin = fecha_fin;
            }
            if (dni != '') {
                data.dni = dni;
            }
            if (cif != '') {
                data.cif = cif;
            }
            if (sector != '') {
                data.sector = sector;
            }
            if (estado != '') {
                data.estado = estado;
            }
		}
	},
	buttons: [
		{
			text: 'Exportar',
			extend: 'excelHtml5',
			title: 'descuentos',
			attr: {
				"data-tooltip": 'Exportar tabla en excel',
				"data-placement": 'auto',
				"title": 'Exportar tabla en excel'
			},
			exportOptions: {
				columns: ':not(.noexp)'
			}
		}
	],
	language: {
		url: assets_url + "lib/DataTables/languages/spanish_alt.lang.json"
	},
	dom: "<'d-flex flex-wrap justify-content-between'<''l><''B><''f>>" +
		"<'row'<'col-sm-12'tr>>" +
		"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
	createdRow: function(row, data, dataIndex) {
		$(row).attr('data-cif', data.cif).attr('data-vecino', data.dni)

	},
	drawCallback: function() {
		// bonificado
		var api = this.api();
		pagina_descuentos = api.column(5).data().sum();
		$('#pagina_descuentos').html(
			pagina_descuentos.toFixed(2) + '€'
		);
	},
	columnDefs: [{
		orderable: false,
		targets: -1
	}]
});
/*$(document).ready(function() {
	var titledoc = 'commercium_descuentos';
	var buttons = new $.fn.dataTable.Buttons(tabladescuentos, {
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
})*/
tabladescuentos.on( 'xhr', function () {
	    var json = tabladescuentos.ajax.json();
		$('#total_descuentos').html(
			json.descuentostotal + '€'
		);
} );
$('#fecha_inicio, #fecha_fin').datetimepicker({
	locale: 'es',
	format: 'YYYY-MM-DD',
	debug: true
});
$(document).on('click', '#searchdesc', function() {
	tabladescuentos.draw();
})	

$(document).on('click', '[data-borrar]', function() {
	var descuentoID = $(this).data('borrar')
	swal.fire({
		icon: 'question',
		title: 'Confirmar acción',
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
})


function anular_descuento(descuentoID) {
	$('#crear-empresa').click(function() {

	});
}

function generartable(response) {
	$('#tabladescuentos').DataTable().destroy()
	$('#tabladescuentos tbody').empty();
	$.each(response.data.descuentos[1], function(i, item) {
		if (item.fileurl !== '') {
			image = '<a href="' + item.fileurl + '" target="_blank" class="text-dark"><i class="fas fa-image"></i></a>';
		} else {
			image = '<i class="fas fa-times" style="color:red;"></i>';
		}
		var $tr = $('<tr>').append(
			$('<td thead="id">').text(item.descuentoID),
			$('<td thead="archivo">').html(image),
			//$('<td thead="vecino">').text(item.nombre + ' ' + item.apellidos),
			$('<td thead="dni">').text(item.dni),
			$('<td thead="empresa">').text(item.razon_social),
			$('<td thead="cif">').text(item.cif),

			$('<td thead="Fecha - Hora">').text(item.fecha_creacion),
			$('<td thead="Cantidad">').text(item.cantidad + '€'),
			$('<td thead="acción">').html('<button class="btn btn-sm btn-link" data-borrar="' + item.descuentoID + '"><i class="fas fa-trash"></i></button>')
		);
		$tr.appendTo('#tabladescuentos tbody');
	})
	totalconsulta = response.data.descuentos[0]
	$('#total_consulta').html(parseFloat(totalconsulta).toFixed(2) + '€');
	table = $('#tabladescuentos').DataTable({
		lengthMenu: [
			[25, 50, 100, 500, -1],
			[25, 50, 100, 500, 'Todos']
		],
		order: [
			[0, "desc"]
		],
		pageLength: 50,
		language: {
			url: base_url + "assets/admin/js/spanish.lang.json"
		},
		drawCallback: function() {
			var api = this.api();
			totalpage = api.column(6, {
				page: 'current'
			}).data().sum();
			$('#total_pagina').html(
				totalpage.toFixed(2) + '€'
			);
		}
	});
	var buttons = new $.fn.dataTable.Buttons(table, {
		buttons: [
			'copyHtml5',
			'excelHtml5',
			'csvHtml5',
			'pdfHtml5'
		]
	}).container().appendTo($('#buttons'));
}