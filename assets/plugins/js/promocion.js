$(document).ready(function() {
	promocionid = $(location).attr("href").split('/').pop();
	if ($('#tablaparticipaciones').length > 0) {
		var tablaparticipaciones = $('#tablaparticipaciones').dataTable({
			scrollX: true,
			autoWidth: false,
			order: [0, "desc"],
			pageLength: 50,
			lengthMenu: [
				[10, 25, 50, 100, -1],
				[10, 25, 50, 100, 'Todos']
			],
			language: {
				url: base_url + "assets/admin/DataTables/languages/spanish.lang.json"
			},
			drawCallback: function(settings) {
				$('[data-tooltip]').tooltip();
				var api = this.api(),
					data;
				var intVal = function(i) {
					return typeof i === 'string' ?
						i.replace(/[\$,]/g, '') * 1 :
						typeof i === 'number' ?
						i : 0;
				};
				var total = api
					.column(6)
					.data()
					.reduce(function(a, b) {
						return intVal(a) + intVal(b);
					}, 0);
				var pageTotal = api
					.column(6, {
						page: 'current'
					})
					.data()
					.reduce(function(a, b) {
						return intVal(a) + intVal(b);
					}, 0);
				$('#total_participaciones').html(total.toFixed(2) + '€');
			},
		});
		var buttons = new $.fn.dataTable.Buttons(tablaparticipaciones, {
			buttons: [
				'copyHtml5', {
					extend: 'excelHtml5',
					title: 'Descuentos_promocion_' + promocionid
				}, {
					extend: 'csvHtml5',
					title: 'Descuentos_promocion_' + promocionid
				}, {
					extend: 'pdfHtml5',
					title: 'Descuentos_promocion_' + promocionid
				}
			]
		}).container().appendTo($('#buttonspart'));
	}

	if ($('#tabladescuentosaplicados').length > 0) {
		var tabladescuentosaplicados = $('#tabladescuentosaplicados').dataTable({
			scrollX: true,
			autoWidth: false,
			order: [0, "desc"],
			pageLength: 50,
			lengthMenu: [
				[10, 25, 50, 100, -1],
				[10, 25, 50, 100, 'Todos']
			],
			language: {
				url: base_url + "assets/admin/DataTables/languages/spanish.lang.json"
			},
			drawCallback: function(settings) {
				$('[data-tooltip]').tooltip();
				var api = this.api(),
					data;
				var intVal = function(i) {
					return typeof i === 'string' ?
						i.replace(/[\$,]/g, '') * 1 :
						typeof i === 'number' ?
						i : 0;
				};
				var total = api
					.column(5)
					.data()
					.reduce(function(a, b) {
						return intVal(a) + intVal(b);
					}, 0);
				var pageTotal = api
					.column(5, {
						page: 'current'
					})
					.data()
					.reduce(function(a, b) {
						return intVal(a) + intVal(b);
					}, 0);
				$('#total_descuentos').html(total.toFixed(2) + '€');
			},
		});
		var buttons = new $.fn.dataTable.Buttons(tabladescuentosaplicados, {
			buttons: [
				'copyHtml5', {
					extend: 'excelHtml5',
					title: 'Descuentos_promocion_' + promocionid
				}, {
					extend: 'csvHtml5',
					title: 'Descuentos_promocion_' + promocionid
				}, {
					extend: 'pdfHtml5',
					title: 'Descuentos_promocion_' + promocionid
				}
			]
		}).container().appendTo($('#buttonsdto'));
	}

	if ($('#tablasextractosempresas').length > 0) {
		var tablasextractosempresas = $('#tablasextractosempresas').dataTable({
			scrollX: true,
			autoWidth: false,
			order: [0, "desc"],
			pageLength: 50,
			lengthMenu: [
				[10, 25, 50, 100, -1],
				[10, 25, 50, 100, 'Todos']
			],
			language: {
				url: base_url + "assets/admin/DataTables/languages/spanish.lang.json"
			},
			drawCallback: function(settings) {
				$('[data-tooltip]').tooltip();
				var api = this.api(),
					data;
				var intVal = function(i) {
					return typeof i === 'string' ?
						i.replace(/[\$,]/g, '') * 1 :
						typeof i === 'number' ?
						i : 0;
				};
				var total = api
					.column(2)
					.data()
					.reduce(function(a, b) {
						return intVal(a) + intVal(b);
					}, 0);
				var pageTotal = api
					.column(2, {
						page: 'current'
					})
					.data()
					.reduce(function(a, b) {
						return intVal(a) + intVal(b);
					}, 0);
				$('#total_descuentos_e').html(total.toFixed(2) + '€');
			},
		});
		var buttons = new $.fn.dataTable.Buttons(tablasextractosempresas, {
			buttons: [
				'copyHtml5', {
					extend: 'excelHtml5',
					title: 'Extracto_empresas_promocion_' + promocionid
				}, {
					extend: 'csvHtml5',
					title: 'Extracto_empresas_promocion_' + promocionid
				}, {
					extend: 'pdfHtml5',
					title: 'Extracto_empresas_promocion_' + promocionid
				}
			]
		}).container().appendTo($('#buttonsextem'));
	}

	if ($('#tablasextractospremiados').length > 0) {
		var tablasextractospremiados = $('#tablasextractospremiados').dataTable({
			scrollX: true,
			autoWidth: false,
			order: [0, "desc"],
			pageLength: 50,
			lengthMenu: [
				[10, 25, 50, 100, -1],
				[10, 25, 50, 100, 'Todos']
			],
			language: {
				url: base_url + "assets/admin/DataTables/languages/spanish.lang.json"
			},
			drawCallback: function(settings) {
				$('[data-tooltip]').tooltip();
				var api = this.api(),
					data;
				var intVal = function(i) {
					return typeof i === 'string' ?
						i.replace(/[\$,]/g, '') * 1 :
						typeof i === 'number' ?
						i : 0;
				};
				var total = api
					.column(2)
					.data()
					.reduce(function(a, b) {
						return intVal(a) + intVal(b);
					}, 0);
				var pageTotal = api
					.column(2, {
						page: 'current'
					})
					.data()
					.reduce(function(a, b) {
						return intVal(a) + intVal(b);
					}, 0);
				$('#total_descuentos_p').html(total.toFixed(2) + '€');
			},
		});
		var buttons = new $.fn.dataTable.Buttons(tablasextractospremiados, {
			buttons: [
				'copyHtml5', {
					extend: 'excelHtml5',
					title: 'Extracto_empresas_promocion_' + promocionid
				}, {
					extend: 'csvHtml5',
					title: 'Extracto_empresas_promocion_' + promocionid
				}, {
					extend: 'pdfHtml5',
					title: 'Extracto_empresas_promocion_' + promocionid
				}
			]
		}).container().appendTo($('#buttonsextpr'));
	}

	$("#sidebarToggle, #sidebarToggleTop").on('click', function(e) {
		if ($('.dataTable').length > 0) {
			$.fn.dataTable.tables({
				visible: true,
				api: true
			}).columns.adjust();
		}
	});

	$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
		$.fn.dataTable.tables({
			visible: true,
			api: true
		}).columns.adjust();
	});



	$('#guardar-datos-promo').click(function() {
		swal.fire({
			icon: 'question',
			title: 'Confirmar acción',
			html: '¿Guardar los datos de la promocion?',
			showCancelButton: true,
			confirmButtonText: 'Si, guardar',
			cancelButtonText: 'Cerrar sin cambios',
		}).then((result) => {
			if (result.isConfirmed) {
				var fd = new FormData();
				fd.append("promocionID", $('[name="promocionID"]').val());
				fd.append("nombre", $("#nombre").val());
				fd.append("descripcion", $("#descripcion").val());
				fd.append("tipo", $("#tipo").val());
				fd.append("startime", $("#startime").val());
				fd.append("endtime", $("#endtime").val());
				fd.append("limittime", $("#limittime").val());
				fd.append("csrf_fecos", $('[name="csrf_fecos"]').val());
				$.ajax({
					url: base_url + "Gestion/editar_promocion_form",
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
								//window.location.href = response.redirect;
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

});


// SORTEO
if ($('#btn-generar_sorteo').length > 0) {
	$(document).on('click', '#btn-generar_sorteo', function() {
		cantidad = $('[name="cuantos_mg"]').val()
		valor = $('[name="cuanto_mg"]').val()
		for (var i = 0; i < cantidad; i++) {
			html = '<tr><td>' + valor + '</td><td></td></tr>';
			$('#tabla_sorteo>tbody').append(html)
		}
	})
}

if ($('#guardar_premios_sorteo').length > 0) {
	$(document).on('click', '#guardar_premios_sorteo', function() {
		var premios = [];
		$('#tabla_sorteo> tbody  > tr').each(function(index, tr) {
			premios.push($(this).children().eq(0).text())
		});
		var par = {};
		par.premiosSorteo = premios
		par.promocionID = parseInt(promocionid)
		var settings = {
			"url": api_url + "WS/Gestion/guardarPremiosSorteo",
			"method": "POST",
			"timeout": 0,
			"headers": {
				"Content-Type": "application/json"
			},
			"data": JSON.stringify({
				"token": localStorage.getItem("penaGestion"),
				"par": par
			}),
		};
		$.ajax(settings).done(function(response) {
			if (response.error == 0) {
				showmsg(response.msg)
				setTimeout(function() {
					window.location.reload();
				}, 600);
			} else {
				showmsg(response.msg)
			}
		}).fail(function(response) {
			showmsg(response.responseJSON.msg)
		});
	})
}


// MOMENTO GANADOR
if ($('#btn-generar_mg').length > 0) {
	$(document).on('click', '#btn-generar_mg', function() {
		start = $('[name="g_fecha_inicio"]').val()
		end = $('[name="g_fecha_fin"]').val()
		cantidad = $('[name="cuantos_mg"]').val()
		valor = $('[name="cuanto_mg"]').val()
		minimo = $('[name="minimo_mg"]').val()

		var fd = new FormData();
		fd.append("fecha_inicio", start);
		fd.append("fecha_final", end);
		fd.append("cantidad", cantidad);
		fd.append("valor", valor);
		fd.append("minimo", minimo);
		fd.append("csrf_fecos", $('[name="csrf_fecos"]').val());

		$.ajax({
			url: base_url + "Codigos/generarCodigosGanadores",
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
				$.each(response.momentos, function(item, value){
					$('#tabla_temp > tbody').append('<tr><td>'+value.time+'</td><td>'+value.valor+'</td><td>'+value.minimo+'</td></tr>')
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
	})
}

function randomDate(start, end, startHour, endHour) {
	var date = new Date(+start + Math.random() * (end - start));
	if (date.getDay() > 0) {
		var hour = 9 + Math.random() * (20 - 9) | 0;
		date.setHours(hour);
		var year = date.getFullYear();
		var month = appendLeadingZeroes(date.getMonth() + 1);
		var day = appendLeadingZeroes(date.getDate());
		var hours = appendLeadingZeroes(date.getHours());
		var minutes = appendLeadingZeroes(date.getMinutes());
		var seconds = appendLeadingZeroes(date.getSeconds());
		date = year + "-" + month + "-" + day + " " + hours + ":" + minutes + ":" + seconds
	} else {
		date = randomDate(new Date(start), new Date(end))
	}
	return date;
}

if ($('#guardar_premios_mg').length > 0) {
	$(document).on('click', '#guardar_premios_mg', function() {
		var fechas = [];
		var premios = [];
		var minimos = [];
		$('#tabla_temp > tbody  > tr').each(function(index, tr) {
			fechas.push($(this).children().eq(0).text());
			premios.push($(this).children().eq(1).text());
			minimos.push($(this).children().eq(2).text());
		});
		var fd = new FormData();
		fd.append("fechas", fechas);
		fd.append("premios", premios);
		fd.append("csrf_fecos", $('[name="csrf_fecos"]').val());
		fd.append("minimos", minimos)
		
		$.ajax({
			url: base_url + "Codigos/guardarCodigosGanadores",
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
					icon: 'info',
					title: 'Registro de datos correcto',
					html: response.msn,
					timer: 3000,
					willClose: function() {
						//window.location.reload();
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
	})
}

if ($('#tabla_sorteo_actual_search').length > 0) {
	$(document).on("keyup", "#tabla_sorteo_actual_search", function() {
		var value = $(this).val().toLowerCase();
		$("#tabla_sorteo_actual tbody tr").filter(function() {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});
}

$(document).on('click', '#guardar_comercios_marcados', function() {
	swal.fire({
		icon: 'question',
		title: 'Confirmar acción',
		html: '¿Actualizar las empresas de la promoción?',
		showCancelButton: true,
		confirmButtonText: 'Si, actualizar',
		cancelButtonText: 'Cerrar sin cambios',
	}).then((result) => {
		if (result.isConfirmed) {
			var fd = new FormData();
			var id_empresa_array = [];
			$("[name='empresaID[]']:checked").each(function(i) {
				id_empresa_array[i] = $(this).val();
			});
			fd.append("id_empresa_array", id_empresa_array);
			fd.append("promocionID", $('[name="promocionID"]').val());
			$.ajax({
				url: base_url + "Gestion/marcarEmpresasPromocion",
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
							//window.location.href = response.redirect;
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

$(document).on("click", '[name="selectAll"]', function() {
	$('[name="empresaID[]"]').prop("checked", this.checked);
});

$(document).on('click', '[data-add_ganador]', function() {
	premiosorteoID = $(this).data('add_ganador')
	var modal = '<div id="modalTemp" class="modal fade" tabindex="-1" role="dialog">';
	modal += '<div class="modal-dialog modal-dialog-centered" role="document">';
	modal += '<div class="modal-content">';
	modal += '<div class="modal-body">';
	modal += '<div class="form-group">';
	modal += '<label for="dni_premiado" class="h3">Indica el DNI premiado</label>';
	modal += '<input class="form-control" type="text" name="dni_premiado" id="dni_premiado">';
	modal += '</div>';
	modal += '</div>';
	modal += '<div class="modal-footer">';
	modal += '<button type="button" class="btn btn-primary" onclick="marcar_premiado(' + premiosorteoID + ');">Si, marcar como premiado</button>';
	modal += '<button type="button" class="btn btn-secondary" data-dismiss="modal">No, cerrar</button>';
	modal += '</div></div></div></div>';
	$("body").append(modal);
	$('#modalTemp').modal('show')
})

function appendLeadingZeroes(n) {
	if (n <= 9) {
		return "0" + n;
	}
	return n
}

function marcar_premiado(premiosorteoid) {
	var dni = $('#dni_premiado').val();
	if (dni.length > 8) {
		var par = new Object();
		par.premiosorteoID = parseInt(premiosorteoid);
		par.dni = dni;
		var settings = {
			"url": base_url + "WS/Gestion/marcarDniPremiado",
			"method": "POST",
			"timeout": 0,
			"headers": {
				"Content-Type": "application/json"
			},
			"data": JSON.stringify({
				"token": localStorage.getItem("penaGestion"),
				"par": par
			}),
		};
		$.ajax(settings).done(function(response) {
			$('#modalTemp .modal-body').html('<p style="font-size: 20px;text-align:center">' + response.msg + '</p>');
			setTimeout(function() {
				window.location.reload();
			}, 1000);
		});
	} else {
		alert('Debes indicar un DNI')
	}

}