$(document).ready(function() {
	id_user = $(location).attr("href").split('/').pop();
	cargar_perfil_vecino();
});

// console.log(localStorage.getItem("penaGestion"))
function cargar_perfil_vecino() {
	var settings = {
		"url": base_url + "/WS/Gestion/getGestores",
		"method": "POST",
		"timeout": 0,
		"headers": {
			"Content-Type": "application/json"
		},
		"data": JSON.stringify({
			"token": localStorage.getItem("penaGestion"),
			"par": {
				"id_user": parseInt(id_user)
			}
		}),
	};

	$.ajax(settings).done(function(response) {
		if (response.error == 0) {
			e = response.data.gestores[0];
			var cif = e.cif;
			$('#namegestor').html(e.nombre + ' ' + e.apellidos)
			$('[name="nombre"]').val(e.nombre)
			$('[name="apellidos"]').val(e.apellidos)
			$('[name="direccion"]').val(e.direccion)
			$('[name="dni"]').val(e.dni)
			$('[name="email"]').val(e.email)
			$('[name="telefono"]').val(e.telefono)
			if (e.estado == 'activo') {
				$('[name="estado"]').prop("checked", true);
			} else {
				$('[name="estado"]').prop("checked", false);
			}

		} else {
			showmsg(response.msg)
		}
	}).fail(function(response) {
		showmsg(response.responseJSON.msg)
	});
}

// enviar el form
function updateData(form) {
	var par = getFormData(form);
	par.id_user = parseInt(id_user)
	var settings = {
		"url": api_url + "WS/Gestion/editarGestor",
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
			cargar_perfil_vecino();
	descuentos_vecino();
		} else {
			showmsg(response.msg)
		}
	}).fail(function(response) {
		showmsg(response.responseJSON.msg)
	});

}