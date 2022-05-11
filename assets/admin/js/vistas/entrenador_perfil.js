$(document).ready(function() {
	$('#crear-entrenador').click(function() {
		swal.fire({
			icon: 'question',
			title: 'Confirmar acción',
			html: '¿Crear nuevo entrenador?',
			showCancelButton: true,
			confirmButtonText: 'Si, crear',
			cancelButtonText: 'Cerrar sin cambios',
		}).then((result) => {
			if (result.isConfirmed) {
				var fd = new FormData();
				fd.append("first_name", $("#first_name").val());
				fd.append("last_name", $("#last_name").val());
				fd.append("dni", $("#dni").val());
				fd.append("email", $("#email").val());
				fd.append("phone", $("#phone").val());
				fd.append("newpassword", $("#newpassword").val());
				fd.append("confirmpassword", $("#confirmpassword").val());
				fd.append("csrf_token", $('[name="csrf_token"]').val());
				if($('#active').is(':checked')){
					fd.append("active", $("#active").val());
				}
				$.ajax({
					url: base_url + "Participantes/nuevo_entrenador_form",
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
							willClose: function() {							}
						})
					}
				});
			}
		});
	});


    $('[data-formulario]').click(function(){
        var club_id = $(this).data('club_id')
        var user_id = $('[name="user_id_club"]').val()
        if(club_id == 'nuevo'){
            // se vacian todos los cammpos y se cambia la action del formualrio
            $('#formulario_club form').trigger("reset");
            $('[name="user_id_club"]').val(user_id)
            $('[name="club_id"]').val(club_id)
            $('#formulario_club .card-title').html('Crear escuela o club')
            $('#formulario_club form').attr("action", base_url + "participantes/nuevo_club_form");
            $('#submit-club-form').html('Crear club')
            $('#formulario_club').modal('show'); 
        }else{
            // ajax para pedir los datos del club



            $('#formulario_club form').attr("action", base_url + "participantes/editar_club_form");
            $('#submit-club-form').html('Guardar datos')
            $('#formulario_club').modal('show'); 
        }
    })

    $('#submit-club-form').click(function() {
		swal.fire({
			icon: 'question',
			title: 'Confirmar acción',
			html: '¿Crear nuevo club?',
			showCancelButton: true,
			confirmButtonText: 'Si, crear',
			cancelButtonText: 'Cerrar sin cambios',
		}).then((result) => {
			if (result.isConfirmed) {
                var action = $('#formulario_club form').attr("action")
				var fd = new FormData();
				fd.append("nombre", $("#nombre_club").val());
				fd.append("descripcion", $("#descripcion_club").val());
				fd.append("direccion", $("#direccion_club").val());
				fd.append("email", $("#email").val());
				fd.append("telefono", $("#telefono_club").val());
				fd.append("user_id", $('[name="user_id_club"]').val());
				fd.append("csrf_token", $('[name="csrf_token"]').val());
				if($('#estado_club').is(':checked')){
					fd.append("estado", $("#estado_club").val());
				}
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
							willClose: function() {}
						})
					}
				});
			}
		});
	});
});