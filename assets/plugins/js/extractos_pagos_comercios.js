$(document).ready(function() {

	var table = $('#descontadocomercios').DataTable({
		processing: true,
		lengthMenu: [
			[25, 50, 100, 500, -1],
			[25, 50, 100, 500, 'Todos']
		],
		order: [
			[0, "desc"]
		],
		pageLength: -1,
		buttons: [
			{
				text: 'Exportar',
				extend: 'excelHtml5',
				title: 'Fecosva_extracto_pagos_empresas_del_' + fecha_inicio + '_al_' + fecha_fin,
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
		drawCallback: function() {
			var api = this.api();
			totalpage = api.column(2, {
				page: 'current'
			}).data().sum();
			$('#total_consulta_vecinos').html(
				totalpage.toFixed(2) + '€'
			);
		}
	});

	/*var titledoc = 'Fecosva_extracto_empresas_del_' + fecha_inicio + '_al_' + fecha_fin
	var buttons = new $.fn.dataTable.Buttons(table, {
		buttons: [{
			extend: 'excelHtml5',
			title: titledoc
		}, {
			extend: 'csvHtml5',
			title: titledoc
		}]
	}).container().appendTo($('#buttonsVecinos'));*/
})

$('#fecha_inicio, #fecha_fin').datetimepicker({
	locale: 'es',
	format: 'YYYY-MM-DD',
	//debug: true
});

$(document).on('click', '#searchdesc', function() {

	var fecha_inicio = $('[name="fecha_inicio"]').val();
	var fecha_fin = $('[name="fecha_fin"]').val();
	var ex_cif = $('[name="ex_cif"]').val();
	var sector = $('[name="sector"]').val();
	var fd = new FormData();
	fd.append("fecha_inicio", fecha_inicio);
	fd.append("fecha_fin", fecha_fin);
	fd.append("ex_cif", ex_cif);
	fd.append("sector", sector);
	fd.append("csrf_fecos", $('[name="csrf_fecos"]').val());

	$.ajax({
		url: base_url + "Extractos/getPagos",
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
			$('#descontadocomercios').DataTable().destroy()
			$('#descontadocomercios tbody').empty();
			$.each(response.datos, function(i, item) {
				var $tr = $('<tr>').append(
					$('<td>').text(item.cif),
					$('<td>').text(item.razon_social),
					$('<td>').text(item.cantidad + '€'),
				);
				$tr.appendTo('#descontadocomercios tbody');
			});

			table = $('#descontadocomercios').DataTable({
				processing: true,
				lengthMenu: [
					[25, 50, 100, 500, -1],
					[25, 50, 100, 500, 'Todos']
				],
				order: [
					[0, "desc"]
				],
				pageLength: -1,
				buttons: [
					{
						text: 'Exportar',
						extend: 'excelHtml5',
						title: 'Fecosva_extracto_pagos_empresas_del_' + fecha_inicio + '_al_' + fecha_fin,
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
				drawCallback: function() {
					var api = this.api();
					totalpage = api.column(2, {
						page: 'current'
					}).data().sum();
					$('#total_consulta_vecinos').html(
						totalpage.toFixed(2) + '€'
					);
				}
			});

			/*var titledoc = 'Fecosva_extracto_empresas_del_' + fecha_inicio + '_al_' + fecha_fin
			$('#buttonsVecinos').empty();
			var buttons = new $.fn.dataTable.Buttons(table, {
				buttons: [{
					extend: 'excelHtml5',
					title: titledoc
				}, {
					extend: 'csvHtml5',
					title: titledoc
				/*}, {
					text: 'Remesa XML',
					action: function(e, dt, node, config) {
						var fecha_inicio = $('[name="fecha_inicio"]').val();
						var fecha_fin = $('[name="fecha_fin"]').val();
						var ex_cif = $('[name="ex_cif"]').val();
						
						var fd = new FormData();
						fd.append("fecha_inicio", fecha_inicio);
						fd.append("fecha_fin", fecha_fin);
						fd.append("ex_cif", ex_cif);
						fd.append("csrf_fecos", $('[name="csrf_fecos"]').val());

						$.ajax({
							url: base_url + "Extractos/extractotxml",
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
								console.log(response.datos)

								var blob = new Blob([response.datos], {
									type: "application/octetstream"
								});

								var isIE = false || !!document.documentMode;
								if (isIE) {
									window.navigator.msSaveBlob(blob, response.file);
								} else {
									var url = window.URL || window.webkitURL;
									link = url.createObjectURL(blob);
									var a = $("<a />");
									a.attr("download", response.file);
									a.attr("href", link);
									$("body").append(a);
									a[0].click();
									$("body").remove(a);
								}
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
				}, {
					text: 'Remesa Pagada',
					action: function(e, dt, node, config) {
						var fecha_inicio = $('[name="fecha_inicio"]').val();
						var fecha_fin = $('[name="fecha_fin"]').val();

						swal.fire({
							icon: 'question',
							title: 'Confirmar acción',
							html: '¿Marcar los descuentos realizados entre el dia '+fecha_inicio+' y el dia '+fecha_fin+' como ya pagados?',
							showCancelButton: true,
							confirmButtonText: 'Si, marcar pagados',
							cancelButtonText: 'Cerrar sin cambios',
						}).then((result) => {
							var modal = '<div id="modalTempPagado" class="modal fade" tabindex="-1" role="dialog">';
							modal += '<div class="modal-dialog modal-dialog-centered" role="document">';
							modal += '<div class="modal-content">';
							modal += '<div class="modal-body">';
							modal += '<p style="font-size: 20px;text-align:center"><span style="font-size:24px; font-weight:bold;">Indica la fecha de pago de los descuentos marcados</span></p>';
							modal += '<div class="input-group date mx-1 my-1" id="fecha_pagado" data-target-input="nearest">';
							modal += '<input type="text" class="form-control datetimepicker-input" data-target="#fecha_pagado" name="fecha_pagado" placeholder="Fecha de pago" data-target="#fecha_pagado" data-toggle="datetimepicker"/>';
							modal += '<div class="input-group-append" data-target="#fecha_pagado" data-toggle="datetimepicker">';
							modal += '<div class="input-group-text bg-primary text-white"><i class="fas fa-calendar-alt"></i></div>';
							modal += '</div>';
							modal += '</div>';

							modal += '</div>';
							modal += '<div class="modal-footer">';
							modal += '<button type="button" class="btn btn-primary" onclick="marcar_pagados_confirm();">Marcar como pagados</button>';
							modal += '<button type="button" class="btn btn-secondary" data-dismiss="modal">No, cerrar</button>';
							modal += '</div></div></div></div>';
							$("body").append(modal);
							$('#fecha_pagado').datetimepicker({
								locale: 'es',
								format: 'YYYY-MM-DD'
							});
							$('#modalTempPagado').modal('show')
						})
					}

				}]
			}).container().appendTo($('#buttonsVecinos'));*/
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

})