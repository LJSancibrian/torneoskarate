$(document).ready(function() {
	$('#crear-promocion').click(function() {
		swal.fire({
			icon: 'question',
			title: 'Confirmar acción',
			html: '¿crear nueva promocion?',
			showCancelButton: true,
			confirmButtonText: 'Si, crear',
			cancelButtonText: 'Cerrar sin cambios',
		}).then((result) => {
			if (result.isConfirmed) {
				var fd = new FormData();
				fd.append("nombre", $("#nombre").val());
				fd.append("descripcion", $("#descripcion").val());
				fd.append("tipo", $("#tipo").val());
				fd.append("startime", $("#startime").val());
				fd.append("endtime", $("#endtime").val());
				fd.append("limittime", $("#limittime").val());
				fd.append("csrf_fecos", $('[name="csrf_fecos"]').val());
				$.ajax({
					url: base_url + "Gestion/nueva_promocion_form",
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
								window.location.href = response.redirect;
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
	});
});