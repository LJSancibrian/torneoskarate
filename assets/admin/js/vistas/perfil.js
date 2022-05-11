$(document).ready(function() {
	$('#guardar-datos').click(function() {
		swal.fire({
			icon: 'question',
			title: 'Confirmar acción',
			html: '¿Guardar los datos del usuario?',
			showCancelButton: true,
			confirmButtonText: 'Si, guardar',
			cancelButtonText: 'Cerrar sin cambios',
		}).then((result) => {
			if (result.isConfirmed) {
				var fd = new FormData();
				fd.append("userID", $('[name="userID"]').val());
				fd.append("first_name", $("#first_name").val());
				fd.append("last_name", $("#last_name").val());
				fd.append("dni", $("#dni").val());
				fd.append("email", $("#email").val());
				fd.append("phone", $("#phone").val());
				fd.append("newpassword", $("#newpassword").val());
				fd.append("confirmpassword", $("#confirmpassword").val());
				fd.append("csrf_token", $('[name="csrf_token"]').val());
				if ($('#active').is(':checked')) {
					fd.append("active", $("#active").val());
				}
				$.ajax({
					url: base_url + "Area_privada/editar_perfil_usuario_form",
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
						$('#nameuser').html($("#first_name").val()+' '+$("#last_name").val());
						swal.fire({
							icon: 'success',
							title: 'Correcto',
							html: response.msn,
							willClose: function() {
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
							}
						})
					}
				});
			}
		});
	});
});